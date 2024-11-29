<?php

declare(strict_types=1);

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Central\ConversationCentralController;
use App\Http\Controllers\Subscription\StripeCheckoutController;
use App\Http\Controllers\Tenant\ConversationTenantController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Stancl\Tenancy\Middleware\InitializeTenancyBySubdomain;
use Stancl\Tenancy\Middleware\ScopeSessions;

use App\Http\Controllers\Tenant\{
    DashboardTenantController,
    ProfileTenantController
};
use Illuminate\Support\Facades\Auth;
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
        Route::get("/", function () {
            if (Auth::guard("web")->check()) {
                $user = Auth::guard("web")->user();
                return response()->json([
                    "authenticated" => true,
                    "user" => $user,
                ]);
            }

            return response()->json(["authenticated" => false]);
        });

        Route::get("login", [
            AuthenticatedSessionController::class,
            "create",
        ])->name("login");

        Route::middleware(["auth"])->group(function () {
            Route::get("/dashboard", [
                DashboardTenantController::class,
                "index",
            ])->name("dashboard");

            Route::get("/conversation", [
                ConversationTenantController::class,
                "index",
            ])->name("conversation");

            Route::post("/conversation/message/send", [
                ConversationCentralController::class,
                "sendMessage",
            ])->name("message.send");

            Route::post("/conversation/create", [
                ConversationCentralController::class,
                "createCoversation",
            ])->name("conversation.create");

            Route::post("/subscription", [
                StripeCheckoutController::class,
                "subscription",
            ]);

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

        //  require __DIR__ . "/auth.php";
    }
);
