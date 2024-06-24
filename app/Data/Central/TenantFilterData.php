<?php

namespace App\Data\Central;

use Illuminate\Database\Eloquent\Builder;

class TenantFilterData
{
    public $builder;
    public $filters;

    public function __construct(Builder $builder, array $filters)
    {
        $this->builder = $builder;
        $this->filters = $filters;
    }

    public static function from(array $data)
    {
        return new self($data["builder"], $data["filters"]);
    }

    public function applyFilters()
    {
        foreach ($this->filters as $filter => $value) {
            if (method_exists($this, $filter)) {
                $this->{$filter}($value);
            }
        }
        return $this;
    }

    // Exemplo de método de filtro para pesquisa por nome ou email
    private function search($value)
    {
        $this->builder
            ->where("name", "like", "%" . $value . "%")
            ->orWhere("email", "like", "%" . $value . "%");
    }

    // Exemplo de método de filtro para inquilinos removidos
    private function trashed($value)
    {
        if ($value) {
            $this->builder->onlyTrashed();
        }
    }

    // Adicione outros métodos de filtro conforme necessário
}
