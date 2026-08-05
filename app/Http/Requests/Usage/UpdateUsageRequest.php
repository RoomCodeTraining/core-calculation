<?php

namespace App\Http\Requests\Usage;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\VehicleGenre;

class UpdateUsageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "label" => "required|string|max:255",
            "description" => "nullable|string|max:255",
        ];
    }

    public function messages(): array
    {
        return [
            "label.required" => "Le label est requis.",
            "label.string" => "Le label doit être une chaîne de caractères.",
            "label.max" => "Le label doit contenir au plus 255 caractères.",
            "description.string" => "La description doit être une chaîne de caractères.",
            "description.max" => "La description doit contenir au plus 255 caractères.",
        ];
    }
}
