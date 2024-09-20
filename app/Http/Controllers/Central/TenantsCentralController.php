<?php

namespace App\Http\Controllers\Central;

use App\Actions\Central\Tenants\GetTenantsQueryAction;
use App\Http\Controllers\Controller;
use App\Jobs\ImportStripeUsersJob;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TenantsCentralController extends Controller
{
    public function __construct(
        private readonly GetTenantsQueryAction $getTenantsQueryAction
    ) {
    }

    public function index()
    {
        $tenantsQuery = $this->getTenantsQueryAction->execute()->get();

        return Inertia::render("Central/Tenants/TenantsCentral", [
            "tenantsLists" => $tenantsQuery,
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
