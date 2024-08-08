<?php

use App\Http\Controllers\Central\{
    ProfileCentralController,
    TenantsCentralController,
    DashboardCentralController,
    MailCentralController,
    PlansCentralController,
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

Route::post("/stripe/webhook", [
    StripeWebhookController::class,
    "handleWebhook",
]);

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
    Route::get("/payments/{paymentsId}", [
        PaymentCentralController::class,
        "details",
    ])->name("payments.details");
    Route::post("/process-payments", [
        PaymentCentralController::class,
        "processPayments",
    ]);

    Route::get("/sync-payments", [
        PaymentCentralController::class,
        "syncPayments",
    ])->name("sync.payments");

    ######## PLANS ########

    Route::get("/plans", [PlansCentralController::class, "index"])->name(
        "plans.index"
    );
    Route::get("/plan/{productID}", [
        PlansCentralController::class,
        "details",
    ])->name("plan.details");

    Route::put("/plans/{productId}", [
        PlansCentralController::class,
        "update",
    ])->name("plan.update");
    Route::put("/price/{priceId}", [
        PlansCentralController::class,
        "updatePrice",
    ])->name("price.update");

    Route::put("/plans/{productID}", [
        PlansCentralController::class,
        "destroy",
    ])->name("plan.destroy");

    Route::post("/plans/create", [
        PlansCentralController::class,
        "create",
    ])->name("plan.create");

    Route::post("/plans/addPriceToProduct", [
        PlansCentralController::class,
        "addPriceToProduct",
    ])->name("plan.addPriceToProduct");

    ######## Mail ########

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
