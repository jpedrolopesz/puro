<?php

use App\Http\Controllers\Auth\{
    AuthenticatedSessionController,
    ConfirmablePasswordController,
    EmailVerificationNotificationController,
    EmailVerificationPromptController,
    NewPasswordController,
    PasswordController,
    PasswordResetLinkController,
    RegisteredUserController,
    VerifyEmailController
};
use Illuminate\Support\Facades\{Auth, Route};

// Guest routes
Route::middleware(["guest", "guest:admin"])->group(function () {
    Route::get("register", [RegisteredUserController::class, "create"])->name(
        "register"
    );
    Route::post("register", [RegisteredUserController::class, "store"]);

    Route::get("login", [
        AuthenticatedSessionController::class,
        "create",
    ])->name("login");
    Route::post("admin/login", [
        AuthenticatedSessionController::class,
        "store",
    ])->name("storeAdmin");
    Route::post("tenant/login", [
        AuthenticatedSessionController::class,
        "storeTenant",
    ])->name("storeTenant");

    Route::get("forgot-password", [
        PasswordResetLinkController::class,
        "create",
    ])->name("password.request");
    Route::post("forgot-password", [
        PasswordResetLinkController::class,
        "store",
    ])->name("password.email");

    Route::get("reset-password/{token}", [
        NewPasswordController::class,
        "create",
    ])->name("password.reset");
    Route::post("reset-password", [
        NewPasswordController::class,
        "store",
    ])->name("password.store");
});

// Protected routes
Route::middleware(["auth", "auth:admin"])->group(function () {
    Route::get("verify-email", EmailVerificationPromptController::class)->name(
        "verification.notice"
    );

    Route::get("verify-email/{id}/{hash}", VerifyEmailController::class)
        ->middleware(["signed", "throttle:6,1"])
        ->name("verification.verify");

    Route::post("email/verification-notification", [
        EmailVerificationNotificationController::class,
        "store",
    ])
        ->middleware("throttle:6,1")
        ->name("verification.send");

    Route::get("confirm-password", [
        ConfirmablePasswordController::class,
        "show",
    ])->name("password.confirm");
    Route::post("confirm-password", [
        ConfirmablePasswordController::class,
        "store",
    ]);

    Route::post("password", [PasswordController::class, "update"])->name(
        "password.update"
    );

    Route::get("/logout", function () {
        Auth::guard("web")->logout();
        return redirect("/");
    })->name("logout");
});
