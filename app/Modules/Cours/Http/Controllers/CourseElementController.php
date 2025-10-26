<?php

namespace App\Modules\Cours\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Cours\Models\CourseElement;
use App\Modules\Cours\Http\Requests\CreateCourseElementRequest;
use App\Modules\Cours\Http\Requests\UpdateCourseElementRequest;
use App\Modules\Cours\Http\Resources\CourseElementResource;
use App\Modules\Cours\Services\CourseElementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class CourseElementController extends Controller
{
    public function __construct(
        protected CourseElementService $courseElementService
    ) {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = $request->only(['search', 'teaching_unit_id', 'credits', 'sort_by', 'sort_order']);
            $perPage = min(max((int) $request->input('per_page', 15), 1), 100);
            
            $courseElements = $this->courseElementService->getAll($filters, $perPage);

            return response()->json([
                'success' => true,
                'data' => CourseElementResource::collection($courseElements),
                'meta' => [
                    'total' => $courseElements->total(),
                    'per_page' => $courseElements->perPage(),
                    'current_page' => $courseElements->currentPage(),
                    'last_page' => $courseElements->lastPage(),
                ],
            ], 200);

        } catch (Exception $e) {
            Log::error('Erreur lors de la récupération des éléments de cours', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des éléments de cours.',
            ], 500);
        }
    }

    public function store(CreateCourseElementRequest $request): JsonResponse
    {
        try {
            $courseElement = $this->courseElementService->create($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Élément de cours créé avec succès.',
                'data' => new CourseElementResource($courseElement->load('teachingUnit')),
            ], 201);

        } catch (Exception $e) {
            Log::error('Erreur lors de la création de l\'élément de cours', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de l\'élément de cours.',
            ], 500);
        }
    }

    public function show(CourseElement $courseElement): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new CourseElementResource($courseElement->load(['teachingUnit', 'resources.file'])),
        ], 200);
    }

    public function update(UpdateCourseElementRequest $request, CourseElement $courseElement): JsonResponse
    {
        try {
            $courseElement = $this->courseElementService->update($courseElement, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Élément de cours mis à jour avec succès.',
                'data' => new CourseElementResource($courseElement),
            ], 200);

        } catch (Exception $e) {
            Log::error('Erreur lors de la mise à jour de l\'élément de cours', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de l\'élément de cours.',
            ], 500);
        }
    }

    public function destroy(CourseElement $courseElement): JsonResponse
    {
        try {
            $this->courseElementService->delete($courseElement);

            return response()->json([
                'success' => true,
                'message' => 'Élément de cours supprimé avec succès.',
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de l\'élément de cours.',
            ], 500);
        }
    }

    /**
     * Attacher un professeur à un élément de cours
     */
    public function attachProfessor(Request $request, CourseElement $courseElement): JsonResponse
    {
        try {
            $request->validate([
                'professor_id' => 'required|exists:professors,id',
            ]);

            $this->courseElementService->attachProfessor($courseElement, $request->professor_id);

            return response()->json([
                'success' => true,
                'message' => 'Professeur attaché avec succès.',
            ], 200);

        } catch (Exception $e) {
            Log::error('Erreur lors de l\'attachement du professeur', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'attachement du professeur.',
            ], 500);
        }
    }

    /**
     * Détacher un professeur d'un élément de cours
     */
    public function detachProfessor(Request $request, CourseElement $courseElement): JsonResponse
    {
        try {
            $request->validate([
                'professor_id' => 'required|exists:professors,id',
            ]);

            $this->courseElementService->detachProfessor($courseElement, $request->professor_id);

            return response()->json([
                'success' => true,
                'message' => 'Professeur détaché avec succès.',
            ], 200);

        } catch (Exception $e) {
            Log::error('Erreur lors du détachement du professeur', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du détachement du professeur.',
            ], 500);
        }
    }
}
