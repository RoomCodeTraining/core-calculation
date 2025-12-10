<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Mail\ApiTokenGenerated;
use App\Models\User;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Générer un token API
        $data['api_token'] = Str::random(60);

        return $data;
    }

    protected function afterCreate(): void
    {
        $user = $this->record;

        // Envoyer l'email avec le token API
        Mail::to($user->email)->send(new ApiTokenGenerated($user, $user->api_token));
    }
}
