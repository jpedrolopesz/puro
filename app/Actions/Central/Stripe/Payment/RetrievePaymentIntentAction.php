<?php

namespace App\Actions\Central\Stripe\Payment;

use Stripe\PaymentIntent;
use Stripe\Customer;
use Stripe\PaymentMethod;
use Exception;

class RetrievePaymentIntentAction
{
    public function execute(string $paymentId): array
    {
        try {
            $paymentIntent = PaymentIntent::retrieve($paymentId);

            if (!$paymentIntent) {
                throw new Exception("Payment not found.");
            }

            $customer = $paymentIntent->customer
                ? Customer::retrieve($paymentIntent->customer)
                : null;
            $paymentMethod = $paymentIntent->payment_method
                ? PaymentMethod::retrieve($paymentIntent->payment_method)
                : null;

            return [
                "id" => $paymentIntent->id,
                "amount" => $paymentIntent->amount,
                "currency" => $paymentIntent->currency,
                "status" => $paymentIntent->status,
                "created" => $paymentIntent->created,
                "customer_name" => $customer ? $customer->name : null,
                "customer_email" => $customer ? $customer->email : null,
                "customer_address" => $customer ? $customer->address : null,
                "customer_phone" => $customer ? $customer->phone : null,
                "description" => $paymentIntent->description,
                "payment_method" => $paymentIntent->payment_method,
                "card_brand" => $paymentMethod
                    ? ($paymentMethod->type == "card"
                        ? $paymentMethod->card->brand
                        : null)
                    : null,
                "card_last4" => $paymentMethod
                    ? ($paymentMethod->type == "card"
                        ? $paymentMethod->card->last4
                        : null)
                    : null,
                "card_exp_month" => $paymentMethod
                    ? ($paymentMethod->type == "card"
                        ? $paymentMethod->card->exp_month
                        : null)
                    : null,
                "card_exp_year" => $paymentMethod
                    ? ($paymentMethod->type == "card"
                        ? $paymentMethod->card->exp_year
                        : null)
                    : null,
                "receipt_email" => $paymentIntent->receipt_email,
                "metadata" => $paymentIntent->metadata,
                "application_fee_amount" =>
                    $paymentIntent->application_fee_amount ?? 0,
                "capture_method" => $paymentIntent->capture_method,
            ];
        } catch (Exception $e) {
            throw new Exception(
                "Failed to retrieve payment details: " . $e->getMessage()
            );
        }
    }
}
