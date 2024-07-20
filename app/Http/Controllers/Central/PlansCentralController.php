<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Stripe\Product;
use Stripe\Price;
use Stripe\Stripe;

class PlansCentralController extends Controller
{
    public function index()
    {
        return Inertia::render("Central/Plans/PlansCentral");
    }

    public function edit($productId)
    {
        try {
            // Configure a chave secreta do Stripe
            Stripe::setApiKey(
                "sk_test_51LRjEpGQW0U1PfqxjAwrWJaaaML9e8xOtowprEoQOF8j2z2Nvn9a5P8KkvDpgVzmeBpCdczITYNhvI1DMYs18qRb00e3YMKUXY"
            );

            // Primeiro, vamos obter o produto que queremos editar
            $product = Product::retrieve($productId);

            if (!$product) {
                return response()->json(
                    [
                        "success" => false,
                        "message" => "Product not found.",
                    ],
                    404
                );
            }

            // Obtém os preços associados ao produto
            $pricesList = [];
            $prices = Price::all(["product" => $product->id]);
            foreach ($prices->data as $price) {
                $pricesList[] = [
                    "id" => $price->id,
                    "currency" => $price->currency,
                    "unit_amount" => $price->unit_amount,
                    "billing_scheme" => $price->billing_scheme,
                    "created" => $product->created,
                    "updated" => $product->updated,
                    "trial_period_days" => $price->trial_period_days,
                    "recurring" => $price->recurring
                        ? [
                            "interval" => $price->recurring->interval,
                            "interval_count" =>
                                $price->recurring->interval_count,
                        ]
                        : null,
                ];
            }

            return Inertia::render("Central/Plans/PlanViewDetails", [
                "product" => [
                    "id" => $product->id,
                    "name" => $product->name,
                    "description" => $product->description,
                    "active" => $product->active,
                    "created" => $product->created,
                    "updated" => $product->updated,
                    "statement_descriptor" => $product->statement_descriptor,
                    "prices" => $pricesList, // Adiciona os preços ao produto
                ],
                // Passa o ID opcional para a view, se necessário
            ]);
        } catch (\Exception $e) {
            // Trata qualquer exceção que possa ocorrer
            return response()->json(
                [
                    "success" => false,
                    "message" => "Failed to fetch product.",
                    "error" => $e->getMessage(),
                ],
                500
            );
        }
    }
}
