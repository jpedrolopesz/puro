<?php

namespace App\Jobs\Central\Stripe\User;

use Stripe\Stripe;
use Stripe\Customer;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Domain;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Events\ImportProgressUpdated;

class ImportStripeUsersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $startingAfter;
    protected $totalCustomers;
    protected $processedCustomers = 0;

    public function __construct(string $startingAfter = null)
    {
        $this->startingAfter = $startingAfter;
    }

    public function handle(): void
    {
        try {
            $this->updateProgress(0, "Initializing import...");

            Stripe::setApiKey(config("services.stripe.secret"));
            $response = Customer::all([
                "limit" => 100,
                "starting_after" => $this->startingAfter,
                "expand" => ["data.subscriptions", "data.default_source"],
            ]);

            $this->totalCustomers =
                $response->total_count ?? count($response->data);
            $this->updateProgress(
                0,
                "Retrieved {$this->totalCustomers} customers from Stripe"
            );

            foreach ($response->data as $customer) {
                try {
                    DB::beginTransaction();
                    $this->processCustomer($customer);
                    DB::commit();

                    $this->processedCustomers++;
                    $progress =
                        ($this->processedCustomers / $this->totalCustomers) *
                        100;
                    $this->updateProgress(
                        $progress,
                        "Processed {$this->processedCustomers} of {$this->totalCustomers} customers"
                    );
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error(
                        "Error processing customer {$customer->id}: " .
                            $e->getMessage()
                    );
                    $this->updateProgress(
                        null,
                        "Error processing customer {$customer->id}: " .
                            $e->getMessage(),
                        true
                    );
                }
            }

            if ($response->has_more) {
                $lastCustomer = end($response->data);
                self::dispatch($lastCustomer->id)->delay(now()->addSeconds(5));
                $this->updateProgress(
                    null,
                    "Scheduling next batch of customers"
                );
            } else {
                $this->updateProgress(100, "Import completed successfully");
            }
        } catch (\Exception $e) {
            Log::error("Failed to import Stripe users: " . $e->getMessage());
            $this->updateProgress(
                null,
                "Failed to import Stripe users: " . $e->getMessage(),
                true
            );
        }
    }

    protected function updateProgress(
        $progress = null,
        $status = "",
        $isError = false
    ) {
        $eventName = $isError ? "import.error" : "import.progress";
        $payload = ["status" => $status];
        if (!is_null($progress)) {
            $payload["progress"] = $progress;
        }
        broadcast(new ImportProgressUpdated($eventName, $payload))->toOthers();
    }

    protected function processCustomer($customer)
    {
        Log::info(
            "Processando cliente do Stripe: {$customer->id}, Email: {$customer->email}"
        );

        $userByStripeId = User::where("stripe_id", $customer->id)->first();
        $userByEmail = User::where("email", $customer->email)->first();

        if ($userByStripeId) {
            Log::info(
                "Usuário encontrado pelo Stripe ID: {$userByStripeId->id}"
            );
            $this->updateExistingUser($userByStripeId, $customer);
        } elseif ($userByEmail) {
            Log::info("Usuário encontrado pelo email: {$userByEmail->id}");
            if (
                $userByEmail->stripe_id &&
                $userByEmail->stripe_id !== $customer->id
            ) {
                Log::warning(
                    "Usuário {$userByEmail->id} já tem um Stripe ID diferente: {$userByEmail->stripe_id}"
                );
                // Decida como lidar com esta situação. Por exemplo:
                // $this->handleConflictingStripeIds($userByEmail, $customer);
            } else {
                $this->updateExistingUser($userByEmail, $customer);
            }
        } else {
            Log::info(
                "Nenhum usuário existente encontrado. Criando novo usuário para {$customer->email}"
            );
            $this->createNewUserWithTenant($customer);
        }
    }

    protected function updateExistingUser($user, $customer)
    {
        $originalStripeId = $user->stripe_id;
        $user->update([
            "stripe_id" => $customer->id,
            "name" => $customer->name ?? $user->name,
            "email" => $customer->email,
            "pm_type" => $this->getPaymentMethodType($customer),
            "pm_last_four" => $this->getPaymentMethodLastFour($customer),
            "trial_ends_at" => $this->getTrialEndsAt($customer),
        ]);

        Log::info(
            "Usuário atualizado: ID {$user->id}, Email: {$user->email}, " .
                "Stripe ID Original: {$originalStripeId}, Novo Stripe ID: {$customer->id}"
        );
    }

    protected function createNewUserWithTenant($customer)
    {
        if (User::where("email", $customer->email)->exists()) {
            Log::warning(
                "Tentativa de criar um usuário duplicado para o email: {$customer->email}"
            );
            return;
        }

        $user = User::create([
            "name" => $customer->name ?? "Stripe Customer",
            "email" => $customer->email,
            "password" => bcrypt(Str::random(16)),
            "stripe_id" => $customer->id,
            "pm_type" => $this->getPaymentMethodType($customer),
            "pm_last_four" => $this->getPaymentMethodLastFour($customer),
            "trial_ends_at" => $this->getTrialEndsAt($customer),
        ]);

        $tenant = Tenant::create([
            "tenancy_name" => $this->generateTenancyName($customer),
            "email" => $customer->email,
            "status" => "active",
            "tenancy_db_name" => $this->generateUniqueDatabaseName(),
            "creator_id" => $user->id,
            "data" => json_encode(["stripe_customer_id" => $customer->id]),
        ]);

        $user->update(["tenant_id" => $tenant->id]);

        $this->createTenantDomain($tenant, $user->identifier);

        Log::info(
            "Novo usuário criado: ID {$user->id}, Email: {$user->email}, " .
                "Stripe ID: {$customer->id}, Tenant ID: {$tenant->id}"
        );
    }

    protected function createTenantDomain($tenant, $userIdentifier)
    {
        $baseDomain = config("app.url");
        $domain = $this->generateUniqueDomain($userIdentifier, $baseDomain);

        Domain::create([
            "domain" => $domain,
            "tenant_id" => $tenant->id,
        ]);

        Log::info(
            "Domínio temporário criado para o tenant {$tenant->id}: {$domain}"
        );
    }

    protected function generateUniqueDomain($userIdentifier, $baseDomain)
    {
        $baseUrl = parse_url($baseDomain, PHP_URL_HOST);
        $domainPrefix = strtolower($userIdentifier);

        $domain = "{$domainPrefix}.{$baseUrl}";
        $counter = 1;

        while (Domain::where("domain", $domain)->exists()) {
            $domain = "{$domainPrefix}-{$counter}.{$baseUrl}";
            $counter++;
        }

        return $domain;
    }

    protected function generateTenancyName($customer)
    {
        $baseName = $customer->name ?? explode("@", $customer->email)[0];
        return Str::slug($baseName) . "-" . Str::random(6);
    }

    protected function generateUniqueDatabaseName()
    {
        do {
            $dbName = "tenant_" . Str::random(10);
        } while (Tenant::where("tenancy_db_name", $dbName)->exists());

        return $dbName;
    }

    protected function getPaymentMethodType($customer)
    {
        if ($customer->default_source) {
            return $customer->default_source->object;
        }
        return null;
    }

    protected function getPaymentMethodLastFour($customer)
    {
        if (
            $customer->default_source &&
            $customer->default_source->object === "card"
        ) {
            return $customer->default_source->last4;
        }
        return null;
    }

    protected function getTrialEndsAt($customer)
    {
        if ($customer->subscriptions && $customer->subscriptions->data) {
            $subscription = $customer->subscriptions->data[0];
            if ($subscription->trial_end) {
                return date("Y-m-d H:i:s", $subscription->trial_end);
            }
        }
        return null;
    }
}
