# Genre - Genres

Gestion des genres de véhicules.

## GET /genres

Récupère la liste de tous les genres avec leurs modèles de véhicules, marques et usages associés.

**Authentification requise**

### Requête

```http
GET /api/genres?per_page=20
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
    "message": "Genres retrieved successfully",
    "data": {
        "data": [
            {
                "id": "genre123",
                "code": "VG01",
                "label": "Véhicule particulier",
                "vehicle_model": {
                    "id": "model123",
                    "name": "Corolla",
                    "brand": {
                        "id": "brand123",
                        "name": "Toyota"
                    }
                },
                "usages": [
                    {
                        "id": "usage123",
                        "code": "U01",
                        "label": "Usage professionnel"
                    }
                ]
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
curl -X GET "http://localhost:8000/api/genres?per_page=20" \
  -H "Authorization: Bearer {token}"
```

---

## GET /genres/{id}

Récupère les informations détaillées d'un genre spécifique avec son modèle de véhicule, sa marque et ses usages associés.

**Authentification requise**

### Requête

```http
GET /api/genres/{id}
Authorization: Bearer {token}
```

### Paramètres d'URL

| Paramètre | Type   | Requis | Description               |
| --------- | ------ | ------ | ------------------------- |
| id        | string | Oui    | Identifiant hash du genre |

### Réponse 200 - Succès

```json
{
    "status": 200,
    "message": "Genre retrieved successfully",
    "data": {
        "id": "genre123",
        "code": "VG01",
        "label": "Véhicule particulier",
        "vehicle_model": {
            "id": "model123",
            "name": "Corolla",
            "brand": {
                "id": "brand123",
                "name": "Toyota"
            }
        },
        "usages": [
            {
                "id": "usage123",
                "code": "U01",
                "label": "Usage professionnel"
            }
        ]
    }
}
```

### Réponse 404 - Non trouvé

```json
{
    "status": 404,
    "message": "Genre not found"
}
```

### Exemple cURL

```bash
curl -X GET http://localhost:8000/api/genres/genre123 \
  -H "Authorization: Bearer {token}"
```
