<?php

use App\Http\Controllers\Central\{
    ProfileCentralController,
    TenantsCentralController,
    DashboardCentralController,
    MailCentralController,
    ProductsCentralController,
    PaymentCentralController,
    StripeWebhookController
};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

// Rota para o webhook do Stripe
Route::post("/stripe/webhook", [
    StripeWebhookController::class,
    "handleWebhook",
]);

// Rotas protegidas por middleware de autenticação
Route::middleware("auth:admin")->group(function () {
    ######## DASHBOARD ########
    Route::get("/dashboard", [
        DashboardCentralController::class,
        "index",
    ])->name("admin.dashboard");

    ######## TENANTS ########
    Route::get("/tenants", [TenantsCentralController::class, "index"])->name(
        "tenants.index"
    );
    Route::get("/tenants/{tenantID}", [
        TenantsCentralController::class,
        "details",
    ])->name("tenant.details");

    ######## PAYMENTS ########
    Route::get("/payments", [PaymentCentralController::class, "index"])->name(
        "payments.index"
    );

    Route::post("/start-sync", [PaymentCentralController::class, "startSync"]);
    Route::get("/broadcasting/auth", function () {
        // Verifique se o usuário está autenticado no guard admin
        return Auth::guard("admin")->check()
            ? response()->json(["auth" => true])
            : response()->json(["auth" => false], 403);
    });

    Route::get("/payments/{paymentsId}", [
        PaymentCentralController::class,
        "details",
    ])->name("payments.details");
    Route::post("/process-payments", [
        PaymentCentralController::class,
        "processPayments",
    ])->name("processPayments");
    Route::get("/sync-payments", [
        PaymentCentralController::class,
        "syncPayments",
    ])->name("sync.payments");

    ######## PRODUCTS ########
    Route::get("/products", [ProductsCentralController::class, "index"])->name(
        "products.index"
    );
    Route::get("/product/{productID}", [
        ProductsCentralController::class,
        "details",
    ])->name("product.details");
    Route::put("/products/{productId}", [
        ProductsCentralController::class,
        "update",
    ])->name("product.update");
    Route::put("/price/{priceId}", [
        ProductsCentralController::class,
        "updatePrice",
    ])->name("price.update");
    Route::put("/products/{productID}", [
        ProductsCentralController::class,
        "destroy",
    ])->name("product.destroy");
    Route::post("/products/create", [
        ProductsCentralController::class,
        "create",
    ])->name("product.create");
    Route::post("/products/addPriceToProduct", [
        ProductsCentralController::class,
        "addPriceToProduct",
    ])->name("product.addPriceToProduct");
    Route::put("/product/{priceId}/default", [
        ProductsCentralController::class,
        "updateDefaultPrice",
    ])->name("product.update.default");

    ######## MAIL ########
    Route::get("/mail", [MailCentralController::class, "index"])->name(
        "mails.index"
    );

    ######## PROFILE ########
    Route::get("/profile", [ProfileCentralController::class, "edit"])->name(
        "profile.edit"
    );
    Route::get("/profile/account", function () {
        return Inertia::render("Central/Profile/Account");
    })->name("profile.account");
    Route::patch("/profile", [ProfileCentralController::class, "update"])->name(
        "profile.update"
    );
    Route::delete("/profile", [
        ProfileCentralController::class,
        "destroy",
    ])->name("profile.destroy");
});

require __DIR__ . "/auth.php";
