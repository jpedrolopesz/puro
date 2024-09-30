<?php

namespace App\Actions\Central\Stripe\Product\Order;

use App\Models\StripeProduct as LocalStripeProduct;
use Illuminate\Support\Facades\Log;
use Stripe\Product as StripeProductAPI;
use Stripe\Price as StripePriceAPI;
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
                        "order" => (string) ($productData["order"] ?? null),
                        "column_count" =>
                            (string) ($productData["columnCount"] ?? null),
                    ];

                    if (isset($productData["metadata"]["monthly_price_id"])) {
                        $metadata["monthly_price_id"] =
                            $productData["metadata"]["monthly_price_id"];
                    }
                    if (isset($productData["metadata"]["yearly_price_id"])) {
                        $metadata["yearly_price_id"] =
                            $productData["metadata"]["yearly_price_id"];
                    }

                    // Atualiza o produto no Stripe
                    $stripeProduct = StripeProductAPI::update(
                        $productData["id"],
                        [
                            "metadata" => $metadata,
                        ]
                    );

                    $updateSuccessful = $this->verifyUpdate(
                        $stripeProduct,
                        $metadata
                    );

                    if ($updateSuccessful) {
                        $monthlyPrice = StripePriceAPI::retrieve(
                            $metadata["monthly_price_id"]
                        );
                        $yearlyPrice = StripePriceAPI::retrieve(
                            $metadata["yearly_price_id"]
                        );

                        LocalStripeProduct::updateOrCreate(
                            ["stripe_product_id" => $productData["id"]],
                            [
                                "name" => $stripeProduct->name,
                                "description" => $stripeProduct->description,
                                "order" => intval($metadata["order"] ?? 0),
                                "column_count" => intval(
                                    $metadata["column_count"] ?? 3
                                ),
                                "monthly_price_id" =>
                                    $metadata["monthly_price_id"],
                                "yearly_price_id" =>
                                    $metadata["yearly_price_id"],
                                "monthly_unit_amount" =>
                                    $monthlyPrice->unit_amount,
                                "yearly_unit_amount" =>
                                    $yearlyPrice->unit_amount,
                                "monthly_recurring_interval" =>
                                    $monthlyPrice->recurring->interval,
                                "yearly_recurring_interval" =>
                                    $yearlyPrice->recurring->interval,
                                "features" => json_decode(
                                    $metadata["features"] ?? "[]"
                                ),
                            ]
                        );

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
        StripeProductAPI $stripeProduct,
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
