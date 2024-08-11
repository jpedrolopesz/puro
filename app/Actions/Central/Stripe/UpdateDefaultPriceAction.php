<?php
namespace App\Actions\Central\Stripe;

use Stripe\Price;
use Stripe\Product;
use Exception;

class UpdateDefaultPriceAction
{
    public function execute(string $priceId, $priceDefault): Product
    {
        try {
            $price = Price::retrieve($priceId);
            $productId = $price->product;
            $product = Product::retrieve($productId);
            $product->default_price = $priceDefault;
            $product->save();
            return $product;
        } catch (Exception $e) {
            throw new Exception(
                "Erro ao atualizar preço padrão: " . $e->getMessage()
            );
        }
    }
}
