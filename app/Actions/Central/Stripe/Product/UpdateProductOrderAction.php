<?php

namespace App\Actions\Central\Stripe\Product;

use Illuminate\Support\Facades\Log;
use Stripe\Product as StripeProduct;
use Stripe\Stripe;
use Stripe\Exception\ApiErrorException;

class UpdateProductOrderAction
{
    public function execute(array $products)
    {
        Stripe::setApiKey(config("services.stripe.secret"));
        $updatedProducts = [];
        $failedUpdates = [];

        try {
            foreach ($products as $productData) {
                try {
                    $metadata = [
                        "order" => (string) $productData["order"],
                    ];

                    // Adiciona informações de preço para intervalos mensais e anuais
                    if (isset($productData["metadata"]["monthly_price_id"])) {
                        $metadata["monthly_price_id"] =
                            $productData["metadata"]["monthly_price_id"];
                    }
                    if (isset($productData["metadata"]["yearly_price_id"])) {
                        $metadata["yearly_price_id"] =
                            $productData["metadata"]["yearly_price_id"];
                    }

                    // Atualiza o produto no Stripe
                    $stripeProduct = StripeProduct::update($productData["id"], [
                        "metadata" => $metadata,
                    ]);

                    $updateSuccessful = $this->verifyUpdate(
                        $stripeProduct,
                        $metadata
                    );

                    if ($updateSuccessful) {
                        $updatedProducts[] = $productData["id"];
                        $this->logSuccessfulUpdate($productData, $metadata);
                    } else {
                        $failedUpdates[] = [
                            "id" => $productData["id"],
                            "reason" => "Falha na verificação pós-atualização",
                        ];
                        Log::warning(
                            "Falha na verificação pós-atualização para o produto: {$productData["id"]}"
                        );
                    }

                    usleep(100000); // 100ms delay to avoid rate limiting
                } catch (ApiErrorException $e) {
                    $failedUpdates[] = [
                        "id" => $productData["id"],
                        "reason" => $e->getMessage(),
                    ];
                    Log::error(
                        "Erro ao atualizar produto {$productData["id"]}: " .
                            $e->getMessage()
                    );
                }
            }

            Log::info(
                "Atualização de ordem concluída. Produtos atualizados: " .
                    implode(", ", $updatedProducts)
            );
            return [
                "updated" => $updatedProducts,
                "failed" => $failedUpdates,
            ];
        } catch (\Exception $e) {
            Log::error(
                "Erro geral ao atualizar produtos no Stripe: " .
                    $e->getMessage()
            );
            throw $e;
        }
    }

    private function verifyUpdate(
        StripeProduct $stripeProduct,
        array $metadata
    ): bool {
        foreach ($metadata as $key => $value) {
            if (
                !isset($stripeProduct->metadata[$key]) ||
                $stripeProduct->metadata[$key] != $value
            ) {
                return false;
            }
        }
        return true;
    }

    private function logSuccessfulUpdate(
        array $productData,
        array $metadata
    ): void {
        $logMessage = "Produto atualizado no Stripe: {$productData["id"]}, Nova ordem: {$metadata["order"]}, ";

        if (isset($metadata["monthly_price_id"])) {
            $logMessage .= "Preço mensal: {$metadata["monthly_price_id"]}, ";
        }
        if (isset($metadata["yearly_price_id"])) {
            $logMessage .= "Preço anual: {$metadata["yearly_price_id"]}, ";
        }

        $logMessage = rtrim($logMessage, ", ");
        Log::info($logMessage);
    }
}
