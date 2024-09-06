<?php

namespace App\Actions\Central\Stripe\Product;

use Illuminate\Support\Facades\Log;
use Stripe\Product as StripeProduct;
use Stripe\Stripe;

class UpdateProductOrderAction
{
    public function execute(array $products)
    {
        Stripe::setApiKey(config("services.stripe.secret"));

        try {
            foreach ($products as $productData) {
                // Recuperar o produto do Stripe
                $stripeProduct = StripeProduct::retrieve($productData["id"]);

                // Atualizar os metadados no Stripe
                StripeProduct::update($productData["id"], [
                    "metadata" => ["order" => $productData["order"]],
                ]);

                Log::info(
                    "Produto atualizado no Stripe: {$productData["id"]}, Nova ordem: {$productData["order"]}"
                );
            }

            Log::info("Atualização de ordem concluída com sucesso");
        } catch (\Exception $e) {
            Log::error(
                "Erro ao atualizar produtos no Stripe: " . $e->getMessage()
            );
            throw $e;
        }
    }
}
