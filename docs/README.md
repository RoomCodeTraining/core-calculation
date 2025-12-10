# Documentation API - Calculation Core

## Introduction

Cette documentation décrit toutes les APIs disponibles pour interagir avec le système Calculation Core. L'API utilise l'authentification Bearer Token via Laravel Sanctum.

## Base URL

```
http://localhost:8000/api
```

Remplacez `http://localhost:8000` par l'URL de votre environnement de production.

## Authentification

La plupart des endpoints nécessitent une authentification. Pour vous authentifier :

1. Utilisez l'endpoint `/auth/login` pour obtenir un token
2. Incluez le token dans l'en-tête `Authorization` de toutes les requêtes suivantes :
    ```
    Authorization: Bearer {votre_token}
    ```

## Structure des réponses

Toutes les réponses suivent un format standardisé :

### Succès

```json
{
    "status": 200,
    "message": "Message de succès",
    "data": {
        // Données de la réponse
    }
}
```

### Erreur

```json
{
    "errors": [
        {
            "status": 400,
            "title": "Titre de l'erreur",
            "detail": "Détail de l'erreur"
        }
    ]
}
```

## Codes de statut HTTP

-   `200` - Succès
-   `201` - Créé avec succès
-   `400` - Requête invalide
-   `401` - Non authentifié
-   `403` - Accès non autorisé
-   `404` - Ressource non trouvée
-   `422` - Erreur de validation
-   `500` - Erreur serveur

## Pagination

Les endpoints qui retournent des listes utilisent la pagination. Par défaut, 15 éléments par page.

### Paramètres de pagination

-   `per_page` : Nombre d'éléments par page (défaut: 15)

### Format de réponse paginée

```json
{
  "status": 200,
  "message": "Données récupérées avec succès",
  "data": {
    "items": [...],
    "pagination": {
      "current_page": 1,
      "last_page": 5,
      "per_page": 15,
      "total": 75
    }
  }
}
```

## Quota API

Certains endpoints consomment du quota API de votre organisation. Le quota restant peut être consulté via l'endpoint `/quota`.

## Documentation par modèle

1. [Auth - Authentification](./auth.md)
2. [Organization - Organisations](./organization.md)
3. [Quota - Gestion du quota](./quota.md)
4. [User - Utilisateurs](./user.md)
5. [Brand - Marques](./brand.md)
6. [VehicleModel - Modèles de véhicules](./vehicle-model.md)
7. [Genre - Genres](./genre.md)
8. [Usage - Usages](./usage.md)
9. [DepreciationTable - Tables de dépréciation](./depreciation-table.md)
10. [Energy - Énergies](./energy.md)
