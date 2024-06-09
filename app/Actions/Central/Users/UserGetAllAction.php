<?php

namespace App\Actions\Central\Users;

use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\Request;
use App\Data\Central\User\UserFilterData;
use App\Models\User;

class UserGetAllAction
{
    public static function run(array $filters = [])
    {
        $data = UserFilterData::from([
            "builder" => User::query(),
            "filters" => $filters,
        ])->applyFilters();

        $pipeline = app(Pipeline::class)
            ->send($data)
            ->through([
                // Adicione aqui os pipes para manipular os filtros
            ])
            ->thenReturn();

        return $pipeline->builder
            ->filter(Request::only("search", "trashed"))
            ->paginate(7)
            ->withQueryString();
    }
}
