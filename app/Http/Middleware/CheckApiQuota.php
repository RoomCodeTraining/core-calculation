<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckApiQuota
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // L'utilisateur est déjà authentifié par auth:sanctum
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non authentifié',
            ], 401);
        }

        // Vérifier que l'utilisateur a une organisation
        if (!$user->organization) {
            return response()->json([
                'success' => false,
                'message' => 'Aucune organisation associée à cet utilisateur',
            ], 403);
        }

        // Vérifier le quota disponible
        if (!$user->organization->hasQuota()) {
            return response()->json([
                'success' => false,
                'message' => 'Quota API épuisé',
                'quota_remaining' => $user->organization->api_quota,
            ], 429);
        }

        // Décrémenter le quota
        $quotaBefore = $user->organization->api_quota;
        $user->organization->decrementQuota();
        $quotaAfter = $user->organization->fresh()->api_quota;

        // Enregistrer l'utilisation dans l'historique
        \App\Models\QuotaUsage::create([
            'organization_id' => $user->organization->id,
            'user_id' => $user->id,
            'endpoint' => $request->path(),
            'method' => $request->method(),
            'quota_used' => 1,
            'quota_remaining_after' => $quotaAfter,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Ajouter l'organisation à la requête pour utilisation dans les contrôleurs
        $request->merge(['organization' => $user->organization]);

        $response = $next($request);

        // Ajouter les informations de quota dans les en-têtes de réponse
        $response->headers->set('X-Quota-Remaining', $quotaAfter);

        return $response;
    }
}
