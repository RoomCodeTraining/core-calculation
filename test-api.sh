#!/bin/bash

# Script de test pour l'API de dépréciation
# Assurez-vous que votre serveur Laravel est démarré (php artisan serve)

BASE_URL="${BASE_URL:-http://localhost:8000}"
API_URL="${BASE_URL}/api"

echo "=========================================="
echo "Tests de l'API de dépréciation"
echo "URL de base: $BASE_URL"
echo "=========================================="
echo ""

# Couleurs pour l'affichage
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# 1. Lister tous les genres (usages VG01-VG13)
echo -e "${BLUE}1. Liste des genres (usages)${NC}"
echo "GET ${API_URL}/genres"
curl -s -X GET "${API_URL}/genres" | jq '.' | head -30
echo ""
echo ""

# 2. Lister tous les usages
echo -e "${BLUE}2. Liste des usages${NC}"
echo "GET ${API_URL}/usages"
curl -s -X GET "${API_URL}/usages" | jq '.' | head -30
echo ""
echo ""

# 3. Lister les tables de dépréciation (avec pagination)
echo -e "${BLUE}3. Liste des tables de dépréciation (première page)${NC}"
echo "GET ${API_URL}/depreciation-tables"
curl -s -X GET "${API_URL}/depreciation-tables" | jq '.data | length'
echo "Nombre total d'entrées dans la première page"
curl -s -X GET "${API_URL}/depreciation-tables" | jq '.data[0:3]' # Afficher les 3 premières
echo ""
echo ""

# 4. Filtrer les tables de dépréciation par usage (exemple: VG01)
echo -e "${BLUE}4. Tables de dépréciation pour VG01 (CYCLO MOTO)${NC}"
echo "GET ${API_URL}/depreciation-tables?filter[usage_id]=<genre_id>"
echo "Note: Remplacez <genre_id> par l'ID réel du genre VG01"
curl -s -X GET "${API_URL}/depreciation-tables" | jq '.data[0]' # Exemple avec première entrée
echo ""
echo ""

# 5. Obtenir une table de dépréciation spécifique par son hash ID
echo -e "${BLUE}5. Obtenir une table de dépréciation spécifique${NC}"
echo "GET ${API_URL}/depreciation-tables/{hash_id}"
echo "Note: Remplacez {hash_id} par un hash ID réel"
FIRST_HASH_ID=$(curl -s -X GET "${API_URL}/depreciation-tables" | jq -r '.data[0].id // empty')
if [ ! -z "$FIRST_HASH_ID" ]; then
    echo "Exemple avec le premier hash ID: $FIRST_HASH_ID"
    curl -s -X GET "${API_URL}/depreciation-tables/${FIRST_HASH_ID}" | jq '.'
else
    echo "Aucune table de dépréciation trouvée"
fi
echo ""
echo ""

# 6. Calculer la dépréciation - Véhicule essence
echo -e "${GREEN}6. Calcul de dépréciation - Véhicule ESSENCE${NC}"
echo "POST ${API_URL}/depreciation-tables"
echo ""
echo "Exemple: Véhicule particulier, essence, 2 ans, 25000 km"
echo ""

# Récupérer un usage_id et energy_id (hash IDs) pour l'exemple
USAGE_HASH_ID=$(curl -s -X GET "${API_URL}/usages" | jq -r '.data[0].id // empty')
ENERGY_HASH_ID=$(curl -s -X GET "${API_URL}/energies" | jq -r '.data[] | select(.code == "VE01") | .id // empty')

# Si on ne trouve pas directement, on utilise des valeurs par défaut
if [ -z "$USAGE_HASH_ID" ] || [ -z "$ENERGY_HASH_ID" ]; then
    echo -e "${YELLOW}⚠️  Aucun usage ou énergie trouvé. Veuillez exécuter les seeders d'abord.${NC}"
    echo "Commande: php artisan migrate --seed"
    exit 1
fi

# Calculer les dates (véhicule de 24 mois)
FIRST_ENTRY_DATE=$(date -v-24m +%Y-%m-%d 2>/dev/null || date -d '24 months ago' +%Y-%m-%d)
EXPERTISE_DATE=$(date +%Y-%m-%d)

PAYLOAD_ESSENCE=$(cat <<EOF
{
  "usage_id": "${USAGE_HASH_ID}",
  "energy_id": "${ENERGY_HASH_ID}",
  "vehicle_new_value": 25000,
  "vehicle_mileage": 25000,
  "first_entry_into_circulation_date": "${FIRST_ENTRY_DATE}",
  "expertise_date": "${EXPERTISE_DATE}",
  "market_incidence_rate": 5
}
EOF
)

echo "Payload:"
echo "$PAYLOAD_ESSENCE" | jq '.'
echo ""
echo "Réponse:"
curl -s -X POST "${API_URL}/depreciation-tables" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "$PAYLOAD_ESSENCE" | jq '.'
echo ""
echo ""

# 7. Calculer la dépréciation - Véhicule diesel
echo -e "${GREEN}7. Calcul de dépréciation - Véhicule DIESEL${NC}"
echo "POST ${API_URL}/depreciation-tables"
echo ""
echo "Exemple: Véhicule utilitaire, diesel, 3 ans, 40000 km"
echo ""

# Récupérer l'énergie diesel
ENERGY_DIESEL_HASH_ID=$(curl -s -X GET "${API_URL}/energies" | jq -r '.data[] | select(.code == "VE02") | .id // empty')

# Calculer les dates (véhicule de 36 mois)
FIRST_ENTRY_DATE_DIESEL=$(date -v-36m +%Y-%m-%d 2>/dev/null || date -d '36 months ago' +%Y-%m-%d)

PAYLOAD_DIESEL=$(cat <<EOF
{
  "usage_id": "${USAGE_HASH_ID}",
  "energy_id": "${ENERGY_DIESEL_HASH_ID}",
  "vehicle_new_value": 35000,
  "vehicle_mileage": 40000,
  "first_entry_into_circulation_date": "${FIRST_ENTRY_DATE_DIESEL}",
  "expertise_date": "${EXPERTISE_DATE}",
  "market_incidence_rate": 3
}
EOF
)

echo "Payload:"
echo "$PAYLOAD_DIESEL" | jq '.'
echo ""
echo "Réponse:"
curl -s -X POST "${API_URL}/depreciation-tables" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "$PAYLOAD_DIESEL" | jq '.'
echo ""
echo ""

# 8. Test avec différents âges de véhicule
echo -e "${GREEN}8. Test avec différents âges de véhicule${NC}"
echo ""

for months in 12 24 36 48 60; do
    echo -e "${YELLOW}Véhicule de ${months} mois:${NC}"
    FIRST_DATE=$(date -v-${months}m +%Y-%m-%d 2>/dev/null || date -d "${months} months ago" +%Y-%m-%d)

    PAYLOAD=$(cat <<EOF
{
  "usage_id": "${USAGE_HASH_ID}",
  "energy_id": "${ENERGY_HASH_ID}",
  "vehicle_new_value": 20000,
  "vehicle_mileage": $((months * 2000)),
  "first_entry_into_circulation_date": "${FIRST_DATE}",
  "expertise_date": "${EXPERTISE_DATE}"
}
EOF
)

    curl -s -X POST "${API_URL}/depreciation-tables" \
      -H "Content-Type: application/json" \
      -H "Accept: application/json" \
      -d "$PAYLOAD" | jq '{month_diff, theorical_depreciation_rate, vehicle_market_value}'
    echo ""
done

echo ""
echo "=========================================="
echo -e "${GREEN}Tests terminés!${NC}"
echo "=========================================="

