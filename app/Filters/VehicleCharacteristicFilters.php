<?php

namespace App\Filters;

use Essa\APIToolKit\Filters\QueryFilters;

class VehicleCharacteristicFilters extends QueryFilters
{
    protected array $allowedFilters = [];

    protected array $columnSearch = [];

    protected array $relationSearch = [
        'vehicleModel' => ['code', 'label'],
        'vehicleModel.brand' => ['code', 'label'],
        'vehicleGenreUsage.vehicleGenre' => ['code', 'label'],
        'vehicleGenreUsage.usage' => ['code', 'label'],
        'dealer' => ['name'],
    ];
}
