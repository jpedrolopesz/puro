<?php

use App\Http\Controllers\Central\{
    ProfileCentralController,
    TenantsCentralController,
    DashboardCentralController,
    BillingCentralController,
    MailCentralController,
    PlansCentralController
};
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

    ######## BILLING ########
    Route::get("/billing", [BillingCentralController::class, "index"])->name(
        "billing.index"
    );

    Route::get("/billing/{paymentsId}", [
        BillingCentralController::class,
        "details",
    ])->name("billing.details");

    Route::get("/billing-central", [BillingCentralController::class, "index"]);

    Route::get("/billing-central/connect-stripe", [
        BillingCentralController::class,
        "connectStripe",
    ]);
    Route::get("/billing-central/stripe-callback", [
        BillingCentralController::class,
        "handleStripeCallback",
    ]);

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
