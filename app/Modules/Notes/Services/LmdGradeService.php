<?php

namespace App\Modules\Notes\Services;

use App\Modules\Notes\Models\LmdSystemGrade;
use App\Modules\Cours\Models\Program;
use Illuminate\Support\Facades\DB;

class LmdGradeService
{
    private GradeCalculationService $calculationService;

    public function __construct(GradeCalculationService $calculationService)
    {
        $this->calculationService = $calculationService;
    }

    /**
     * Récupère les étudiants d'un programme avec leurs notes
     * 
     * @param Program $program
     * @return \Illuminate\Support\Collection
     */
    public function getStudentsByProgram(Program $program)
    {
        $classGroup = $program->classGroup;

        // Récupère les academic paths liés à cette classe
        $academicPaths = \App\Modules\Inscription\Models\AcademicPath::whereHas('studentPendingStudent', function ($q) use ($classGroup) {
            $q->where('academic_year_id', $classGroup->academic_year_id)
                ->where('study_level', $classGroup->level)
                ->where('year_decision', '!=', 'failed');
        })->get();

        return $academicPaths->map(function ($academicPath) use ($program) {
            $studentPending = $academicPath->studentPendingStudent;
            $pendingStudent = $studentPending?->pendingStudent;
            $personalInfo = $pendingStudent?->personalInformation;

            // Récupérer la note
            $grade = LmdSystemGrade::where('student_pending_student_id', $academicPath->student_pending_student_id)
                ->where('program_id', $program->id)
                ->first();

            return [
                'student_pending_student_id' => $academicPath->student_pending_student_id,
                'last_name' => $personalInfo?->last_name,
                'first_names' => $personalInfo?->first_names,
                'grades' => $grade?->grades ?? [],
                'average' => $grade?->average,
                'retake_grades' => $grade?->retake_grades ?? [],
                'retake_average' => $grade?->retake_average,
                'validated' => $grade?->validated ?? false,
            ];
        })->filter(function ($item) {
            return $item['last_name'] && $item['first_names'];
        })->sortBy('last_name')->values();
    }

    /**
     * Ajoute une colonne de notes (un nouveau devoir)
     * 
     * @param int $programId
     * @param array $notes ['student_id' => value]
     * @param bool $sessionNormale
     * @return array
     */
    public function addNoteColumn(int $programId, array $notes, bool $sessionNormale = true): array
    {
        $program = Program::findOrFail($programId);
        $results = [];

        DB::beginTransaction();
        try {
            foreach ($notes as $studentPendingStudentId => $note) {
                // Récupère ou crée l'enregistrement
                $grade = LmdSystemGrade::where('student_pending_student_id', $studentPendingStudentId)
                    ->where('program_id', $programId)
                    ->first();

                if ($grade) {
                    // Ajoute la note au tableau existant
                    $gradesArray = $sessionNormale 
                        ? ($grade->grades ?? [])
                        : ($grade->retake_grades ?? []);
                    
                    $gradesArray[] = $note;

                    if ($sessionNormale) {
                        $grade->grades = $gradesArray;
                    } else {
                        $grade->retake_grades = $gradesArray;
                    }
                } else {
                    // Première note
                    $grade = new LmdSystemGrade();
                    $grade->student_pending_student_id = $studentPendingStudentId;
                    $grade->program_id = $programId;
                    
                    if ($sessionNormale) {
                        $grade->grades = [$note];
                    } else {
                        $grade->retake_grades = [$note];
                    }
                }

                // Récupère la pondération actuelle
                $pond = $sessionNormale 
                    ? ($program->weighting ?? [])
                    : ($program->retake_weighting ?? []);

                // Calcule la moyenne si le nombre de notes correspond
                $currentGrades = $sessionNormale ? $grade->grades : $grade->retake_grades;
                if (count($currentGrades) === count($pond) && count($pond) > 0) {
                    $moyenne = $this->calculationService->calculateMoyennePonderee($currentGrades, $pond);
                    
                    if ($sessionNormale) {
                        $grade->average = $moyenne;
                        $grade->validated = $this->calculationService->isValidated($moyenne, $grade->retake_average);
                        $grade->must_retake = $this->calculationService->mustRetake($moyenne);
                    } else {
                        $grade->retake_average = $moyenne;
                        $grade->retaken = true;
                        $grade->validated = $this->calculationService->isValidated($grade->average, $moyenne);
                        $grade->must_retake = $this->calculationService->mustRetake($moyenne);
                    }
                }

                $grade->save();
                $results[] = $grade;
            }

            // Met à jour la pondération du programme (automatique équilibrée)
            if (!empty($results)) {
                $columnCount = count($sessionNormale ? $results[0]->grades : $results[0]->retake_grades);
                $newPond = $this->calculationService->getBalancedPonderation($columnCount);
                
                if ($sessionNormale) {
                    $program->weighting = $newPond;
                } else {
                    $program->retake_weighting = $newPond;
                }
                $program->save();

                // Recalcule les moyennes de TOUS les étudiants avec la nouvelle pondération
                $this->recalculateAllAverages($programId, $sessionNormale);
            }

            DB::commit();
            return $results;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Modifie une note à une position donnée
     * 
     * @param int $studentPendingStudentId
     * @param int $programId
     * @param int $position
     * @param float $note
     * @param bool $sessionNormale
     * @return bool
     */
    public function updateNoteAtPosition(
        int $studentPendingStudentId,
        int $programId,
        int $position,
        float $note,
        bool $sessionNormale = true
    ): bool {
        $grade = LmdSystemGrade::where('student_pending_student_id', $studentPendingStudentId)
            ->where('program_id', $programId)
            ->first();

        if (!$grade) {
            return false;
        }

        $gradesArray = $sessionNormale ? ($grade->grades ?? []) : ($grade->retake_grades ?? []);

        if (!isset($gradesArray[$position])) {
            return false;
        }

        $gradesArray[$position] = $note;

        if ($sessionNormale) {
            $grade->grades = $gradesArray;
        } else {
            $grade->retake_grades = $gradesArray;
        }

        // Recalcule la moyenne
        $program = Program::findOrFail($programId);
        $pond = $sessionNormale ? ($program->weighting ?? []) : ($program->retake_weighting ?? []);
        
        if (count($gradesArray) === count($pond) && count($pond) > 0) {
            $moyenne = $this->calculationService->calculateMoyennePonderee($gradesArray, $pond);
            
            if ($sessionNormale) {
                $grade->average = $moyenne;
                $grade->validated = $this->calculationService->isValidated($moyenne, $grade->retake_average);
                $grade->must_retake = $this->calculationService->mustRetake($moyenne);
            } else {
                $grade->retake_average = $moyenne;
                $grade->validated = $this->calculationService->isValidated($grade->average, $moyenne);
                $grade->must_retake = $this->calculationService->mustRetake($moyenne);
            }
        }

        return $grade->save();
    }

    /**
     * Supprime une colonne de notes (un devoir) pour tous les étudiants
     * 
     * @param int $programId
     * @param int $columnIndex
     * @param bool $sessionNormale
     * @return bool
     */
    public function deleteNoteColumn(int $programId, int $columnIndex, bool $sessionNormale = true): bool
    {
        $program = Program::findOrFail($programId);
        $grades = LmdSystemGrade::where('program_id', $programId)->get();

        $maxNotes = 0;

        foreach ($grades as $grade) {
            $gradesArray = $sessionNormale ? ($grade->grades ?? []) : ($grade->retake_grades ?? []);

            if (isset($gradesArray[$columnIndex])) {
                array_splice($gradesArray, $columnIndex, 1);
            }

            if ($sessionNormale) {
                $grade->grades = $gradesArray;
            } else {
                $grade->retake_grades = $gradesArray;
            }

            // Recalcule la moyenne
            $pond = $this->calculationService->getBalancedPonderation(count($gradesArray));
            $moyenne = $this->calculationService->calculateMoyennePonderee($gradesArray, $pond);

            if ($sessionNormale) {
                $grade->average = $moyenne;
                $grade->validated = $this->calculationService->isValidated($moyenne, $grade->retake_average);
                $grade->must_retake = $this->calculationService->mustRetake($moyenne);
            } else {
                $grade->retake_average = $moyenne;
                $grade->validated = $this->calculationService->isValidated($grade->average, $moyenne);
                $grade->must_retake = $this->calculationService->mustRetake($moyenne);
            }

            $grade->save();

            if (count($gradesArray) > $maxNotes) {
                $maxNotes = count($gradesArray);
            }
        }

        // Met à jour la pondération du programme
        $newPond = $this->calculationService->getBalancedPonderation($maxNotes);
        if ($sessionNormale) {
            $program->weighting = $newPond;
        } else {
            $program->retake_weighting = $newPond;
        }
        $program->save();

        return true;
    }

    /**
     * Définit une pondération manuelle et recalcule toutes les moyennes
     * 
     * @param int $programId
     * @param array $pond
     * @param bool $sessionNormale
     * @return bool
     */
    public function setPonderation(int $programId, array $pond, bool $sessionNormale = true): bool
    {
        // Vérifie que la somme fait 100
        if (array_sum($pond) !== 100) {
            throw new \InvalidArgumentException('La somme de la pondération doit faire 100%');
        }

        $program = Program::findOrFail($programId);
        
        if ($sessionNormale) {
            $program->weighting = $pond;
        } else {
            $program->retake_weighting = $pond;
        }
        $program->save();

        // Recalcule toutes les moyennes
        return $this->recalculateAllAverages($programId, $sessionNormale);
    }

    /**
     * Recalcule les moyennes de tous les étudiants d'un programme
     * 
     * @param int $programId
     * @param bool $sessionNormale
     * @return bool
     */
    private function recalculateAllAverages(int $programId, bool $sessionNormale = true): bool
    {
        $program = Program::findOrFail($programId);
        $pond = $sessionNormale ? ($program->weighting ?? []) : ($program->retake_weighting ?? []);
        $grades = LmdSystemGrade::where('program_id', $programId)->get();

        foreach ($grades as $grade) {
            $gradesArray = $sessionNormale ? ($grade->grades ?? []) : ($grade->retake_grades ?? []);
            
            if (count($gradesArray) === count($pond) && count($pond) > 0) {
                $moyenne = $this->calculationService->calculateMoyennePonderee($gradesArray, $pond);
                
                if ($sessionNormale) {
                    $grade->average = $moyenne;
                    $grade->validated = $this->calculationService->isValidated($moyenne, $grade->retake_average);
                    $grade->must_retake = $this->calculationService->mustRetake($moyenne);
                } else {
                    $grade->retake_average = $moyenne;
                    $grade->validated = $this->calculationService->isValidated($grade->average, $moyenne);
                    $grade->must_retake = $this->calculationService->mustRetake($moyenne);
                }
                
                $grade->save();
            }
        }

        return true;
    }
}
