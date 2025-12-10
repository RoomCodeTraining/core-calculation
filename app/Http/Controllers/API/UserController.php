<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\ApiTokenGenerated;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;

/**
 * @group Utilisateurs
 *
 * Gestion des utilisateurs. **Toutes les opérations sont réservées aux administrateurs uniquement.**
 */
class UserController extends Controller
{
    /**
     * Créer un utilisateur
     *
     * Crée un nouvel utilisateur dans une organisation. Un token API est automatiquement généré et envoyé par email.
     * **Réservé aux administrateurs uniquement.**
     *
     * @bodyParam name string required Le nom complet de l'utilisateur. Example: John Doe
     * @bodyParam email string required L'adresse email unique de l'utilisateur. Example: user@example.com
     * @bodyParam password string required Le mot de passe de l'utilisateur (minimum 8 caractères). Example: password123
     * @bodyParam organization_id string required L'identifiant hash de l'organisation. Example: org123
     * @bodyParam role string Le rôle de l'utilisateur (admin ou user, défaut: user). Example: user
     *
     * @response 201 {
     *   "status": 201,
     *   "message": "Utilisateur créé avec succès",
     *   "data": {
     *     "user": {
     *       "id": "user123",
     *       "name": "John Doe",
     *       "email": "user@example.com",
     *       "role": "user",
     *       "organization": {
     *         "id": "org123",
     *         "name": "Mon Organisation",
     *         "slug": "mon-organisation"
     *       },
     *       "created_at": "2024-01-01T00:00:00.000000Z"
     *     },
     *     "token": "1|abcdefghijklmnopqrstuvwxyz1234567890",
     *     "token_type": "Bearer"
     *   }
     * }
     * @response 403 {
     *   "errors": [
     *     {
     *       "status": 403,
     *       "title": "Accès non autorisé",
     *       "detail": "Seuls les administrateurs peuvent créer des utilisateurs."
     *     }
     *   ]
     * }
     * @response 404 {
     *   "status": 404,
     *   "message": "Organisation non trouvée"
     * }
     */
    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        // Seuls les admins peuvent créer des utilisateurs
        if (!$user || !$user->isAdmin()) {
            return $this->responseUnAuthorized(
                'Seuls les administrateurs peuvent créer des utilisateurs.',
                'Accès non autorisé'
            );
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => ['required', 'string', Password::defaults()],
            'organization_id' => 'required|string',
            'role' => 'nullable|string|in:admin,user',
        ]);

        // Vérifier que l'organisation existe
        $organization = Organization::findByHashId($validated['organization_id']);
        if (!$organization) {
            return $this->responseNotFound('Organisation non trouvée');
        }

        // Rôle par défaut : user
        $validated['role'] = $validated['role'] ?? 'user';

        // Créer l'utilisateur
        $newUser = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'organization_id' => $organization->id,
            'role' => $validated['role'],
        ]);

        // Générer un token API
        $token = $newUser->createToken('auth_token')->plainTextToken;
        $newUser->api_token = $token;
        $newUser->save();

        // Envoyer l'email avec le token API
        Mail::to($newUser->email)->send(new ApiTokenGenerated($newUser, $token));

        return $this->responseCreated('Utilisateur créé avec succès', [
            'user' => [
                'id' => $newUser->hashId,
                'name' => $newUser->name,
                'email' => $newUser->email,
                'role' => $newUser->role,
                'organization' => [
                    'id' => $organization->hashId,
                    'name' => $organization->name,
                    'slug' => $organization->slug,
                ],
                'created_at' => $newUser->created_at->toISOString(),
            ],
            'token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Liste des utilisateurs
     *
     * Récupère la liste des utilisateurs de l'organisation de l'administrateur authentifié.
     * **Réservé aux administrateurs uniquement.**
     *
     * @queryParam per_page integer Nombre d'éléments par page (défaut: 15). Example: 20
     *
     * @response 200 {
     *   "status": 200,
     *   "message": "Utilisateurs récupérés avec succès",
     *   "data": {
     *     "users": [
     *       {
     *         "id": "user123",
     *         "name": "John Doe",
     *         "email": "user@example.com",
     *         "role": "user",
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
     *       "detail": "Seuls les administrateurs peuvent voir les utilisateurs."
     *     }
     *   ]
     * }
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        // Seuls les admins peuvent voir les utilisateurs
        if (!$user || !$user->isAdmin()) {
            return $this->responseUnAuthorized(
                'Seuls les administrateurs peuvent voir les utilisateurs.',
                'Accès non autorisé'
            );
        }

        if (!$user->organization) {
            return $this->responseNotFound('Organisation non trouvée');
        }

        $users = $user->organization->users()
            ->where('role', '!=', 'admin') // Ne pas inclure les autres admins
            ->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return $this->responseSuccess('Utilisateurs récupérés avec succès', [
            'users' => $users->map(function ($u) {
                return [
                    'id' => $u->hashId,
                    'name' => $u->name,
                    'email' => $u->email,
                    'role' => $u->role,
                    'created_at' => $u->created_at->toISOString(),
                ];
            }),
            'pagination' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
            ],
        ]);
    }

    /**
     * Détails d'un utilisateur
     *
     * Récupère les informations détaillées d'un utilisateur spécifique de la même organisation.
     * **Réservé aux administrateurs uniquement.**
     *
     * @urlParam id string required L'identifiant hash de l'utilisateur. Example: user123
     *
     * @response 200 {
     *   "status": 200,
     *   "message": "Utilisateur récupéré avec succès",
     *   "data": {
     *     "user": {
     *       "id": "user123",
     *       "name": "John Doe",
     *       "email": "user@example.com",
     *       "role": "user",
     *       "organization": {
     *         "id": "org123",
     *         "name": "Mon Organisation",
     *         "slug": "mon-organisation"
     *       },
     *       "created_at": "2024-01-01T00:00:00.000000Z",
     *       "updated_at": "2024-01-01T00:00:00.000000Z"
     *     }
     *   }
     * }
     * @response 403 {
     *   "errors": [
     *     {
     *       "status": 403,
     *       "title": "Accès non autorisé",
     *       "detail": "Seuls les administrateurs peuvent voir les détails d'un utilisateur."
     *     }
     *   ]
     * }
     * @response 404 {
     *   "status": 404,
     *   "message": "Utilisateur non trouvé"
     * }
     */
    public function show(Request $request, string $id): JsonResponse
    {
        $user = $request->user();

        // Seuls les admins peuvent voir les détails d'un utilisateur
        if (!$user || !$user->isAdmin()) {
            return $this->responseUnAuthorized(
                'Seuls les administrateurs peuvent voir les détails d\'un utilisateur.',
                'Accès non autorisé'
            );
        }

        $targetUser = User::findByHashId($id);

        if (!$targetUser) {
            return $this->responseNotFound('Utilisateur non trouvé');
        }

        // Vérifier que l'utilisateur appartient à la même organisation
        if ($targetUser->organization_id !== $user->organization_id) {
            return $this->responseUnAuthorized(
                'Vous ne pouvez voir que les utilisateurs de votre organisation.',
                'Accès non autorisé'
            );
        }

        return $this->responseSuccess('Utilisateur récupéré avec succès', [
            'user' => [
                'id' => $targetUser->hashId,
                'name' => $targetUser->name,
                'email' => $targetUser->email,
                'role' => $targetUser->role,
                'organization' => $targetUser->organization ? [
                    'id' => $targetUser->organization->hashId,
                    'name' => $targetUser->organization->name,
                    'slug' => $targetUser->organization->slug,
                ] : null,
                'created_at' => $targetUser->created_at->toISOString(),
                'updated_at' => $targetUser->updated_at->toISOString(),
            ],
        ]);
    }
}
