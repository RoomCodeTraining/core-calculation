<?php

namespace App\Filters;

use Essa\APIToolKit\Filters\QueryFilters;

class BrandFilters extends QueryFilters
{
    public function name($value)
    {
        return $this->builder->where('name', 'like', "%{$value}%");
    }

    public function slug($value)
    {
        return $this->builder->where('slug', $value);
    }
}

