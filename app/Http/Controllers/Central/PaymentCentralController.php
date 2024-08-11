<?php

namespace App\Http\Controllers\Central;

use App\Actions\Central\Stripe\Payment\{
    RetrievePaymentIntentAction,
    ProcessPaymentsAction
};
use App\Http\Controllers\Controller;
use App\Services\Stripe\StripeService;
use App\Models\Payment;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Exception;

class PaymentCentralController extends Controller
{
    protected $retrievePaymentIntentAction;
    protected $processPaymentsAction;
    protected $stripeService;

    public function __construct(
        RetrievePaymentIntentAction $retrievePaymentIntentAction,
        ProcessPaymentsAction $processPaymentsAction,
        StripeService $stripeService
    ) {
        $this->retrievePaymentIntentAction = $retrievePaymentIntentAction;
        $this->processPaymentsAction = $processPaymentsAction;
        $this->stripeService = $stripeService;
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
            $this->processPaymentsAction->execute();
            return response()->json(["success" => true]);
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
}
