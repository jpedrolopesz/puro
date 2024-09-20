<?php

namespace App\Actions\Central\Tenants;

use Illuminate\Database\Eloquent\Builder;
use App\Models\Tenant;

class GetTenantsQueryAction
{
    public function execute(): Builder
    {
        return Tenant::query()->with(["domain", "creator"]);
    }
}
