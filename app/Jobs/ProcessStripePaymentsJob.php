<?php

namespace App\Jobs;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Models\Payment;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessStripePaymentsJob implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels,
        Batchable;

    /**
     * @var string|null
     */
    protected $startingAfter;

    /**
     * Create a new job instance.
     *
     * @param string|null $startingAfter
     * @return void
     */
    public function __construct(string $startingAfter = null)
    {
        $this->startingAfter = $startingAfter;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        Log::info(
            "Iniciando o processamento de PaymentIntents com starting_after: " .
                $this->startingAfter
        );

        try {
            // Configure a chave secreta do Stripe
            Stripe::setApiKey(config("services.stripe.secret"));

            // Buscar PaymentIntents com paginação
            $response = PaymentIntent::all([
                "limit" => 50,
                "starting_after" => $this->startingAfter,
            ]);

            // Processar os PaymentIntents
            foreach ($response->data as $paymentIntent) {
                // Verifique se o pagamento já existe
                $existingPayment = Payment::where(
                    "stripe_payment_id",
                    $paymentIntent->id
                )->first();

                if ($existingPayment) {
                    Log::info("Pagamento já existe: " . $existingPayment->id);
                    continue; // Pule para o próximo PaymentIntent
                }

                // Recupere os detalhes do cliente, se disponível
                $customerDetails = $paymentIntent->customer
                    ? \Stripe\Customer::retrieve($paymentIntent->customer)
                    : null;

                // Recupere os detalhes do método de pagamento, se disponível
                $paymentMethodDetails = $paymentIntent->payment_method
                    ? \Stripe\PaymentMethod::retrieve(
                        $paymentIntent->payment_method
                    )
                    : null;

                // Crie um novo pagamento no banco de dados
                $payment = new Payment([
                    "stripe_payment_id" => $paymentIntent->id,
                    "user_id" => null, // Defina o usuário associado se necessário
                    "amount" => $paymentIntent->amount / 100, // Converta de centavos para unidades (se aplicável)
                    "currency" => $paymentIntent->currency,
                    "status" => $paymentIntent->status,
                    "description" =>
                        $paymentIntent->description ??
                        "No description provided",
                    "payment_date" => (new \DateTime())
                        ->setTimestamp($paymentIntent->created)
                        ->format("Y-m-d H:i:s"),
                    "customer_name" => $customerDetails->name ?? null,
                    "customer_email" => $customerDetails->email ?? null,
                    "payment_method_type" =>
                        $paymentMethodDetails->type ?? null,
                    "payment_method_last4" =>
                        $paymentMethodDetails->card->last4 ?? null,
                    "payment_method_brand" =>
                        $paymentMethodDetails->card->brand ?? null,
                    "receipt_email" => $paymentIntent->receipt_email,
                    "application_fee_amount" =>
                        $paymentIntent->application_fee_amount ?? null,
                    "capture_method" => $paymentIntent->capture_method,
                ]);

                // Salve o pagamento no banco de dados
                $payment->save();
            }

            // Verifique se há mais PaymentIntents para processar
            if ($response->has_more) {
                $lastPaymentIntent = end($response->data);
                // Despache um novo job com o próximo ponto de partida
                ProcessStripePaymentsJob::dispatch($lastPaymentIntent->id);
            }
        } catch (\Exception $e) {
            Log::error(
                "Falha ao processar PaymentIntents: " . $e->getMessage()
            );
        }
    }
}
