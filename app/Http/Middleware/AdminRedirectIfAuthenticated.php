<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminRedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard("admin")->check()) {
            if ($request->routeIs("login")) {
                return redirect()->route("admin.dashboard");
            }
        } else {
            if ($request->routeIs("admin.dashboard")) {
                return redirect()->route("login");
            }
        }
        return $next($request);
    }
}
