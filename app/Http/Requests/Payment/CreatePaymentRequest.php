<?php

namespace App\Http\Requests\Payment;

use App\Models\Transaction;
use App\Models\PaymentType;
use App\Models\PaymentMethod;
use Illuminate\Foundation\Http\FormRequest;

class CreatePaymentRequest extends FormRequest
{
    public function prepareForValidation()
    {
        $this->merge([
            'transaction_id' => $this->transaction_id ? Transaction::keyFromHashId($this->transaction_id) : null,
            'payment_type_id' => $this->payment_type_id ? PaymentType::keyFromHashId($this->payment_type_id) : null,
            'payment_method_id' => $this->payment_method_id ? PaymentMethod::keyFromHashId($this->payment_method_id) : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'transaction_id' => 'required|exists:transactions,id',
            'payment_type_id' => 'required|exists:payment_types,id',
            'payment_method_id' => 'nullable|exists:payment_methods,id',
            'date' => 'required|date_format:Y-m-d',
            'amount' => 'required|numeric|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'date.date' => 'La date est invalide.',
            'date.date_format' => 'Le format de la date est invalide.',
        ];
    }
}
