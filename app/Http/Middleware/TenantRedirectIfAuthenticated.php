<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TenantRedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Verifica se o usuário está autenticado como admin
        if (Auth::guard("web")->check()) {
            // Verifica se a rota atual não é a rota admin.dashboard para evitar loop
            if ($request->routeIs("tenant.login")) {
                return redirect()->route("tenant.dashboard");
            }
        } else {
            // Se o usuário não estiver autenticado, redireciona para a página de login
            if ($request->routeIs("tenant.dashboard")) {
                return redirect()->route("tenant.login");
            }
        }

        // Permite prosseguir com a requisição atual
        return $next($request);
    }
}
