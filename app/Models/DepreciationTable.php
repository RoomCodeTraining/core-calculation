<?php

namespace App\Models;

use Essa\APIToolKit\Filters\Filterable;
use Illuminate\Database\Eloquent\Model;
use Deligoez\LaravelModelHashId\Traits\HasHashId;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DepreciationTable extends Model
{
    use HasFactory, Filterable, HasHashId;

    protected $fillable = [
        'value',
        'usage_id',
        'vehicle_age_id',
    ];

    /**
     * Get the genre (usage) that owns the depreciation table.
     */
    public function genre(): BelongsTo
    {
        return $this->belongsTo(Genre::class, 'usage_id');
    }

    /**
     * Get the vehicle genre (usage) that owns the depreciation table.
     * @deprecated Use genre() instead
     */
    public function vehicleGenre(): BelongsTo
    {
        return $this->belongsTo(Genre::class, 'usage_id');
    }

    /**
     * Get the vehicle age that owns the depreciation table.
     */
    public function vehicleAge(): BelongsTo
    {
        return $this->belongsTo(VehicleAge::class);
    }
}

