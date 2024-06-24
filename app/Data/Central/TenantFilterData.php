<?php

namespace App\Data\Central;

class TenantFilterData
{
    public $builder;
    public $filters;

    public function __construct($builder, array $filters)
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

    private function search($value)
    {
        $this->builder
            ->where("name", "like", "%" . $value . "%")
            ->orWhere("email", "like", "%" . $value . "%");
    }

    private function trashed($value)
    {
        if ($value) {
            $this->builder->onlyTrashed();
        }
    }
}
