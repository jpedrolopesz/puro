<?php

namespace App\Actions\Central\Stripe\Product;

use Stripe\Product;

class UpdateStripeProductAction
{
    public function execute(string $productId, array $data): Product
    {
        return Product::update($productId, [
            "name" => $data["name"],
            "description" => $data["description"],
            "active" => $data["active"],
        ]);
    }
}
