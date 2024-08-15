<?php

namespace App\Actions\Central\Dashboard;

use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class CalculateAnnualPaymentTotals
{
    public function execute()
    {
        // Obter o total de pagamentos
        $totalAmount = Payment::sum("amount");
        $totalAmountFormatted = $this->formatPrice($totalAmount);

        // Consulta para obter o total de pagamentos por ano
        $yearlyTotals = Payment::select(
            DB::raw("YEAR(payment_date) as year"),
            DB::raw("SUM(amount) as total_amount")
        )
            ->groupBy(DB::raw("YEAR(payment_date)"))
            ->orderBy(DB::raw("YEAR(payment_date)"))
            ->get();

        // Formatação dos totais anuais
        $yearlyData = $yearlyTotals->mapWithKeys(function ($item) {
            return [$item->year => $this->formatPrice($item->total_amount)];
        });

        return [
            "total_amount" => $totalAmountFormatted,
            "yearly_totals" => $yearlyData,
        ];
    }

    private function formatPrice($amount)
    {
        return number_format($amount, 2, ",", ".");
    }
}
