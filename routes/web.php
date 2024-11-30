<?php

use App\Http\Controllers\StripeWebhookController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Public routes
Route::get("/", function () {
    return Inertia::render("LandingPage/Page", [
        "canLogin" => Route::has("login"),
        "canRegister" => Route::has("register"),
    ]);
})->name("home");

// Load other route files
require __DIR__ . "/auth.php";
require __DIR__ . "/admin.php";
