<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use App\Models\Tenant;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render("Auth/Login", [
            "domain" => env("SESSION_DOMAIN"),
            "canResetPassword" => Route::has("password.request"),
            "status" => session("status"),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        if (Auth::guard("admin")->check()) {
            return redirect()->route("admin.dashboard");
        } elseif (Auth::guard("web")->check()) {
            $user = Auth::guard("web")->user();
            $tenant = Tenant::find($user->tenant_id);

            $domain = $tenant->domains()->first()->domain;
            $centralDomains = config("tenancy.central_domains");

            $url =
                "https://" .
                $domain .
                "." .
                $centralDomains[0] .
                route("tenant.dashboard", [], false);

            return Redirect::to($url);
        }

        return redirect()->route("login"); // fallback
    }

    /**
     * Handle an incoming authentication tenant request.
     */
    public function storeTenant(LoginRequest $request)
    {
        // dd("ok - storeTenant");
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->route("tenant.dashboard");
    }

    /**
     * Destroy an authenticated session.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard("web")->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect("/");
    }
}
