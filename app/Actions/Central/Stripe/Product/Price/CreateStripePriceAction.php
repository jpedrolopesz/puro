<?php

namespace App\Actions\Central\Stripe\Product\Price;

use Stripe\Price;
use Exception;

class CreateStripePriceAction
{
    public function execute(array $data): Price
    {
        $recurringConfig = isset($data["recurring"])
            ? [
                "interval" => $data["recurring"],
                "interval_count" => 1,
            ]
            : null;

        return Price::create([
            "unit_amount" => $data["price"] * 100,
            "currency" => $data["currency"],
            "product" => $data["product_id"],
            "recurring" => $recurringConfig,
            "nickname" => $data["nickname"] ?? null,
        ]);
    }
}
