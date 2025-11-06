# StudentService - Documentation

## Vue d'ensemble

Le `StudentService` est un service réutilisable qui gère les opérations liées aux étudiants inscrits, notamment la détection automatique des redoublants.

## Emplacement

```
app/Modules/Inscription/Services/StudentService.php
```

## Méthodes disponibles

### 1. `getAll(array $filters, int $perPage)` 
Récupère une liste paginée d'étudiants avec filtres optionnels.

### 2. `getById(int $id)`
Récupère les détails d'un étudiant par son ID.

### 3. `isRepeatingStudent(int $studentPendingStudentId, string $level)` ⭐

**Méthode clé réutilisable depuis tous les modules**

#### Description
Vérifie si un étudiant est redoublant pour un niveau donné en analysant la table `academic_paths`.

#### Logique
Un étudiant est considéré comme **redoublant** si dans la table `academic_paths`, pour un **même niveau** (`study_level`), il existe des enregistrements avec des **années académiques différentes** (`academic_year_id`).

#### Paramètres
- `$studentPendingStudentId` (int|null) : ID de la relation `student_pending_student`
- `$level` (string) : Niveau d'études (L1, L2, L3, M1, M2, etc.)

#### Retour
- `bool` : `true` si l'étudiant est redoublant, `false` sinon

## Exemples d'utilisation

### Depuis le module Inscription

```php
use App\Modules\Inscription\Services\StudentService;

class SomeController extends Controller
{
    public function __construct(
        protected StudentService $studentService
    ) {}

    public function checkStudent($studentPendingStudentId)
    {
        $isRepeating = $this->studentService->isRepeatingStudent($studentPendingStudentId, 'L1');
        
        if ($isRepeating) {
            // Logique spécifique pour les redoublants
            return "Cet étudiant redouble la L1";
        }
        
        return "Première fois en L1";
    }
}
```

### Depuis un autre module (Finance, Notes, etc.)

```php
use App\Modules\Inscription\Services\StudentService;

class PaiementService
{
    public function calculateFees($studentPendingStudentId, $level)
    {
        $studentService = app(StudentService::class);
        $isRepeating = $studentService->isRepeatingStudent($studentPendingStudentId, $level);
        
        if ($isRepeating) {
            // Appliquer des frais différents pour les redoublants
            $fees = $this->getRepeatingStudentFees();
        } else {
            $fees = $this->getRegularFees();
        }
        
        return $fees;
    }
}
```

### Dans un seeder ou une commande Artisan

```php
use App\Modules\Inscription\Services\StudentService;

$studentService = app(StudentService::class);

$students = DB::table('student_pending_student')->get();

foreach ($students as $student) {
    $isRepeating = $studentService->isRepeatingStudent($student->id, 'L1');
    echo "Student {$student->id}: " . ($isRepeating ? "Redoublant" : "Non redoublant") . "\n";
}
```

## Structure de la table academic_paths

```sql
CREATE TABLE academic_paths (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    uuid VARCHAR(36) UNIQUE,
    student_pending_student_id BIGINT UNSIGNED,
    academic_year_id BIGINT UNSIGNED,
    study_level VARCHAR(255),          -- L1, L2, L3, M1, M2
    year_decision ENUM('pass', 'fail', 'repeat'),
    role_id BIGINT UNSIGNED,
    financial_status ENUM('Exonéré', 'Non exonéré'),
    cohort VARCHAR(255),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP NULL
);
```

## Exemple de données pour un redoublant

```php
// Année 2023-2024 : Étudiant en L1, décision = repeat
[
    'student_pending_student_id' => 1,
    'academic_year_id' => 1,  // 2023-2024
    'study_level' => 'L1',
    'year_decision' => 'repeat',
]

// Année 2024-2025 : Même étudiant refait L1, décision = pass
[
    'student_pending_student_id' => 1,
    'academic_year_id' => 2,  // 2024-2025
    'study_level' => 'L1',
    'year_decision' => 'pass',
]

// Result: isRepeatingStudent(1, 'L1') => TRUE
```

## Avantages de cette approche

✅ **Centralisé** : Une seule source de vérité pour le statut redoublant  
✅ **Réutilisable** : Utilisable depuis n'importe quel module  
✅ **Performant** : Query optimisée avec count distinct  
✅ **Sécurisé** : Gestion des erreurs avec logs  
✅ **Documenté** : PHPDoc complet avec exemples  

## Notes importantes

- La méthode retourne `false` si `$studentPendingStudentId` est `null`
- Les erreurs sont loggées automatiquement
- La logique est basée sur les données réelles de `academic_paths`
- Compatible avec tous les modules du backend
