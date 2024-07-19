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
use App\Http\Controllers\Subscription\StripeCheckoutController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get("/", function () {
    return Inertia::render("Welcome", [
        "canLogin" => Route::has("login"),
        "canRegister" => Route::has("register"),
        "laravelVersion" => Application::VERSION,
        "phpVersion" => PHP_VERSION,
    ]);
});

Route::post("/stripe/webhook", [
    StripeWebhookController::class,
    "handleWebhook",
]);

Route::get("/payments", [PaymentCentralController::class, "index"])->name(
    "payments.index"
);

Route::get("/payments/{paymentsId}", [
    PaymentCentralController::class,
    "details",
])->name("payments.details");

Route::get("/sync-payments", [
    PaymentCentralController::class,
    "syncPayments",
])->name("sync.payments");

Route::middleware("auth:admin")->group(function () {
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

    ######## Plans ########

    Route::get("/plans", [PlansCentralController::class, "index"])->name(
        "plans.index"
    );
    Route::get("/plans/{planId}", [
        PlansCentralController::class,
        "edit",
    ])->name("plan.edit");

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
    })->name("profile.appearanc");

    Route::get("/profile/appearanc", function () {
        return Inertia::render("Central/Profile/Appearance");
    })->name("profile.appearance");
    Route::patch("/profile", [ProfileCentralController::class, "update"])->name(
        "profile.update"
    );
    Route::delete("/profile", [
        ProfileCentralController::class,
        "destroy",
    ])->name("profile.destroy");
});

require __DIR__ . "/auth.php";
