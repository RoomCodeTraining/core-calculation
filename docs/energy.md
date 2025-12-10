# Energy - Énergies

Gestion des sources d'énergie des véhicules.

## GET /energies

Récupère la liste de toutes les sources d'énergie disponibles.

**Authentification requise**

### Requête

```http
GET /api/energies?per_page=20
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
    "message": "Energies retrieved successfully",
    "data": {
        "data": [
            {
                "id": "energy123",
                "code": "VE01",
                "label": "Essence"
            },
            {
                "id": "energy456",
                "code": "VE02",
                "label": "Gasoil / Diesel"
            },
            {
                "id": "energy789",
                "code": "VE03",
                "label": "Électrique"
            },
            {
                "id": "energy012",
                "code": "VE04",
                "label": "Hybride"
            }
        ],
        "pagination": {
            "current_page": 1,
            "last_page": 1,
            "per_page": 15,
            "total": 4
        }
    }
}
```

### Codes d'énergie disponibles

-   **VE01** : Essence
-   **VE02** : Gasoil / Diesel
-   **VE03** : Électrique
-   **VE04** : Hybride

### Exemple cURL

```bash
curl -X GET "http://localhost:8000/api/energies?per_page=20" \
  -H "Authorization: Bearer {token}"
```

### Exemple : Récupérer l'ID d'une énergie spécifique

Pour trouver l'identifiant hash d'une énergie spécifique (par exemple, Essence) :

```bash
curl -s -X GET "http://localhost:8000/api/energies" \
  -H "Authorization: Bearer {token}" \
  | jq '.data[] | select(.code == "VE01") | .id'
```
