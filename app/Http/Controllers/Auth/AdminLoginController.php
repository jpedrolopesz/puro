<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    public function showLoginForm()
    {
        return view("auth.admin-login");
    }

    public function store(LoginRequest $request)
    {
        $request->authenticate();
        $request->session()->regenerate();

        if (
            Auth::guard("admin")->attempt(
                $request->only("email", "password"),
                $request->filled("remember")
            )
        ) {
            return redirect()->intended(route("admin.dashboard"));
        }
    }

    public function logout(Request $request)
    {
        dd("öm");
        if (Auth::guard("admin")->check()) {
            Auth::guard("admin")->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return redirect()->route("/");
    }
}
