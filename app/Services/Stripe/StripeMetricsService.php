<?php

namespace App\Services\Stripe;

use Stripe\Stripe;
use Illuminate\Support\Facades\Log;

class StripeMetricsService
{
    public function __construct()
    {
        // Configure a chave secreta do Stripe
        Stripe::setApiKey(config("services.stripe.secret"));
    }

    /**
     * Obter volume bruto de pagamentos.
     *
     * @return array
     */
    public function getMonthlyGrossVolumes()
    {
        try {
            $monthlyVolumes = [];

            // Definir anos inicial e final para consulta (ajuste conforme necessidade)
            $startYear = 2020; // Ano inicial
            $endYear = date("Y"); // Ano atual

            // Loop para buscar volumes brutos mensais
            for ($year = $startYear; $year <= $endYear; $year++) {
                for ($month = 1; $month <= 12; $month++) {
                    // Definir data inicial e final para o mês atual
                    $startDate = strtotime("$year-$month-01");
                    $endDate = strtotime(
                        date(
                            "Y-m-d",
                            strtotime("first day of next month", $startDate)
                        )
                    );

                    $grossVolume = 0;

                    // Faz uma chamada à API do Stripe para obter as transações de saldo
                    $params = [
                        "limit" => 100,
                        "created" => [
                            "gte" => $startDate,
                            "lt" => $endDate,
                        ],
                    ];

                    $balanceTransactions = \Stripe\BalanceTransaction::all(
                        $params
                    );

                    foreach ($balanceTransactions->data as $transaction) {
                        if (
                            $transaction->type === "charge" &&
                            $transaction->created >= $startDate &&
                            $transaction->created < $endDate
                        ) {
                            $grossVolume += $transaction->amount;
                        }
                    }

                    // Armazenar os volumes brutos para o mês atual
                    $monthlyVolumes[] = [
                        "year" => $year,
                        "month" => $month,
                        "gross_volume" => $grossVolume,
                    ];
                }
            }

            return $monthlyVolumes;
        } catch (\Exception $e) {
            Log::error(
                "Falha ao obter volume bruto mensal: " . $e->getMessage()
            );
            return ["error" => $e->getMessage()];
        }
    }

    /**
     * Obter todos os pagamentos.
     *
     * @return array
     */
    public function getAllPayments()
    {
        try {
            $payments = [];
            $startingAfter = null;

            do {
                // Faz uma chamada à API do Stripe para obter os PaymentIntents
                $params = ["limit" => 100];
                if ($startingAfter) {
                    $params["starting_after"] = $startingAfter;
                }

                $paymentIntents = \Stripe\PaymentIntent::all($params);
                $payments = array_merge($payments, $paymentIntents->data);

                // Obtenha o ID do último PaymentIntent para paginação
                $startingAfter = end($paymentIntents->data)->id;
            } while ($paymentIntents->has_more);

            return $payments;
        } catch (\Exception $e) {
            Log::error("Falha ao obter pagamentos: " . $e->getMessage());
            return ["error" => $e->getMessage()];
        }
    }

    /**
     * Obter outras métricas desejadas.
     *
     * @return array
     */
    public function getOtherMetrics()
    {
        try {
            $refunds = [];
            $startingAfter = null;

            do {
                // Faz uma chamada à API do Stripe para obter os reembolsos
                $params = ["limit" => 100];
                if ($startingAfter) {
                    $params["starting_after"] = $startingAfter;
                }

                $refundsResponse = \Stripe\Refund::all($params);
                $refunds = array_merge($refunds, $refundsResponse->data);

                // Obtenha o ID do último reembolso para paginação
                $startingAfter = end($refundsResponse->data)->id;
            } while ($refundsResponse->has_more);

            $totalRefunds = array_reduce(
                $refunds,
                function ($carry, $refund) {
                    return $carry + $refund->amount;
                },
                0
            );

            return [
                "total_refunds" => $totalRefunds,
                "currency" => $refunds[0]->currency,
            ];
        } catch (\Exception $e) {
            Log::error("Falha ao obter outras métricas: " . $e->getMessage());
            return ["error" => $e->getMessage()];
        }
    }
}
