<?php

namespace App\Jobs;

use Stripe\Stripe;
use Stripe\Customer;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;
use App\Models\Payment;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessStripePayments implements ShouldQueue
{
    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels,
        Batchable;

    protected $paymentIntentId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $paymentIntentId)
    {
        $this->paymentIntentId = $paymentIntentId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        try {
            // Configure a chave secreta do Stripe
            Stripe::setApiKey(config("services.stripe.secret"));

            // Recupere o PaymentIntent usando o ID
            $paymentIntent = PaymentIntent::retrieve($this->paymentIntentId);

            $existingPayment = Payment::where(
                "stripe_payment_id",
                $paymentIntent->id
            )->first();

            if ($existingPayment) {
                // Se o pagamento já existe, não faça nada
                Log::info("Pagamento já existe: " . $existingPayment->id);
                return;
            }
            // Recupere os detalhes do cliente, se disponível
            $customerDetails = $paymentIntent->customer
                ? Customer::retrieve($paymentIntent->customer)
                : null;

            // Recupere os detalhes do método de pagamento, se disponível
            $paymentMethodDetails = $paymentIntent->payment_method
                ? PaymentMethod::retrieve($paymentIntent->payment_method)
                : null;

            // Crie um novo pagamento no banco de dados
            $payment = new Payment();
            $payment->stripe_payment_id = $paymentIntent->id;
            $payment->amount = $paymentIntent->amount;
            $payment->currency = $paymentIntent->currency;
            $payment->status = $paymentIntent->status;
            $payment->payment_date = date(
                "Y-m-d H:i:s",
                $paymentIntent->created
            );
            $payment->customer_name = $customerDetails
                ? $customerDetails->name
                : null;
            $payment->customer_email = $customerDetails
                ? $customerDetails->email
                : null;

            $payment->description = $paymentIntent->description
                ? $paymentIntent->description
                : null;

            // Dados sensíveis do método de pagamento
            $payment->payment_method_type = $paymentMethodDetails
                ? $paymentMethodDetails->type
                : null;
            $payment->payment_method_last4 = $paymentMethodDetails
                ? $paymentMethodDetails->card->last4
                : null;
            $payment->payment_method_brand = $paymentMethodDetails
                ? $paymentMethodDetails->card->brand
                : null;

            $payment->receipt_email = $paymentIntent->receipt_email;
            $payment->application_fee_amount =
                $paymentIntent->application_fee_amount ?? null;
            $payment->capture_method = $paymentIntent->capture_method;

            // Salve o pagamento no banco de dados
            $payment->save();

            Log::info("Pagamento salvo com sucesso: " . $payment->id);
        } catch (\Exception $e) {
            Log::error("Falha ao salvar pagamento: " . $e->getMessage());
        }
    }
}
