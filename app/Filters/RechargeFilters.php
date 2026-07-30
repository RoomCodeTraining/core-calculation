<?php

namespace App\Filters;

use Essa\APIToolKit\Filters\QueryFilters;

class RechargeFilters extends QueryFilters
{
    protected array $allowedFilters = ['reference'];

    protected array $columnSearch = ['reference'];
}
