<?php

namespace App\Filters;

use Essa\APIToolKit\Filters\QueryFilters;

class VehicleModelFilters extends QueryFilters
{
    public function name($value)
    {
        return $this->builder->where('name', 'like', "%{$value}%");
    }

    public function slug($value)
    {
        return $this->builder->where('slug', $value);
    }

    public function brand_id($value)
    {
        return $this->builder->where('brand_id', $value);
    }
}









