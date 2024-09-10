<?php
namespace App\Actions\Central\Stripe\Product\Price;

use Stripe\Price;
use Stripe\Product;

class UpdateStripePriceDefaultAction
{
    public function execute(string $priceId, string $priceDefault): Product
    {
        $price = Price::retrieve($priceId);
        $product = Product::retrieve($price->product);

        $product->default_price = $priceDefault;
        $product->save();

        return $product;
    }
}
