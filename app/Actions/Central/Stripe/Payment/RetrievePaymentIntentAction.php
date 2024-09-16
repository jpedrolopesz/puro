<?php

namespace App\Actions\Central\Stripe\Payment;

use Stripe\PaymentIntent;
use Stripe\Customer;
use Stripe\PaymentMethod;
use Stripe\Charge;
use Stripe\Refund;
use Exception;
use Illuminate\Support\Facades\Log;

class RetrievePaymentIntentAction
{
    public function execute(string $chargeId): array
    {
        try {
            Log::info("Iniciando a recuperação do Charge: " . $chargeId);

            $charge = Charge::retrieve($chargeId);
            if (!$charge) {
                throw new Exception("Charge not found.");
            }

            $paymentIntent = $charge->payment_intent
                ? PaymentIntent::retrieve($charge->payment_intent)
                : null;

            $customer = $charge->customer
                ? Customer::retrieve($charge->customer)
                : null;

            $paymentMethod = $charge->payment_method
                ? PaymentMethod::retrieve($charge->payment_method)
                : null;

            $refunds = $this->getRefunds($charge->id);

            $paymentData = [
                "id" => $charge->id,
                "amount" => $charge->amount / 100, // Convert to decimal
                "currency" => $charge->currency,
                "status" => $charge->status,
                "created" => $charge->created,
                "customer_name" => $customer ? $customer->name : null,
                "customer_email" => $customer ? $customer->email : null,
                "description" => $charge->description,
                "payment_method" => $charge->payment_method,
                "card_brand" =>
                    $charge->payment_method_details->card->brand ?? null,
                "card_last4" =>
                    $charge->payment_method_details->card->last4 ?? null,
                "card_exp_month" =>
                    $charge->payment_method_details->card->exp_month ?? null,
                "card_exp_year" =>
                    $charge->payment_method_details->card->exp_year ?? null,
                "receipt_email" => $charge->receipt_email,
                "metadata" => $charge->metadata,
                "application_fee_amount" =>
                    ($charge->application_fee_amount ?? 0) / 100,
                "capture_method" => $paymentIntent
                    ? $paymentIntent->capture_method
                    : null,
                "amount_refunded" => $charge->amount_refunded / 100,
                "refunded" => $charge->refunded,
                "disputed" => $charge->disputed,
                "failure_code" => $charge->failure_code,
                "failure_message" => $charge->failure_message,
                "captured" => $charge->captured,
                "payment_date" => date("Y-m-d H:i:s", $charge->created),
                "refunds" => $refunds,
            ];

            Log::info("Charge recuperado com sucesso: " . $chargeId);

            return $paymentData;
        } catch (Exception $e) {
            Log::error(
                "Falha ao recuperar detalhes do pagamento: " . $e->getMessage()
            );
            throw new Exception(
                "Failed to retrieve payment details: " . $e->getMessage()
            );
        }
    }

    protected function getRefunds(string $chargeId): array
    {
        try {
            $refunds = Refund::all(["charge" => $chargeId]);
            return array_map(function ($refund) {
                return [
                    "id" => $refund->id,
                    "amount" => $refund->amount / 100,
                    "status" => $refund->status,
                    "reason" => $refund->reason,
                    "refund_date" => date("Y-m-d H:i:s", $refund->created),
                ];
            }, $refunds->data);
        } catch (Exception $e) {
            Log::error(
                "Erro ao processar reembolsos para o charge {$chargeId}: " .
                    $e->getMessage()
            );
            return [];
        }
    }
}
