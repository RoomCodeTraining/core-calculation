# VehicleModel - Modèles de véhicules

Gestion des modèles de véhicules.

## GET /vehicle-models

Récupère la liste de tous les modèles de véhicules avec leurs marques et genres associés.

**Authentification requise**

### Requête

```http
GET /api/vehicle-models?per_page=20
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
    "message": "Vehicle models retrieved successfully",
    "data": {
        "data": [
            {
                "id": "model123",
                "name": "Corolla",
                "brand": {
                    "id": "brand123",
                    "name": "Toyota"
                },
                "genres": [
                    {
                        "id": "genre123",
                        "code": "VG01",
                        "label": "Véhicule particulier"
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
curl -X GET "http://localhost:8000/api/vehicle-models?per_page=20" \
  -H "Authorization: Bearer {token}"
```

---

## GET /vehicle-models/{id}

Récupère les informations détaillées d'un modèle de véhicule spécifique avec sa marque et ses genres associés.

**Authentification requise**

### Requête

```http
GET /api/vehicle-models/{id}
Authorization: Bearer {token}
```

### Paramètres d'URL

| Paramètre | Type   | Requis | Description                            |
| --------- | ------ | ------ | -------------------------------------- |
| id        | string | Oui    | Identifiant hash du modèle de véhicule |

### Réponse 200 - Succès

```json
{
  "status": 200,
  "message": "Vehicle model retrieved successfully",
  "data": {
    "id": "model123",
    "name": "Corolla",
    "brand": {
      "id": "brand123",
      "name": "Toyota"
    },
    "genres": [
      {
        "id": "genre123",
        "code": "VG01",
        "label": "Véhicule particulier",
        "usages": [...]
      }
    ]
  }
}
```

### Réponse 404 - Non trouvé

```json
{
    "status": 404,
    "message": "Vehicle model not found"
}
```

### Exemple cURL

```bash
curl -X GET http://localhost:8000/api/vehicle-models/model123 \
  -H "Authorization: Bearer {token}"
```
