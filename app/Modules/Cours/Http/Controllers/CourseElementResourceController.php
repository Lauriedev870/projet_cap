<?php

namespace App\Modules\Cours\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Cours\Models\CourseElementResource as CourseResource;
use App\Modules\Cours\Http\Requests\CreateCourseElementResourceRequest;
use App\Modules\Cours\Http\Resources\CourseElementResourceResource;
use App\Modules\Cours\Services\CourseElementResourceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class CourseElementResourceController extends Controller
{
    public function __construct(
        protected CourseElementResourceService $resourceService
    ) {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $filters = $request->only(['course_element_id', 'resource_type', 'sort_by', 'sort_order']);
            if ($request->filled('is_public')) {
                $filters['is_public'] = $request->boolean('is_public');
            }
            $perPage = min(max((int) $request->input('per_page', 15), 1), 100);
            
            $resources = $this->resourceService->getAll($filters, $perPage);

            return response()->json([
                'success' => true,
                'data' => CourseElementResourceResource::collection($resources),
                'meta' => [
                    'total' => $resources->total(),
                    'per_page' => $resources->perPage(),
                    'current_page' => $resources->currentPage(),
                    'last_page' => $resources->lastPage(),
                ],
            ], 200);

        } catch (Exception $e) {
            Log::error('Erreur lors de la récupération des ressources', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des ressources.',
            ], 500);
        }
    }

    public function store(CreateCourseElementResourceRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();
            $resource = $this->resourceService->create($data, $request->file('file'), auth()->id());

            return response()->json([
                'success' => true,
                'message' => 'Ressource créée avec succès.',
                'data' => new CourseElementResourceResource($resource->load('file')),
            ], 201);

        } catch (Exception $e) {
            Log::error('Erreur lors de la création de la ressource', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de la ressource.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function show(CourseResource $courseElementResource): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new CourseElementResourceResource($courseElementResource->load(['courseElement', 'file'])),
        ], 200);
    }

    public function update(Request $request, CourseResource $courseElementResource): JsonResponse
    {
        try {
            $data = $request->validate([
                'title' => 'sometimes|required|string|max:255',
                'description' => 'nullable|string',
                'is_public' => 'nullable|boolean',
            ]);

            $resource = $this->resourceService->update($courseElementResource, $data);

            return response()->json([
                'success' => true,
                'message' => 'Ressource mise à jour avec succès.',
                'data' => new CourseElementResourceResource($resource),
            ], 200);

        } catch (Exception $e) {
            Log::error('Erreur lors de la mise à jour de la ressource', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de la ressource.',
            ], 500);
        }
    }

    public function destroy(CourseResource $courseElementResource): JsonResponse
    {
        try {
            $this->resourceService->delete($courseElementResource, auth()->id());

            return response()->json([
                'success' => true,
                'message' => 'Ressource supprimée avec succès.',
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de la ressource.',
            ], 500);
        }
    }
}
