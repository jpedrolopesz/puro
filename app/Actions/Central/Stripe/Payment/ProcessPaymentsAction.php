<?php

namespace App\Actions\Central\Stripe\Payment;

use App\Jobs\ProcessStripePayments;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;
use App\Services\Stripe\StripePaymentIntentsManager;

class ProcessPaymentsAction
{
    protected $stripePaymentIntentsManager;

    public function __construct(
        StripePaymentIntentsManager $stripePaymentIntentsManager
    ) {
        $this->stripePaymentIntentsManager = $stripePaymentIntentsManager;
    }

    public function execute(): void
    {
        $paymentIntents = $this->stripePaymentIntentsManager->getAllPaymentIntents();

        $batch = Bus::batch([
            ...array_map(
                fn($paymentIntent) => new ProcessStripePayments(
                    $paymentIntent->id
                ),
                $paymentIntents
            ),
        ])->dispatch();

        $progress = $batch->progress();
        Log::info("Batch Progress: $progress%");
    }
}
