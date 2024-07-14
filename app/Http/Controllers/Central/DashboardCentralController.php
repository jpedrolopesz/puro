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
        $annualVolumes = $this->stripeMetricsService->getAnnualGrossVolumes();

        // Montar os dados no formato desejado
        $data = [];

        foreach ($annualVolumes as $volume) {
            $data[] = [
                "year" => $volume["year"],
                "Export Growth Rate" => $volume["gross_volume"] * 0.02, // Exemplo de cálculo fictício
                "Import Growth Rate" => $volume["gross_volume"] * 0.015, // Exemplo de cálculo fictício
            ];
        }

        return Inertia::render("Central/Dashboard/DashboardCentral", [
            "admin" => Auth::guard("admin")->user(),
            "data" => $data,
        ]);
    }
}
