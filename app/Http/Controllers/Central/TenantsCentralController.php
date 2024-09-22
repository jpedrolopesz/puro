<?php

namespace App\Http\Controllers\Central;

use App\Actions\Central\Tenants\{
    GetTenantsQueryAction,
    CountStripeCustomerPaymentsAction,
    GetTenantUsersAction
};
use App\Http\Controllers\Controller;
use App\Jobs\Central\Stripe\User\ImportStripeUsersJob;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TenantsCentralController extends Controller
{
    public function __construct(
        private readonly GetTenantsQueryAction $getTenantsQueryAction,
        private readonly CountStripeCustomerPaymentsAction $countStripeCustomerPaymentsAction,
        private readonly GetTenantUsersAction $getTenantsUsersAction
    ) {
    }

    public function index()
    {
        $tenantsQuery = $this->getTenantsQueryAction->execute()->get();

        return Inertia::render("Central/Tenants/TenantsCentral", [
            "tenantsLists" => $tenantsQuery,
        ]);
    }

    public function details(Tenant $tenant)
    {
        $tenant = $tenant->load(["creator", "domain"]);
        $user = $tenant->creator;
        $customerPayments = null;

        if ($user && $user->stripe_id) {
            $customerPayments = $this->countStripeCustomerPaymentsAction->execute(
                $user->stripe_id
            );
        }

        $tenantUsers = $this->getTenantsUsersAction->execute($tenant);

        return Inertia::render("Central/Tenants/TenantViewDetails", [
            "tenantDetails" => $tenant,
            "customerPayments" => $customerPayments,
            "tenantUsers" => $tenantUsers,
        ]);
    }

    public function processImportUserTenant(Request $request)
    {
        ImportStripeUsersJob::dispatch();
    }
}
