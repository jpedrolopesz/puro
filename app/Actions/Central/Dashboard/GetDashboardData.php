<?php

namespace App\Actions\Central\Dashboard;

use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class GetDashboardData
{
    public function execute()
    {
        $query = Payment::select(
            DB::raw("SUM(amount) as total_amount"),
            DB::raw("YEAR(payment_date) as year"),
            DB::raw("MONTH(payment_date) as month")
        )
            ->groupBy(
                DB::raw("YEAR(payment_date)"),
                DB::raw("MONTH(payment_date)")
            )
            ->orderBy(DB::raw("YEAR(payment_date)"))
            ->orderBy(DB::raw("MONTH(payment_date)"));

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

        // Identificar todos os anos presentes nos pagamentos
        $years = $payments->pluck("year")->unique()->sort()->values();

        // Agrupar por mês e montar a estrutura desejada
        $groupedData = [];
        foreach ($months as $monthNumber => $monthName) {
            $groupedData[$monthName] = ["month" => $monthName];
            // Inicializar todos os anos com 0
            foreach ($years as $year) {
                $groupedData[$monthName][(string) $year] = 0;
            }
        }

        // Preencher os valores reais dos pagamentos
        foreach ($payments as $payment) {
            $monthName = $months[$payment->month]; // Nome do mês
            $groupedData[$monthName][(string) $payment->year] =
                $payment->total_amount;
        }

        // Transformar array associativo em array simples
        return array_values($groupedData);
    }
}
