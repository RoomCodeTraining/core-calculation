<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrganizationResource\Pages;
use App\Filament\Resources\OrganizationResource\RelationManagers\UsersRelationManager;
use App\Models\Organization;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class OrganizationResource extends Resource
{
    protected static ?string $model = Organization::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';

    protected static ?string $navigationLabel = 'Organisations';

    protected static ?string $modelLabel = 'Organisation';

    protected static ?string $pluralModelLabel = 'Organisations';

    protected static ?string $navigationGroup = 'Quota';

    protected static ?int $navigationSort = 1;

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nom')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(fn (string $operation, $state, Forms\Set $set) => $operation === 'create' ? $set('slug', \Illuminate\Support\Str::slug($state)) : null),
                Forms\Components\TextInput::make('slug')
                    ->label('Slug')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),
                Forms\Components\TextInput::make('api_quota')
                    ->label('Quota API')
                    ->numeric()
                    ->default(0)
                    ->required()
                    ->minValue(0)
                    ->helperText('Nombre d\'appels API disponibles pour cette organisation'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('slug')
                    ->label('Slug')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('api_quota')
                    ->label('Quota API')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => $state > 100 ? 'success' : ($state > 0 ? 'warning' : 'danger')),
                Tables\Columns\TextColumn::make('users_count')
                    ->label('Utilisateurs')
                    ->counts('users')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Modifié le')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('recharge_quota')
                    ->label('Recharger le quota')
                    ->icon('heroicon-o-plus-circle')
                    ->color('success')
                    ->form([
                        Forms\Components\TextInput::make('amount')
                            ->label('Montant à ajouter')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->default(100),
                        Forms\Components\Textarea::make('notes')
                            ->label('Notes (optionnel)')
                            ->rows(3)
                            ->maxLength(500),
                    ])
                    ->action(function (Organization $record, array $data): void {
                        $record->rechargeQuota(
                            $data['amount'],
                            Auth::id(),
                            $data['notes'] ?? null
                        );
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Recharger le quota API')
                    ->modalDescription('Combien d\'appels API souhaitez-vous ajouter à cette organisation ?')
                    ->modalSubmitActionLabel('Recharger'),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            UsersRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrganizations::route('/'),
            'create' => Pages\CreateOrganization::route('/create'),
            'edit' => Pages\EditOrganization::route('/{record}/edit'),
        ];
    }
}
