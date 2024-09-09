<?php

namespace App\Actions\Central\Stripe\Product;

use Stripe\Product;
use Stripe\Price;

class GetProductsAndPricesAction
{
    /**
     * Executa a formatação dos dados.
     *
     * @return array Dados formatados.
     */
    public function execute(): array
    {
        $pricesResponse = Price::all();
        $productsResponse = Product::all();

        $prices = $pricesResponse->data;
        $products = $productsResponse->data;

        return $this->formatData($products, $prices);
    }

    /**
     * Formats the product and price data.
     *
     * @param array $products List of products.
     * @param array $prices List of prices.
     * @return array Formatted data.
     */

    private function formatData(array $products, array $prices): array
    {
        $formatted = [];
        $pricesByProduct = [];
        $productsById = [];

        // Organize products by ID for easy lookup
        foreach ($products as $product) {
            $productsById[$product["id"]] = $product;
        }

        // Process all prices, even those without a matching product
        foreach ($prices as $price) {
            $productId = $price["product"];
            if (!isset($pricesByProduct[$productId])) {
                $pricesByProduct[$productId] = [];
            }
            $pricesByProduct[$productId][] = [
                "id" => $price["id"],
                "currency" => $price["currency"],
                "unit_amount" => $price["unit_amount"],
                "billing_scheme" => $price["billing_scheme"],
                "trial_period_days" =>
                    $price["recurring"]["trial_period_days"] ?? null,
                "recurring" => [
                    "interval" => $price["recurring"]["interval"] ?? null,
                    "interval_count" =>
                        $price["recurring"]["interval_count"] ?? null,
                ],
            ];
        }

        // Format products with their prices
        foreach ($pricesByProduct as $productId => $productPrices) {
            $product = $productsById[$productId] ?? null;
            $formatted[] = [
                "id" => $productId,
                "name" => $product["name"] ?? "Unknown Product",
                "description" => $product["description"] ?? null,
                "active" => $product["active"] ?? false,
                "created" => $product["created"] ?? null,
                "updated" => $product["updated"] ?? null,
                "statement_descriptor" =>
                    $product["statement_descriptor"] ?? null,
                "prices" => $productPrices,
            ];
        }

        return $formatted;
    }
}
