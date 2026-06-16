<?php

namespace App\Models;

use App\Filters\VehicleGenreUsageFilters;
use Essa\APIToolKit\Filters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Deligoez\LaravelModelHashId\Traits\HasHashId;
use Deligoez\LaravelModelHashId\Traits\HasHashIdRouting;
use Illuminate\Database\Eloquent\SoftDeletes;


class VehicleGenreUsage extends Model
{
    use HasFactory, Filterable, HasHashId, HasHashIdRouting, SoftDeletes;

    protected string $default_filters = VehicleGenreUsageFilters::class;

    /**
     * Mass-assignable attributes.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the vehicle genre of this vehicle genre usage
     */
    public function vehicleGenre(): BelongsTo
    {
        return $this->belongsTo(VehicleGenre::class);
    }

    /**
     * Get the usage of this vehicle genre usage
     */
    public function usage(): BelongsTo
    {
        return $this->belongsTo(Usage::class);
    }

    /**
     * Get the status of this vehicle genre usage
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    /**
     * Get the user who created this vehicle genre usage
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this vehicle genre usage
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the user who deleted this vehicle genre usage
     */
    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    /**
     * Get the pivot associations of this vehicle genre usage
     */
    public function vehicleCharacteristicGenreUsages(): HasMany
    {
        return $this->hasMany(VehicleCharacteristicGenreUsage::class);
    }

    /**
     * Get the vehicle characteristics for this vehicle genre usage
     */
    public function vehicleCharacteristics(): BelongsToMany
    {
        return $this->belongsToMany(
            VehicleCharacteristic::class,
            'vehicle_characteristic_genre_usages'
        )->withPivot(['status_id', 'created_by', 'updated_by', 'deleted_by']);
    }

    /**
     * Get the depreciation tables for this vehicle genre usage
     */
    public function depreciationTables(): HasMany
    {
        return $this->hasMany(DepreciationTable::class);
    }
}
