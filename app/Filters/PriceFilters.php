<?php

namespace App\Filters;

use Essa\APIToolKit\Filters\QueryFilters;

class PriceFilters extends QueryFilters
{
    protected array $allowedFilters = [];

    protected array $columnSearch = [];
}
