<?php

namespace App\Filament\Resources\OrganizationResource\Pages;

use App\Filament\Resources\OrganizationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrganization extends EditRecord
{
    protected static string $resource = OrganizationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('recharge_quota')
                ->label('Recharger le quota')
                ->icon('heroicon-o-plus-circle')
                ->color('success')
                ->form([
                    \Filament\Forms\Components\TextInput::make('amount')
                        ->label('Montant à ajouter')
                        ->numeric()
                        ->required()
                        ->minValue(1)
                        ->default(100),
                    \Filament\Forms\Components\Textarea::make('notes')
                        ->label('Notes (optionnel)')
                        ->rows(3)
                        ->maxLength(500),
                ])
                ->action(function (array $data): void {
                    $this->record->rechargeQuota(
                        $data['amount'],
                        \Illuminate\Support\Facades\Auth::id(),
                        $data['notes'] ?? null
                    );
                    $this->refreshFormData(['api_quota']);
                })
                ->requiresConfirmation()
                ->modalHeading('Recharger le quota API')
                ->modalDescription('Combien d\'appels API souhaitez-vous ajouter à cette organisation ?')
                ->modalSubmitActionLabel('Recharger'),
            Actions\DeleteAction::make(),
        ];
    }
}
