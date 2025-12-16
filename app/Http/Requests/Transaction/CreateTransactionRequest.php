<?php

namespace App\Http\Requests\Transaction;

use App\Models\Entity;
use App\Models\TransactionType;
use Illuminate\Foundation\Http\FormRequest;

class CreateTransactionRequest extends FormRequest
{
    public function prepareForValidation()
    {
        $this->merge([
            'entity_id' => $this->entity_id ? Entity::keyFromHashId($this->entity_id) : null,
            'transaction_type_id' => $this->transaction_type_id ? TransactionType::keyFromHashId($this->transaction_type_id) : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'quantity' => 'required|numeric|min:1',
            'description' => 'nullable|string',
            'entity_id' => 'required|exists:entities,id',
            'transaction_type_id' => 'required|exists:transaction_types,id',
        ];
    }

    public function messages(): array
    {
        return [
            'quantity.required' => 'La quantité est requise',
            'quantity.numeric' => 'La quantité doit être un nombre',
            'quantity.min' => 'La quantité doit être supérieure ou égale à 1',
            'description.string' => 'La description doit être une chaîne de caractères',
            'entity_id.required' => 'L\'entité est requise',
            'entity_id.exists' => 'L\'entité n\'existe pas',
            'transaction_type_id.required' => 'Le type de transaction est requise',
            'transaction_type_id.exists' => 'Le type de transaction n\'existe pas',
        ];
    }
}
