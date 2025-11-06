<?php

namespace App\Modules\Inscription\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Inscription\Models\ClassGroup;
use App\Modules\Inscription\Services\ClassGroupService;
use App\Modules\Inscription\Http\Requests\StoreClassGroupRequest;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Class Groups",
 *     description="Gestion des groupes de classe"
 * )
 */
class ClassGroupController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected ClassGroupService $classGroupService
    ) {
        $this->middleware('auth:sanctum');
    }

    /**
     * Liste des groupes pour une classe
     */
    public function index(Request $request): JsonResponse
    {
        $groups = $this->classGroupService->getGroups(
            $request->input('academic_year_id'),
            $request->input('department_id'),
            $request->input('study_level')
        );

        return $this->successResponse($groups, 'Groupes récupérés avec succès');
    }

    /**
     * Créer des groupes pour une classe
     */
    public function store(StoreClassGroupRequest $request): JsonResponse
    {
        $result = $this->classGroupService->createGroups($request->validated());
        
        return $this->createdResponse($result, 'Groupes créés avec succès');
    }

    /**
     * Détails d'un groupe
     */
    public function show(ClassGroup $classGroup): JsonResponse
    {
        $group = $this->classGroupService->getGroupDetails($classGroup);
        
        return $this->successResponse($group, 'Détails du groupe récupérés avec succès');
    }

    /**
     * Supprimer un groupe
     */
    public function destroy(ClassGroup $classGroup): JsonResponse
    {
        $this->classGroupService->deleteGroup($classGroup);
        
        return $this->deletedResponse('Groupe supprimé avec succès');
    }

    /**
     * Supprimer tous les groupes d'une classe
     */
    public function destroyAll(Request $request): JsonResponse
    {
        $deleted = $this->classGroupService->deleteAllGroups(
            $request->input('academic_year_id'),
            $request->input('department_id'),
            $request->input('study_level')
        );
        
        return $this->deletedResponse("$deleted groupe(s) supprimé(s) avec succès");
    }
}
