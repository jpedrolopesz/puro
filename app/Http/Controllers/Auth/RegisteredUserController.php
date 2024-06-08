<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tenant;

use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): Response
    {
        return Inertia::render("Auth/Register");
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            "name" => "required|string|max:255",
            "email" =>
                "required|string|lowercase|email|max:255|unique:" . User::class,
            "password" => ["required", "confirmed", Rules\Password::defaults()],
        ]);

        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => Hash::make($request->password),
        ]);

        $tenant = Tenant::create([
            "tenancy_db_name" => $request->domain . $request->name,
            "domain" => $request->domain,
            "email" => $request->email,
        ]);
        $tenant->createDomain([
            "domain" => $request->domain,
        ]);

        event(new Registered($user));

        Auth::login($user);

        $url =
            "http://" . $request->domain . route("tenant.dashboard", [], false);

        $user = $request->user();
        $tenant = Tenant::find($user->tenant_id);
        $domain = $tenant->domains()->first()->domain;
        $centralDomains = config("tenancy.central_domains");

        $url =
            "http://" .
            $domain .
            "." .
            $centralDomains[0] .
            route("dashboard", [], false);

        return Inertia::location($url);
    }
}
