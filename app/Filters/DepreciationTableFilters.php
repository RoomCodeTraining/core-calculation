<?php

namespace App\Filters;

use Essa\APIToolKit\Filters\QueryFilters;

class DepreciationTableFilters extends QueryFilters
{
    protected array $allowedFilters = [];

    protected array $columnSearch = ['value'];
}
