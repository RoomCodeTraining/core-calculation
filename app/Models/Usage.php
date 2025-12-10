<?php

namespace App\Models;

use Essa\APIToolKit\Filters\Filterable;
use Illuminate\Database\Eloquent\Model;
use Deligoez\LaravelModelHashId\Traits\HasHashId;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Usage extends Model
{
    use HasFactory, Filterable, HasHashId;

    protected $fillable = [
        'genre_id',
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
     * Get the genre that owns the usage.
     */
    public function genre(): BelongsTo
    {
        return $this->belongsTo(Genre::class);
    }
}

