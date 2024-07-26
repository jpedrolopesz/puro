<?php

namespace App\Services\Stripe;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Jobs\ProcessStripePayments;

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

            $paymentIntents = PaymentIntent::all($params);

            foreach ($paymentIntents->data as $paymentIntent) {
                ProcessStripePayments::dispatch($paymentIntent->id);
                $allPaymentIntents[] = $paymentIntent;
                $startingAfter = $paymentIntent->id; // Atualiza o ID para a próxima página
            }
        } while ($paymentIntents->has_more);

        return $allPaymentIntents;
    }

    public static function getPaymentIntentDetails($paymentId)
    {
        Stripe::setApiKey(config("services.stripe.secret"));

        // Despache o job para processar e salvar o pagamento usando o ID
        ProcessStripePayments::dispatch($paymentId);

        return PaymentIntent::retrieve($paymentId);
    }
}
