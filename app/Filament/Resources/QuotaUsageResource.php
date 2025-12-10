<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuotaUsageResource\Pages;
use App\Models\QuotaUsage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class QuotaUsageResource extends Resource
{
    protected static ?string $model = QuotaUsage::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Utilisations de Quota';

    protected static ?string $modelLabel = 'Utilisation de Quota';

    protected static ?string $pluralModelLabel = 'Utilisations de Quota';

    protected static ?string $navigationGroup = 'Quota';

    protected static ?int $navigationSort = 4;


    public static function canCreate(): bool
    {
        return false; // Les utilisations sont créées automatiquement par le middleware
    }

    public static function canEdit($record): bool
    {
        return false; // Les utilisations ne peuvent pas être modifiées
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('organization_id')
                    ->label('Organisation')
                    ->relationship('organization', 'name')
                    ->required()
                    ->disabled()
                    ->dehydrated(),
                Forms\Components\Select::make('user_id')
                    ->label('Utilisateur')
                    ->relationship('user', 'name')
                    ->disabled()
                    ->dehydrated(),
                Forms\Components\TextInput::make('endpoint')
                    ->label('Endpoint')
                    ->required()
                    ->maxLength(255)
                    ->disabled(),
                Forms\Components\TextInput::make('method')
                    ->label('Méthode HTTP')
                    ->required()
                    ->maxLength(255)
                    ->disabled(),
                Forms\Components\TextInput::make('quota_used')
                    ->label('Quota utilisé')
                    ->required()
                    ->numeric()
                    ->disabled(),
                Forms\Components\TextInput::make('quota_remaining_after')
                    ->label('Quota restant après')
                    ->required()
                    ->numeric()
                    ->disabled(),
                Forms\Components\TextInput::make('ip_address')
                    ->label('Adresse IP')
                    ->maxLength(255)
                    ->disabled(),
                Forms\Components\Textarea::make('user_agent')
                    ->label('User Agent')
                    ->disabled()
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
                    ->label('Utilisateur')
                    ->searchable()
                    ->sortable()
                    ->default('N/A'),
                Tables\Columns\TextColumn::make('method')
                    ->label('Méthode')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'GET' => 'info',
                        'POST' => 'success',
                        'PUT' => 'warning',
                        'DELETE' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('endpoint')
                    ->label('Endpoint')
                    ->searchable()
                    ->limit(50),
                Tables\Columns\TextColumn::make('quota_used')
                    ->label('Quota utilisé')
                    ->numeric()
                    ->badge()
                    ->color('warning')
                    ->sortable(),
                Tables\Columns\TextColumn::make('quota_remaining_after')
                    ->label('Quota restant')
                    ->numeric()
                    ->badge()
                    ->color(fn ($state) => $state > 100 ? 'success' : ($state > 0 ? 'warning' : 'danger'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date')
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
                Tables\Filters\SelectFilter::make('method')
                    ->label('Méthode HTTP')
                    ->options([
                        'GET' => 'GET',
                        'POST' => 'POST',
                        'PUT' => 'PUT',
                        'DELETE' => 'DELETE',
                    ]),
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
            'index' => Pages\ListQuotaUsages::route('/'),
            'view' => Pages\ViewQuotaUsage::route('/{record}'),
        ];
    }
}
