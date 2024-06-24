<?php

namespace App\Http\Controllers\Central;

use App\Actions\Central\Tenants\TenantGetAllAction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Inertia\Inertia;

class TenantsController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(["search", "trashed"]);
        $tenants = TenantGetAllAction::run($filters);

        return Inertia::render("Central/Tenants/TenantsCentral", [
            "tenants" => $tenants,
        ]);
    }
}
