<?php

namespace App\Http\Controllers\Central;

use App\Actions\Central\Stripe\Product\Order\{
    RetrieveOrderedProductsAction,
    UpdateProductOrderAction
};
use App\Http\Controllers\Controller;
use Illuminate\Http\{Request, RedirectResponse};
use Inertia\{Inertia, Response};

class ProductsBuilderCentralController extends Controller
{
    public function __construct(
        private readonly UpdateProductOrderAction $updateProductOrderAction,
        private readonly RetrieveOrderedProductsAction $retrieveOrderedProductsAction
    ) {
    }

    public function index(): Response
    {
        $products = $this->retrieveOrderedProductsAction->execute();

        return Inertia::render("Central/Products/Builder/ProductsBuilder", [
            "products" => $products,
        ]);
    }

    public function updateOrder(Request $request): RedirectResponse
    {
        //dd($request->all());
        $result = $this->updateProductOrderAction->execute($request->products);
        return back();
    }
}
