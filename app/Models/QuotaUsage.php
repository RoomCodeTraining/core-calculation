<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuotaUsage extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'user_id',
        'endpoint',
        'method',
        'quota_used',
        'quota_remaining_after',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'quota_used' => 'integer',
        'quota_remaining_after' => 'integer',
    ];

    /**
     * Get the organization that owns the usage.
     */
    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get the user who made the API call.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
