<?php

namespace App\Actions\Central\Stripe\Product;

use Stripe\Product;
use Stripe\Price;

class UpdateStripeProductArchivedAction
{
    public function execute(string $productId): void
    {
        $prices = Price::all(["product" => $productId]);

        foreach ($prices as $price) {
            Price::update($price->id, ["active" => false]);
        }
        Product::update($productId, ["active" => false]);
    }
}
