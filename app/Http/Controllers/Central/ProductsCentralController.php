<?php

namespace App\Http\Controllers\Central;

use App\Actions\Central\Stripe\Product\{
    GetProductPriceDetailsAction,
    CreateStripeProductAction,
    UpdateStripeProductAction,
    UpdateStripeProductArchivedAction
};

use App\Actions\Central\Stripe\Product\Order\{RetrieveOrderedProductsAction};

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
        private readonly CreateStripeProductAction $createStripeProductAction,
        private readonly CreateStripePriceAction $createStripePriceAction,
        private readonly UpdateStripeProductAction $updateStripeProductAction,
        private readonly UpdateStripePriceArchivedAction $updateStripePriceAction,
        private readonly UpdateStripePriceDefaultAction $updateStripePriceDefaultAction,
        private readonly UpdateStripeProductArchivedAction $updateStripeProductArchivedAction,
        private readonly RetrieveOrderedProductsAction $retrieveOrderedProductsAction
    ) {
        Stripe::setApiKey(config("services.stripe.secret"));
    }

    public function index(Request $request): Response
    {
        $filter = $request->input("filter", "active");
        $data = $this->retrieveOrderedProductsAction->execute($filter);

        return Inertia::render("Central/Products/ProductsCentral", [
            "products" => $data,
            "currentFilter" => $filter,
        ]);
    }

    public function details(string $productId): Response
    {
        $data = $this->getProductPriceDetailsAction->execute($productId);
        return Inertia::render("Central/Products/ProductViewDetails", [
            "product" => $data,
        ]);
    }

    public function create(ProductCreateRequest $request): RedirectResponse
    {
        $product = $this->createStripeProductAction->execute(
            $request->validated()
        );

        if ($request->has("price")) {
            $this->createStripePriceAction->execute([
                "product_id" => $product->id,
                "price" => $request->input("price"),
                "currency" => $request->input("currency"),
                "recurring" => $request->input("recurring"),
                "metadata" => $request->input("metadata"),
            ]);
        }

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

    public function updateProductArchived(
        string $productId,
        bool $archive = true
    ): RedirectResponse {
        $this->updateStripeProductArchivedAction->execute($productId, $archive);

        return back();
    }
}
