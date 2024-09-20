<?php

namespace App\Jobs;

use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Refund;
use Stripe\BalanceTransaction;
use App\Models\Payment;
use App\Models\Refund as RefundModel;
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

    protected $startingAfter;

    public function __construct(string $startingAfter = null)
    {
        $this->startingAfter = $startingAfter;
    }

    public function handle(): void
    {
        Log::info(
            "Iniciando o processamento de Charges com starting_after: " .
                $this->startingAfter
        );

        try {
            Stripe::setApiKey(config("services.stripe.secret"));
            $response = Charge::all([
                "limit" => 50,
                "starting_after" => $this->startingAfter,
            ]);

            Log::info(
                "Número de Charges recuperados: " . count($response->data)
            );

            foreach ($response->data as $charge) {
                $this->processCharge($charge);
            }

            if ($response->has_more) {
                $lastCharge = end($response->data);
                self::dispatch($lastCharge->id);
                Log::info(
                    "Agendando próximo job com starting_after: " .
                        $lastCharge->id
                );
            } else {
                Log::info(
                    "Processamento de Charges concluído. Não há mais dados para processar."
                );
            }
        } catch (\Exception $e) {
            Log::error("Falha ao processar Charges: " . $e->getMessage());
        }
    }

    protected function processCharge($charge)
    {
        Log::info(
            "Processando charge: " .
                $charge->id .
                " com status: " .
                $charge->status
        );

        $existingPayment = Payment::firstWhere(
            "stripe_payment_id",
            $charge->id
        );

        if ($existingPayment) {
            Log::info("Pagamento já existe: " . $existingPayment->id);
            $this->updateExistingPayment($existingPayment, $charge);
        } else {
            $this->createNewPayment($charge);
        }

        if ($charge->refunded || $charge->amount_refunded > 0) {
            $this->processRefunds($charge);
        }
    }

    protected function updateExistingPayment($payment, $charge)
    {
        $balanceTransaction = $this->getBalanceTransaction($charge);

        $updateData = [
            "amount_refunded" => $charge->amount_refunded / 100,
            "refunded" => $charge->refunded,
            "disputed" => $charge->disputed,
            "captured" => $charge->captured,
            "status" => $charge->status,
            "seller_message" => $charge->outcome->seller_message ?? null,
            "decline_reason" => $charge->outcome->reason ?? null,
        ];

        if ($balanceTransaction) {
            $updateData = array_merge($updateData, [
                "fee" => $balanceTransaction->fee / 100,
                "converted_amount" => $balanceTransaction->amount / 100,
                "converted_currency" => $balanceTransaction->currency,
            ]);
        }

        $payment->update($updateData);
        Log::info("Pagamento atualizado: " . $payment->id);
    }

    protected function createNewPayment($charge)
    {
        $balanceTransaction = $this->getBalanceTransaction($charge);

        $paymentData = [
            "stripe_payment_id" => $charge->id,
            "created_date" => now()->setTimestamp($charge->created),
            "amount" => $charge->amount / 100,
            "amount_refunded" => $charge->amount_refunded / 100,
            "currency" => $charge->currency,
            "refunded" => $charge->refunded,
            "disputed" => $charge->disputed,
            "captured" => $charge->captured,
            "description" => $charge->description ?? "No description provided",
            "status" => $charge->status,
            "seller_message" => $charge->outcome->seller_message ?? null,
            "decline_reason" => $charge->outcome->reason ?? null,
            "card_id" => $charge->payment_method ?? null,
            "customer_id" => $charge->customer ?? null,
            "customer_email" => $charge->billing_details->email ?? null,
            "invoice_id" => $charge->invoice ?? null,
            "statement_descriptor" => $charge->statement_descriptor ?? null,
            "payment_date" => now()->setTimestamp($charge->created),
            "additional_info" => json_encode([
                "payment_method_details" => $charge->payment_method_details,
                "risk_score" => $charge->outcome->risk_score ?? null,
                "risk_level" => $charge->outcome->risk_level ?? null,
            ]),
        ];

        if ($balanceTransaction) {
            $paymentData = array_merge($paymentData, [
                "fee" => $balanceTransaction->fee / 100,
                "converted_amount" => $balanceTransaction->amount / 100,
                "converted_currency" => $balanceTransaction->currency,
                "taxes_on_fee" => $this->calculateTaxesOnFee(
                    $balanceTransaction
                ),
            ]);
        }

        $payment = Payment::create($paymentData);

        Log::info("Novo pagamento criado: " . $payment->id);
    }
    protected function processRefunds($charge)
    {
        Log::info("Processando reembolsos para o charge: " . $charge->id);

        try {
            $refunds = Refund::all([
                "charge" => $charge->id,
                "limit" => 100,
            ]);

            Log::info(
                "Número de reembolsos encontrados: " . count($refunds->data)
            );

            foreach ($refunds->data as $refund) {
                $existingRefund = RefundModel::firstWhere(
                    "stripe_refund_id",
                    $refund->id
                );
                if ($existingRefund) {
                    Log::info("Reembolso já existe: " . $refund->id);
                    continue;
                }

                RefundModel::create([
                    "payment_id" => Payment::where(
                        "stripe_payment_id",
                        $charge->id
                    )->first()->id,
                    "stripe_refund_id" => $refund->id,
                    "amount" => $refund->amount / 100,
                    "status" => $refund->status,
                    "reason" => $refund->reason,
                    "refund_date" => now()->setTimestamp($refund->created),
                ]);

                Log::info("Novo reembolso processado: " . $refund->id);
            }
        } catch (\Exception $e) {
            Log::error(
                "Erro ao processar reembolsos para o charge {$charge->id}: " .
                    $e->getMessage()
            );
        }
    }

    protected function getBalanceTransaction($charge)
    {
        if ($charge->balance_transaction) {
            try {
                return BalanceTransaction::retrieve(
                    $charge->balance_transaction
                );
            } catch (\Exception $e) {
                Log::error(
                    "Erro ao recuperar balance transaction: " . $e->getMessage()
                );
            }
        }
        return null;
    }

    protected function calculateTaxesOnFee($balanceTransaction)
    {
        $taxesOnFee = 0;
        foreach ($balanceTransaction->fee_details as $feeDetail) {
            if ($feeDetail->type === "tax") {
                $taxesOnFee += $feeDetail->amount;
            }
        }
        return $taxesOnFee / 100;
    }
}
