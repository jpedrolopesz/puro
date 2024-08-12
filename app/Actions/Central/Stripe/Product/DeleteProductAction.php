<?php

namespace App\Actions\Central\Stripe\Product;

use Stripe\Product;
use Stripe\Price;
use Exception;

class DeleteProductAction
{
    public function execute(string $productId): void
    {
        try {
            $product = Product::retrieve($productId);
            $product->metadata["status"] = "inactive";
            $product->save();

            $prices = Price::all(["product" => $productId]);
            foreach ($prices->data as $price) {
                $price->metadata["status"] = "inactive";
                $price->save();
            }
        } catch (Exception $e) {
            throw new Exception("Erro ao deletar produto: " . $e->getMessage());
        }
    }
}
