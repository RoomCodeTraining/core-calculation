<?php

namespace App\Models;

use Deligoez\LaravelModelHashId\Traits\HasHashId;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends Model
{
    use HasFactory, HasHashId;

    protected $fillable = [
        'name',
        'slug',
        'api_quota',
    ];

    protected $casts = [
        'api_quota' => 'integer',
    ];

    /**
     * Get the users for the organization.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the quota recharges for the organization.
     */
    public function quotaRecharges(): HasMany
    {
        return $this->hasMany(QuotaRecharge::class);
    }

    /**
     * Get the quota usages for the organization.
     */
    public function quotaUsages(): HasMany
    {
        return $this->hasMany(QuotaUsage::class);
    }

    /**
     * Décrémente le quota d'appels API.
     */
    public function decrementQuota(int $amount = 1): bool
    {
        if ($this->api_quota >= $amount) {
            $this->decrement('api_quota', $amount);
            return true;
        }
        return false;
    }

    /**
     * Vérifie si l'organisation a encore du quota disponible.
     */
    public function hasQuota(int $amount = 1): bool
    {
        return $this->api_quota >= $amount;
    }

    /**
     * Recharge le quota d'appels API et enregistre l'historique.
     */
    public function rechargeQuota(int $amount, ?int $userId = null, ?string $notes = null): void
    {
        $quotaBefore = $this->api_quota;
        $this->increment('api_quota', $amount);
        $quotaAfter = $this->fresh()->api_quota;

        // Enregistrer l'historique de recharge
        QuotaRecharge::create([
            'organization_id' => $this->id,
            'user_id' => $userId,
            'amount' => $amount,
            'quota_before' => $quotaBefore,
            'quota_after' => $quotaAfter,
            'notes' => $notes,
        ]);
    }
}
