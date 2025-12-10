# User - Utilisateurs

Gestion des utilisateurs. **Toutes les opérations sont réservées aux administrateurs uniquement.**

## POST /users

Crée un nouvel utilisateur dans une organisation. Un token API est automatiquement généré et envoyé par email.

**Authentification requise** - **Réservé aux administrateurs uniquement**

### Requête

```http
POST /api/users
Authorization: Bearer {token}
Content-Type: application/json
```

### Paramètres

| Paramètre       | Type   | Requis | Description                                          |
| --------------- | ------ | ------ | ---------------------------------------------------- |
| name            | string | Oui    | Nom complet de l'utilisateur                         |
| email           | string | Oui    | Adresse email unique de l'utilisateur                |
| password        | string | Oui    | Mot de passe de l'utilisateur (minimum 8 caractères) |
| organization_id | string | Oui    | Identifiant hash de l'organisation                   |
| role            | string | Non    | Rôle de l'utilisateur (admin ou user, défaut: user)  |

### Exemple de requête

```json
{
    "name": "John Doe",
    "email": "user@example.com",
    "password": "password123",
    "organization_id": "org123",
    "role": "user"
}
```

### Réponse 201 - Créé avec succès

```json
{
    "status": 201,
    "message": "Utilisateur créé avec succès",
    "data": {
        "user": {
            "id": "user123",
            "name": "John Doe",
            "email": "user@example.com",
            "role": "user",
            "organization": {
                "id": "org123",
                "name": "Mon Organisation",
                "slug": "mon-organisation"
            },
            "created_at": "2024-01-01T00:00:00.000000Z"
        },
        "token": "1|abcdefghijklmnopqrstuvwxyz1234567890",
        "token_type": "Bearer"
    }
}
```

### Réponse 403 - Accès non autorisé

```json
{
    "errors": [
        {
            "status": 403,
            "title": "Accès non autorisé",
            "detail": "Seuls les administrateurs peuvent créer des utilisateurs."
        }
    ]
}
```

### Réponse 404 - Organisation non trouvée

```json
{
    "status": 404,
    "message": "Organisation non trouvée"
}
```

### Exemple cURL

```bash
curl -X POST http://localhost:8000/api/users \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "user@example.com",
    "password": "password123",
    "organization_id": "org123",
    "role": "user"
  }'
```

---

## GET /users

Récupère la liste des utilisateurs de l'organisation de l'administrateur authentifié.

**Authentification requise** - **Réservé aux administrateurs uniquement**

### Requête

```http
GET /api/users?per_page=20
Authorization: Bearer {token}
```

### Paramètres de requête

| Paramètre | Type    | Requis | Description                             |
| --------- | ------- | ------ | --------------------------------------- |
| per_page  | integer | Non    | Nombre d'éléments par page (défaut: 15) |

### Réponse 200 - Succès

```json
{
    "status": 200,
    "message": "Utilisateurs récupérés avec succès",
    "data": {
        "users": [
            {
                "id": "user123",
                "name": "John Doe",
                "email": "user@example.com",
                "role": "user",
                "created_at": "2024-01-01T00:00:00.000000Z"
            }
        ],
        "pagination": {
            "current_page": 1,
            "last_page": 1,
            "per_page": 15,
            "total": 1
        }
    }
}
```

### Réponse 403 - Accès non autorisé

```json
{
    "errors": [
        {
            "status": 403,
            "title": "Accès non autorisé",
            "detail": "Seuls les administrateurs peuvent voir les utilisateurs."
        }
    ]
}
```

### Exemple cURL

```bash
curl -X GET "http://localhost:8000/api/users?per_page=20" \
  -H "Authorization: Bearer {token}"
```

---

## GET /users/{id}

Récupère les informations détaillées d'un utilisateur spécifique de la même organisation.

**Authentification requise** - **Réservé aux administrateurs uniquement**

### Requête

```http
GET /api/users/{id}
Authorization: Bearer {token}
```

### Paramètres d'URL

| Paramètre | Type   | Requis | Description                       |
| --------- | ------ | ------ | --------------------------------- |
| id        | string | Oui    | Identifiant hash de l'utilisateur |

### Réponse 200 - Succès

```json
{
    "status": 200,
    "message": "Utilisateur récupéré avec succès",
    "data": {
        "user": {
            "id": "user123",
            "name": "John Doe",
            "email": "user@example.com",
            "role": "user",
            "organization": {
                "id": "org123",
                "name": "Mon Organisation",
                "slug": "mon-organisation"
            },
            "created_at": "2024-01-01T00:00:00.000000Z",
            "updated_at": "2024-01-01T00:00:00.000000Z"
        }
    }
}
```

### Réponse 403 - Accès non autorisé

```json
{
    "errors": [
        {
            "status": 403,
            "title": "Accès non autorisé",
            "detail": "Seuls les administrateurs peuvent voir les détails d'un utilisateur."
        }
    ]
}
```

### Réponse 404 - Utilisateur non trouvé

```json
{
    "status": 404,
    "message": "Utilisateur non trouvé"
}
```

### Exemple cURL

```bash
curl -X GET http://localhost:8000/api/users/user123 \
  -H "Authorization: Bearer {token}"
```
