<?php

namespace App\Http\Controllers\Central;

use App\Actions\Central\Stripe\Product\{
    GetProductPriceDetailsAction,
    CreateProductAction,
    UpdateStripeProductAction,
    UpdateStripeProductArchivedAction,
    RetrieveOrderedProductsAction
};

use App\Actions\Central\Stripe\Product\Price\{
    CreateStripePriceAction,
    UpdateStripePriceArchivedAction,
    UpdateStripePriceDefaultAction
};
use App\Http\Requests\Central\Stripe\Product\Price\{
    PriceUpdateRequest,
    PriceCreateRequest
};
use App\Http\Requests\Central\Stripe\Product\{
    ProductCreateRequest,
    ProductUpdateRequest
};
use App\Http\Requests\Central\Stripe\Product\Price\UpdatePriceArchivedRequest;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Inertia\{Inertia, Response};
use Stripe\Stripe;

class ProductsCentralController extends Controller
{
    public function __construct(
        private readonly GetProductPriceDetailsAction $getProductPriceDetailsAction,
        private readonly CreateProductAction $createProductAction,
        private readonly CreateStripePriceAction $createStripePriceAction,
        private readonly UpdateStripeProductAction $updateStripeProductAction,
        private readonly UpdateStripePriceArchivedAction $updateStripePriceAction,
        private readonly UpdateStripePriceDefaultAction $updateStripePriceDefaultAction,
        private readonly UpdateStripeProductArchivedAction $updateStripeProductArchivedAction,
        private readonly RetrieveOrderedProductsAction $retrieveOrderedProductsAction
    ) {
        Stripe::setApiKey(config("services.stripe.secret"));
    }

    public function index(): Response
    {
        $data = $this->retrieveOrderedProductsAction->execute();
        return Inertia::render("Central/Products/ProductsCentral", [
            "products" => $data,
        ]);
    }

    public function details(string $productID): Response
    {
        $data = $this->getProductPriceDetailsAction->execute($productID);
        return Inertia::render("Central/Products/ProductViewDetails", [
            "product" => $data,
        ]);
    }

    public function create(ProductCreateRequest $request): RedirectResponse
    {
        $product = $this->createProductAction->execute($request->validated());

        return back();
    }

    public function addPriceToProduct(
        PriceCreateRequest $request
    ): RedirectResponse {
        $price = $this->createStripePriceAction->execute($request->validated());
        return back();
    }

    public function update(
        ProductUpdateRequest $request,
        string $productId
    ): RedirectResponse {
        $product = $this->updateStripeProductAction->execute(
            $productId,
            $request->validated()
        );
        if ($request->has("price_id")) {
            $this->updateStripePriceAction->execute(
                $request->input("price_id"),
                ["active" => $request->input("active")]
            );
        }
        return back();
    }

    public function updatePriceArchived(
        UpdatePriceArchivedRequest $request,
        string $priceId
    ): RedirectResponse {
        $isActive = $request->getActiveStatus();

        $this->updateStripePriceAction->execute($priceId, [
            "active" => $isActive,
        ]);
        return back();
    }

    public function updatePriceDefault(
        string $priceId,
        PriceUpdateRequest $request
    ): RedirectResponse {
        $price = $this->updateStripePriceDefaultAction->execute(
            $priceId,
            $request->input("priceDefault")
        );
        return back();
    }

    public function updateProductArchived(string $productId): RedirectResponse
    {
        $deleted = $this->updateStripeProductArchivedAction->execute(
            $productId
        );

        return back();
    }
}
