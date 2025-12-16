<?php

namespace App\Http\Requests\TransactionType;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransactionTypeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'label' => 'required|string|max:255',
            'description' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'label.required' => 'Le label est requis',
            'label.string' => 'Le label doit être une chaîne de caractères',
            'label.max' => 'Le label doit contenir au maximum 255 caractères',
            'description.string' => 'La description doit être une chaîne de caractères',
        ];
    }
}
