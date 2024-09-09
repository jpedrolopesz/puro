<?php

namespace App\Actions\Central\Stripe\Product;

use Stripe\Product;
use Stripe\Price;
use Stripe\Stripe;

class RetrieveOrderedProductsAction
{
    public function execute(): array
    {
        Stripe::setApiKey(config("services.stripe.secret"));

        $pricesResponse = Price::all();
        $productsResponse = Product::all(["active" => true]);
        $prices = $pricesResponse->data;
        $products = $productsResponse->data;

        $formattedData = $this->formatData($products, $prices);

        return $this->sortProducts($formattedData);
    }

    private function formatData(array $products, array $prices): array
    {
        $formatted = [];
        $pricesByProduct = [];

        foreach ($prices as $price) {
            $productId = $price->product;
            if (!isset($pricesByProduct[$productId])) {
                $pricesByProduct[$productId] = [];
            }
            $pricesByProduct[$productId][] = [
                "id" => $price->id,
                "currency" => $price->currency,
                "unit_amount" => $price->unit_amount,
                "billing_scheme" => $price->billing_scheme,
                "trial_period_days" =>
                    $price->recurring->trial_period_days ?? null,
                "recurring" => [
                    "interval" => $price->recurring->interval ?? null,
                    "interval_count" =>
                        $price->recurring->interval_count ?? null,
                ],
            ];
        }

        foreach ($products as $product) {
            $formatted[] = [
                "id" => $product->id,
                "name" => $product->name,
                "description" => $product->description ?? null,
                "active" => $product->active,
                "created" => $product->created,
                "updated" => $product->updated ?? null,
                "statement_descriptor" =>
                    $product->statement_descriptor ?? null,
                "prices" => $pricesByProduct[$product->id] ?? [],
                "metadata" => $product->metadata->toArray(),
            ];
        }

        return $formatted;
    }

    private function sortProducts(array $products): array
    {
        usort($products, function ($a, $b) {
            $orderA =
                isset($a["metadata"]["order"]) &&
                is_numeric($a["metadata"]["order"])
                    ? intval($a["metadata"]["order"])
                    : PHP_INT_MAX;
            $orderB =
                isset($b["metadata"]["order"]) &&
                is_numeric($b["metadata"]["order"])
                    ? intval($b["metadata"]["order"])
                    : PHP_INT_MAX;

            if ($orderA === $orderB) {
                return strcmp($a["name"], $b["name"]);
            }

            return $orderA - $orderB;
        });

        return $products;
    }
}
