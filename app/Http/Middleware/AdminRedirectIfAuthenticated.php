<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AdminRedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle(Request $request, Closure $next, $guard = null)
    {
        if ($guard === "admin" && Auth::guard("admin")->check()) {
            return redirect()->route("admin.dashboard");
        }

        // Verifica se o usuário está autenticado com o guard "web"
        if (Auth::guard("web")->check()) {
            $user = Auth::guard("web")->user();
            $tenant = Tenant::find($user->tenant_id);

            if ($tenant && $tenant->domains()->exists()) {
                $domain = $tenant->domains()->first()->domain;
                $centralDomains = config("tenancy.central_domains");

                $url =
                    "http://" .
                    $domain .
                    "." .
                    $centralDomains[0] .
                    route("tenant.dashboard", [], false);

                return Inertia::location($url);
            }
        }

        // Se $guard não estiver definido ou for nulo, use uma lista padrão de guards para verificar
        $guards = empty($guard) ? ["web"] : [$guard];

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Redireciona o usuário autenticado para a rota principal
                return redirect("/");
            }
        }

        // Permite que usuários não autenticados continuem para a próxima etapa
        return $next($request);
    }
}
