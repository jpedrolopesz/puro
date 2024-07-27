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

    public function create(Request $request)
    {
        dd($request->all());
        try {
            // Valida os dados de entrada
            $request->validate([
                "name" => "required|string|max:255",
                "description" => "nullable|string",
                "price" => "nullable|numeric|min:0",
                "currency" => "required_with:price|string|size:3", // Assuming currency code is 3 letters (e.g., 'usd')
                "recurring" => "nullable|array",
                "recurring.interval" =>
                    "nullable|string|in:day,week,month,year",
                "recurring.interval_count" => "nullable|integer|min:1",
            ]);

            $product = Product::create([
                "name" => $request->input("name"),
                "description" => $request->input("description"),
            ]);

            if ($request->has("price")) {
                $price = Price::create([
                    "unit_amount" => $request->input("price") * 100, // Multiplicado por 100 para armazenar em centavos
                    "currency" => $request->input("currency"),
                    "product" => $product->id,
                    "recurring" => $request->input("recurring")
                        ? [
                            "interval" => $request->input("recurring.interval"),
                            "interval_count" => $request->input(
                                "recurring.interval_count"
                            ),
                        ]
                        : null,
                ]);
            }
        } catch (\Exception $e) {
            return back()->withErrors(["error" => $e->getMessage()]);
        }
    }
}
