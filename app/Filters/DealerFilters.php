<?php

namespace App\Filters;

use Essa\APIToolKit\Filters\QueryFilters;

class DealerFilters extends QueryFilters
{
    protected array $allowedFilters = [];

    protected array $columnSearch = ['name', 'email', 'phone'];
}
