# Organization - Organisations

Les organisations permettent de regrouper des utilisateurs et de gérer leur quota API.

## POST /organizations

Crée une nouvelle organisation. Le slug sera généré automatiquement à partir du nom si non fourni.

**Authentification requise**

### Requête

```http
POST /api/organizations
Authorization: Bearer {token}
Content-Type: application/json
```

### Paramètres

| Paramètre | Type    | Requis | Description                                                          |
| --------- | ------- | ------ | -------------------------------------------------------------------- |
| name      | string  | Oui    | Nom de l'organisation (unique)                                       |
| slug      | string  | Non    | Slug unique de l'organisation (généré automatiquement si non fourni) |
| api_quota | integer | Non    | Quota API initial (défaut: 0)                                        |

### Exemple de requête

```json
{
    "name": "Mon Organisation",
    "slug": "mon-organisation",
    "api_quota": 1000
}
```

### Réponse 201 - Créé avec succès

```json
{
    "status": 201,
    "message": "Organisation créée avec succès",
    "data": {
        "organization": {
            "id": "org123",
            "name": "Mon Organisation",
            "slug": "mon-organisation",
            "api_quota": 1000,
            "created_at": "2024-01-01T00:00:00.000000Z"
        }
    }
}
```

### Exemple cURL

```bash
curl -X POST http://localhost:8000/api/organizations \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Mon Organisation",
    "api_quota": 1000
  }'
```

---

## GET /organizations

Récupère la liste de toutes les organisations.

**Authentification requise** - **Réservé aux administrateurs uniquement**

### Requête

```http
GET /api/organizations?per_page=20
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
    "message": "Organisations récupérées avec succès",
    "data": {
        "organizations": [
            {
                "id": "org123",
                "name": "Mon Organisation",
                "slug": "mon-organisation",
                "api_quota": 1000,
                "users_count": 5,
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
            "detail": "Seuls les administrateurs peuvent accéder à cette ressource."
        }
    ]
}
```

### Exemple cURL

```bash
curl -X GET "http://localhost:8000/api/organizations?per_page=20" \
  -H "Authorization: Bearer {token}"
```

---

## GET /organizations/{id}

Récupère les informations détaillées d'une organisation spécifique.

**Authentification requise**

### Requête

```http
GET /api/organizations/{id}
Authorization: Bearer {token}
```

### Paramètres d'URL

| Paramètre | Type   | Requis | Description                        |
| --------- | ------ | ------ | ---------------------------------- |
| id        | string | Oui    | Identifiant hash de l'organisation |

### Réponse 200 - Succès

```json
{
    "status": 200,
    "message": "Organisation récupérée avec succès",
    "data": {
        "organization": {
            "id": "org123",
            "name": "Mon Organisation",
            "slug": "mon-organisation",
            "api_quota": 1000,
            "users_count": 5,
            "created_at": "2024-01-01T00:00:00.000000Z",
            "updated_at": "2024-01-01T00:00:00.000000Z"
        }
    }
}
```

### Réponse 404 - Non trouvé

```json
{
    "status": 404,
    "message": "Organisation non trouvée"
}
```

### Exemple cURL

```bash
curl -X GET http://localhost:8000/api/organizations/org123 \
  -H "Authorization: Bearer {token}"
```
