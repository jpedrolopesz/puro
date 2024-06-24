<?php

namespace App\Actions\Central\Tenants;

use App\Data\Central\TenantFilterData;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;

class TenantGetAllAction
{
    public static function run(array $filters = [])
    {
        $data = TenantFilterData::from([
            "builder" => Tenant::query(),
            "filters" => $filters,
        ]);

        $pipeline = app(Pipeline::class)
            ->send($data)
            ->through([])
            ->thenReturn();

        return $pipeline->builder
            ->paginate(isset($filters["perPage"]) ? $filters["perPage"] : 10)
            ->withQueryString();
    }
}
