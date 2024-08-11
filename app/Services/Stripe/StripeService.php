<?php

namespace App\Services\Stripe;

use Stripe\Stripe;

class StripeService
{
    public function __construct()
    {
        Stripe::setApiKey(config("services.stripe.secret"));
    }
}
