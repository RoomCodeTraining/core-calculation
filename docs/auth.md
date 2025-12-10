# Auth - Authentification

## POST /auth/login

Authentifie un utilisateur et retourne un token Bearer pour les requêtes suivantes.

**Endpoint public** - Aucune authentification requise

### Requête

```http
POST /api/auth/login
Content-Type: application/json
```

### Paramètres

| Paramètre | Type   | Requis | Description                    |
| --------- | ------ | ------ | ------------------------------ |
| email     | string | Oui    | Adresse email de l'utilisateur |
| password  | string | Oui    | Mot de passe de l'utilisateur  |

### Exemple de requête

```json
{
    "email": "user@example.com",
    "password": "password123"
}
```

### Réponse 200 - Succès

```json
{
    "status": 200,
    "message": "Authentification réussie",
    "data": {
        "user": {
            "id": "abc123",
            "name": "John Doe",
            "email": "user@example.com",
            "role": "user",
            "organization": {
                "id": "org123",
                "name": "Mon Organisation",
                "slug": "mon-organisation",
                "api_quota": 1000
            }
        },
        "token": "1|abcdefghijklmnopqrstuvwxyz1234567890",
        "token_type": "Bearer"
    }
}
```

### Réponse 401 - Échec d'authentification

```json
{
    "errors": [
        {
            "status": 401,
            "title": "Authentification échouée",
            "detail": "Les identifiants fournis sont incorrects."
        }
    ]
}
```

### Exemple cURL

```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "user@example.com",
    "password": "password123"
  }'
```

---

## POST /auth/logout

Déconnecte l'utilisateur authentifié et révoque le token d'accès actuel.

**Authentification requise**

### Requête

```http
POST /api/auth/logout
Authorization: Bearer {token}
```

### Réponse 200 - Succès

```json
{
    "status": 200,
    "message": "Déconnexion réussie"
}
```

### Exemple cURL

```bash
curl -X POST http://localhost:8000/api/auth/logout \
  -H "Authorization: Bearer {token}"
```

---

## GET /auth/me

Retourne les informations de l'utilisateur actuellement authentifié, incluant son organisation et son quota.

**Authentification requise**

### Requête

```http
GET /api/auth/me
Authorization: Bearer {token}
```

### Réponse 200 - Succès

```json
{
    "status": 200,
    "message": "Informations utilisateur récupérées avec succès",
    "data": {
        "user": {
            "id": "abc123",
            "name": "John Doe",
            "email": "user@example.com",
            "role": "user",
            "organization": {
                "id": "org123",
                "name": "Mon Organisation",
                "slug": "mon-organisation",
                "api_quota": 1000
            }
        }
    }
}
```

### Exemple cURL

```bash
curl -X GET http://localhost:8000/api/auth/me \
  -H "Authorization: Bearer {token}"
```
