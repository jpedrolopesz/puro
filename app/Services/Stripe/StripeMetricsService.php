<?php

namespace App\Services\Stripe;

use Stripe\Stripe;
use Stripe\Balance;
use Stripe\Subscription;
use Stripe\Exception\ApiErrorException;

class StripeMetricsService
{
    public function __construct()
    {
        // Configure a chave secreta do Stripe
        Stripe::setApiKey(config("services.stripe.secret"));
    }

    // Recupera a receita total
    public function getTotalRevenue()
    {
        try {
            $balance = Balance::retrieve();
            $totalRevenue = 0;
            foreach ($balance->available as $available) {
                $totalRevenue += $available->amount;
            }
            return $totalRevenue;
        } catch (ApiErrorException $e) {
            // Trate a exceção apropriadamente
            return "Error retrieving total revenue: " . $e->getMessage();
        }
    }
}
