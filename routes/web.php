<?php

use App\Http\Controllers\Central\{
    ProfileCentralController,
    TenantsCentralController,
    DashboardCentralController,
    ConversationCentralController,
    ProductsCentralController,
    PaymentCentralController,
    StripeWebhookController,
    ProductsBuilderCentralController
};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Public routes
Route::get("/", function () {
    if (Auth::guard("admin")->check()) {
        $user = Auth::guard("admin")->user();
        return response()->json([
            "authenticated" => Auth::guard("admin")->check(),
            "user" => $user,
        ]);
    }

    return response()->json(["authenticated" => false]);

    return Inertia::render("Welcome", [
        "canLogin" => Route::has("login"),
        "canRegister" => Route::has("register"),
    ]);
});

Route::post("/stripe/webhook", [
    StripeWebhookController::class,
    "handleWebhook",
]);

// Protected routes
Route::middleware("auth:admin")->group(function () {
    // Dashboard
    Route::get("/dashboard", [
        DashboardCentralController::class,
        "index",
    ])->name("admin.dashboard");

    // Tenants
    Route::get("/tenants", [TenantsCentralController::class, "index"])->name(
        "tenants.index"
    );
    Route::get("/tenants/{tenantID}", [
        TenantsCentralController::class,
        "details",
    ])->name("tenant.details");

    // Payments
    Route::prefix("payments")->group(function () {
        Route::get("/", [PaymentCentralController::class, "index"])->name(
            "payments.index"
        );
        Route::get("/{paymentsId}", [
            PaymentCentralController::class,
            "details",
        ])->name("payments.details");
        Route::post("/process", [
            PaymentCentralController::class,
            "processPayments",
        ])->name("processPayments");
        Route::get("/sync", [
            PaymentCentralController::class,
            "syncPayments",
        ])->name("sync.payments");
        Route::post("/start-sync", [
            PaymentCentralController::class,
            "startSync",
        ]);
    });

    // Products
    Route::prefix("products")->group(function () {
        Route::get("/", [ProductsCentralController::class, "index"])->name(
            "products.index"
        );
        Route::get("/{productID}", [
            ProductsCentralController::class,
            "details",
        ])->name("product.details");
        Route::put("/{productId}", [
            ProductsCentralController::class,
            "update",
        ])->name("product.update");

        Route::patch("/{productId}/archive", [
            ProductsCentralController::class,
            "updateProductArchived",
        ])->name("product.archive");

        Route::post("/create", [
            ProductsCentralController::class,
            "create",
        ])->name("product.create");

        Route::post("/addPriceToProduct", [
            ProductsCentralController::class,
            "addPriceToProduct",
        ])->name("product.addPriceToProduct");

        // Product Price
        Route::put("/{priceId}/default", [
            ProductsCentralController::class,
            "updatePriceDefault",
        ])->name("price.update.default");
        Route::put("/{priceId}/archived", [
            ProductsCentralController::class,
            "updatePriceArchived",
        ])->name("price.update.archived");

        // Product Builder
        Route::get("/builder", [
            ProductsBuilderCentralController::class,
            "index",
        ])->name("products.builder.index");
        Route::post("/update-order", [
            ProductsBuilderCentralController::class,
            "updateOrder",
        ])->name("products.updateOrder");
    });

    // Profile
    Route::prefix("profile")->group(function () {
        Route::get("/", [ProfileCentralController::class, "edit"])->name(
            "profile.edit"
        );
        Route::get("/account", function () {
            return Inertia::render("Central/Profile/Account");
        })->name("profile.account");
        Route::patch("/", [ProfileCentralController::class, "update"])->name(
            "profile.update"
        );
        Route::delete("/", [ProfileCentralController::class, "destroy"])->name(
            "profile.destroy"
        );
    });

    // Conversation
    Route::prefix("conversation")->group(function () {
        Route::get("/", [ConversationCentralController::class, "index"])->name(
            "conversation.index"
        );
        Route::post("/message/send", [
            ConversationCentralController::class,
            "sendMessage",
        ])->name("admin.message.send");
        Route::post("/create", [
            ConversationCentralController::class,
            "createCoversation",
        ])->name("admin.conversation.create");
    });

    Broadcast::routes();
});

require __DIR__ . "/auth.php";
