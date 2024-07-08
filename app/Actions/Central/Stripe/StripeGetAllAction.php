<?php

namespace App\Actions\Central\Stripe;

use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\PaymentMethod;
use Stripe\Customer;
use App\Data\DTO\PaymentStripeDetailsDTO;

class StripeGetAllAction
{
    public static function getAllPaymentIntents()
    {
        // Configure a chave secreta do Stripe
        Stripe::setApiKey(
            "sk_test_51LRjEpGQW0U1PfqxjAwrWJaaaML9e8xOtowprEoQOF8j2z2Nvn9a5P8KkvDpgVzmeBpCdczITYNhvI1DMYs18qRb00e3YMKUXY"
        );
        // Faz uma chamada à API para buscar todos os PaymentIntents
        $paymentIntents = PaymentIntent::all(["limit" => 1]);

        // Monta um array com as propriedades desejadas de cada pagamento
        $paymentList = [];

        foreach ($paymentIntents->data as $paymentIntent) {
            $paymentList[] = self::preparePaymentDTO($paymentIntent);
        }

        return $paymentList;
    }

    public static function getPaymentIntentDetails($paymentId)
    {
        // Configure a chave secreta do Stripe
        Stripe::setApiKey(config("services.stripe.secret"));

        // Faz uma chamada à API para buscar o PaymentIntent com o ID fornecido
        $paymentIntent = PaymentIntent::retrieve($paymentId);

        if (!$paymentIntent) {
            return null;
        }

        return self::preparePaymentDTO($paymentIntent);
    }

    private static function preparePaymentDTO($paymentIntent)
    {
        // Fetch customer details if available
        $customerDetails = $paymentIntent->customer
            ? Customer::retrieve($paymentIntent->customer)
            : null;

        // Fetch payment method details if available
        $paymentMethodDetails = $paymentIntent->payment_method
            ? PaymentMethod::retrieve($paymentIntent->payment_method)
            : null;

        return new PaymentStripeDetailsDTO(
            $paymentIntent->id,
            $paymentIntent->amount,
            $paymentIntent->currency,
            $paymentIntent->status,
            $paymentIntent->created,
            $customerDetails ? $customerDetails->name : null,
            $customerDetails ? $customerDetails->email : null,
            $customerDetails ? $customerDetails->address : null,
            $customerDetails ? $customerDetails->phone : null,
            $paymentIntent->description,
            $paymentIntent->payment_method,
            $paymentMethodDetails ? $paymentMethodDetails->card->brand : null,
            $paymentMethodDetails ? $paymentMethodDetails->card->last4 : null,
            $paymentMethodDetails
                ? $paymentMethodDetails->card->exp_month
                : null,
            $paymentMethodDetails
                ? $paymentMethodDetails->card->exp_year
                : null,
            $paymentIntent->receipt_email,
            $paymentIntent->metadata,
            $paymentIntent->application_fee_amount ?? 0,
            $paymentIntent->capture_method
        );
    }
}
