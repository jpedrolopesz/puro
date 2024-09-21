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

class ImportStripeUsersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $startingAfter;

    public function __construct(string $startingAfter = null)
    {
        $this->startingAfter = $startingAfter;
    }

    public function handle(): void
    {
        Log::info(
            "Iniciando importação de usuários do Stripe com starting_after: " .
                $this->startingAfter
        );

        try {
            Stripe::setApiKey(config("services.stripe.secret"));
            $response = Customer::all([
                "limit" => 100,
                "starting_after" => $this->startingAfter,
                "expand" => ["data.subscriptions", "data.default_source"],
            ]);

            Log::info(
                "Número de clientes do Stripe recuperados: " .
                    count($response->data)
            );

            foreach ($response->data as $customer) {
                try {
                    DB::beginTransaction();
                    $this->processCustomer($customer);
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error(
                        "Erro ao processar cliente {$customer->id}: " .
                            $e->getMessage()
                    );
                }
            }

            if ($response->has_more) {
                $lastCustomer = end($response->data);
                self::dispatch($lastCustomer->id)->delay(now()->addSeconds(5));
                Log::info(
                    "Agendando próximo job com starting_after: " .
                        $lastCustomer->id
                );
            } else {
                Log::info(
                    "Importação de usuários do Stripe concluída. Não há mais dados para processar."
                );
            }
        } catch (\Exception $e) {
            Log::error(
                "Falha ao importar usuários do Stripe: " . $e->getMessage()
            );
        }
    }

    protected function processCustomer($customer)
    {
        Log::info("Processando cliente do Stripe: " . $customer->id);

        $user = User::where("stripe_id", $customer->id)->first();

        if (!$user) {
            $user = User::where("email", $customer->email)->first();
        }

        if ($user) {
            $this->updateExistingUser($user, $customer);
        } else {
            $this->createNewUserWithTenant($customer);
        }
    }

    protected function updateExistingUser($user, $customer)
    {
        $user->update([
            "stripe_id" => $customer->id,
            "name" => $customer->name ?? $user->name,
            "email" => $customer->email,
            "pm_type" => $this->getPaymentMethodType($customer),
            "pm_last_four" => $this->getPaymentMethodLastFour($customer),
            "trial_ends_at" => $this->getTrialEndsAt($customer),
        ]);

        Log::info(
            "Usuário atualizado: " .
                $user->id .
                " com Stripe ID: " .
                $customer->id
        );
    }

    protected function createNewUserWithTenant($customer)
    {
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
            "Novo usuário criado: " .
                $user->id .
                " com Stripe ID: " .
                $customer->id .
                " e Tenant ID: " .
                $tenant->id
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
