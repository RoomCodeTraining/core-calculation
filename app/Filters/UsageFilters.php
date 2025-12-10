<?php

namespace App\Filters;

use Essa\APIToolKit\Filters\QueryFilters;

class UsageFilters extends QueryFilters
{
    public function name($value)
    {
        return $this->builder->where('name', 'like', "%{$value}%");
    }

    public function slug($value)
    {
        return $this->builder->where('slug', $value);
    }

    public function genre_id($value)
    {
        return $this->builder->where('genre_id', $value);
    }
}









