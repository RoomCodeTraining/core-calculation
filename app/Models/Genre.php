<?php

namespace App\Models;

use Essa\APIToolKit\Filters\Filterable;
use Illuminate\Database\Eloquent\Model;
use Deligoez\LaravelModelHashId\Traits\HasHashId;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Genre extends Model
{
    use HasFactory, Filterable, HasHashId;

    protected $fillable = [
        'vehicle_model_id',
        'name',
        'slug',
        'code',
        'max_mileage_essence_per_year',
        'max_mileage_diesel_per_year',
        'label',
        'description',
        'disabled_at',
    ];

    protected $casts = [
        'disabled_at' => 'datetime',
    ];

    /**
     * Get the vehicle model that owns the genre.
     */
    public function vehicleModel(): BelongsTo
    {
        return $this->belongsTo(VehicleModel::class);
    }

    /**
     * Get the usages for the genre.
     */
    public function usages(): HasMany
    {
        return $this->hasMany(Usage::class);
    }

    /**
     * Get the depreciation tables for the genre.
     */
    public function depreciationTables(): HasMany
    {
        return $this->hasMany(DepreciationTable::class, 'usage_id');
    }
}

