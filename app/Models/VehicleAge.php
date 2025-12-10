<?php

namespace App\Models;

use Essa\APIToolKit\Filters\Filterable;
use Illuminate\Database\Eloquent\Model;
use Deligoez\LaravelModelHashId\Traits\HasHashId;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VehicleAge extends Model
{
    use HasFactory, Filterable, HasHashId;

    protected $fillable = [
        'value',
        'label',
        'description',
        'disabled_at',
    ];

    protected $casts = [
        'disabled_at' => 'datetime',
    ];

    /**
     * Get the depreciation tables for the vehicle age.
     */
    public function depreciationTables(): HasMany
    {
        return $this->hasMany(DepreciationTable::class);
    }
}

