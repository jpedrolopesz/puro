<?php

namespace App\Http\Controllers\Central;

use App\Actions\Central\Dashboard\GetDashboardData;
use App\Actions\Central\Dashboard\GetTotalPayments;
use App\Http\Controllers\Controller;
use App\Jobs\CalculateSalesVolume;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Inertia\Inertia;
use Stripe;
use Stripe\StripeClient;

class DashboardCentralController extends Controller
{
    protected $getDashboardData;
    protected $getTotalPayments;

    public function __construct(
        GetDashboardData $getDashboardData,
        GetTotalPayments $getTotalPayments
    ) {
        $this->getDashboardData = $getDashboardData;
        $this->getTotalPayments = $getTotalPayments;
    }
    public function index(Request $request)
    {
        $monthlyPayments = $this->getDashboardData->execute();
        $totalPayments = $this->getTotalPayments->execute();

        // Set your secret key. Remember to switch to your live secret key in production.
        // See your keys here: https://dashboard.stripe.com/apikeys
        \Stripe\Stripe::setApiKey(
            "sk_test_51LRjEpGQW0U1PfqxjAwrWJaaaML9e8xOtowprEoQOF8j2z2Nvn9a5P8KkvDpgVzmeBpCdczITYNhvI1DMYs18qRb00e3YMKUXY"
        );
        CalculateSalesVolume::dispatch();

        return Inertia::render("Central/Dashboard/DashboardCentral", [
            "admin" => Auth::guard("admin")->user(),
            "monthlyPayments" => $monthlyPayments,
            "totalPayments" => $totalPayments,
        ]);
    }
}
