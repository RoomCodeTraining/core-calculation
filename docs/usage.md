# Usage - Usages

Gestion des usages de véhicules.

## GET /usages

Récupère la liste de tous les usages avec leurs genres, modèles de véhicules et marques associés.

**Authentification requise**

### Requête

```http
GET /api/usages?per_page=20
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
    "message": "Usages retrieved successfully",
    "data": {
        "data": [
            {
                "id": "usage123",
                "code": "U01",
                "label": "Usage professionnel",
                "genre": {
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
                    }
                }
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
curl -X GET "http://localhost:8000/api/usages?per_page=20" \
  -H "Authorization: Bearer {token}"
```

---

## GET /usages/{id}

Récupère les informations détaillées d'un usage spécifique avec son genre, modèle de véhicule et marque associés.

**Authentification requise**

### Requête

```http
GET /api/usages/{id}
Authorization: Bearer {token}
```

### Paramètres d'URL

| Paramètre | Type   | Requis | Description                 |
| --------- | ------ | ------ | --------------------------- |
| id        | string | Oui    | Identifiant hash de l'usage |

### Réponse 200 - Succès

```json
{
    "status": 200,
    "message": "Usage retrieved successfully",
    "data": {
        "id": "usage123",
        "code": "U01",
        "label": "Usage professionnel",
        "genre": {
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
            }
        }
    }
}
```

### Réponse 404 - Non trouvé

```json
{
    "status": 404,
    "message": "Usage not found"
}
```

### Exemple cURL

```bash
curl -X GET http://localhost:8000/api/usages/usage123 \
  -H "Authorization: Bearer {token}"
```
