<?php

namespace App\Actions\Central\Dashboard;

use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class GetDashboardData
{
    public function execute()
    {
        // Construir a query base
        $query = Payment::select(
            DB::raw("SUM(amount) as total_amount"),
            DB::raw("YEAR(payment_date) as year"),
            DB::raw("MONTH(payment_date) as month")
        )
            ->groupBy(
                DB::raw("YEAR(payment_date)"),
                DB::raw("MONTH(payment_date)")
            )
            ->orderBy(DB::raw("YEAR(payment_date)"), "desc")
            ->orderBy(DB::raw("MONTH(payment_date)"), "desc");

        // Recuperar os dados
        $payments = $query->get();

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

        // Calcular a receita total
        $totalRevenue = $payments->sum("total_amount");

        // Calcular a variação percentual em relação ao mês anterior
        $currentMonthRevenue = $payments->first()->total_amount ?? 0;
        $previousMonthRevenue = $payments->skip(1)->first()->total_amount ?? 0;
        $percentageChange =
            $previousMonthRevenue != 0
                ? (($currentMonthRevenue - $previousMonthRevenue) /
                        $previousMonthRevenue) *
                    100
                : 0;

        // Agrupar por mês e montar a estrutura desejada
        $groupedData = [];
        foreach ($months as $monthNumber => $monthName) {
            $groupedData[$monthName] = ["month" => $monthName];
        }

        foreach ($payments as $payment) {
            $monthName = $months[$payment->month]; // Nome do mês
            $groupedData[$monthName][(string) $payment->year] =
                $payment->total_amount;
        }

        // Preencher os meses que não têm valores com 0
        foreach ($groupedData as $monthName => $data) {
            foreach ($payments as $payment) {
                if (!isset($groupedData[$monthName][(string) $payment->year])) {
                    $groupedData[$monthName][(string) $payment->year] = 0;
                }
            }
        }

        // Transformar array associativo em array simples
        $groupedDataArray = array_values($groupedData);

        return [
            "monthlyPayments" => $groupedDataArray,
            "totalRevenue" => $totalRevenue,
            "percentageChange" => $percentageChange,
        ];
    }
}
