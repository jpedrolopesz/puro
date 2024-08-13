<?php

namespace App\Http\Controllers\Central;

use App\Actions\Central\Dashboard\GetDashboardData;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;

class DashboardCentralController extends Controller
{
    protected $getDashboardData;

    public function __construct(GetDashboardData $getDashboardData)
    {
        $this->getDashboardData = $getDashboardData;
    }
    public function index(Request $request)
    {
        $monthlyPayments = $this->getDashboardData->execute();

        // Obtém os valores do StripeMetricsService

        return Inertia::render("Central/Dashboard/DashboardCentral", [
            "admin" => Auth::guard("admin")->user(),
            "monthlyPayments" => $monthlyPayments,
        ]);
    }
}
