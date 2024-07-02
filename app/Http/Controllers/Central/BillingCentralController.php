<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Http;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\Product;
use Stripe\Price;

class BillingCentralController extends Controller
{
    public function index()
    {
        return Inertia::render("Central/Billing/BillingCentral");
    }

    // Abordagem que  vou fazer vai ser a verificaóes se as chaces estao no config/service.
    // Após a verificaçao eu irei disponibilizar o conteudo na tela.
    //

    public function connectStripe()
    {
        try {
            // Configure a chave secreta do Stripe
            Stripe::setApiKey(
                "sk_test_51LRjEpGQW0U1PfqxjAwrWJaaaML9e8xOtowprEoQOF8j2z2Nvn9a5P8KkvDpgVzmeBpCdczITYNhvI1DMYs18qRb00e3YMKUXY"
            );

            // Faz uma chamada à API para verificar s
            //
            // e a integração está funcionando
            // $charges = Charge::all(["limit" => 1]);
            //
            $products = Product::all();

            // Montar um array com as propriedades desejadas de cada produto
            $productList = [];

            foreach ($products->data as $product) {
                // Inicializa array para armazenar preços
                $pricesList = [];

                // Obtém os preços associados ao produto
                $prices = Price::all(["product" => $product->id]);
                foreach ($prices->data as $price) {
                    $pricesList[] = [
                        "id" => $price->id,
                        "currency" => $price->currency,
                        "unit_amount" => $price->unit_amount,
                        "billing_scheme" => $price->billing_scheme,
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

                $productList[] = [
                    "id" => $product->id,
                    "name" => $product->name,
                    "description" => $product->description,
                    "active" => $product->active,
                    "created" => $product->created,
                    "updated" => $product->updated,
                    "statement_descriptor" => $product->statement_descriptor,
                    "prices" => $pricesList, // Adiciona os preços ao produto
                ];
            }
            dd($productList);

            return response()->json([
                "success" => true,
                "message" => "Stripe integration is working.",
                "data" => $productList,
            ]);
        } catch (\Exception $e) {
            return response()->json(
                [
                    "success" => false,
                    "message" => "Stripe integration failed.",
                    "error" => $e->getMessage(),
                ],
                500
            );
        }
    }
}
