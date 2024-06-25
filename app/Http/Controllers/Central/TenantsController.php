<?php

namespace App\Http\Controllers\Central;

use App\Actions\Central\Tenants\TenantGetAllAction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Inertia\Inertia;

class TenantsController extends Controller
{
    public function index(Tenant $request)
    {
        $tenantsData = $request->all();

        return Inertia::render("Central/Tenants/TenantsCentral", [
            "tenants" => $tenantsData,
        ]);
    }
}
