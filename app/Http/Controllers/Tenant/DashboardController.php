<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Tenant;
use App\Models\Domain;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $tenants = Tenant::all();
        $data = [];

        foreach ($tenants as $tenant) {
            $tenantData = $tenant->toArray();

            // Pega os domínios do tenant
            $domains = Domain::where("tenant_id", $tenant->id)->get();
            $tenantData["domains"] = $domains->toArray();

            // Pega os usuários do tenant
            $users = User::where("tenant_id", $tenant->id)->get();
            $tenantData["users"] = $users
                ->map(function ($user) {
                    $userData = $user->toArray();

                    // Pega o token de reset de senha do usuário
                    $passwordResetToken = DB::table("password_reset_tokens")
                        ->where("email", $user->email)
                        ->first();
                    $userData["password_reset_token"] = $passwordResetToken
                        ? (array) $passwordResetToken
                        : null;

                    // Pega a sessão do usuário
                    $session = DB::table("sessions")
                        ->where("user_id", $user->id)
                        ->first();
                    $userData["session"] = $session ? (array) $session : null;

                    return $userData;
                })
                ->toArray();

            $data["tenants"][] = $tenantData;
        }

        return Inertia::render("Tenant/Dashboard/DashboardTenant");
    }
}
