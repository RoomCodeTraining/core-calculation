# DepreciationTable - Tables de dépréciation

Gestion des tables de dépréciation et calcul de la valeur théorique du marché.

## GET /depreciation-tables

Récupère la liste de toutes les tables de dépréciation avec leurs genres et âges de véhicules associés.

**Authentification requise**

### Requête

```http
GET /api/depreciation-tables?per_page=20
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
    "message": "Depreciation tables retrieved successfully",
    "data": {
        "data": [
            {
                "id": "table123",
                "genre": {
                    "id": "genre123",
                    "code": "VG01",
                    "label": "Véhicule particulier"
                },
                "vehicle_age": {
                    "id": "age123",
                    "min_age": 0,
                    "max_age": 12
                },
                "depreciation_rate": 15.5
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
curl -X GET "http://localhost:8000/api/depreciation-tables?per_page=20" \
  -H "Authorization: Bearer {token}"
```

---

## GET /depreciation-tables/{id}

Récupère les informations détaillées d'une table de dépréciation spécifique.

**Authentification requise**

### Requête

```http
GET /api/depreciation-tables/{id}
Authorization: Bearer {token}
```

### Paramètres d'URL

| Paramètre | Type   | Requis | Description                                  |
| --------- | ------ | ------ | -------------------------------------------- |
| id        | string | Oui    | Identifiant hash de la table de dépréciation |

### Réponse 200 - Succès

```json
{
    "status": 200,
    "message": "Depreciation table retrieved successfully",
    "data": {
        "id": "table123",
        "genre": {
            "id": "genre123",
            "code": "VG01",
            "label": "Véhicule particulier"
        },
        "vehicle_age": {
            "id": "age123",
            "min_age": 0,
            "max_age": 12
        },
        "depreciation_rate": 15.5
    }
}
```

### Réponse 404 - Non trouvé

```json
{
    "status": 404,
    "message": "Depreciation table not found"
}
```

### Exemple cURL

```bash
curl -X GET http://localhost:8000/api/depreciation-tables/table123 \
  -H "Authorization: Bearer {token}"
```

---

## POST /depreciation-tables

Calcule la valeur théorique du marché et la dépréciation d'un véhicule.

**Authentification requise** - **Consomme du quota API**

### Requête

```http
POST /api/depreciation-tables
Authorization: Bearer {token}
Content-Type: application/json
```

### Paramètres

| Paramètre                         | Type    | Requis | Description                                                                          |
| --------------------------------- | ------- | ------ | ------------------------------------------------------------------------------------ |
| usage_id                          | string  | Oui    | Identifiant hash de l'usage du véhicule                                              |
| energy_id                         | string  | Oui    | Identifiant hash de la source d'énergie (essence/diesel/électrique/hybride)          |
| vehicle_new_value                 | number  | Oui    | Valeur neuve du véhicule (≥ 0)                                                       |
| vehicle_mileage                   | integer | Oui    | Kilométrage actuel du véhicule (≥ 0)                                                 |
| first_entry_into_circulation_date | date    | Oui    | Date de première mise en circulation (format: YYYY-MM-DD)                            |
| expertise_date                    | date    | Oui    | Date d'expertise (format: YYYY-MM-DD, doit être ≥ first_entry_into_circulation_date) |
| market_incidence_rate             | number  | Non    | Taux d'incidence de marché (0-100, défaut: 0)                                        |

### Exemple de requête

```json
{
    "usage_id": "usage123",
    "energy_id": "energy123",
    "vehicle_new_value": 25000,
    "vehicle_mileage": 50000,
    "first_entry_into_circulation_date": "2020-01-15",
    "expertise_date": "2024-01-15",
    "market_incidence_rate": 5
}
```

### Réponse 200 - Succès

```json
{
    "status": 200,
    "message": "Depreciation calculated successfully",
    "data": {
        "expertise_date": "2024-01-15",
        "first_entry_into_circulation_date": "2020-01-15",
        "year_diff": 4,
        "month_diff": 48,
        "vehicle_age": {
            "id": "age123",
            "min_age": 0,
            "max_age": 48
        },
        "vehicle_new_value": 25000,
        "vehicle_mileage": 50000,
        "theorical_depreciation_rate": 15.5,
        "theorical_vehicle_market_value": 21125,
        "depreciation_rate": 18.2,
        "vehicle_market_value": 20450,
        "is_up": false,
        "market_incidence_rate": 5,
        "market_incidence": 1250,
        "kilometric_incidence": -500,
        "usage": {
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
        },
        "energy": {
            "id": "energy123",
            "code": "VE01",
            "label": "Essence"
        }
    }
}
```

### Réponse 400 - Erreur de validation

```json
{
    "errors": [
        {
            "status": 400,
            "title": "Erreur de validation",
            "detail": "Le champ vehicle_new_value doit être un nombre."
        }
    ]
}
```

### Réponse 404 - Usage ou énergie non trouvé

```json
{
    "status": 404,
    "message": "Usage not found"
}
```

### Notes importantes

-   Le calcul utilise l'usage pour trouver la table de dépréciation appropriée
-   L'âge du véhicule est calculé à partir des dates `first_entry_into_circulation_date` et `expertise_date`
-   La source d'énergie (essence/diesel) est utilisée pour calculer l'incidence kilométrique :
    -   **Essence (VE01)** : `((max_mileage_essence_per_month * month_diff) - vehicle_mileage) * 25`
    -   **Diesel (VE02)** : `((max_mileage_diesel_per_month * month_diff) - vehicle_mileage) * 40`
-   L'incidence kilométrique peut être positive (si le kilométrage est inférieur à la moyenne) ou négative
-   Le `market_incidence_rate` permet d'ajuster la valeur selon les conditions du marché
-   **Cet endpoint consomme 1 unité de quota API**

### Exemple cURL

```bash
curl -X POST http://localhost:8000/api/depreciation-tables \
  -H "Authorization: Bearer {token}" \
  -H "Content-Type: application/json" \
  -d '{
    "usage_id": "usage123",
    "energy_id": "energy123",
    "vehicle_new_value": 25000,
    "vehicle_mileage": 50000,
    "first_entry_into_circulation_date": "2020-01-15",
    "expertise_date": "2024-01-15",
    "market_incidence_rate": 5
  }'
```
