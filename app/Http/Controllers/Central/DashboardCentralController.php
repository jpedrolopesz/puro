<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Services\Stripe\StripeMetricsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardCentralController extends Controller
{
    protected $stripeMetricsService;

    public function __construct(StripeMetricsService $stripeMetricsService)
    {
        $this->stripeMetricsService = $stripeMetricsService;
    }
    public function index()
    {
        //$grossVolume = $this->stripeMetricsService->getGrossVolume();
        //$allPayments = $this->stripeMetricsService->getAllPayments();
        //$otherMetrics = $this->stripeMetricsService->getOtherMetrics();

        //dd($grossVolume);
        //dd($allPayments);
        // dd($otherMetrics);
        //
        //
        //
        // Obter o volume bruto de pagamentos
        $annualVolumes = $this->stripeMetricsService->getMonthlyGrossVolumes();

        // Montar os dados no formato desejado
        $data = [];

        foreach ($annualVolumes as $volume) {
            // Para cada mês do ano, formate o ano e o mês juntos
            for ($month = 1; $month <= 12; $month++) {
                $data[] = [
                    "year" =>
                        $volume["year"] . str_pad($month, 2, "0", STR_PAD_LEFT), // Formato: "YYYY-MM"
                    "Volume bruto" => $volume["gross_volume"] * 0.02, // Exemplo de cálculo fictício
                    "Volume líquido de vendas" =>
                        $volume["gross_volume"] * 0.015, // Exemplo de cálculo fictício
                ];
            }
        }

        return Inertia::render("Central/Dashboard/DashboardCentral", [
            "admin" => Auth::guard("admin")->user(),
            "gross_volume" => $data,
        ]);
    }
}
