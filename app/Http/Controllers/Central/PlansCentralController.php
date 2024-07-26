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
    public function __construct()
    {
        Stripe::setApiKey(config("services.stripe.secret"));
    }
    public function index()
    {
        return Inertia::render("Central/Plans/PlansCentral");
    }

    public function details($productId)
    {
        try {
            $product = Product::retrieve($productId);
            $prices = Price::all(["product" => $productId]);

            return Inertia::render("Central/Plans/PlanViewDetails", [
                "product" => $product,
                "prices" => $prices->data,
            ]);
        } catch (\Exception $e) {
            return back()->withErrors(["error" => $e->getMessage()]);
        }
    }

    public function update(Request $request, $productId)
    {
        try {
            $product = Product::retrieve($productId);
            $product->name = $request->input("name");
            $product->description = $request->input("description");
            $product->save();

            // Atualiza o preço (opcional)
            if ($request->has("price_id")) {
                $price = Price::retrieve($request->input("price_id"));
                $price->unit_amount = $request->input("price");
                $price->save();
            }
        } catch (\Exception $e) {
            return back()->withErrors(["error" => $e->getMessage()]);
        }
    }
}
