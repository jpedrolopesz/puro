<?php

use App\Http\Controllers\Central\DashboardCentral;
use App\Http\Controllers\Central\TenantsController;

use App\Http\Controllers\Central\ProfileCentralController;
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

######## TENANTS ########
Route::get("/tenants", [TenantsController::class, "index"])->name(
    "tenants.index"
);

Route::middleware("auth:admin")->group(function () {
    Route::get("/dashboard", [DashboardCentral::class, "index"])->name(
        "admin.dashboard"
    );
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
