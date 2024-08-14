<?php
namespace App\Actions\Central\Dashboard;

use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class GetTotalPayments
{
    public function execute()
    {
        // Consulta para somar todos os pagamentos
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

        // Consulta para obter o total de pagamentos por mês
        $monthlyTotals = Payment::select(
            DB::raw("YEAR(payment_date) as year"),
            DB::raw("MONTH(payment_date) as month"),
            DB::raw("SUM(amount) as total_amount")
        )
            ->groupBy(
                DB::raw("YEAR(payment_date)"),
                DB::raw("MONTH(payment_date)")
            )
            ->orderBy(DB::raw("YEAR(payment_date)"))
            ->orderBy(DB::raw("MONTH(payment_date)"))
            ->get();

        // Lista de todos os meses do ano
        $months = [
            1 => "January",
            2 => "February",
            3 => "March",
            4 => "April",
            5 => "May",
            6 => "June",
            7 => "July",
            8 => "August",
            9 => "September",
            10 => "October",
            11 => "November",
            12 => "December",
        ];

        // Formatação dos totais anuais
        $yearlyData = $yearlyTotals->mapWithKeys(function ($item) {
            return [$item->year => $this->formatPrice($item->total_amount)];
        });

        // Formatação dos totais mensais
        $monthlyData = [];
        foreach ($monthlyTotals as $total) {
            $monthName = $months[$total->month];
            if (!isset($monthlyData[$total->year])) {
                $monthlyData[$total->year] = [];
            }
            $monthlyData[$total->year][$monthName] = $this->formatPrice(
                $total->total_amount
            );
        }

        return [
            "total_amount" => $totalAmountFormatted,
            "yearly_totals" => $yearlyData,
            "monthly_totals" => $monthlyData,
        ];
    }

    private function formatPrice($amount)
    {
        return number_format($amount, 2, ",", ".");
    }
}
