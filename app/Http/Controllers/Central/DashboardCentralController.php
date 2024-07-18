<?php

namespace App\Http\Controllers\Central;

use App\Actions\Central\Dashboard\GetDashboardData;
use App\Http\Controllers\Controller;
use App\Services\Stripe\StripeMetricsService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class DashboardCentralController extends Controller
{
    protected $getDashboardData;
    protected $stripeMetricsService;

    public function __construct(
        GetDashboardData $getDashboardData,
        StripeMetricsService $stripeMetricsService
    ) {
        $this->getDashboardData = $getDashboardData;
        $this->stripeMetricsService = $stripeMetricsService;
    }
    public function index(Request $request)
    {
        $monthlyPayments = $this->getDashboardData->execute();

        // Obtém os valores do StripeMetricsService
        $totalRevenue = $this->stripeMetricsService->getTotalRevenue();

        return Inertia::render("Central/Dashboard/DashboardCentral", [
            "admin" => Auth::guard("admin")->user(),
            "monthlyPayments" => $monthlyPayments,
            "totalRevenue" => $totalRevenue,
        ]);
    }
}
