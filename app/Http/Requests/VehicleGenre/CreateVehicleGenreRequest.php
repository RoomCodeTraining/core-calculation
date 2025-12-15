<?php

namespace App\Http\Requests\VehicleGenre;

use App\Models\VehicleModel;
use Illuminate\Foundation\Http\FormRequest;

class CreateVehicleGenreRequest extends FormRequest
{
    public function prepareForValidation()
    {
        $this->merge([
            'vehicle_model_id' => $this->vehicle_model_id ? VehicleModel::keyFromHashId($this->vehicle_model_id) : null,
        ]);
    }
    
    public function rules(): array
    {
        return [
            'code' => 'required|string|max:255|unique:vehicle_genres,code',
            'label' => 'required|string|max:255',
            'description' => 'nullable|string',
            'max_mileage_essence_per_year' => 'required|numeric',
            'max_mileage_diesel_per_year' => 'required|numeric',
            'vehicle_model_id' => 'required|exists:vehicle_models,id',
        ];
    }

    public function messages(): array
    {
        return [
            'vehicle_model_id.required' => 'Le modèle de véhicule est requis.',
            'vehicle_model_id.exists' => 'Le modèle de véhicule n\'existe pas.',
            'code.required' => 'Le code est requis.',
            'code.string' => 'Le code doit être une chaîne de caractères.',
            'code.max' => 'Le code doit contenir au plus 255 caractères.',
            'code.unique' => 'Le code doit être unique.',
            'label.required' => 'Le label est requis.',
            'label.string' => 'Le label doit être une chaîne de caractères.',
            'label.max' => 'Le label doit contenir au plus 255 caractères.',
            'description.string' => 'La description doit être une chaîne de caractères.',
            'max_mileage_essence_per_year.required' => 'La distance maximale d\'essence par an est requise.',
            'max_mileage_essence_per_year.numeric' => 'La distance maximale d\'essence par an doit être un nombre.',
            'max_mileage_diesel_per_year.required' => 'La distance maximale de diesel par an est requise.',
            'max_mileage_diesel_per_year.numeric' => 'La distance maximale de diesel par an doit être un nombre.',
        ];
    }
}
