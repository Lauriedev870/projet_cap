<?php

namespace App\Modules\Inscription\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Inscription\Models\SubmissionPeriod;
use App\Modules\Inscription\Models\ReclamationPeriod;
use App\Modules\Inscription\Models\AcademicYear;
use App\Modules\Inscription\Http\Resources\SubmissionPeriodResource;
use App\Modules\Inscription\Http\Resources\ReclamationPeriodResource;
use App\Modules\Inscription\Http\Resources\AcademicYearResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Submission Management",
 *     description="Gestion des périodes de soumission et réclamation"
 * )
 */

class SubmissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * @OA\Get(
     *     path="/api/submissions/active-periods",
     *     summary="Périodes de soumission actives",
     *     description="Récupère la liste des périodes de soumission actuellement actives",
     *     operationId="getActiveSubmissionPeriods",
     *     tags={"Submission Management"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Périodes récupérées avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/SubmissionPeriod"))
     *         )
     *     ),
     *     @OA\Response(response=401, description="Non authentifié")
     * )
     */
    public function getActiveSubmissionPeriods(): JsonResponse
    {
        $activePeriods = SubmissionPeriod::with(['academicYear'])
            ->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->get();

        return response()->json([
            'success' => true,
            'data' => SubmissionPeriodResource::collection($activePeriods),
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/submissions/active-reclamation-periods",
     *     summary="Périodes de réclamation actives",
     *     description="Récupère la liste des périodes de réclamation actuellement actives",
     *     operationId="getActiveReclamationPeriods",
     *     tags={"Submission Management"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Périodes récupérées avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/ReclamationPeriod"))
     *         )
     *     ),
     *     @OA\Response(response=401, description="Non authentifié")
     * )
     */
    public function getActiveReclamationPeriods(): JsonResponse
    {
        $activePeriods = ReclamationPeriod::with(['academicYear'])
            ->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->get();

        return response()->json([
            'success' => true,
            'data' => ReclamationPeriodResource::collection($activePeriods),
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/submissions/check-status",
     *     summary="Vérifier le statut de soumission",
     *     description="Vérifie si la soumission est ouverte pour une année académique donnée",
     *     operationId="checkSubmissionStatus",
     *     tags={"Submission Management"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"academic_year_id"},
     *             @OA\Property(property="academic_year_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Statut vérifié avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="is_open", type="boolean", example=true),
     *                 @OA\Property(property="academic_year", ref="#/components/schemas/AcademicYear"),
     *                 @OA\Property(property="submission_period", ref="#/components/schemas/SubmissionPeriod"),
     *                 @OA\Property(property="current_time", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Non authentifié"),
     *     @OA\Response(response=422, description="Données invalides")
     * )
     */
    public function checkSubmissionStatus(Request $request): JsonResponse
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
        ]);

        $academicYear = AcademicYear::find($request->academic_year_id);

        $isOpen = $academicYear->submission_start <= now() && $academicYear->submission_end >= now();

        $submissionPeriod = SubmissionPeriod::where('academic_year_id', $request->academic_year_id)
            ->where('is_active', true)
            ->first();

        return response()->json([
            'success' => true,
            'data' => [
                'is_open' => $isOpen,
                'academic_year' => new AcademicYearResource($academicYear),
                'submission_period' => $submissionPeriod ? new SubmissionPeriodResource($submissionPeriod) : null,
                'current_time' => now()->toISOString(),
            ],
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/submissions/check-reclamation-status",
     *     summary="Vérifier le statut de réclamation",
     *     description="Vérifie si la réclamation est ouverte pour une année académique donnée",
     *     operationId="checkReclamationStatus",
     *     tags={"Submission Management"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"academic_year_id"},
     *             @OA\Property(property="academic_year_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Statut vérifié avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="is_open", type="boolean", example=true),
     *                 @OA\Property(property="academic_year", ref="#/components/schemas/AcademicYear"),
     *                 @OA\Property(property="reclamation_period", ref="#/components/schemas/ReclamationPeriod"),
     *                 @OA\Property(property="current_time", type="string", format="date-time")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Non authentifié"),
     *     @OA\Response(response=422, description="Données invalides")
     * )
     */
    public function checkReclamationStatus(Request $request): JsonResponse
    {
        $request->validate([
            'academic_year_id' => 'required|exists:academic_years,id',
        ]);

        $academicYear = AcademicYear::find($request->academic_year_id);

        $isOpen = $academicYear->reclamation_start <= now() && $academicYear->reclamation_end >= now();

        $reclamationPeriod = ReclamationPeriod::where('academic_year_id', $request->academic_year_id)
            ->where('is_active', true)
            ->first();

        return response()->json([
            'success' => true,
            'data' => [
                'is_open' => $isOpen,
                'academic_year' => new AcademicYearResource($academicYear),
                'reclamation_period' => $reclamationPeriod ? new ReclamationPeriodResource($reclamationPeriod) : null,
                'current_time' => now()->toISOString(),
            ],
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/academic-years",
     *     summary="Liste des années académiques",
     *     description="Récupère la liste de toutes les années académiques",
     *     operationId="getAcademicYears",
     *     tags={"Submission Management"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Années académiques récupérées avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/AcademicYear"))
     *         )
     *     ),
     *     @OA\Response(response=401, description="Non authentifié")
     * )
     */
    public function getAcademicYears(): JsonResponse
    {
        $academicYears = AcademicYear::orderBy('year_start', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => AcademicYearResource::collection($academicYears),
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/academic-years/{academicYear}",
     *     summary="Détails d'une année académique",
     *     description="Récupère les détails d'une année académique spécifique avec ses périodes",
     *     operationId="getAcademicYear",
     *     tags={"Submission Management"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="academicYear",
     *         in="path",
     *         description="ID de l'année académique",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Année académique récupérée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/AcademicYear")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Non authentifié"),
     *     @OA\Response(response=404, description="Année académique non trouvée")
     * )
     */
    public function getAcademicYear(AcademicYear $academicYear): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new AcademicYearResource($academicYear->load(['submissionPeriods', 'reclamationPeriods'])),
        ]);
    }
}
