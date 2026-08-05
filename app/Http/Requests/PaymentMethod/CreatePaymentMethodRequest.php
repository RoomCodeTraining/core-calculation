<?php

namespace App\Http\Requests\PaymentMethod;

use Illuminate\Foundation\Http\FormRequest;

class CreatePaymentMethodRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'label' => 'required|string|max:255|unique:payment_methods,label',
            'description' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg',
        ];
    }

    public function messages(): array
    {
        return [
            'label.required' => 'Le libellé est requis.',
            'label.string' => 'Le libellé doit être une chaîne de caractères.',
            'label.max' => 'Le libellé doit contenir au maximum 255 caractères.',
            'label.unique' => 'Ce libellé existe déjà.',
            'description.string' => 'La description doit être une chaîne de caractères.',
            'description.max' => 'La description doit contenir au maximum 255 caractères.',
            'logo.image' => 'Le logo doit être une image.',
            'logo.mimes' => 'Le logo doit être une image de type jpeg, png, jpg, gif ou svg.',
        ];
    }
}
