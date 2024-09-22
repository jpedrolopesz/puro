<?php

namespace App\Actions\Central\Tenants;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Collection;

class GetTenantUsersAction
{
    public function execute(Tenant $tenant): Collection
    {
        return $tenant->users()->get();
    }
}
