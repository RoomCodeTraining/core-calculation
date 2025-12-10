<?php

namespace App\Filament\Resources\QuotaRechargeResource\Pages;

use App\Filament\Resources\QuotaRechargeResource;
use App\Models\Organization;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateQuotaRecharge extends CreateRecord
{
    protected static string $resource = QuotaRechargeResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        // Récupérer l'organisation et utiliser sa méthode rechargeQuota
        $organization = Organization::find($data['organization_id']);

        if (!$organization) {
            throw new \Exception('Organisation non trouvée');
        }

        // Utiliser la méthode rechargeQuota qui crée automatiquement l'historique
        $organization->rechargeQuota(
            $data['amount'],
            $data['user_id'] ?? \Illuminate\Support\Facades\Auth::id(),
            $data['notes'] ?? null
        );

        // Récupérer la dernière recharge créée
        $recharge = $organization->quotaRecharges()->latest()->first();

        if (!$recharge) {
            throw new \Exception('Erreur lors de la création de la recharge');
        }

        return $recharge;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
