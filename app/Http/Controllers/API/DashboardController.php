<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Entity;
use App\Models\Status;
use App\Models\Vehicle;
use App\Enums\StatusEnum;
use App\Models\Assignment;
use App\Models\EntityType;
use App\Enums\EntityTypeEnum;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Essa\APIToolKit\Api\ApiResponse;

/**
 * @group Statistiques du tableau de bord
 */
class DashboardController extends Controller
{
    use ApiResponse;

    /**
     * Afficher les statistiques des utilisateurs
     */
    public function users() : JsonResponse
    {
        // $this->authorize('viewAny', User::class);

        return $this->responseSuccess(null, [
            'total_users' => ['value' => User::accessibleBy(auth()->user())->count()],
            'active_users' => ['value' => User::where('status_id', Status::where('code', StatusEnum::ACTIVE)->first()->id)->accessibleBy(auth()->user())->count()],
            'inactive_users' => ['value' => User::where('status_id', Status::where('code', StatusEnum::INACTIVE)->first()->id)->accessibleBy(auth()->user())->count()],
        ]);
    }

    /**
     * Afficher les statistiques des dossiers
     */
    public function calculations() : JsonResponse
    {
        // $this->authorize('viewAny', Assignment::class);

        return $this->responseSuccess(null, [
            'total_calculations' => ['value' => Calculation::accessibleBy(auth()->user())->count()],
        ]);
    }

    /**
     * Afficher les statistiques des caractéristiques des véhicules
     */
    public function vehicleCharacteristics() : JsonResponse
    {
        // $this->authorize('viewAny', User::class);

        return $this->responseSuccess(null, [
            'total_vehicle_characteristics' => ['value' => VehicleCharacteristic::accessibleBy(auth()->user())->count()],
        ]);
    }
}
