<?php

namespace App\Actions\Central\Stripe\Product\Order;

use Stripe\Product;
use Stripe\Price;
use Stripe\Stripe;

class RetrieveOrderedProductsAction
{
    public function execute(string $filter = "all"): array
    {
        Stripe::setApiKey(config("services.stripe.secret"));

        $params = [
            "expand" => ["data.default_price"],
        ];

        if ($filter !== "all") {
            $params["active"] = $filter === "active";
        }

        $productsResponse = Product::all($params);
        $products = $productsResponse->data;

        $formattedData = $this->formatData($products, $filter);
        return $this->sortProducts($formattedData);
    }

    private function formatData(array $products, string $filter): array
    {
        $formatted = [];
        foreach ($products as $product) {
            if (
                $filter !== "all" &&
                $product->active !== ($filter === "active")
            ) {
                continue;
            }

            $hasSubscription = false;
            $prices = [];
            $processedPriceIds = [];

            // Processar o preço padrão, se existir
            if (
                $product->default_price &&
                $product->default_price->type === "recurring"
            ) {
                $hasSubscription = true;
                $prices[] = $this->formatPrice($product->default_price);
                $processedPriceIds[] = $product->default_price->id;
            }

            // Buscar todos os preços associados ao produto
            $pricesResponse = Price::all(["product" => $product->id]);
            foreach ($pricesResponse->data as $price) {
                // Verificar se já processamos este preço
                if (!in_array($price->id, $processedPriceIds)) {
                    if ($price->type === "recurring") {
                        $hasSubscription = true;
                    }
                    $prices[] = $this->formatPrice($price);
                    $processedPriceIds[] = $price->id;
                }
            }

            $formatted[] = [
                "id" => $product->id,
                "name" => $product->name,
                "description" => $product->description ?? null,
                "active" => $product->active,
                "created" => $product->created,
                "updated" => $product->updated ?? null,
                "statement_descriptor" =>
                    $product->statement_descriptor ?? null,
                "prices" => $prices,
                "metadata" => $product->metadata->toArray(),
                "has_subscription" => $hasSubscription,
            ];
        }
        return $formatted;
    }

    private function formatPrice($price): array
    {
        return [
            "id" => $price->id,
            "currency" => $price->currency,
            "unit_amount" => $price->unit_amount,
            "billing_scheme" => $price->billing_scheme,
            "trial_period_days" => $price->recurring->trial_period_days ?? null,
            "recurring" => [
                "interval" => $price->recurring->interval ?? null,
                "interval_count" => $price->recurring->interval_count ?? null,
            ],
            "type" => $price->type,
        ];
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
