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
                "stripe_payment_id" => $charge->id,
                "amount" => $charge->amount / 100,
                "amount_refunded" => $charge->amount_refunded / 100,
                "currency" => $charge->currency,
                "refunded" => $charge->refunded,
                "disputed" => $charge->disputed,
                "captured" => $charge->captured,
                "description" => $charge->description,
                "fee" => ($charge->application_fee_amount ?? 0) / 100,
                "status" => $charge->status,
                "seller_message" => $charge->outcome->seller_message ?? null,
                "decline_reason" => $charge->outcome->reason ?? null,
                "card_id" => $charge->payment_method,
                "customer_id" => $charge->customer,
                "customer_name" => $customer ? $customer->name : null,
                "customer_email" => $customer ? $customer->email : null,
                "customer_description" => $customer
                    ? $customer->description
                    : null,
                "invoice_id" => $charge->invoice ?? null,
                "statement_descriptor" => $charge->statement_descriptor,
                "payment_date" => date("Y-m-d H:i:s", $charge->created),
            ];

            // Retrieve balance transaction details
            if ($charge->balance_transaction) {
                $balanceTransaction = \Stripe\BalanceTransaction::retrieve(
                    $charge->balance_transaction
                );

                $paymentData["fee"] = $balanceTransaction->fee / 100;
                $paymentData["net_amount"] = $balanceTransaction->net / 100;

                // Detailed fee breakdown
                $paymentData["fee_details"] = array_map(function ($feeDetail) {
                    return [
                        "type" => $feeDetail->type,
                        "amount" => $feeDetail->amount / 100,
                        "currency" => $feeDetail->currency,
                        "description" => $feeDetail->description ?? null,
                    ];
                }, $balanceTransaction->fee_details);

                // Exchange rate information
                if ($balanceTransaction->exchange_rate) {
                    $paymentData["converted_amount"] =
                        $balanceTransaction->amount / 100;
                    $paymentData["converted_currency"] =
                        $balanceTransaction->currency;
                    $paymentData["exchange_rate"] =
                        $balanceTransaction->exchange_rate;
                }
            }

            $paymentData["additional_info"] = json_encode([
                "payment_method_details" => $charge->payment_method_details,
                "risk_score" => $charge->outcome->risk_score ?? null,
                "risk_level" => $charge->outcome->risk_level ?? null,
                "receipt_email" => $charge->receipt_email,
                "metadata" => $charge->metadata,
                "capture_method" => $paymentIntent
                    ? $paymentIntent->capture_method
                    : null,
                "failure_code" => $charge->failure_code,
                "failure_message" => $charge->failure_message,
            ]);

            // Add refunds if you're handling them separately
            if (!empty($refunds)) {
                $paymentData["refunds"] = $refunds;
            }

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
