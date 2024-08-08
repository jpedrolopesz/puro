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

            // Verifica se o preço foi fornecido
            if ($request->has("price")) {
                $recurringInterval = $request->input("recurring");
                $recurringConfig = null;

                // Configuração para pagamento recorrente, se aplicável
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

    public function addPriceToProduct(Request $request)
    {
        try {
            $productId = $request->input("product_id");
            $priceAmount = $request->input("price");
            $currency = $request->input("currency");
            $recurringInterval = $request->input("recurring");
            $description = $request->input("description");

            // Busca o produto pelo ID fornecido
            $product = Product::retrieve($productId, []);

            // Configuração para pagamento recorrente, se aplicável
            $recurringConfig = null;
            if ($recurringInterval) {
                $recurringConfig = [
                    "interval" => $recurringInterval,
                    "interval_count" => 1,
                ];
            }

            // Cria um novo preço associado ao produto
            $price = Price::create([
                "unit_amount" => $priceAmount * 100, // Assumindo que o preço é em dólares e precisa ser convertido para centavos
                "currency" => $currency,
                "product" => $product->id, // ID do produto
                "recurring" => $recurringConfig,
                "description" => $description, // Adiciona a descrição se necessário
            ]);

            return response()->json(
                ["success" => true, "price" => $price],
                201
            );
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $productId)
    {
        try {
            $product = Product::retrieve($productId);
            $product->name = $request->input("name");
            $product->description = $request->input("description");
            $product->active = $request->input("active");

            $product->save();

            if ($request->has("price_id")) {
                $priceId = $request->input("price_id");
                $price = Price::retrieve($priceId);
                $price->active = $request->input("active");
                $price->save();
            }
        } catch (\Exception $e) {
            return back()->withErrors(["error" => $e->getMessage()]);
        }
    }

    public function updatePrice(Request $request, $priceId)
    {
        try {
            $price = Price::retrieve($priceId);
            $currentActiveStatus = $request->input("active");
            $price->active = !$currentActiveStatus;
            $price->save();

            return response()->json(["success" => true]);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 400);
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
