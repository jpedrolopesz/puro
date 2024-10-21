<?php

namespace App\Actions\Central\Stripe\Product;

use Stripe\Product;

class CreateStripeProductAction
{
    public function execute(array $data): Product
    {
        return Product::create([
            "name" => $data["name"],
            "description" => $data["description"],
            "metadata" => $this->formatMetadata($data["features"]),
        ]);
    }

    private function formatMetadata(array $features): array
    {
        return array_combine(
            array_map(
                fn($index) => "feature_" . ($index + 1),
                array_keys($features)
            ),
            $features
        );
    }
}
