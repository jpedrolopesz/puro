<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class DashboardCentralController extends Controller
{
    public function index(Request $request)
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
            ->orderBy(DB::raw("YEAR(payment_date)"))
            ->orderBy(DB::raw("MONTH(payment_date)"));

        // Recuperar os dados
        $payments = $query->get();

        // Agrupar por mês e montar a estrutura desejada
        $groupedData = [];
        foreach ($payments as $payment) {
            $monthName = Carbon::create()
                ->month($payment->month)
                ->format("F"); // Nome do mês
            if (!isset($groupedData[$monthName])) {
                $groupedData[$monthName] = [
                    "month" => $monthName,
                ];
            }
            $groupedData[$monthName][(string) $payment->year] =
                $payment->total_amount;
        }

        // Transformar array associativo em array simples
        $result = ["date" => array_values($groupedData)];

        return Inertia::render("Central/Dashboard/DashboardCentral", [
            "admin" => Auth::guard("admin")->user(),
            "resultDate" => $result,
        ]);
    }
}
