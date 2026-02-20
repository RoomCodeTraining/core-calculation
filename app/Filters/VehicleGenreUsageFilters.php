<?php

namespace App\Filters;

use Essa\APIToolKit\Filters\QueryFilters;

class VehicleGenreUsageFilters extends QueryFilters
{
    protected array $allowedFilters = [];

    protected array $columnSearch = [];

    protected array $relationSearch = [
        'vehicleGenre' => ['code', 'label'],
        'usage' => ['code', 'label'],
    ];
}
