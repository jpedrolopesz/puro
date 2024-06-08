<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Stancl\Tenancy\Middleware\InitializeTenancyBySubdomain;
use Stancl\Tenancy\Middleware\ScopeSessions;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

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
        Route::get("/dashboard", function () {
            return Inertia::render("Dashboard");
        })->name("dashboard");

        Route::middleware("auth")->group(function () {
            Route::get("/profile", [ProfileController::class, "edit"])->name(
                "profile.edit"
            );
            Route::patch("/profile", [
                ProfileController::class,
                "update",
            ])->name("profile.update");
            Route::delete("/profile", [
                ProfileController::class,
                "destroy",
            ])->name("profile.destroy");
        });

        require __DIR__ . "/auth.php";
    }
);
