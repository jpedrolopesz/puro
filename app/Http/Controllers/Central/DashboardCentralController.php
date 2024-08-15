<?php

namespace App\Http\Controllers\Central;

use App\Actions\Central\Dashboard\{
    GetMonthlyPaymentSummary,
    CalculateAnnualPaymentTotals
};
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardCentralController extends Controller
{
    protected $getMonthlyPaymentSummary;
    protected $calculateAnnualPaymentTotals;

    public function __construct(
        GetMonthlyPaymentSummary $getMonthlyPaymentSummary,
        CalculateAnnualPaymentTotals $calculateAnnualPaymentTotals
    ) {
        $this->getMonthlyPaymentSummary = $getMonthlyPaymentSummary;
        $this->calculateAnnualPaymentTotals = $calculateAnnualPaymentTotals;
    }
    public function index()
    {
        $getMonthlyPaymentSummary = $this->getMonthlyPaymentSummary->execute();
        $calculateAnnualPaymentTotals = $this->calculateAnnualPaymentTotals->execute();

        return Inertia::render("Central/Dashboard/DashboardCentral", [
            "admin" => Auth::guard("admin")->user(),
            "getMonthlyPaymentSummary" => $getMonthlyPaymentSummary,
            "calculateAnnualPaymentTotals" => $calculateAnnualPaymentTotals,
        ]);
    }
}
