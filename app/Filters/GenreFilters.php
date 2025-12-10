<?php

namespace App\Filters;

use Essa\APIToolKit\Filters\QueryFilters;

class GenreFilters extends QueryFilters
{
    public function name($value)
    {
        return $this->builder->where('name', 'like', "%{$value}%");
    }

    public function slug($value)
    {
        return $this->builder->where('slug', $value);
    }

    public function vehicle_model_id($value)
    {
        return $this->builder->where('vehicle_model_id', $value);
    }
}









