<?php

namespace App\Actions\Central\Stripe\Product;

use Stripe\Product;
use Exception;

class UpdateProductAction
{
    public function execute(string $productId, array $data): Product
    {
        try {
            $product = Product::retrieve($productId);
            $product->name = $data["name"];
            $product->description = $data["description"];
            $product->active = $data["active"];
            $product->save();
            return $product;
        } catch (Exception $e) {
            throw new Exception(
                "Erro ao atualizar produto: " . $e->getMessage()
            );
        }
    }
}
