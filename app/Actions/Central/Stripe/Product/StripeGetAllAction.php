<?php

namespace App\Actions\Central\Stripe;

use Stripe\Stripe;
use Stripe\PaymentMethod;
use Stripe\Customer;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class StripeGetAllAction
{
    public static function getAllPaymentIntents()
    {
        // Configure a chave secreta do Stripe
        Stripe::setApiKey(
            "sk_test_51LRjEpGQW0U1PfqxjAwrWJaaaML9e8xOtowprEoQOF8j2z2Nvn9a5P8KkvDpgVzmeBpCdczITYNhvI1DMYs18qRb00e3YMKUXY"
        );
        // Faz uma chamada à API para buscar todos os PaymentIntents
        $paymentIntents = \Stripe\PaymentIntent::all(["limit" => 10]);

        // Salva os pagamentos no banco de dados
        foreach ($paymentIntents->data as $paymentIntent) {
            self::savePaymentToDatabase($paymentIntent);
        }

        return $paymentIntents->data;
    }

    public static function getPaymentIntentDetails($paymentId)
    {
        // Configure a chave secreta do Stripe
        Stripe::setApiKey(config("services.stripe.secret"));

        // Faz uma chamada à API para buscar o PaymentIntent com o ID fornecido
        $paymentIntent = \Stripe\PaymentIntent::retrieve($paymentId);

        // Salva o pagamento no banco de dados
        self::savePaymentToDatabase($paymentIntent); // Agora podemos acessar diretamente

        return $paymentIntent;
    }

    public static function savePaymentToDatabase($paymentIntent)
    {
        try {
            // Configure a chave secreta do Stripe
            Stripe::setApiKey(config("services.stripe.secret"));

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
            // Trate qualquer exceção conforme necessário
            return false;
        }

        return true;
    }
}
