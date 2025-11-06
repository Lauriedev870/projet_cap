<?php

namespace App\Modules\Inscription\Services;

use App\Modules\Inscription\Models\ClassGroup;
use App\Modules\Inscription\Models\StudentGroup;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ClassGroupService
{
    /**
     * Récupérer les groupes d'une classe
     */
    public function getGroups($academicYearId, $departmentId, $studyLevel)
    {
        $query = ClassGroup::with(['studentGroups.student'])
            ->where('academic_year_id', $academicYearId)
            ->where('department_id', $departmentId)
            ->where('study_level', $studyLevel)
            ->orderBy('group_name');

        return $query->get()->map(function ($group) {
            return [
                'id' => $group->id,
                'group_name' => $group->group_name,
                'students_count' => $group->studentGroups->count(),
                'students' => $group->studentGroups->map(function ($sg) {
                    return [
                        'id' => $sg->student->id,
                        'matricule' => $sg->student->matricule,
                        'nom_prenoms' => $sg->student->nom . ' ' . $sg->student->prenoms,
                    ];
                }),
            ];
        });
    }

    /**
     * Créer des groupes pour une classe
     */
    public function createGroups(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Supprimer les anciens groupes si demandé
            if ($data['replace_existing'] ?? false) {
                $this->deleteAllGroups(
                    $data['academic_year_id'],
                    $data['department_id'],
                    $data['study_level']
                );
            }

            $createdGroups = [];

            // Créer chaque groupe avec ses étudiants
            foreach ($data['groups'] as $groupData) {
                $classGroup = ClassGroup::create([
                    'uuid' => (string) Str::uuid(),
                    'academic_year_id' => $data['academic_year_id'],
                    'department_id' => $data['department_id'],
                    'study_level' => $data['study_level'],
                    'group_name' => $groupData['name'],
                ]);

                // Ajouter les étudiants au groupe
                foreach ($groupData['student_ids'] as $studentId) {
                    StudentGroup::create([
                        'uuid' => (string) Str::uuid(),
                        'class_group_id' => $classGroup->id,
                        'student_id' => $studentId,
                    ]);
                }

                $createdGroups[] = [
                    'id' => $classGroup->id,
                    'group_name' => $classGroup->group_name,
                    'students_count' => count($groupData['student_ids']),
                ];
            }

            Log::info('Groupes de classe créés', [
                'academic_year_id' => $data['academic_year_id'],
                'department_id' => $data['department_id'],
                'study_level' => $data['study_level'],
                'groups_count' => count($createdGroups),
            ]);

            return $createdGroups;
        });
    }

    /**
     * Récupérer les détails d'un groupe
     */
    public function getGroupDetails(ClassGroup $classGroup)
    {
        $classGroup->load(['studentGroups.student', 'academicYear', 'department']);

        return [
            'id' => $classGroup->id,
            'group_name' => $classGroup->group_name,
            'academic_year' => $classGroup->academicYear->academic_year,
            'department' => $classGroup->department->name,
            'study_level' => $classGroup->study_level,
            'students_count' => $classGroup->studentGroups->count(),
            'students' => $classGroup->studentGroups->map(function ($sg) {
                return [
                    'id' => $sg->student->id,
                    'matricule' => $sg->student->matricule,
                    'nom_prenoms' => $sg->student->nom . ' ' . $sg->student->prenoms,
                    'sexe' => $sg->student->sexe,
                ];
            }),
        ];
    }

    /**
     * Supprimer un groupe
     */
    public function deleteGroup(ClassGroup $classGroup): bool
    {
        try {
            $classGroup->delete();

            Log::info('Groupe de classe supprimé', [
                'class_group_id' => $classGroup->id,
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Erreur suppression groupe de classe', [
                'class_group_id' => $classGroup->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Supprimer tous les groupes d'une classe
     */
    public function deleteAllGroups($academicYearId, $departmentId, $studyLevel): int
    {
        return DB::transaction(function () use ($academicYearId, $departmentId, $studyLevel) {
            $deleted = ClassGroup::where('academic_year_id', $academicYearId)
                ->where('department_id', $departmentId)
                ->where('study_level', $studyLevel)
                ->delete();

            Log::info('Tous les groupes de classe supprimés', [
                'academic_year_id' => $academicYearId,
                'department_id' => $departmentId,
                'study_level' => $studyLevel,
                'deleted_count' => $deleted,
            ]);

            return $deleted;
        });
    }
}
