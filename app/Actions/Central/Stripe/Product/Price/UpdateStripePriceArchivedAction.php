<?php

namespace App\Actions\Central\Stripe\Product\Price;

use Stripe\Price;

class UpdateStripePriceArchivedAction
{
    public function execute(string $priceId, array $data): Price
    {
        $price = Price::update($priceId, [
            "active" => $data["active"],
        ]);
        return $price;
    }
}
