<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get("/", function () {
    return Inertia::render("LandingPage/Page", [
        "canLogin" => Route::has("login"),
        "canRegister" => Route::has("register"),
    ]);
})->name("home");

require base_path("routes/auth.php");
require base_path("routes/admin.php");
