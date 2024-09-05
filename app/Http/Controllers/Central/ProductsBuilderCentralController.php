<?php

namespace App\Http\Controllers\Central;

use App\Actions\Central\Stripe\Product\GetProductsAndPricesAction;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Stripe\Stripe;

class ProductsBuilderCentralController extends Controller
{
    protected $getProductsAndPricesAction;

    public function __construct(
        GetProductsAndPricesAction $getProductsAndPricesAction
    ) {
        Stripe::setApiKey(config("services.stripe.secret"));
        $this->getProductsAndPricesAction = $getProductsAndPricesAction;
    }

    public function index()
    {
        $formattedData = $this->getProductsAndPricesAction->execute();

        return Inertia::render("Central/Products/Builder/ProductsBuilder", [
            "products" => $formattedData,
        ]);
    }
}
