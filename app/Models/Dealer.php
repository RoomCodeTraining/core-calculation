<?php

namespace App\Models;

use App\Filters\DealerFilters;
use Essa\APIToolKit\Filters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Deligoez\LaravelModelHashId\Traits\HasHashId;
use Deligoez\LaravelModelHashId\Traits\HasHashIdRouting;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dealer extends Model
{
    use HasFactory, Filterable, HasHashId, HasHashIdRouting, SoftDeletes;

    protected string $default_filters = DealerFilters::class;

    /**
     * Mass-assignable attributes.
     *
     * @var array
     */
    protected $guarded = [
        
    ];

    /**
     * Get all vehicle characteristics for this dealer
     */
    public function vehicleCharacteristics(): HasMany
    {
        return $this->hasMany(VehicleCharacteristic::class);
    }

    /**
     * Get the status of this dealer
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    /**
     * Get the user who created this dealer
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this dealer
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the user who deleted this dealer
     */
    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
