<?php

namespace App\Http\Controllers\Central;

use App\Actions\Central\Stripe\Product\GetProductsAndPricesAction;
use App\Actions\Central\Stripe\Product\{
    UpdateProductOrderAction,
    RetrieveOrderedProductsAction
};
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Stripe\Stripe;

class ProductsBuilderCentralController extends Controller
{
    protected $getProductsAndPricesAction;
    protected $updateProductOrderAction;
    protected $retrieveOrderedProductsAction;

    public function __construct(
        GetProductsAndPricesAction $getProductsAndPricesAction,
        UpdateProductOrderAction $updateProductOrderAction,
        RetrieveOrderedProductsAction $retrieveOrderedProductsAction
    ) {
        Stripe::setApiKey(config("services.stripe.secret"));
        $this->getProductsAndPricesAction = $getProductsAndPricesAction;
        $this->updateProductOrderAction = $updateProductOrderAction;
        $this->retrieveOrderedProductsAction = $retrieveOrderedProductsAction;
    }

    public function index()
    {
        $products = $this->retrieveOrderedProductsAction->execute();
        //  dd($products);
        $data = $this->getProductsAndPricesAction->execute();
        //dd($data);
        return Inertia::render("Central/Products/Builder/ProductsBuilder", [
            "products" => $products,
        ]);
    }

    public function updateOrder(Request $request)
    {
        //dd($request->all());

        $result = $this->updateProductOrderAction->execute($request->products);
        return;
    }
}
