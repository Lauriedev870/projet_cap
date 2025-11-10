# Documentation API - Module Cours

## Base URL
`/api/cours`

Toutes les routes nécessitent une authentification via `auth:sanctum`.

---

## 📚 Teaching Units (Unités d'Enseignement - UE)

### Liste des UE
```http
GET /api/cours/teaching-units
```

**Query Parameters:**
- `search` (string) - Recherche par nom ou code
- `sort_by` (string) - Champ de tri (défaut: created_at)
- `sort_order` (asc|desc) - Ordre de tri (défaut: desc)
- `per_page` (int) - Nombre d'éléments par page

### Créer une UE
```http
POST /api/cours/teaching-units
```

**Body:**
```json
{
  "name": "Mathématiques Générales",
  "code": "MATH101"
}
```

### Détails d'une UE
```http
GET /api/cours/teaching-units/{id}
```

### Modifier une UE
```http
PUT /api/cours/teaching-units/{id}
```

### Supprimer une UE
```http
DELETE /api/cours/teaching-units/{id}
```

### Liste des ECUE d'une UE
```http
GET /api/cours/teaching-units/{id}/course-elements
```

---

## 📖 Course Elements (Éléments Constitutifs d'UE - ECUE)

### Liste des ECUE
```http
GET /api/cours/course-elements
```

**Query Parameters:**
- `search` (string) - Recherche
- `teaching_unit_id` (int) - Filtrer par UE
- `credits` (int) - Filtrer par crédits
- `sort_by` (string)
- `sort_order` (asc|desc)

### Créer un ECUE
```http
POST /api/cours/course-elements
```

**Body:**
```json
{
  "name": "Algèbre Linéaire",
  "code": "MATH101-ALG",
  "credits": 3,
  "teaching_unit_id": 1
}
```

### Détails d'un ECUE
```http
GET /api/cours/course-elements/{id}
```

### Modifier un ECUE
```http
PUT /api/cours/course-elements/{id}
```

### Supprimer un ECUE
```http
DELETE /api/cours/course-elements/{id}
```

### Attacher un professeur à un ECUE
```http
POST /api/cours/course-elements/{id}/professors/attach
```

**Body:**
```json
{
  "professor_id": 5
}
```

### Détacher un professeur d'un ECUE
```http
POST /api/cours/course-elements/{id}/professors/detach
```

**Body:**
```json
{
  "professor_id": 5
}
```

### Liste des professeurs d'un ECUE
```http
GET /api/cours/course-elements/{id}/professors
```

### Liste des ressources d'un ECUE
```http
GET /api/cours/course-elements/{id}/resources
```

---

## 📁 Course Resources (Ressources Pédagogiques)

### Liste des ressources
```http
GET /api/cours/course-resources
```

**Query Parameters:**
- `course_element_id` (int) - Filtrer par ECUE
- `resource_type` (string) - Type de ressource
- `is_public` (boolean) - Ressources publiques uniquement

### Créer une ressource
```http
POST /api/cours/course-resources
Content-Type: multipart/form-data
```

**Body (FormData):**
- `course_element_id` (int) - **required**
- `title` (string) - **required**
- `description` (text) - optional
- `resource_type` (string) - Type: syllabus, cours, td, tp, examen
- `is_public` (boolean) - Défaut: false
- `file` (file) - **required**

### Détails d'une ressource
```http
GET /api/cours/course-resources/{id}
```

### Modifier une ressource
```http
PUT /api/cours/course-resources/{id}
```

### Supprimer une ressource
```http
DELETE /api/cours/course-resources/{id}
```

---

## 📅 Programs (Emploi du temps / Assignations de cours)

### Liste des programmes
```http
GET /api/cours/programs
```

**Query Parameters:**
- `class_group_id` (int) - Filtrer par groupe
- `course_element_id` (int) - Filtrer par ECUE
- `professor_id` (int) - Filtrer par professeur
- `search` (string) - Recherche globale

### Créer un programme
```http
POST /api/cours/programs
```

**Body:**
```json
{
  "class_group_id": 12,
  "course_element_professor_id": 5,
  "weighting": {
    "CC": 30,
    "TP": 20,
    "EXAMEN": 50
  }
}
```

**Note:** La somme des pondérations **doit être égale à 100** !

### Détails d'un programme
```http
GET /api/cours/programs/{id}
```

### Modifier un programme
```http
PUT /api/cours/programs/{id}
```

**Body:**
```json
{
  "weighting": {
    "CC": 40,
    "EXAMEN": 60
  }
}
```

### Supprimer un programme
```http
DELETE /api/cours/programs/{id}
```

### Créer plusieurs programmes en masse
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
    },
    {
      "class_group_id": 13,
      "course_element_professor_id": 5,
      "weighting": {
        "CC": 30,
        "TP": 20,
        "EXAMEN": 50
      }
    }
  ]
}
```

**Réponse:**
```json
{
  "success": true,
  "message": "3 programme(s) créé(s) avec succès",
  "data": {
    "created": [...],
    "errors": [],
    "summary": {
      "success_count": 3,
      "error_count": 0,
      "total": 3
    }
  }
}
```

### Copier les programmes d'une classe à une autre
```http
POST /api/cours/programs/copy
```

Permet de dupliquer l'emploi du temps d'une classe vers une autre classe (par exemple d'une année à une autre).

**Body:**
```json
{
  "source_class_group_id": 12,
  "target_class_group_id": 25
}
```

**Réponse:**
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

## 🔍 Routes Utilitaires

### Emploi du temps d'un groupe de classe
```http
GET /api/cours/class-groups/{classGroupId}/programs
```
Retourne tous les cours assignés à un groupe de classe (emploi du temps complet).

### Programmes d'un professeur
```http
GET /api/cours/professors/{professorId}/programs
```
Retourne tous les cours enseignés par un professeur.

### Programmes d'un élément de cours
```http
GET /api/cours/course-elements/{courseElementId}/programs
```
Retourne toutes les assignations d'un ECUE à différentes classes.

---

## 📊 Exemples de Flux

### Créer un emploi du temps complet

1. **Créer une UE**
```http
POST /api/cours/teaching-units
{
  "name": "Mathématiques",
  "code": "MATH101"
}
```

2. **Créer un ECUE**
```http
POST /api/cours/course-elements
{
  "name": "Algèbre",
  "code": "MATH101-ALG",
  "credits": 3,
  "teaching_unit_id": 1
}
```

3. **Assigner un professeur**
```http
POST /api/cours/course-elements/1/professors/attach
{
  "professor_id": 5
}
```
→ Cela crée une entrée dans `course_element_professor` avec un ID (ex: 10)

4. **Ajouter au programme de la classe**
```http
POST /api/cours/programs
{
  "class_group_id": 12,
  "course_element_professor_id": 10,
  "weighting": {
    "CC": 30,
    "TP": 20,
    "EXAMEN": 50
  }
}
```

5. **Consulter l'emploi du temps**
```http
GET /api/cours/class-groups/12/programs
```

---

## ✅ Validation de Pondération

La pondération est un objet JSON avec des clés libres et des valeurs numériques.

**Règles:**
- ✓ Chaque valeur doit être un nombre entre 0 et 100
- ✓ La somme de toutes les valeurs **doit être exactement 100**

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
{"CC": 101}               // ❌ Somme = 101
```

---

## 🚀 Prochaines Étapes

Pour utiliser ces APIs:
1. Exécuter la migration: `php artisan migrate`
2. Tester avec Postman ou votre client HTTP préféré
3. Les relations sont chargées automatiquement dans les réponses
