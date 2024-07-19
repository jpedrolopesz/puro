<?php

declare(strict_types=1);

use App\Http\Controllers\Subscription\StripeCheckoutController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Stancl\Tenancy\Middleware\InitializeTenancyBySubdomain;
use Stancl\Tenancy\Middleware\ScopeSessions;

use App\Http\Controllers\Tenant\{
    DashboardTenantController,
    ProfileTenantController
};
use Inertia\Inertia;

Route::group(
    [
        "as" => "tenant.",
        "middleware" => [
            "web",

            InitializeTenancyBySubdomain::class,
            PreventAccessFromCentralDomains::class,
            ScopeSessions::class,
        ],
    ],
    function () {
        Route::middleware("auth")->group(function () {
            Route::post("/subscription", [
                StripeCheckoutController::class,
                "subscription",
            ]);

            Route::get("/stripe/checkout", [
                StripeCheckoutController::class,
                "index",
            ])->name("stripe.checkout");

            Route::get("/dashboard", [
                DashboardTenantController::class,
                "index",
            ])->name("dashboard");

            Route::get("/profile", [
                ProfileTenantController::class,
                "edit",
            ])->name("profile.edit");

            Route::get("/profile/account", function () {
                return Inertia::render("Tenant/Profile/Account");
            })->name("profile.account");

            Route::get("/profile/plans", function () {
                return Inertia::render("Tenant/Profile/Plans");
            })->name("profile.plans");

            Route::patch("/profile", [
                ProfileTenantController::class,
                "update",
            ])->name("profile.update");

            Route::delete("/profile", [
                ProfileTenantController::class,
                "destroy",
            ])->name("profile.destroy");
        });

        require __DIR__ . "/auth.php";
    }
);
