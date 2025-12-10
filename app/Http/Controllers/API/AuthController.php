<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * @group Authentification
 *
 * Gestion de l'authentification des utilisateurs via API.
 */
class AuthController extends Controller
{
    /**
     * Authentification
     *
     * Authentifie un utilisateur avec son email et mot de passe, et retourne un token Bearer pour les requêtes suivantes.
     *
     * @unauthenticated
     *
     * @bodyParam email string required L'adresse email de l'utilisateur. Example: user@example.com
     * @bodyParam password string required Le mot de passe de l'utilisateur. Example: password123
     *
     * @response 200 {
     *   "status": 200,
     *   "message": "Authentification réussie",
     *   "data": {
     *     "user": {
     *       "id": "abc123",
     *       "name": "John Doe",
     *       "email": "user@example.com",
     *       "role": "user",
     *       "organization": {
     *         "id": "org123",
     *         "name": "Mon Organisation",
     *         "slug": "mon-organisation",
     *         "api_quota": 1000
     *       }
     *     },
     *     "token": "1|abcdefghijklmnopqrstuvwxyz1234567890",
     *     "token_type": "Bearer"
     *   }
     * }
     * @response 401 {
     *   "errors": [
     *     {
     *       "status": 401,
     *       "title": "Authentification échouée",
     *       "detail": "Les identifiants fournis sont incorrects."
     *     }
     *   ]
     * }
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->responseUnAuthenticated(
                'Les identifiants fournis sont incorrects.',
                'Authentification échouée'
            );
        }

        // Créer un token Sanctum
        $token = $user->createToken('auth_token')->plainTextToken;

        // Mettre à jour le champ api_token dans la base de données
        $user->api_token = $token;
        $user->save();

        return $this->responseSuccess('Authentification réussie', [
            'user' => [
                'id' => $user->hashId,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'organization' => $user->organization ? [
                    'id' => $user->organization->hashId,
                    'name' => $user->organization->name,
                    'slug' => $user->organization->slug,
                    'api_quota' => $user->organization->api_quota,
                ] : null,
            ],
            'token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Déconnexion
     *
     * Déconnecte l'utilisateur authentifié et révoque le token d'accès actuel.
     *
     * @response 200 {
     *   "status": 200,
     *   "message": "Déconnexion réussie"
     * }
     */
    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user) {
            // Révoquer le token actuel
            $request->user()->currentAccessToken()->delete();

            // Optionnel : révoquer tous les tokens de l'utilisateur
            // $user->tokens()->delete();
        }

        return $this->responseSuccess('Déconnexion réussie');
    }

    /**
     * Informations utilisateur
     *
     * Retourne les informations de l'utilisateur actuellement authentifié, incluant son organisation et son quota.
     *
     * @response 200 {
     *   "status": 200,
     *   "message": "Informations utilisateur récupérées avec succès",
     *   "data": {
     *     "user": {
     *       "id": "abc123",
     *       "name": "John Doe",
     *       "email": "user@example.com",
     *       "role": "user",
     *       "organization": {
     *         "id": "org123",
     *         "name": "Mon Organisation",
     *         "slug": "mon-organisation",
     *         "api_quota": 1000
     *       }
     *     }
     *   }
     * }
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        return $this->responseSuccess('Informations utilisateur récupérées avec succès', [
            'user' => [
                'id' => $user->hashId,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'organization' => $user->organization ? [
                    'id' => $user->organization->hashId,
                    'name' => $user->organization->name,
                    'slug' => $user->organization->slug,
                    'api_quota' => $user->organization->api_quota,
                ] : null,
            ],
        ]);
    }
}
