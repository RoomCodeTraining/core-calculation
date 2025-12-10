<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class QuotaController extends Controller
{
    /**
     * Affiche le quota disponible de l'organisation de l'utilisateur.
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
     * Affiche l'historique de rechargement du quota.
     */
    public function recharges(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user || !$user->organization) {
            return $this->responseNotFound('Organisation non trouvée');
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
     * Affiche l'historique d'utilisation du quota.
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
