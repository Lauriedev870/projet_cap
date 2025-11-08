# Nouvelles Fonctionnalités - Module Cours

## ✅ Fonctionnalités Ajoutées

### 1. 📦 Création en Masse de Programmes (Bulk Create)

Permet de créer plusieurs programmes en une seule requête.

**Endpoint:**
```http
POST /api/cours/programs/bulk
```

**Body:**
```json
{
  "programs": [
    {
      "class_group_id": 12,
      "course_element_professor_id": 5,
      "weighting": {
        "CC": 30,
        "TP": 20,
        "EXAMEN": 50
      }
    },
    {
      "class_group_id": 12,
      "course_element_professor_id": 8,
      "weighting": {
        "CC": 40,
        "EXAMEN": 60
      }
    }
  ]
}
```

**Avantages:**
- ✅ Création rapide de plusieurs programmes
- ✅ Gestion automatique des doublons (skip si déjà existant)
- ✅ Rapport détaillé des succès et erreurs
- ✅ Transaction par programme (un échec n'affecte pas les autres)

**Cas d'usage:**
- Créer l'emploi du temps complet d'une classe en une fois
- Importer des programmes depuis un fichier Excel/CSV
- Initialiser les emplois du temps de plusieurs classes

---

### 2. 🔄 Copie de Programmes entre Classes

Permet de dupliquer l'emploi du temps d'une classe vers une autre classe (typiquement d'une année académique à une autre).

**Endpoint:**
```http
POST /api/cours/programs/copy
```

**Body:**
```json
{
  "source_class_group_id": 12,
  "target_class_group_id": 25
}
```

**Avantages:**
- ✅ Duplication rapide d'un emploi du temps
- ✅ Conserve les mêmes cours, professeurs et pondérations
- ✅ Gère automatiquement les doublons (skip si déjà existant)
- ✅ Rapport détaillé avec programmes créés, ignorés et erreurs

**Cas d'usage:**
- Copier l'emploi du temps de l'année 2024-2025 vers 2025-2026
- Dupliquer l'emploi du temps d'une classe L1 vers une autre classe L1
- Réutiliser la structure d'un emploi du temps pour une nouvelle promotion

**Exemple de réponse:**
```json
{
  "success": true,
  "message": "15 programme(s) copié(s) avec succès, 2 ignoré(s) (déjà existants)",
  "data": {
    "created": [...],
    "skipped": [
      {
        "source_program_id": 5,
        "course_element_professor_id": 10,
        "reason": "Ce cours existe déjà dans la classe cible."
      }
    ],
    "errors": [],
    "summary": {
      "total_source": 17,
      "success_count": 15,
      "skipped_count": 2,
      "error_count": 0
    }
  }
}
```

---

### 3. 🔑 UUID dans les Réponses

Le champ `uuid` est désormais systématiquement inclus dans toutes les réponses du modèle `Program`.

**Structure de réponse:**
```json
{
  "id": 1,
  "uuid": "550e8400-e29b-41d4-a716-446655440000",
  "class_group_id": 12,
  "course_element_professor_id": 5,
  "weighting": {
    "CC": 30,
    "TP": 20,
    "EXAMEN": 50
  },
  "created_at": "2025-11-08T12:00:00.000Z",
  "updated_at": "2025-11-08T12:00:00.000Z"
}
```

**Avantages:**
- ✅ Le front-end peut utiliser le `uuid` au lieu de l'`id` numérique
- ✅ Plus sécurisé (pas d'exposition de l'ordre d'insertion)
- ✅ Compatible avec les systèmes distribués
- ✅ L'`id` reste présent pour les jointures internes

---

## 📁 Fichiers Créés

**Requests:**
- `BulkCreateProgramsRequest.php` - Validation création en masse
- `CopyProgramsRequest.php` - Validation copie de programmes

**Services (méthodes ajoutées):**
- `ProgramService::bulkCreate()` - Création en masse
- `ProgramService::copyPrograms()` - Copie entre classes

**Controllers (méthodes ajoutées):**
- `ProgramController::bulkStore()` - Endpoint création en masse
- `ProgramController::copyPrograms()` - Endpoint copie

**Routes:**
- `POST /api/cours/programs/bulk`
- `POST /api/cours/programs/copy`

---

## 🎯 Validation de Pondération

La validation reste la même pour tous les endpoints :

**Règles:**
- ✅ Chaque valeur entre 0 et 100
- ✅ Somme totale = 100 (obligatoire)
- ✅ Clés libres (CC, TP, EXAMEN, PROJET, etc.)

**Exemples valides:**
```json
{"CC": 40, "EXAMEN": 60}
{"CC": 30, "TP": 20, "EXAMEN": 50}
{"CC": 25, "TP": 25, "PROJET": 20, "EXAMEN": 30}
```

**Exemples invalides:**
```json
{"CC": 40, "EXAMEN": 50}  // ❌ Somme = 90
{"CC": 150}               // ❌ Valeur > 100
```

---

## 🚀 Exemple de Flux Complet

### Scénario: Créer l'emploi du temps de plusieurs classes pour l'année 2025-2026

1. **Créer l'emploi du temps de la classe pilote (L1 Info A)**
```http
POST /api/cours/programs/bulk
{
  "programs": [
    {"class_group_id": 12, "course_element_professor_id": 5, "weighting": {"CC": 30, "TP": 20, "EXAMEN": 50}},
    {"class_group_id": 12, "course_element_professor_id": 8, "weighting": {"CC": 40, "EXAMEN": 60}},
    {"class_group_id": 12, "course_element_professor_id": 10, "weighting": {"CC": 30, "TP": 20, "EXAMEN": 50}}
  ]
}
```

2. **Copier vers L1 Info B**
```http
POST /api/cours/programs/copy
{
  "source_class_group_id": 12,
  "target_class_group_id": 13
}
```

3. **Copier vers L1 Info C**
```http
POST /api/cours/programs/copy
{
  "source_class_group_id": 12,
  "target_class_group_id": 14
}
```

4. **Vérifier l'emploi du temps créé**
```http
GET /api/cours/class-groups/13/programs
GET /api/cours/class-groups/14/programs
```

---

## ✨ Résumé des Avantages

| Fonctionnalité | Avant | Après |
|---------------|-------|-------|
| Création programmes | 1 par 1 (lent) | En masse (rapide) |
| Duplication emploi du temps | Manuel (fastidieux) | Automatique en 1 clic |
| UUID dans réponses | ❌ Non | ✅ Oui |
| Gestion doublons | ❌ Erreur | ✅ Skip automatique |
| Rapport d'exécution | ❌ Non | ✅ Détaillé |

---

## 📝 Notes Importantes

- Le `uuid` est généré automatiquement par le trait `HasUuid`
- L'`id` numérique reste présent et ne doit pas être supprimé
- Les doublons sont détectés automatiquement (même `class_group_id` + `course_element_professor_id`)
- Les erreurs sont isolées : un échec n'affecte pas les autres créations
- La copie conserve uniquement : `course_element_professor_id` et `weighting`
