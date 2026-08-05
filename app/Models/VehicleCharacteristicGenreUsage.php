<?php

namespace App\Models;

use App\Enums\StatusEnum;
use App\Filters\VehicleCharacteristicGenreUsageFilters;
use Deligoez\LaravelModelHashId\Traits\HasHashId;
use Deligoez\LaravelModelHashId\Traits\HasHashIdRouting;
use Essa\APIToolKit\Filters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleCharacteristicGenreUsage extends Model
{
    use Filterable;
    use HasFactory;
    use HasHashId;
    use HasHashIdRouting;

    protected string $default_filters = VehicleCharacteristicGenreUsageFilters::class;

    protected $guarded = [];

    public function vehicleCharacteristic(): BelongsTo
    {
        return $this->belongsTo(VehicleCharacteristic::class);
    }

    public function vehicleGenreUsage(): BelongsTo
    {
        return $this->belongsTo(VehicleGenreUsage::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public static function mapSyncData(array $ids): array
    {
        $statusId = Status::where('code', StatusEnum::ACTIVE)->first()->id;
        $userId = auth()->user()->id;

        $pivot = [
            'status_id' => $statusId,
            'created_by' => $userId,
            'updated_by' => $userId,
        ];

        return collect($ids)->mapWithKeys(fn ($id) => [$id => $pivot])->all();
    }
}
