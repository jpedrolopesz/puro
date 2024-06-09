<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Tenant\DashboardController;
use App\Models\Tenant;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Stancl\Tenancy\Middleware\InitializeTenancyBySubdomain;
use Stancl\Tenancy\Middleware\ScopeSessions;

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
        Route::get("/dashboard", [DashboardController::class, "index"])
            ->middleware(["auth", "verified"])
            ->name("dashboard");

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
