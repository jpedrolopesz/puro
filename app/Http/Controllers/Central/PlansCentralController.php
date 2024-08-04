<?php

namespace App\Http\Controllers\Central;

use App\Actions\Central\Stripe\{PriceGetAllAction, PriceGetDetailsAction};
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Stripe\Product;
use Stripe\Price;
use Stripe\Stripe;

class PlansCentralController extends Controller
{
    protected $priceGetAllAction;
    protected $priceGetDetailsAction;

    public function __construct(
        PriceGetAllAction $priceGetAllAction,
        PriceGetDetailsAction $priceGetDetailsAction
    ) {
        Stripe::setApiKey(config("services.stripe.secret"));
        $this->priceGetAllAction = $priceGetAllAction;
        $this->priceGetDetailsAction = $priceGetDetailsAction;
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
            $formattedData = $this->priceGetDetailsAction->execute($productID);

            return Inertia::render("Central/Plans/PlanViewDetails", [
                "product" => $formattedData,
            ]);
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

    public function update(Request $request, $productId)
    {
        //dd($request->all());
        try {
            $product = Product::retrieve($productId);
            $product->name = $request->input("name");
            $product->description = $request->input("description");
            $product->active = $request->input("active");

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

    public function destroy($productID): RedirectResponse
    {
        dd($productID);
        try {
            // Recupera o produto usando o ID
            $product = Product::retrieve($productID);

            // Atualiza o produto para um estado inativo
            // Na prática, você pode usar um campo de descrição ou metadados para marcar o estado inativo
            $product->metadata["status"] = "inactive"; // Exemplo de uso de metadados para marcar o estado
            $product->save();

            // Recupera e atualiza todos os preços associados ao produto
            $prices = Price::all(["product" => $productID]);
            foreach ($prices->data as $price) {
                // Atualiza o preço para um estado inativo
                // Na prática, você pode usar um campo de descrição ou metadados para marcar o estado inativo
                $price->metadata["status"] = "inactive"; // Exemplo de uso de metadados para marcar o estado
                $price->save();
            }
        } catch (\Exception $e) {
            return back()->withErrors(["error" => $e->getMessage()]);
        }
    }
}
