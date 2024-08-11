<?php

namespace App\Actions\Central\Stripe;

use Stripe\Product;
use Exception;

class CreateProductAction
{
    public function execute(array $data): Product
    {
        try {
            return Product::create([
                "name" => $data["name"],
                "description" => $data["description"],
            ]);
        } catch (Exception $e) {
            throw new Exception("Erro ao criar produto: " . $e->getMessage());
        }
    }
}
