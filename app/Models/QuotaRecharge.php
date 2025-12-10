<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuotaRecharge extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'user_id',
        'amount',
        'quota_before',
        'quota_after',
        'notes',
    ];

    protected $casts = [
        'amount' => 'integer',
        'quota_before' => 'integer',
        'quota_after' => 'integer',
    ];

    /**
     * Get the organization that owns the recharge.
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get the user who performed the recharge.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
