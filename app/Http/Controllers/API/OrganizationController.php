<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * @group Organisations
 *
 * Gestion des organisations. Les organisations permettent de regrouper des utilisateurs et de gérer leur quota API.
 */
class OrganizationController extends Controller
{
    /**
     * Créer une organisation
     *
     * Crée une nouvelle organisation. Le slug sera généré automatiquement à partir du nom si non fourni.
     *
     * @bodyParam name string required Le nom de l'organisation. Example: Mon Organisation
     * @bodyParam slug string Le slug unique de l'organisation (généré automatiquement si non fourni). Example: mon-organisation
     * @bodyParam api_quota integer Le quota API initial (défaut: 0). Example: 1000
     *
     * @response 201 {
     *   "status": 201,
     *   "message": "Organisation créée avec succès",
     *   "data": {
     *     "organization": {
     *       "id": "org123",
     *       "name": "Mon Organisation",
     *       "slug": "mon-organisation",
     *       "api_quota": 1000,
     *       "created_at": "2024-01-01T00:00:00.000000Z"
     *     }
     *   }
     * }
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:organizations,name',
            'slug' => 'nullable|string|max:255|unique:organizations,slug',
            'api_quota' => 'nullable|integer|min:0',
        ]);

        // Générer le slug automatiquement si non fourni
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);

            // Vérifier l'unicité du slug généré
            $baseSlug = $validated['slug'];
            $counter = 1;
            while (Organization::where('slug', $validated['slug'])->exists()) {
                $validated['slug'] = $baseSlug . '-' . $counter;
                $counter++;
            }
        }

        // Valeur par défaut pour le quota
        $validated['api_quota'] = $validated['api_quota'] ?? 0;

        $organization = Organization::create($validated);

        return $this->responseCreated('Organisation créée avec succès', [
            'organization' => [
                'id' => $organization->hashId,
                'name' => $organization->name,
                'slug' => $organization->slug,
                'api_quota' => $organization->api_quota,
                'created_at' => $organization->created_at->toISOString(),
            ],
        ]);
    }

    /**
     * Détails d'une organisation
     *
     * Récupère les informations détaillées d'une organisation spécifique.
     *
     * @urlParam id string required L'identifiant hash de l'organisation. Example: org123
     *
     * @response 200 {
     *   "status": 200,
     *   "message": "Organisation récupérée avec succès",
     *   "data": {
     *     "organization": {
     *       "id": "org123",
     *       "name": "Mon Organisation",
     *       "slug": "mon-organisation",
     *       "api_quota": 1000,
     *       "users_count": 5,
     *       "created_at": "2024-01-01T00:00:00.000000Z",
     *       "updated_at": "2024-01-01T00:00:00.000000Z"
     *     }
     *   }
     * }
     * @response 404 {
     *   "status": 404,
     *   "message": "Organisation non trouvée"
     * }
     */
    public function show(Request $request, string $id): JsonResponse
    {
        $organization = Organization::findByHashId($id);

        if (!$organization) {
            return $this->responseNotFound('Organisation non trouvée');
        }

        return $this->responseSuccess('Organisation récupérée avec succès', [
            'organization' => [
                'id' => $organization->hashId,
                'name' => $organization->name,
                'slug' => $organization->slug,
                'api_quota' => $organization->api_quota,
                'users_count' => $organization->users()->count(),
                'created_at' => $organization->created_at->toISOString(),
                'updated_at' => $organization->updated_at->toISOString(),
            ],
        ]);
    }

    /**
     * Liste des organisations
     *
     * Récupère la liste de toutes les organisations. **Réservé aux administrateurs uniquement.**
     *
     * @queryParam per_page integer Nombre d'éléments par page (défaut: 15). Example: 20
     *
     * @response 200 {
     *   "status": 200,
     *   "message": "Organisations récupérées avec succès",
     *   "data": {
     *     "organizations": [
     *       {
     *         "id": "org123",
     *         "name": "Mon Organisation",
     *         "slug": "mon-organisation",
     *         "api_quota": 1000,
     *         "users_count": 5,
     *         "created_at": "2024-01-01T00:00:00.000000Z"
     *       }
     *     ],
     *     "pagination": {
     *       "current_page": 1,
     *       "last_page": 1,
     *       "per_page": 15,
     *       "total": 1
     *     }
     *   }
     * }
     * @response 403 {
     *   "errors": [
     *     {
     *       "status": 403,
     *       "title": "Accès non autorisé",
     *       "detail": "Seuls les administrateurs peuvent accéder à cette ressource."
     *     }
     *   ]
     * }
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        // Seuls les admins peuvent voir toutes les organisations
        if (!$user || !$user->isAdmin()) {
            return $this->responseUnAuthorized(
                'Seuls les administrateurs peuvent accéder à cette ressource.',
                'Accès non autorisé'
            );
        }

        $organizations = Organization::withCount('users')
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return $this->responseSuccess('Organisations récupérées avec succès', [
            'organizations' => $organizations->map(function ($organization) {
                return [
                    'id' => $organization->hashId,
                    'name' => $organization->name,
                    'slug' => $organization->slug,
                    'api_quota' => $organization->api_quota,
                    'users_count' => $organization->users_count,
                    'created_at' => $organization->created_at->toISOString(),
                ];
            }),
            'pagination' => [
                'current_page' => $organizations->currentPage(),
                'last_page' => $organizations->lastPage(),
                'per_page' => $organizations->perPage(),
                'total' => $organizations->total(),
            ],
        ]);
    }
}
