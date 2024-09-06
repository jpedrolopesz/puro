<?php

namespace App\Actions\Central\Stripe\Product;

use Stripe\Product as StripeProduct;
use Stripe\Stripe;

class RetrieveOrderedProductsAction
{
    public function execute()
    {
        Stripe::setApiKey(config("services.stripe.secret"));

        $products = StripeProduct::all(["active" => true])->data;

        usort($products, function ($a, $b) {
            $orderA = isset($a->metadata["order"])
                ? intval($a->metadata["order"])
                : PHP_INT_MAX;
            $orderB = isset($b->metadata["order"])
                ? intval($b->metadata["order"])
                : PHP_INT_MAX;
            return $orderA - $orderB;
        });

        return $products;
    }
}
