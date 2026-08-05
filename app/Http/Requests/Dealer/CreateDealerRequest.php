<?php

namespace App\Http\Requests\Dealer;

use Illuminate\Foundation\Http\FormRequest;

class CreateDealerRequest extends FormRequest
{
    public function prepareForValidation()
    {
        $this->merge([
            "email" => $this->email ? strtolower($this->email) : null,
        ]);
    }

    public function rules(): array
    {
        return [
            "name" => "required|string|max:255",
            "email" => "required|email|unique:dealers,email|regex:" . self::emailRegex() . "|max:255",
            "telephone" => "required|string|max:255",
            "address" => "required|string|max:255",
        ];
    }

    public function messages(): array
    {
        return [
            "name.required" => "Le nom est requis.",
            "name.string" => "Le nom doit être une chaîne de caractères.",
            "name.max" => "Le nom doit contenir au plus 255 caractères.",
            "email.required" => "L'email est requis.",
            "email.email" => "L'email doit être une adresse email valide.",
            "email.unique" => "L'email est déjà utilisé.",
            "email.regex" => "L'email doit être une adresse email valide.",
            "email.max" => "L'email doit contenir au plus 255 caractères.",
            "telephone.required" => "Le téléphone est requis.",
            "telephone.string" => "Le téléphone doit être une chaîne de caractères.",
            "telephone.max" => "Le téléphone doit contenir au plus 255 caractères.",
            "address.required" => "L'adresse est requise.",
            "address.string" => "L'adresse doit être une chaîne de caractères.",
            "address.max" => "L'adresse doit contenir au plus 255 caractères.",
        ];
    }

    public static function emailRegex(): string
    {
        return "/^[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}$/";
    }
}
