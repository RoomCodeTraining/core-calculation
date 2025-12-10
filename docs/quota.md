# Quota - Gestion du quota API

Gestion du quota API des organisations. Le quota détermine le nombre d'appels API disponibles.

## GET /quota

Récupère le quota API restant de l'organisation de l'utilisateur authentifié.

**Authentification requise**

### Requête

```http
GET /api/quota
Authorization: Bearer {token}
```

### Réponse 200 - Succès

```json
{
    "status": 200,
    "message": "Quota récupéré avec succès",
    "data": {
        "quota_remaining": 850,
        "organization": {
            "id": "org123",
            "name": "Mon Organisation",
            "slug": "mon-organisation"
        }
    }
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
curl -X GET http://localhost:8000/api/quota \
  -H "Authorization: Bearer {token}"
```

---

## POST /quota/recharge

Recharge le quota API de l'organisation. L'historique de la recharge est automatiquement enregistré.

**Authentification requise** - **Réservé aux administrateurs uniquement**

### Requête

```http
POST /api/quota/recharge
Authorization: Bearer {token}
Content-Type: application/json
```

### Paramètres

| Paramètre | Type    | Requis | Description                             |
| --------- | ------- | ------ | --------------------------------------- |
| amount    | integer | Oui    | Montant de quota à ajouter (minimum: 1) |
| notes     | string  | Non    | Notes optionnelles pour cette recharge  |

### Exemple de requête

```json
{
    "amount": 500,
    "notes": "Recharge mensuelle"
}
```

### Réponse 200 - Succès

```json
{
    "status": 200,
    "message": "Quota rechargé avec succès",
    "data": {
        "recharge": {
            "amount": 500,
            "quota_before": 350,
            "quota_after": 850,
            "notes": "Recharge mensuelle"
        },
        "organization": {
            "id": "org123",
            "name": "Mon Organisation",
            "api_quota": 850
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
            "detail": "Seuls les administrateurs peuvent recharger le quota."
        }
    ]
}
```

### Exemple cURL

```bash
curl -X POST http://localhost:8000/api/quota/recharge \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "amount": 500,
    "notes": "Recharge mensuelle"
  }'
```

---

## GET /quota/recharges

Récupère l'historique de tous les rechargements de quota de l'organisation.

**Authentification requise** - **Réservé aux administrateurs uniquement**

### Requête

```http
GET /api/quota/recharges?per_page=20
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
    "message": "Historique de rechargement récupéré avec succès",
    "data": {
        "recharges": [
            {
                "id": 1,
                "amount": 500,
                "quota_before": 350,
                "quota_after": 850,
                "notes": "Recharge mensuelle",
                "recharged_by": {
                    "name": "Admin User",
                    "email": "admin@example.com"
                },
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
            "detail": "Seuls les administrateurs peuvent voir l'historique des rechargements."
        }
    ]
}
```

### Exemple cURL

```bash
curl -X GET "http://localhost:8000/api/quota/recharges?per_page=20" \
  -H "Authorization: Bearer {token}"
```

---

## GET /quota/usages

Récupère l'historique de l'utilisation du quota API de l'organisation, incluant tous les appels API effectués.

**Authentification requise**

### Requête

```http
GET /api/quota/usages?per_page=20
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
    "message": "Historique d'utilisation récupéré avec succès",
    "data": {
        "usages": [
            {
                "id": 1,
                "endpoint": "/api/depreciation-tables",
                "method": "POST",
                "quota_used": 1,
                "quota_remaining_after": 849,
                "user": {
                    "name": "John Doe",
                    "email": "user@example.com"
                },
                "ip_address": "192.168.1.1",
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

### Exemple cURL

```bash
curl -X GET "http://localhost:8000/api/quota/usages?per_page=20" \
  -H "Authorization: Bearer {token}"
```
