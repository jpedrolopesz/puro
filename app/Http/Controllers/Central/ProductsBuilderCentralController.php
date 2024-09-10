<?php

namespace App\Http\Controllers\Central;

use App\Actions\Central\Stripe\Product\RetrieveOrderedProductsAction;
use App\Actions\Central\Stripe\Product\Order\UpdateProductOrderAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductsBuilderCentralController extends Controller
{
    protected $updateProductOrderAction;
    protected $retrieveOrderedProductsAction;

    public function __construct(
        UpdateProductOrderAction $updateProductOrderAction,
        RetrieveOrderedProductsAction $retrieveOrderedProductsAction
    ) {
        //  Stripe::setApiKey(config("services.stripe.secret"));
        $this->updateProductOrderAction = $updateProductOrderAction;
        $this->retrieveOrderedProductsAction = $retrieveOrderedProductsAction;
    }

    public function index()
    {
        $products = $this->retrieveOrderedProductsAction->execute();

        return Inertia::render("Central/Products/Builder/ProductsBuilder", [
            "products" => $products,
        ]);
    }

    public function updateOrder(Request $request)
    {
        $result = $this->updateProductOrderAction->execute($request->products);
        return;
    }
}
