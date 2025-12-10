<?php

namespace App\Models;

use Essa\APIToolKit\Filters\Filterable;
use Illuminate\Database\Eloquent\Model;
use Deligoez\LaravelModelHashId\Traits\HasHashId;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Brand extends Model
{
    use HasFactory, Filterable, HasHashId;

    protected $fillable = [
        'name',
        'slug',
    ];

    /**
     * Get the vehicle models for the brand.
     */
    public function vehicleModels(): HasMany
    {
        return $this->hasMany(VehicleModel::class);
    }
}

