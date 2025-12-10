<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuotaRechargeResource\Pages;
use App\Filament\Resources\QuotaRechargeResource\RelationManagers;
use App\Models\QuotaRecharge;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuotaRechargeResource extends Resource
{
    protected static ?string $model = QuotaRecharge::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-path';

    protected static ?string $navigationLabel = 'Recharges de Quota';

    protected static ?string $modelLabel = 'Recharge de Quota';

    protected static ?string $pluralModelLabel = 'Recharges de Quota';

    protected static ?string $navigationGroup = 'Quota';

    protected static ?int $navigationSort = 3;

    public static function canCreate(): bool
    {
        $user = \Illuminate\Support\Facades\Auth::user();

        if (!$user) {
            return false;
        }

        return $user->isAdmin();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('organization_id')
                    ->label('Organisation')
                    ->relationship('organization', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->reactive()
                    ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                        if ($state) {
                            $organization = \App\Models\Organization::find($state);
                            if ($organization) {
                                $set('quota_before', $organization->api_quota);
                                // Recalculer le quota après si un montant est déjà défini
                                $amount = $get('amount') ?? 0;
                                $set('quota_after', $organization->api_quota + $amount);
                            }
                        }
                    })
                    ->disabled(fn ($record) => $record !== null),
                Forms\Components\Select::make('user_id')
                    ->label('Utilisateur admin')
                    ->relationship('user', 'name')
                    ->default(fn () => \Illuminate\Support\Facades\Auth::id())
                    ->disabled(fn ($record) => $record !== null)
                    ->dehydrated(),
                Forms\Components\TextInput::make('amount')
                    ->label('Montant à ajouter')
                    ->required()
                    ->numeric()
                    ->minValue(1)
                    ->default(100)
                    ->reactive()
                    ->afterStateUpdated(function ($state, Forms\Get $get, Forms\Set $set) {
                        $quotaBefore = $get('quota_before') ?? 0;
                        $set('quota_after', $quotaBefore + ($state ?? 0));
                    })
                    ->disabled(fn ($record) => $record !== null),
                Forms\Components\TextInput::make('quota_before')
                    ->label('Quota avant')
                    ->required()
                    ->numeric()
                    ->disabled()
                    ->dehydrated()
                    ->reactive(),
                Forms\Components\TextInput::make('quota_after')
                    ->label('Quota après')
                    ->required()
                    ->numeric()
                    ->disabled()
                    ->dehydrated(),
                Forms\Components\Textarea::make('notes')
                    ->label('Notes')
                    ->rows(3)
                    ->maxLength(500)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('organization.name')
                    ->label('Organisation')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Rechargé par')
                    ->searchable()
                    ->sortable()
                    ->default('Système'),
                Tables\Columns\TextColumn::make('amount')
                    ->label('Montant ajouté')
                    ->numeric()
                    ->badge()
                    ->color('success')
                    ->sortable(),
                Tables\Columns\TextColumn::make('quota_before')
                    ->label('Quota avant')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quota_after')
                    ->label('Quota après')
                    ->numeric()
                    ->badge()
                    ->color('info')
                    ->sortable(),
                Tables\Columns\TextColumn::make('notes')
                    ->label('Notes')
                    ->limit(50)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date de recharge')
                    ->dateTime()
                    ->sortable()
                    ->default('-'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('organization_id')
                    ->label('Organisation')
                    ->relationship('organization', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListQuotaRecharges::route('/'),
            'create' => Pages\CreateQuotaRecharge::route('/create'),
            'view' => Pages\ViewQuotaRecharge::route('/{record}'),
        ];
    }
}
