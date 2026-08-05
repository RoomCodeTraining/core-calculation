<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;

class RejectTransactionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'rejection_reason' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'rejection_reason.required' => 'Le motif de rejet est requis',
        ];
    }
}
