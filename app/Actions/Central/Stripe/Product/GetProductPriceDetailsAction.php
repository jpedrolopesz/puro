<?php

namespace App\Actions\Central\Stripe\Product;

use Stripe\Product;
use Stripe\Price;

class GetProductPriceDetailsAction
{
    public function execute(string $productId): array
    {
        $productResponse = Product::retrieve($productId);

        $pricesResponse = Price::all(["product" => $productId]);
        $prices = $pricesResponse->data;

        return $this->formatData($productResponse, $prices);
    }

    /**
     * Formats the product and price data.
     *
     * @param array $prices List of prices.
     * @return array Formatted data.
     */

    private function formatData($product, array $prices): array
    {
        $formattedPrices = [];

        foreach ($prices as $price) {
            $formattedPrices[] = [
                "id" => $price["id"],
                "currency" => $price["currency"],
                "unit_amount" => $price["unit_amount"],
                "active" => $price["active"],
                "billing_scheme" => $price["billing_scheme"],
                "nickname" => $price["nickname"],

                "trial_period_days" =>
                    $price["recurring"]["trial_period_days"] ?? null,
                "recurring" => [
                    "interval" => $price["recurring"]["interval"] ?? null,
                    "interval_count" =>
                        $price["recurring"]["interval_count"] ?? null,
                ],
                "created" => $price["created"],
            ];
        }

        return [
            "id" => $product["id"],
            "name" => $product["name"],
            "description" => $product["description"] ?? null,
            "active" => $product["active"],
            "default_price" => $product["default_price"],
            "created" => $product["created"],
            "updated" => $product["updated"] ?? null,
            "statement_descriptor" => $product["statement_descriptor"] ?? null,
            "prices" => $formattedPrices,
        ];
    }
}
