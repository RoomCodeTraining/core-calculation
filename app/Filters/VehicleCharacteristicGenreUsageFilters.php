<?php

namespace App\Filters;

use Essa\APIToolKit\Filters\QueryFilters;

class VehicleCharacteristicGenreUsageFilters extends QueryFilters
{
    protected array $allowedFilters = [
        'vehicle_characteristic_id',
        'vehicle_genre_usage_id',
        'status_id',
    ];

    protected array $columnSearch = [];
}
