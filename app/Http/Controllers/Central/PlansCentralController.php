<?php

namespace App\Http\Controllers\Central;

use App\Actions\Central\Stripe\PriceGetAllAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Stripe\Product;
use Stripe\Price;
use Stripe\Stripe;

class PlansCentralController extends Controller
{
    protected $priceGetAllAction;

    public function __construct(PriceGetAllAction $priceGetAllAction)
    {
        Stripe::setApiKey(config("services.stripe.secret"));
        $this->priceGetAllAction = $priceGetAllAction;
    }

    public function index()
    {
        try {
            $formattedData = $this->priceGetAllAction->execute();

            return Inertia::render("Central/Plans/PlansCentral", [
                "plans" => $formattedData,
            ]);
        } catch (\Exception $e) {
            return back()->withErrors(["error" => $e->getMessage()]);
        }
    }

    public function details($productID)
    {
        try {
            $product = Product::retrieve($productID);
            $prices = Price::all(["product" => $productID]);

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
        try {
            $product = Product::create([
                "name" => $request->input("name"),
                "description" => $request->input("description"),
            ]);
            if ($request->has("price")) {
                $recurringInterval = $request->input("recurring");
                $recurringConfig = null;

                if ($recurringInterval) {
                    $recurringConfig = [
                        "interval" => $recurringInterval,
                        "interval_count" => 1,
                    ];
                }

                $price = Price::create([
                    "unit_amount" => $request->input("price") * 100,
                    "currency" => $request->input("currency"),
                    "product" => $product->id,
                    "recurring" => $recurringConfig,
                ]);
            }
        } catch (\Exception $e) {
            return back()->withErrors(["error" => $e->getMessage()]);
        }
    }
}
