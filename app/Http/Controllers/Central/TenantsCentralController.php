<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Inertia\Inertia;

class TenantsCentralController extends Controller
{
    public function index()
    {
        $tenantsData = Tenant::with([
            "creator",
            "activityLogs",
            "resourceUsage",
            "supportTickets",
        ])->get();

        return Inertia::render("Central/Tenants/TenantsCentral", [
            "tenantsLists" => $tenantsData,
        ]);
    }

    public function details(Tenant $tenantId, $id)
    {
        $tenantId = Tenant::with([
            "creator",
            "activityLogs",
            "resourceUsage",
            "supportTickets",
        ])->find($id);

        return Inertia::render("Central/Tenants/TenantViewDetails", [
            "tenantDetails" => $tenantId,
        ]);
    }
}
