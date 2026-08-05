<?php

namespace App\Models;

use App\Filters\VehicleCharacteristicFilters;
use App\Builders\VehicleCharacteristic\VehicleCharacteristicBuilder;
use Essa\APIToolKit\Filters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Deligoez\LaravelModelHashId\Traits\HasHashId;
use Deligoez\LaravelModelHashId\Traits\HasHashIdRouting;


class VehicleCharacteristic extends Model
{
    use HasFactory, Filterable, HasHashId, HasHashIdRouting, SoftDeletes;

    protected string $default_filters = VehicleCharacteristicFilters::class;

    /**
     * Mass-assignable attributes.
     *
     * @var array
     */
    protected $guarded = [];

    protected $casts = [
        'date' => 'date:Y-m-d',
    ];

    /**
     * Get the vehicle model of this vehicle characteristic
     */
    public function vehicleModel(): BelongsTo
    {
        return $this->belongsTo(VehicleModel::class);
    }

    /**
     * Get the pivot associations of this vehicle characteristic
     */
    public function vehicleCharacteristicGenreUsages(): HasMany
    {
        return $this->hasMany(VehicleCharacteristicGenreUsage::class);
    }

    /**
     * Get the vehicle genre usages of this vehicle characteristic
     */
    public function vehicleGenreUsages(): BelongsToMany
    {
        return $this->belongsToMany(
            VehicleGenreUsage::class,
            'vehicle_characteristic_genre_usages'
        )->withPivot(['status_id', 'created_by', 'updated_by', 'deleted_by']);
    }

    /**
     * Get the energy of this vehicle characteristic
     */
    public function vehicleEnergy(): BelongsTo
    {
        return $this->belongsTo(VehicleEnergy::class);
    }

    /**
     * Get the dealer of this vehicle characteristic
     */
    public function dealer(): BelongsTo
    {
        return $this->belongsTo(Dealer::class);
    }

    /**
     * Get the status of this vehicle characteristic
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    /**
     * Get the user who created this vehicle characteristic
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    /**
     * Get the user who last updated this vehicle characteristic
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    
    
    /**
     * Get the user who deleted this vehicle characteristic
     */
    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    /**
     * Get the builder for this vehicle characteristic
     */
    public function newEloquentBuilder($query): VehicleCharacteristicBuilder
    {
        return new VehicleCharacteristicBuilder($query);
    }
}
