<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        commands: __DIR__ . "/../routes/console.php",
        health: "/up",
        using: function () {
            $centralDomains = config("tenancy.central_domains");

            foreach ($centralDomains as $domain) {
                Route::middleware("web")
                    ->domain($domain)
                    ->group(base_path("routes/web.php"));
            }

            Route::middleware("web")->group(base_path("routes/tenant.php"));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            "auth.admin" =>
                \App\Http\Middleware\AdminRedirectIfAuthenticated::class,
            "auth.tenant" =>
                \App\Http\Middleware\TenantRedirectIfAuthenticated::class,
        ]);

        $middleware->validateCsrfTokens(except: ["stripe/*"]);
        $middleware->web(
            append: [
                \App\Http\Middleware\HandleInertiaRequests::class,
                \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
                // "auth.token" =>\App\Http\Middleware\AuthenticateWithToken::class,
            ]
        );

        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
