<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;

class CancelTransactionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'cancellation_reason' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'cancellation_reason.required' => 'Le motif d\'annulation est requis',
        ];
    }
}
