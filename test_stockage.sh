#!/bin/bash

# 🧪 Script de Test du Module Stockage
# Date: 28 Octobre 2025

echo "======================================"
echo "🧪 TESTS DU MODULE STOCKAGE"
echo "======================================"
echo ""

BASE_URL="http://localhost:8000/api/stockage"

# Couleurs
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Fonction de test
test_endpoint() {
    local method=$1
    local endpoint=$2
    local description=$3
    local data=$4
    
    echo -e "${YELLOW}Test:${NC} $description"
    echo -e "${YELLOW}Endpoint:${NC} $method $endpoint"
    
    if [ -z "$data" ]; then
        response=$(curl -s -X $method "$BASE_URL$endpoint" \
            -H "Accept: application/json" \
            -w "\nHTTP_STATUS:%{http_code}")
    else
        response=$(curl -s -X $method "$BASE_URL$endpoint" \
            -H "Accept: application/json" \
            -H "Content-Type: application/json" \
            -d "$data" \
            -w "\nHTTP_STATUS:%{http_code}")
    fi
    
    http_status=$(echo "$response" | grep "HTTP_STATUS" | cut -d: -f2)
    body=$(echo "$response" | sed '/HTTP_STATUS/d')
    
    echo -e "${YELLOW}Status:${NC} $http_status"
    echo -e "${YELLOW}Response:${NC}"
    echo "$body" | python3 -m json.tool 2>/dev/null || echo "$body"
    
    # Vérifier le status
    if [ "$http_status" = "200" ] || [ "$http_status" = "201" ]; then
        echo -e "${GREEN}✅ SUCCESS${NC}"
    elif [ "$http_status" = "401" ]; then
        echo -e "${YELLOW}⚠️  AUTHENTIFICATION REQUISE (Normal)${NC}"
    elif [ "$http_status" = "404" ]; then
        echo -e "${YELLOW}⚠️  NON TROUVÉ (Normal pour certains tests)${NC}"
    else
        echo -e "${RED}❌ FAILED${NC}"
    fi
    
    echo ""
    echo "--------------------------------------"
    echo ""
}

# ===========================================
# TESTS ROUTES PUBLIQUES
# ===========================================
echo ""
echo "📂 TESTS ROUTES PUBLIQUES"
echo "=========================================="
echo ""

# Test 1: Fichiers publics
test_endpoint "GET" "/files/public" "Lister les fichiers publics"

# Test 2: Documents publics (liste)
test_endpoint "GET" "/documents" "Lister les documents officiels"

# Test 3: Accès à un partage (sans token valide)
test_endpoint "GET" "/files/share/test-token-123" "Accéder à un partage (token invalide)"

# ===========================================
# TESTS ROUTES PROTÉGÉES (Sans Auth)
# ===========================================
echo ""
echo "🔒 TESTS ROUTES PROTÉGÉES (Sans Authentification)"
echo "=========================================="
echo ""

# Test 4: Lister fichiers (sans auth)
test_endpoint "GET" "/files/" "Lister les fichiers (doit échouer)"

# Test 5: Créer fichier (sans auth)
test_endpoint "POST" "/files/" "Créer un fichier (doit échouer)" '{"name":"test.pdf"}'

# Test 6: Télécharger fichier (sans auth)
test_endpoint "GET" "/files/1/download" "Télécharger fichier (doit échouer)"

# Test 7: Permissions (sans auth)
test_endpoint "GET" "/files/1/permissions/" "Lister permissions (doit échouer)"

# Test 8: Partages (sans auth)
test_endpoint "GET" "/files/1/shares/" "Lister partages (doit échouer)"

# ===========================================
# TESTS ENDPOINTS SPÉCIFIQUES
# ===========================================
echo ""
echo "🎯 TESTS ENDPOINTS SPÉCIFIQUES"
echo "=========================================="
echo ""

# Test 9: Document spécifique (public)
test_endpoint "GET" "/documents/1" "Voir un document (peut échouer si ID 1 n'existe pas)"

# Test 10: Filtrer fichiers publics
test_endpoint "GET" "/files/public?collection=documents" "Filtrer fichiers publics par collection"

# ===========================================
# RÉSUMÉ
# ===========================================
echo ""
echo "======================================"
echo "📊 RÉSUMÉ DES TESTS"
echo "======================================"
echo ""
echo "Tests exécutés:"
echo "  - Routes publiques: 3 tests"
echo "  - Routes protégées: 5 tests"
echo "  - Endpoints spécifiques: 2 tests"
echo ""
echo "✅ Routes attendues qui retournent 200/201: Fichiers publics, Documents"
echo "⚠️  Routes attendues qui retournent 401: Fichiers protégés (normal)"
echo "⚠️  Routes attendues qui retournent 404: Ressources inexistantes (normal)"
echo ""
echo "======================================"
echo "FIN DES TESTS"
echo "======================================"
