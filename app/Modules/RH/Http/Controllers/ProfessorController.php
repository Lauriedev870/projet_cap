<?php

namespace App\Modules\RH\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\RH\Models\Professor;
use App\Modules\RH\Http\Requests\CreateProfessorRequest;
use App\Modules\RH\Http\Requests\UpdateProfessorRequest;
use App\Modules\RH\Http\Resources\ProfessorResource;
use App\Modules\RH\Services\ProfessorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class ProfessorController extends Controller
{
    public function __construct(
        protected ProfessorService $professorService
    ) {
        $this->middleware('auth:sanctum');
    }

    /**
     * Liste des professeurs avec recherche et filtres
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $filters = $request->only(['search', 'status', 'grade_id', 'sort_by', 'sort_order']);
            $perPage = min(max((int) $request->input('per_page', 15), 1), 100);
            
            $professors = $this->professorService->getAll($filters, $perPage);

            return response()->json([
                'success' => true,
                'data' => ProfessorResource::collection($professors),
                'meta' => [
                    'total' => $professors->total(),
                    'per_page' => $professors->perPage(),
                    'current_page' => $professors->currentPage(),
                    'last_page' => $professors->lastPage(),
                    'from' => $professors->firstItem(),
                    'to' => $professors->lastItem(),
                ],
            ], 200);

        } catch (Exception $e) {
            Log::error('Erreur lors de la récupération des professeurs', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des professeurs.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Créer un nouveau professeur
     */
    public function store(CreateProfessorRequest $request): JsonResponse
    {
        try {
            $data = $request->except(['rib', 'ifu']);
            $ribFile = $request->file('rib');
            $ifuFile = $request->file('ifu');

            $professor = $this->professorService->create(
                $data,
                $ribFile,
                $ifuFile,
                auth()->id()
            );

            return response()->json([
                'success' => true,
                'message' => 'Professeur créé avec succès.',
                'data' => new ProfessorResource($professor->load('grade')),
            ], 201);

        } catch (Exception $e) {
            Log::error('Erreur lors de la création du professeur', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du professeur.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Afficher un professeur
     */
    public function show(Professor $professor): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new ProfessorResource($professor->load('grade')),
        ], 200);
    }

    /**
     * Mettre à jour un professeur
     */
    public function update(UpdateProfessorRequest $request, Professor $professor): JsonResponse
    {
        try {
            $data = $request->except(['rib', 'ifu']);
            $ribFile = $request->file('rib');
            $ifuFile = $request->file('ifu');

            $professor = $this->professorService->update(
                $professor,
                $data,
                $ribFile,
                $ifuFile,
                auth()->id()
            );

            return response()->json([
                'success' => true,
                'message' => 'Professeur mis à jour avec succès.',
                'data' => new ProfessorResource($professor),
            ], 200);

        } catch (Exception $e) {
            Log::error('Erreur lors de la mise à jour du professeur', [
                'professor_id' => $professor->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du professeur.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Supprimer un professeur
     */
    public function destroy(Professor $professor): JsonResponse
    {
        try {
            $this->professorService->delete($professor);

            return response()->json([
                'success' => true,
                'message' => 'Professeur supprimé avec succès.',
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du professeur.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }
}
