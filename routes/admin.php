<?php

use App\Http\Controllers\Central\{
    ProfileCentralController,
    TenantsCentralController,
    DashboardCentralController,
    ConversationCentralController,
    ProductsCentralController,
    PaymentCentralController,
    ProductsBuilderCentralController
};
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware("auth:admin")
    ->name("admin.")
    ->group(function () {
        // Dashboard
        Route::get("/dashboard", [
            DashboardCentralController::class,
            "index",
        ])->name("dashboard");

        // Tenants Management
        Route::prefix("tenants")
            ->name("tenants.")
            ->group(function () {
                Route::get("/", [
                    TenantsCentralController::class,
                    "index",
                ])->name("index");
                Route::get("/{tenant}", [
                    TenantsCentralController::class,
                    "details",
                ])->name("show");
                Route::post("/user/import", [
                    TenantsCentralController::class,
                    "processImportUserTenant",
                ])->name("user.import");
            });

        // Payments Management
        Route::prefix("payments")
            ->name("payments.")
            ->group(function () {
                Route::get("/", [
                    PaymentCentralController::class,
                    "index",
                ])->name("index");
                Route::get("/{paymentsId}", [
                    PaymentCentralController::class,
                    "details",
                ])->name("details");
                Route::post("/process", [
                    PaymentCentralController::class,
                    "processPayments",
                ])->name("process");
                Route::get("/sync", [
                    PaymentCentralController::class,
                    "syncPayments",
                ])->name("sync");
                Route::post("/start-sync", [
                    PaymentCentralController::class,
                    "startSync",
                ])->name("start-sync");
            });

        // Products Management
        Route::prefix("products")
            ->name("products.")
            ->group(function () {
                Route::get("/", [
                    ProductsCentralController::class,
                    "index",
                ])->name("index");
                Route::get("/{productId}", [
                    ProductsCentralController::class,
                    "details",
                ])->name("details");
                Route::put("/{productId}", [
                    ProductsCentralController::class,
                    "update",
                ])->name("update");
                Route::post("/create", [
                    ProductsCentralController::class,
                    "create",
                ])->name("create");
                Route::post("/addPriceToProduct", [
                    ProductsCentralController::class,
                    "addPriceToProduct",
                ])->name("price.add");

                // Archive/Unarchive
                Route::patch("/{productId}/archive", [
                    ProductsCentralController::class,
                    "updateProductArchived",
                ])->name("archive");
                Route::patch("/{productId}/unarchive", [
                    ProductsCentralController::class,
                    "updateProductArchived",
                ])
                    ->name("unarchive")
                    ->defaults("archive", false);

                // Price Management
                Route::put("/{priceId}/default", [
                    ProductsCentralController::class,
                    "updatePriceDefault",
                ])->name("price.default");
                Route::put("/{priceId}/archived", [
                    ProductsCentralController::class,
                    "updatePriceArchived",
                ])->name("price.archive");

                // Product Builder
                Route::get("/builder/rearrange", [
                    ProductsBuilderCentralController::class,
                    "index",
                ])->name("builder");
                Route::post("/update-order", [
                    ProductsBuilderCentralController::class,
                    "updateOrder",
                ])->name("order.update");
            });

        // Profile Management
        Route::prefix("profile")
            ->name("profile.")
            ->group(function () {
                Route::get("/", [
                    ProfileCentralController::class,
                    "edit",
                ])->name("edit");
                Route::get("/account", function () {
                    return Inertia::render("Central/Profile/Account");
                })->name("account");
                Route::patch("/", [
                    ProfileCentralController::class,
                    "update",
                ])->name("update");
                Route::delete("/", [
                    ProfileCentralController::class,
                    "destroy",
                ])->name("destroy");
            });

        // Conversation Management
        Route::prefix("conversation")
            ->name("conversation.")
            ->group(function () {
                Route::get("/", [
                    ConversationCentralController::class,
                    "index",
                ])->name("index");
                Route::post("/message/send", [
                    ConversationCentralController::class,
                    "sendMessage",
                ])->name("message.send");
                Route::post("/create", [
                    ConversationCentralController::class,
                    "createCoversation",
                ])->name("create");
            });

        Broadcast::routes();
    });
