<?php

namespace App\Modules\Cours\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Cours\Models\TeachingUnit;
use App\Modules\Cours\Http\Requests\CreateTeachingUnitRequest;
use App\Modules\Cours\Http\Requests\UpdateTeachingUnitRequest;
use App\Modules\Cours\Http\Resources\TeachingUnitResource;
use App\Modules\Cours\Services\TeachingUnitService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class TeachingUnitController extends Controller
{
    public function __construct(
        protected TeachingUnitService $teachingUnitService
    ) {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = $request->only(['search', 'sort_by', 'sort_order']);
            $perPage = min(max((int) $request->input('per_page', 15), 1), 100);
            
            $teachingUnits = $this->teachingUnitService->getAll($filters, $perPage);

            return response()->json([
                'success' => true,
                'data' => TeachingUnitResource::collection($teachingUnits),
                'meta' => [
                    'total' => $teachingUnits->total(),
                    'per_page' => $teachingUnits->perPage(),
                    'current_page' => $teachingUnits->currentPage(),
                    'last_page' => $teachingUnits->lastPage(),
                ],
            ], 200);

        } catch (Exception $e) {
            Log::error('Erreur lors de la récupération des unités d\'enseignement', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des unités d\'enseignement.',
            ], 500);
        }
    }

    public function store(CreateTeachingUnitRequest $request): JsonResponse
    {
        try {
            $teachingUnit = $this->teachingUnitService->create($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Unité d\'enseignement créée avec succès.',
                'data' => new TeachingUnitResource($teachingUnit),
            ], 201);

        } catch (Exception $e) {
            Log::error('Erreur lors de la création de l\'unité d\'enseignement', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de l\'unité d\'enseignement.',
            ], 500);
        }
    }

    public function show(TeachingUnit $teachingUnit): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new TeachingUnitResource($teachingUnit->load('courseElements')),
        ], 200);
    }

    public function update(UpdateTeachingUnitRequest $request, TeachingUnit $teachingUnit): JsonResponse
    {
        try {
            $teachingUnit = $this->teachingUnitService->update($teachingUnit, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Unité d\'enseignement mise à jour avec succès.',
                'data' => new TeachingUnitResource($teachingUnit),
            ], 200);

        } catch (Exception $e) {
            Log::error('Erreur lors de la mise à jour de l\'unité d\'enseignement', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de l\'unité d\'enseignement.',
            ], 500);
        }
    }

    public function destroy(TeachingUnit $teachingUnit): JsonResponse
    {
        try {
            $this->teachingUnitService->delete($teachingUnit);

            return response()->json([
                'success' => true,
                'message' => 'Unité d\'enseignement supprimée avec succès.',
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de l\'unité d\'enseignement.',
            ], 500);
        }
    }
}
