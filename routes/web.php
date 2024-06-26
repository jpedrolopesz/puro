<?php

use App\Http\Controllers\Central\{
    ProfileCentralController,
    TenantsController,
    DashboardCentral,
    BillingCentralController
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
    Route::get("/dashboard", [DashboardCentral::class, "index"])->name(
        "admin.dashboard"
    );

    ######## TENANTS ########
    Route::get("/tenants", [TenantsController::class, "index"])->name(
        "tenants.index"
    );

    ######## BILLING ########
    Route::get("/billing", [BillingCentralController::class, "index"])->name(
        "billing.index"
    );
    Route::get("/billing-central", [BillingCentralController::class, "index"]);
    Route::get("/billing-central/connect-stripe", [
        BillingCentralController::class,
        "connectStripe",
    ]);
    Route::get("/billing-central/stripe-callback", [
        BillingCentralController::class,
        "handleStripeCallback",
    ]);

    ######## PROFILE ########

    Route::get("/profile", [ProfileCentralController::class, "edit"])->name(
        "profile.edit"
    );
    Route::get("/profile/account", function () {
        return Inertia::render("Central/Profile/Account");
    })->name("profile.appearance");

    Route::get("/profile/appearance", function () {
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
