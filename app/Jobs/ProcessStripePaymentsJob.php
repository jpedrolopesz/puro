<?php

namespace App\Jobs;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Charge;
use Stripe\Refund;
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

        if ($charge->status === "failed") {
            Log::info("Cobrança falhou: " . $charge->id);
        }

        if ($charge->refunded || $charge->amount_refunded > 0) {
            $this->processRefunds($charge);
        }
    }

    protected function updateExistingPayment($payment, $charge)
    {
        $payment->update([
            "status" => $charge->status,
            "amount_refunded" => $charge->amount_refunded / 100,
            "refunded" => $charge->refunded,
        ]);
        Log::info("Pagamento atualizado: " . $payment->id);
    }

    protected function createNewPayment($charge)
    {
        $payment = Payment::create([
            "stripe_payment_id" => $charge->id,
            "user_id" => null, // Você pode querer associar ao usuário se possível
            "amount" => $charge->amount / 100,
            "currency" => $charge->currency,
            "status" => $charge->status,
            "description" => $charge->description ?? "No description provided",
            "payment_date" => now()->setTimestamp($charge->created),
            "customer_name" => $charge->billing_details->name,
            "customer_email" => $charge->billing_details->email,
            "payment_method_type" => $charge->payment_method_details->type,
            "payment_method_last4" =>
                $charge->payment_method_details->card->last4,
            "payment_method_brand" =>
                $charge->payment_method_details->card->brand,
            "receipt_email" => $charge->receipt_email,
            "amount_refunded" => $charge->amount_refunded / 100,
            "refunded" => $charge->refunded,
            "disputed" => $charge->disputed,
            "failure_code" => $charge->failure_code,
            "failure_message" => $charge->failure_message,
            "captured" => $charge->captured,
        ]);
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
}
