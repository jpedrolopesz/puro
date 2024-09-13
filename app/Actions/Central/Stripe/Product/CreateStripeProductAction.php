<?php

namespace App\Actions\Central\Stripe\Product;

use Stripe\Product;

class CreateStripeProductAction
{
    public function execute(array $data): Product
    {
        return Product::create([
            "name" => $data["name"],
            "description" => $data["description"],
        ]);
    }
}
