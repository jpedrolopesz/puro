<?php
namespace App\Services\Stripe;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Jobs\ProcessStripePayments;
use Illuminate\Support\Facades\Log;

class StripePaymentIntentsManager
{
    public static function getAllPaymentIntents()
    {
        Stripe::setApiKey(config("services.stripe.secret"));

        $limit = 80;
        $startingAfter = null;

        $allPaymentIntents = [];
        do {
            $params = [
                "limit" => $limit,
            ];

            if ($startingAfter) {
                $params["starting_after"] = $startingAfter;
            }

            Log::info("Fetching PaymentIntents with params: ", $params);

            $paymentIntents = PaymentIntent::all($params);

            foreach ($paymentIntents->data as $paymentIntent) {
                Log::info(
                    "Dispatching job for PaymentIntent: " . $paymentIntent->id
                );
                ProcessStripePayments::dispatch($paymentIntent->id);
                $allPaymentIntents[] = $paymentIntent;
                $startingAfter = $paymentIntent->id; // Atualiza o ID para a próxima página
            }

            Log::info(
                "Has more PaymentIntents: " .
                    ($paymentIntents->has_more ? "Yes" : "No")
            );
        } while ($paymentIntents->has_more);

        return $allPaymentIntents;
    }

    public static function getPaymentIntentDetails($paymentId)
    {
        Stripe::setApiKey(config("services.stripe.secret"));

        // Despache o job para processar e salvar o pagamento usando o ID
        Log::info("Dispatching job for PaymentIntent: " . $paymentId);
        ProcessStripePayments::dispatch($paymentId);

        return PaymentIntent::retrieve($paymentId);
    }
}
