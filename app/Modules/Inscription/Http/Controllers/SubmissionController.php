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

class SubmissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * Récupérer les périodes de soumission actives.
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
     * Récupérer les périodes de réclamation actives.
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
     * Vérifier si la soumission est ouverte pour une année académique.
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
     * Vérifier si la réclamation est ouverte pour une année académique.
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
     * Liste des années académiques.
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
     * Détails d'une année académique.
     */
    public function getAcademicYear(AcademicYear $academicYear): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new AcademicYearResource($academicYear->load(['submissionPeriods', 'reclamationPeriods'])),
        ]);
    }
}
