<?php

namespace App\Models;

use Essa\APIToolKit\Filters\Filterable;
use Illuminate\Database\Eloquent\Model;
use Deligoez\LaravelModelHashId\Traits\HasHashId;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VehicleModel extends Model
{
    use HasFactory, Filterable, HasHashId;

    protected $fillable = [
        'brand_id',
        'name',
        'slug',
    ];

    /**
     * Get the brand that owns the vehicle model.
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * Get the genres for the vehicle model.
     */
    public function genres(): HasMany
    {
        return $this->hasMany(Genre::class);
    }
}

