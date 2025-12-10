# Brand - Marques

Gestion des marques de véhicules.

## GET /brands

Récupère la liste de toutes les marques avec leurs modèles de véhicules associés.

**Authentification requise**

### Requête

```http
GET /api/brands?per_page=20
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
    "message": "Brands retrieved successfully",
    "data": {
        "data": [
            {
                "id": "brand123",
                "name": "Toyota",
                "vehicle_models": [
                    {
                        "id": "model123",
                        "name": "Corolla"
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
curl -X GET "http://localhost:8000/api/brands?per_page=20" \
  -H "Authorization: Bearer {token}"
```

---

## GET /brands/{id}

Récupère les informations détaillées d'une marque spécifique avec tous ses modèles, genres et usages associés.

**Authentification requise**

### Requête

```http
GET /api/brands/{id}
Authorization: Bearer {token}
```

### Paramètres d'URL

| Paramètre | Type   | Requis | Description                   |
| --------- | ------ | ------ | ----------------------------- |
| id        | string | Oui    | Identifiant hash de la marque |

### Réponse 200 - Succès

```json
{
  "status": 200,
  "message": "Brand retrieved successfully",
  "data": {
    "id": "brand123",
    "name": "Toyota",
    "vehicle_models": [
      {
        "id": "model123",
        "name": "Corolla",
        "genres": [
          {
            "id": "genre123",
            "code": "VG01",
            "label": "Véhicule particulier",
            "usages": [...]
          }
        ]
      }
    ]
  }
}
```

### Réponse 404 - Non trouvé

```json
{
    "status": 404,
    "message": "Brand not found"
}
```

### Exemple cURL

```bash
curl -X GET http://localhost:8000/api/brands/brand123 \
  -H "Authorization: Bearer {token}"
```
