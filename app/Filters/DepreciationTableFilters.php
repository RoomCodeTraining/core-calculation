<?php

namespace App\Filters;

use Essa\APIToolKit\Filters\QueryFilters;

class DepreciationTableFilters extends QueryFilters
{
    public function vehicle_genre_id($value)
    {
        return $this->builder->where('usage_id', $value);
    }

    public function vehicle_age_id($value)
    {
        return $this->builder->where('vehicle_age_id', $value);
    }

    public function value($value)
    {
        return $this->builder->where('value', $value);
    }
}

