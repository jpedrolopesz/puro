<?php

namespace App\Http\Controllers\Central;

use App\Actions\Central\Dashboard\{
    GetMonthlyPaymentSummary,
    CalculateAnnualPaymentTotals,
    CalculateAnnualSubscriberTotals
};
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardCentralController extends Controller
{
    protected $getMonthlyPaymentSummary;
    protected $calculateAnnualPaymentTotals;
    protected $calculateAnnualSubscriberTotals;

    public function __construct(
        GetMonthlyPaymentSummary $getMonthlyPaymentSummary,
        CalculateAnnualPaymentTotals $calculateAnnualPaymentTotals,
        CalculateAnnualSubscriberTotals $calculateAnnualSubscriberTotals
    ) {
        $this->getMonthlyPaymentSummary = $getMonthlyPaymentSummary;
        $this->calculateAnnualPaymentTotals = $calculateAnnualPaymentTotals;
        $this->calculateAnnualSubscriberTotals = $calculateAnnualSubscriberTotals;
    }
    public function index()
    {
        $getMonthlyPaymentSummary = $this->getMonthlyPaymentSummary->execute();
        $calculateAnnualPaymentTotals = $this->calculateAnnualPaymentTotals->execute();
        $calculateAnnualSubscriberTotals = $this->calculateAnnualSubscriberTotals->execute();

        // dd($calculateAnnualSubscriberTotals);

        return Inertia::render("Central/Dashboard/DashboardCentral", [
            "admin" => Auth::guard("admin")->user(),
            "getMonthlyPaymentSummary" => $getMonthlyPaymentSummary,
            "calculateAnnualPaymentTotals" => $calculateAnnualPaymentTotals,
            "calculateAnnualSubscriberTotals" => $calculateAnnualSubscriberTotals,
        ]);
    }
}
