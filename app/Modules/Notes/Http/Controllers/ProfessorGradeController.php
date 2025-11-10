<?php

namespace App\Modules\Notes\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Notes\Services\LmdGradeService;
use App\Modules\Notes\Services\OldGradeService;
use App\Modules\Cours\Models\Program;
use App\Modules\Inscription\Models\ClassGroup;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfessorGradeController extends Controller
{
    use ApiResponse;

    private LmdGradeService $lmdGradeService;
    private OldGradeService $oldGradeService;

    public function __construct(LmdGradeService $lmdGradeService, OldGradeService $oldGradeService)
    {
        $this->lmdGradeService = $lmdGradeService;
        $this->oldGradeService = $oldGradeService;
    }

    /**
     * Obtient les classes d'un professeur pour un semestre donné
     * 
     * GET /api/notes/professor/my-classes-by-semester/{semester}
     */
    public function getClassesBySemester(Request $request, int $semester): JsonResponse
    {
        $professorId = $request->user()->professor_id ?? $request->input('professor_id');

        if (!$professorId) {
            return $this->notFoundResponse('Professeur non trouvé');
        }

        // Récupérer l'année académique courante
        $academicYear = \App\Modules\Inscription\Models\AcademicYear::latest('start_date')->first();

        if (!$academicYear) {
            return $this->successResponse([
                'classes' => [],
                'semester' => $semester,
            ], 'Aucune année académique trouvée');
        }

        // Récupérer les classes où le professeur a des programmes pour ce semestre
        $classes = ClassGroup::where('academic_year_id', $academicYear->id)
            ->whereHas('programs', function ($query) use ($professorId, $semester) {
                $query->whereHas('courseElementProfessor', function ($subQuery) use ($professorId) {
                    $subQuery->where('professor_id', $professorId);
                });
                // TODO: Ajouter filtre semestre si le champ existe dans programs
            })
            ->with(['department', 'cycle'])
            ->get();

        return $this->successResponse([
            'classes' => $classes,
            'semester' => $semester,
            'academic_year' => $academicYear,
        ], 'Classes récupérées avec succès');
    }

    /**
     * Obtient les programmes d'une classe pour un professeur
     * 
     * GET /api/notes/professor/programs-by-class/{class_group_id}
     */
    public function getProgramsByClass(Request $request, int $classGroupId): JsonResponse
    {
        $professorId = $request->user()->professor_id ?? $request->input('professor_id');

        if (!$professorId) {
            return $this->notFoundResponse('Professeur non trouvé');
        }

        $classGroup = ClassGroup::with(['department', 'cycle'])->findOrFail($classGroupId);

        $programs = Program::where('class_group_id', $classGroupId)
            ->whereHas('courseElementProfessor', function ($query) use ($professorId) {
                $query->where('professor_id', $professorId);
            })
            ->with(['courseElementProfessor.courseElement', 'courseElementProfessor.professor'])
            ->get();

        return $this->successResponse([
            'class_group' => $classGroup,
            'programs' => $programs->map(function ($program) {
                return [
                    'id' => $program->id,
                    'uuid' => $program->uuid,
                    'course_name' => $program->courseElementProfessor->courseElement->name ?? 'N/A',
                    'professor_name' => $program->courseElementProfessor->professor->name ?? 'N/A',
                    'weighting' => $program->weighting ?? [],
                    'column_count' => count($program->weighting ?? []),
                ];
            }),
        ], 'Programmes récupérés avec succès');
    }

    /**
     * Obtient la fiche de notation pour un programme (avec les étudiants)
     * Détecte automatiquement si c'est LMD ou ancien système
     * 
     * GET /api/notes/professor/students-by-program/{program_uuid}
     */
    public function getStudentsByProgram(Request $request, string $programUuid): JsonResponse
    {
        $program = Program::where('uuid', $programUuid)
            ->with(['classGroup.cycle', 'courseElementProfessor.courseElement', 'courseElementProfessor.professor'])
            ->firstOrFail();

        // Détecte si c'est LMD ou ancien système
        $isLmd = $program->classGroup->cycle->is_lmd ?? false;

        if ($isLmd) {
            $students = $this->lmdGradeService->getStudentsByProgram($program);
        } else {
            $students = $this->oldGradeService->getStudentsByProgram($program);
        }

        return $this->successResponse([
            'program' => [
                'id' => $program->id,
                'uuid' => $program->uuid,
                'name' => $program->courseElementProfessor->courseElement->name ?? 'N/A',
                'class_group' => [
                    'id' => $program->classGroup->id,
                    'name' => $program->classGroup->name ?? 'N/A',
                    'level' => $program->classGroup->level ?? 'N/A',
                ],
                'is_lmd' => $isLmd,
                'weighting' => $program->weighting ?? [],
                'retake_weighting' => $program->retake_weighting ?? [],
                'column_count' => count($program->weighting ?? []),
            ],
            'students' => $students,
            'total_students' => $students->count(),
        ], 'Étudiants récupérés avec succès');
    }
}
