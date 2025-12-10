# Commandes cURL pour tester l'API de dépréciation

## Configuration
Remplacez `http://localhost:8000` par l'URL de votre serveur si nécessaire.

```bash
export BASE_URL="http://localhost:8000"
export API_URL="${BASE_URL}/api"
```

## 1. Lister tous les genres (usages VG01-VG13)

```bash
curl -X GET "${API_URL}/genres" | jq '.'
```

## 2. Lister tous les usages

```bash
curl -X GET "${API_URL}/usages" | jq '.'
```

## 3. Lister les tables de dépréciation (paginated)

```bash
curl -X GET "${API_URL}/depreciation-tables" | jq '.'
```

### Avec filtres (exemple: par usage_id)

```bash
# Remplacez <genre_id> par l'ID réel
curl -X GET "${API_URL}/depreciation-tables?filter[usage_id]=<genre_id>" | jq '.'
```

## 4. Obtenir une table de dépréciation spécifique

```bash
# Remplacez {hash_id} par un hash ID réel (obtenu depuis la liste)
curl -X GET "${API_URL}/depreciation-tables/{hash_id}" | jq '.'
```

## 5. Calculer la dépréciation - Véhicule ESSENCE

**Important**: `usage_id` et `energy_id` doivent être des **hash IDs** (strings), pas des IDs numériques.

```bash
# D'abord, récupérer un usage_id et energy_id (hash IDs)
USAGE_HASH_ID=$(curl -s "${API_URL}/usages" | jq -r '.data[0].id')
ENERGY_HASH_ID=$(curl -s "${API_URL}/energies" | jq -r '.data[] | select(.code == "VE01") | .id')

# Ensuite, calculer la dépréciation
curl -X POST "${API_URL}/depreciation-tables" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "{
    \"usage_id\": \"${USAGE_HASH_ID}\",
    \"energy_id\": \"${ENERGY_HASH_ID}\",
    \"vehicle_new_value\": 25000,
    \"vehicle_mileage\": 25000,
    \"first_entry_into_circulation_date\": \"2022-01-15\",
    \"expertise_date\": \"2024-01-15\",
    \"market_incidence_rate\": 5
  }" | jq '.'
```

## 6. Calculer la dépréciation - Véhicule DIESEL

```bash
# Récupérer un usage_id et energy_id (hash IDs)
USAGE_HASH_ID=$(curl -s "${API_URL}/usages" | jq -r '.data[0].id')
ENERGY_HASH_ID=$(curl -s "${API_URL}/energies" | jq -r '.data[] | select(.code == "VE02") | .id')

curl -X POST "${API_URL}/depreciation-tables" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "{
    \"usage_id\": \"${USAGE_HASH_ID}\",
    \"energy_id\": \"${ENERGY_HASH_ID}\",
    \"vehicle_new_value\": 35000,
    \"vehicle_mileage\": 40000,
    \"first_entry_into_circulation_date\": \"2021-01-15\",
    \"expertise_date\": \"2024-01-15\",
    \"market_incidence_rate\": 3
  }" | jq '.'
```

## 7. Test avec différents âges de véhicule

**Note**: Récupérez d'abord un `usage_id` et `energy_id` (hash IDs) depuis les APIs

```bash
USAGE_HASH_ID=$(curl -s "${API_URL}/usages" | jq -r '.data[0].id')
ENERGY_HASH_ID=$(curl -s "${API_URL}/energies" | jq -r '.data[] | select(.code == "VE01") | .id')
```

### Véhicule de 12 mois (1 an)

```bash
curl -X POST "${API_URL}/depreciation-tables" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "{
    \"usage_id\": \"${USAGE_HASH_ID}\",
    \"energy_id\": \"${ENERGY_HASH_ID}\",
    \"vehicle_new_value\": 20000,
    \"vehicle_mileage\": 24000,
    \"first_entry_into_circulation_date\": \"2023-01-15\",
    \"expertise_date\": \"2024-01-15\"
  }" | jq '.'
```

### Véhicule de 24 mois (2 ans)

```bash
curl -X POST "${API_URL}/depreciation-tables" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "{
    \"usage_id\": \"${USAGE_HASH_ID}\",
    \"energy_id\": \"${ENERGY_HASH_ID}\",
    \"vehicle_new_value\": 20000,
    \"vehicle_mileage\": 48000,
    \"first_entry_into_circulation_date\": \"2022-01-15\",
    \"expertise_date\": \"2024-01-15\"
  }" | jq '.'
```

### Véhicule de 36 mois (3 ans)

```bash
curl -X POST "${API_URL}/depreciation-tables" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "{
    \"usage_id\": \"${USAGE_HASH_ID}\",
    \"energy_id\": \"${ENERGY_HASH_ID}\",
    \"vehicle_new_value\": 20000,
    \"vehicle_mileage\": 72000,
    \"first_entry_into_circulation_date\": \"2021-01-15\",
    \"expertise_date\": \"2024-01-15\"
  }" | jq '.'
```

### Véhicule de 60 mois (5 ans)

```bash
curl -X POST "${API_URL}/depreciation-tables" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "{
    \"usage_id\": \"${USAGE_HASH_ID}\",
    \"energy_id\": \"${ENERGY_HASH_ID}\",
    \"vehicle_new_value\": 20000,
    \"vehicle_mileage\": 120000,
    \"first_entry_into_circulation_date\": \"2019-01-15\",
    \"expertise_date\": \"2024-01-15\"
  }" | jq '.'
```

## 8. Test avec différents usages

### VG01 - CYCLO MOTO

```bash
# Trouvez un usage lié au genre VG01 (hash ID)
USAGE_HASH_ID=$(curl -s "${API_URL}/usages" | jq -r '.data[] | select(.genre.code == "VG01") | .id' | head -1)

curl -X POST "${API_URL}/depreciation-tables" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "{
    \"usage_id\": \"${USAGE_HASH_ID}\",
    \"energy_id\": \"${ENERGY_HASH_ID}\",
    \"vehicle_new_value\": 5000,
    \"vehicle_mileage\": 10000,
    \"first_entry_into_circulation_date\": \"2022-01-15\",
    \"expertise_date\": \"2024-01-15\"
  }" | jq '.'
```

### VG04 - VÉHICULE PARTICULIER

```bash
USAGE_HASH_ID=$(curl -s "${API_URL}/usages" | jq -r '.data[] | select(.genre.code == "VG04") | .id' | head -1)

curl -X POST "${API_URL}/depreciation-tables" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "{
    \"usage_id\": \"${USAGE_HASH_ID}\",
    \"energy_id\": \"${ENERGY_HASH_ID}\",
    \"vehicle_new_value\": 30000,
    \"vehicle_mileage\": 50000,
    \"first_entry_into_circulation_date\": \"2021-01-15\",
    \"expertise_date\": \"2024-01-15\"
  }" | jq '.'
```

## 9. Test de validation d'erreurs

### Test avec données manquantes

```bash
curl -X POST "${API_URL}/depreciation-tables" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{}' | jq '.'
```

### Test avec dates invalides

```bash
# Récupérer un usage_id (hash ID) valide
USAGE_HASH_ID=$(curl -s "${API_URL}/usages" | jq -r '.data[0].id')

curl -X POST "${API_URL}/depreciation-tables" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "{
    \"usage_id\": \"${USAGE_HASH_ID}\",
    \"energy_id\": \"${ENERGY_HASH_ID}\",
    \"vehicle_new_value\": 20000,
    \"vehicle_mileage\": 25000,
    \"first_entry_into_circulation_date\": \"2024-01-15\",
    \"expertise_date\": \"2022-01-15\"
  }" | jq '.'
```

### Test avec usage_id invalide (hash ID inexistant)

```bash
ENERGY_HASH_ID=$(curl -s "${API_URL}/energies" | jq -r '.data[] | select(.code == "VE01") | .id')

curl -X POST "${API_URL}/depreciation-tables" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "{
    \"usage_id\": \"invalid_hash_id_12345\",
    \"energy_id\": \"${ENERGY_HASH_ID}\",
    "vehicle_new_value": 20000,
    "vehicle_mileage": 25000,
    "first_entry_into_circulation_date": "2022-01-15",
    "expertise_date": "2024-01-15"
  }' | jq '.'
```

## Notes importantes

1. **usage_id** : Doit être un **hash ID** (string) obtenu depuis `/api/usages`, pas un ID numérique
2. **energy_id** : Doit être un **hash ID** (string) obtenu depuis `/api/energies`, pas un ID numérique
   - Pour trouver l'énergie ESSENCE (VE01) : `curl -s "${API_URL}/energies" | jq '.data[] | select(.code == "VE01") | .id'`
   - Pour trouver l'énergie GASOIL/DIESEL (VE02) : `curl -s "${API_URL}/energies" | jq '.data[] | select(.code == "VE02") | .id'`
   - Pour trouver l'énergie ELECTRIQUE (VE03) : `curl -s "${API_URL}/energies" | jq '.data[] | select(.code == "VE03") | .id'`
   - Pour trouver l'énergie HYBRIDE (VE04) : `curl -s "${API_URL}/energies" | jq '.data[] | select(.code == "VE04") | .id'`
3. **Dates** : Format `YYYY-MM-DD`
4. **market_incidence_rate** : Optionnel, valeur entre 0 et 100

## Obtenir les IDs nécessaires

```bash
# Obtenir tous les usages avec leurs hash IDs
curl -s "${API_URL}/usages" | jq '.data[] | {id: .id, name: .name, code: .code, genre_code: .genre.code}'

# Obtenir toutes les énergies avec leurs hash IDs
curl -s "${API_URL}/energies" | jq '.data[] | {id: .id, code: .code, label: .label}'

# Obtenir tous les genres avec leurs hash IDs
curl -s "${API_URL}/genres" | jq '.data[] | {id: .id, code: .code, label: .label}'

# Exemple: Récupérer le premier usage_id (hash ID) pour les tests
curl -s "${API_URL}/usages" | jq -r '.data[0].id'

# Exemple: Récupérer l'énergie ESSENCE (hash ID)
curl -s "${API_URL}/energies" | jq -r '.data[] | select(.code == "VE01") | .id'
```

