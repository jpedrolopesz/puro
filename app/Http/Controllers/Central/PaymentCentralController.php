<?php

namespace App\Http\Controllers\Central;

use App\Actions\Central\Stripe\Payment\{RetrievePaymentIntentAction};
use App\Http\Controllers\Controller;
use App\Jobs\ProcessStripePaymentsJob;
use App\Jobs\TestJob;
use App\Models\Payment;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Exception;

class PaymentCentralController extends Controller
{
    protected $retrievePaymentIntentAction;
    protected $stripeService;

    public function __construct(
        RetrievePaymentIntentAction $retrievePaymentIntentAction
    ) {
        $this->retrievePaymentIntentAction = $retrievePaymentIntentAction;
    }

    public function index()
    {
        try {
            $payments = Payment::all();
            return Inertia::render("Central/Payments/PaymentsCentral", [
                "paymentLists" => $payments,
            ]);
        } catch (Exception $e) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Failed to retrieve payments.",
                    "error" => $e->getMessage(),
                ],
                500
            );
        }
    }

    public function details($paymentId)
    {
        try {
            $paymentDetails = $this->retrievePaymentIntentAction->execute(
                $paymentId
            );
            return Inertia::render("Central/Payments/PaymentsViewDetails", [
                "paymentDetails" => $paymentDetails,
            ]);
        } catch (Exception $e) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Failed to retrieve payment details.",
                    "error" => $e->getMessage(),
                ],
                500
            );
        }
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

    public function startSync()
    {
        TestJob::dispatch();
    }
}
