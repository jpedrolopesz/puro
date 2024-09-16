<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Jobs\ImportStripeUsersJob;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TenantsCentralController extends Controller
{
    public function index()
    {
        $tenantsData = Tenant::with(["creator"])->get();

        return Inertia::render("Central/Tenants/TenantsCentral", [
            "tenantsLists" => $tenantsData,
        ]);
    }

    public function details(Tenant $tenantId, $id)
    {
        $tenantId = Tenant::with(["creator", "domain"])->find($id);

        return Inertia::render("Central/Tenants/TenantViewDetails", [
            "tenantDetails" => $tenantId,
        ]);
    }

    public function processImportUserTenant(Request $request)
    {
        ImportStripeUsersJob::dispatch();
    }
}
