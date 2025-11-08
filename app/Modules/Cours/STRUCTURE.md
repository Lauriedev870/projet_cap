# Structure du Module Cours

## Architecture de Base de Données

### Tables Principales

#### 1. `teaching_units` (Unités d'Enseignement - UE)
- Contient les unités d'enseignement
- Exemple: "Mathématiques Générales", "Informatique Fondamentale"

#### 2. `course_elements` (Éléments Constitutifs d'UE - ECUE)
- Appartient à une `teaching_unit`
- Exemple: "Algèbre Linéaire" dans UE "Mathématiques"

#### 3. `course_element_professor` (Table Pivot avec ID)
- **Important**: Cette table a un ID auto-incrémenté
- Lie un `course_element` avec un `professor`
- C'est une assignation de cours à un professeur

#### 4. `programs` (Programmes/Emplois du temps)
- Lie un `class_group` avec une assignation cours-professeur
- Référence: `course_element_professor_id` → ID de la table pivot
- Contient: `weighting` (pondération des évaluations)

#### 5. `course_element_resources` (Ressources Pédagogiques)
- Documents/fichiers liés à un élément de cours
- Types: syllabus, cours, TD, TP, examens, etc.

## Relations

### Program (Emploi du temps)
```
Program
├── classGroup (via class_group_id)
├── courseElementProfessor (via course_element_professor_id) → Table pivot
│   ├── courseElement
│   └── professor
├── courseElement (hasOneThrough via courseElementProfessor)
└── professor (hasOneThrough via courseElementProfessor)
```

### CourseElement (ECUE)
```
CourseElement
├── teachingUnit (belongsTo)
├── professors (belongsToMany via course_element_professor)
├── courseElementProfessors (hasMany) → Table pivot avec ID
├── programs (hasManyThrough via courseElementProfessors)
└── resources (hasMany)
```

### Professor
```
Professor
├── courseElements (belongsToMany via course_element_professor)
├── courseElementProfessors (hasMany) → Assignations
└── programs (hasManyThrough via courseElementProfessors)
```

### ClassGroup
```
ClassGroup
├── academicYear (belongsTo)
├── department (belongsTo)
├── coursePrograms (hasMany Program)
└── studentGroups (hasMany)
```

## Cas d'Usage

### Créer un emploi du temps pour une classe

1. **Créer Teaching Unit**
   ```php
   TeachingUnit::create(['name' => 'Mathématiques', 'code' => 'MATH101']);
   ```

2. **Créer Course Element**
   ```php
   CourseElement::create([
       'name' => 'Algèbre',
       'code' => 'MATH101-ALG',
       'credits' => 3,
       'teaching_unit_id' => $teachingUnit->id
   ]);
   ```

3. **Assigner Professeur à Course Element**
   ```php
   $assignment = CourseElementProfessor::create([
       'course_element_id' => $courseElement->id,
       'professor_id' => $professor->id
   ]);
   // ou via relation:
   $courseElement->professors()->attach($professorId);
   ```

4. **Créer Program (Ajouter au calendrier de la classe)**
   ```php
   Program::create([
       'class_group_id' => $classGroup->id,
       'course_element_professor_id' => $assignment->id,
       'weighting' => [
           'CC' => 30,    // Contrôle Continu
           'TP' => 20,    // Travaux Pratiques
           'EXAMEN' => 50 // Examen Final
       ]
   ]);
   ```

## APIs Manquantes

### Program API (À créer)
- `GET /api/cours/programs` - Liste des programmes
- `POST /api/cours/programs` - Créer un programme
- `GET /api/cours/programs/{id}` - Détails d'un programme
- `PUT /api/cours/programs/{id}` - Modifier un programme
- `DELETE /api/cours/programs/{id}` - Supprimer un programme

### Routes Utiles (À créer)
- `GET /api/cours/class-groups/{id}/programs` - Emploi du temps d'une classe
- `GET /api/cours/professors/{id}/programs` - Programmes d'un professeur
- `GET /api/cours/course-elements/{id}/professors` - Professeurs d'un ECUE

### Migration
Exécuter la migration:
```bash
php artisan migrate
```
