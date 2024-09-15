<?php

namespace App\Http\Controllers\Central;

use App\Actions\Central\Stripe\Payment\{
    RetrievePaymentIntentAction,
    RetrieveFilteredPaymentsAction
};
use App\Http\Controllers\Controller;
use App\Jobs\ProcessStripePaymentsJob;
use App\Jobs\TestJob;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Exception;

class PaymentCentralController extends Controller
{
    public function __construct(
        private readonly RetrievePaymentIntentAction $retrievePaymentIntentAction,
        private readonly RetrieveFilteredPaymentsAction $retrieveFilteredPaymentsAction
    ) {
    }

    public function index(Request $request)
    {
        $filter = $request->input("filter", "all");

        $payments = $this->retrieveFilteredPaymentsAction->execute($filter);
        $statistics = $this->retrieveFilteredPaymentsAction->getStatistics();

        return Inertia::render("Central/Payments/PaymentsCentral", [
            "payments" => $payments,
            "statistics" => $statistics,
            "currentFilter" => $filter,
        ]);
    }

    public function details($paymentId)
    {
        $paymentDetails = $this->retrievePaymentIntentAction->execute(
            $paymentId
        );
        return Inertia::render("Central/Payments/PaymentsViewDetails", [
            "paymentDetails" => $paymentDetails,
        ]);
    }

    public function processPayments(Request $request)
    {
        try {
            ProcessStripePaymentsJob::dispatch();
        } catch (Exception $e) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Failed to process payments.",
                    "error" => $e->getMessage(),
                ],
                500
            );
        }
    }

    public function startSync(Request $request)
    {
        TestJob::dispatch();
    }
}
