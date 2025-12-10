<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Quota
 *
 * Gestion du quota API des organisations. Le quota détermine le nombre d'appels API disponibles.
 */
class QuotaController extends Controller
{
    /**
     * Quota disponible
     *
     * Récupère le quota API restant de l'organisation de l'utilisateur authentifié.
     *
     * @response 200 {
     *   "status": 200,
     *   "message": "Quota récupéré avec succès",
     *   "data": {
     *     "quota_remaining": 850,
     *     "organization": {
     *       "id": "org123",
     *       "name": "Mon Organisation",
     *       "slug": "mon-organisation"
     *     }
     *   }
     * }
     * @response 404 {
     *   "status": 404,
     *   "message": "Organisation non trouvée"
     * }
     */
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user || !$user->organization) {
            return $this->responseNotFound('Organisation non trouvée');
        }

        return $this->responseSuccess('Quota récupéré avec succès', [
            'quota_remaining' => $user->organization->api_quota,
            'organization' => [
                'id' => $user->organization->hashId,
                'name' => $user->organization->name,
                'slug' => $user->organization->slug,
            ],
        ]);
    }

    /**
     * Recharger le quota
     *
     * Recharge le quota API de l'organisation. **Réservé aux administrateurs uniquement.**
     * L'historique de la recharge est automatiquement enregistré.
     *
     * @bodyParam amount integer required Le montant de quota à ajouter (minimum: 1). Example: 500
     * @bodyParam notes string Notes optionnelles pour cette recharge. Example: Recharge mensuelle
     *
     * @response 200 {
     *   "status": 200,
     *   "message": "Quota rechargé avec succès",
     *   "data": {
     *     "recharge": {
     *       "amount": 500,
     *       "quota_before": 350,
     *       "quota_after": 850,
     *       "notes": "Recharge mensuelle"
     *     },
     *     "organization": {
     *       "id": "org123",
     *       "name": "Mon Organisation",
     *       "api_quota": 850
     *     }
     *   }
     * }
     * @response 403 {
     *   "errors": [
     *     {
     *       "status": 403,
     *       "title": "Accès non autorisé",
     *       "detail": "Seuls les administrateurs peuvent recharger le quota."
     *     }
     *   ]
     * }
     */
    public function recharge(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user || !$user->organization) {
            return $this->responseNotFound('Organisation non trouvée');
        }

        // Seuls les admins peuvent recharger le quota
        if (!$user->isAdmin()) {
            return $this->responseUnAuthorized(
                'Seuls les administrateurs peuvent recharger le quota.',
                'Accès non autorisé'
            );
        }

        $validated = $request->validate([
            'amount' => 'required|integer|min:1',
            'notes' => 'nullable|string|max:500',
        ]);

        $organization = $user->organization;
        $quotaBefore = $organization->api_quota;

        // Recharger le quota
        $organization->rechargeQuota(
            $validated['amount'],
            $user->id,
            $validated['notes'] ?? null
        );

        // Rafraîchir l'organisation pour obtenir le nouveau quota
        $organization->refresh();

        return $this->responseSuccess('Quota rechargé avec succès', [
            'recharge' => [
                'amount' => $validated['amount'],
                'quota_before' => $quotaBefore,
                'quota_after' => $organization->api_quota,
                'notes' => $validated['notes'] ?? null,
            ],
            'organization' => [
                'id' => $organization->hashId,
                'name' => $organization->name,
                'api_quota' => $organization->api_quota,
            ],
        ]);
    }

    /**
     * Historique des rechargements
     *
     * Récupère l'historique de tous les rechargements de quota de l'organisation. **Réservé aux administrateurs uniquement.**
     *
     * @queryParam per_page integer Nombre d'éléments par page (défaut: 15). Example: 20
     *
     * @response 200 {
     *   "status": 200,
     *   "message": "Historique de rechargement récupéré avec succès",
     *   "data": {
     *     "recharges": [
     *       {
     *         "id": 1,
     *         "amount": 500,
     *         "quota_before": 350,
     *         "quota_after": 850,
     *         "notes": "Recharge mensuelle",
     *         "recharged_by": {
     *           "name": "Admin User",
     *           "email": "admin@example.com"
     *         },
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
     *       "detail": "Seuls les administrateurs peuvent voir l'historique des rechargements."
     *     }
     *   ]
     * }
     */
    public function recharges(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user || !$user->organization) {
            return $this->responseNotFound('Organisation non trouvée');
        }

        // Seuls les admins peuvent voir l'historique des rechargements
        if (!$user->isAdmin()) {
            return $this->responseUnAuthorized(
                'Seuls les administrateurs peuvent voir l\'historique des rechargements.',
                'Accès non autorisé'
            );
        }

        $recharges = $user->organization->quotaRecharges()
            ->with('user:id,name,email')
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return $this->responseSuccess('Historique de rechargement récupéré avec succès', [
            'recharges' => $recharges->map(function ($recharge) {
                return [
                    'id' => $recharge->id,
                    'amount' => $recharge->amount,
                    'quota_before' => $recharge->quota_before,
                    'quota_after' => $recharge->quota_after,
                    'notes' => $recharge->notes,
                    'recharged_by' => $recharge->user ? [
                        'name' => $recharge->user->name,
                        'email' => $recharge->user->email,
                    ] : null,
                    'created_at' => $recharge->created_at->toISOString(),
                ];
            }),
            'pagination' => [
                'current_page' => $recharges->currentPage(),
                'last_page' => $recharges->lastPage(),
                'per_page' => $recharges->perPage(),
                'total' => $recharges->total(),
            ],
        ]);
    }

    /**
     * Historique d'utilisation
     *
     * Récupère l'historique de l'utilisation du quota API de l'organisation, incluant tous les appels API effectués.
     *
     * @queryParam per_page integer Nombre d'éléments par page (défaut: 15). Example: 20
     *
     * @response 200 {
     *   "status": 200,
     *   "message": "Historique d'utilisation récupéré avec succès",
     *   "data": {
     *     "usages": [
     *       {
     *         "id": 1,
     *         "endpoint": "/api/depreciation-tables",
     *         "method": "POST",
     *         "quota_used": 1,
     *         "quota_remaining_after": 849,
     *         "user": {
     *           "name": "John Doe",
     *           "email": "user@example.com"
     *         },
     *         "ip_address": "192.168.1.1",
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
     */
    public function usages(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user || !$user->organization) {
            return $this->responseNotFound('Organisation non trouvée');
        }

        $usages = $user->organization->quotaUsages()
            ->with('user:id,name,email')
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return $this->responseSuccess('Historique d\'utilisation récupéré avec succès', [
            'usages' => $usages->map(function ($usage) {
                return [
                    'id' => $usage->id,
                    'endpoint' => $usage->endpoint,
                    'method' => $usage->method,
                    'quota_used' => $usage->quota_used,
                    'quota_remaining_after' => $usage->quota_remaining_after,
                    'user' => $usage->user ? [
                        'name' => $usage->user->name,
                        'email' => $usage->user->email,
                    ] : null,
                    'ip_address' => $usage->ip_address,
                    'created_at' => $usage->created_at->toISOString(),
                ];
            }),
            'pagination' => [
                'current_page' => $usages->currentPage(),
                'last_page' => $usages->lastPage(),
                'per_page' => $usages->perPage(),
                'total' => $usages->total(),
            ],
        ]);
    }
}
