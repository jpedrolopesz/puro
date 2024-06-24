<?php

namespace App\Actions\Central\Tenants;

use Illuminate\Pipeline\Pipeline;
use App\Data\Central\TenantFilterData;
use App\Models\Tenant;

class TenantGetAllAction
{
    public static function run(array $filters = [])
    {
        $data = TenantFilterData::from([
            "builder" => Tenant::query(),
            "filters" => $filters,
        ])->applyFilters();

        $pipeline = app(Pipeline::class)
            ->send($data)
            ->through([
                // Adicione aqui os pipes para manipular os filtros
            ])
            ->thenReturn();

        return $pipeline->builder
            // ->filter(Request::only("search", "trashed"))
            ->paginate(7)
            ->withQueryString();
    }
}
