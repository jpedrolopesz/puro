<?php

namespace App\Http\Controllers\Central;

use App\Actions\Central\Stripe\{
    GetProductsAndPricesAction,
    GetProductPriceDetailsAction,
    CreateProductAction,
    CreatePriceAction,
    UpdateProductAction,
    UpdatePriceAction,
    UpdateDefaultPriceAction,
    DeleteProductAction
};
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Stripe\Stripe;

class ProductsCentralController extends Controller
{
    protected $getProductsAndPricesAction;
    protected $getProductPriceDetailsAction;
    protected $createProductAction;
    protected $createPriceAction;
    protected $updateProductAction;
    protected $updatePriceAction;
    protected $updateDefaultPriceAction;
    protected $deleteProductAction;

    public function __construct(
        GetProductsAndPricesAction $getProductsAndPricesAction,
        GetProductPriceDetailsAction $getProductPriceDetailsAction,
        CreateProductAction $createProductAction,
        CreatePriceAction $createPriceAction,
        UpdateProductAction $updateProductAction,
        UpdatePriceAction $updatePriceAction,
        UpdateDefaultPriceAction $updateDefaultPriceAction,
        DeleteProductAction $deleteProductAction
    ) {
        Stripe::setApiKey(config("services.stripe.secret"));
        $this->getProductsAndPricesAction = $getProductsAndPricesAction;
        $this->getProductPriceDetailsAction = $getProductPriceDetailsAction;
        $this->createProductAction = $createProductAction;
        $this->createPriceAction = $createPriceAction;
        $this->updateProductAction = $updateProductAction;
        $this->updatePriceAction = $updatePriceAction;
        $this->updateDefaultPriceAction = $updateDefaultPriceAction;
        $this->deleteProductAction = $deleteProductAction;
    }

    public function index()
    {
        try {
            $formattedData = $this->getProductsAndPricesAction->execute();
            return Inertia::render("Central/Products/ProductsCentral", [
                "products" => $formattedData,
            ]);
        } catch (\Exception $e) {
            return back()->withErrors(["error" => $e->getMessage()]);
        }
    }

    public function details($productID)
    {
        try {
            $formattedData = $this->getProductPriceDetailsAction->execute(
                $productID
            );
            return Inertia::render("Central/Products/ProductViewDetails", [
                "product" => $formattedData,
            ]);
        } catch (\Exception $e) {
            return back()->withErrors(["error" => $e->getMessage()]);
        }
    }

    public function create(Request $request)
    {
        try {
            $product = $this->createProductAction->execute([
                "name" => $request->input("name"),
                "description" => $request->input("description"),
            ]);

            if ($request->has("price")) {
                $this->createPriceAction->execute([
                    "price" => $request->input("price"),
                    "currency" => $request->input("currency"),
                    "product_id" => $product->id,
                    "recurring" => $request->input("recurring"),
                    "nickname" => $request->input("nickname"),
                ]);
            }
        } catch (\Exception $e) {
            return back()->withErrors(["error" => $e->getMessage()]);
        }
    }

    public function addPriceToProduct(Request $request)
    {
        try {
            $this->createPriceAction->execute([
                "price" => $request->input("price"),
                "currency" => $request->input("currency"),
                "product_id" => $request->input("product_id"),
                "recurring" => $request->input("recurring"),
                "nickname" => $request->input("nickname"),
            ]);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $productId)
    {
        try {
            $this->updateProductAction->execute($productId, [
                "name" => $request->input("name"),
                "description" => $request->input("description"),
                "active" => $request->input("active"),
            ]);

            if ($request->has("price_id")) {
                $this->updatePriceAction->execute($request->input("price_id"), [
                    "active" => $request->input("active"),
                ]);
            }
        } catch (\Exception $e) {
            return back()->withErrors(["error" => $e->getMessage()]);
        }
    }

    public function updatePrice(Request $request, $priceId)
    {
        try {
            $this->updatePriceAction->execute($priceId, [
                "active" => $request->input("active"),
            ]);
            return response()->json(["success" => true]);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 400);
        }
    }

    public function updateDefaultPrice(Request $request, $priceId)
    {
        try {
            $this->updateDefaultPriceAction->execute(
                $priceId,
                $request->input("priceDefault")
            );
            return response()->json(["success" => true]);
        } catch (\Exception $e) {
            return response()->json(["error" => $e->getMessage()], 400);
        }
    }

    public function destroy($productID): RedirectResponse
    {
        try {
            $this->deleteProductAction->execute($productID);
        } catch (\Exception $e) {
            return back()->withErrors(["error" => $e->getMessage()]);
        }
    }
}
