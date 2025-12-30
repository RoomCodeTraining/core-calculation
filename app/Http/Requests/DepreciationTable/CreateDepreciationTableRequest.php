<?php

namespace App\Http\Requests\DepreciationTable;

use App\Models\VehicleGenreUsage;
use App\Models\VehicleAge;
use Illuminate\Foundation\Http\FormRequest;

class CreateDepreciationTableRequest extends FormRequest
{
    public function prepareForValidation()
    {
        $this->merge([
            'vehicle_genre_usage_id' => $this->vehicle_genre_usage_id ? VehicleGenreUsage::keyFromHashId($this->vehicle_genre_usage_id) : null,
            'vehicle_age_id' => $this->vehicle_age_id ? VehicleAge::keyFromHashId($this->vehicle_age_id) : null,
        ]);
    }
    public function rules(): array
    {
        return [
            'vehicle_genre_usage_id' => 'required|exists:vehicle_genre_usages,id',
            'vehicle_age_id' => 'required|exists:vehicle_ages,id',
            'value' => 'required|integer|min:0',
        ];
    }
}
