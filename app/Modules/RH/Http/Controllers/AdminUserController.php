<?php

namespace App\Modules\RH\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\RH\Http\Requests\CreateAdminUserRequest;
use App\Modules\RH\Http\Requests\UpdateAdminUserRequest;
use App\Modules\RH\Http\Resources\AdminUserResource;
use App\Modules\RH\Services\AdminUserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class AdminUserController extends Controller
{
    public function __construct(
        protected AdminUserService $adminUserService
    ) {
        $this->middleware('auth:sanctum');
    }

    /**
     * Liste des utilisateurs administratifs
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $filters = $request->only(['search', 'role_id', 'sort_by', 'sort_order']);
            $perPage = min(max((int) $request->input('per_page', 15), 1), 100);
            
            $users = $this->adminUserService->getAll($filters, $perPage);

            return response()->json([
                'success' => true,
                'data' => AdminUserResource::collection($users),
                'meta' => [
                    'total' => $users->total(),
                    'per_page' => $users->perPage(),
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                    'from' => $users->firstItem(),
                    'to' => $users->lastItem(),
                ],
            ], 200);

        } catch (Exception $e) {
            Log::error('Erreur lors de la récupération des utilisateurs', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des utilisateurs.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Créer un nouvel utilisateur administratif
     */
    public function store(CreateAdminUserRequest $request): JsonResponse
    {
        try {
            $data = $request->except(['rib', 'ifu', 'photo']);
            
            $user = $this->adminUserService->create(
                $data,
                $request->file('rib'),
                $request->file('ifu'),
                $request->file('photo'),
                auth()->id()
            );

            return response()->json([
                'success' => true,
                'message' => 'Utilisateur créé avec succès.',
                'data' => new AdminUserResource($user->load('roles')),
            ], 201);

        } catch (Exception $e) {
            Log::error('Erreur lors de la création de l\'utilisateur', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de l\'utilisateur.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Afficher un utilisateur
     */
    public function show(User $adminUser): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new AdminUserResource($adminUser->load('roles')),
        ], 200);
    }

    /**
     * Mettre à jour un utilisateur
     */
    public function update(UpdateAdminUserRequest $request, User $adminUser): JsonResponse
    {
        try {
            $data = $request->except(['rib', 'ifu', 'photo']);
            
            $user = $this->adminUserService->update(
                $adminUser,
                $data,
                $request->file('rib'),
                $request->file('ifu'),
                $request->file('photo'),
                auth()->id()
            );

            return response()->json([
                'success' => true,
                'message' => 'Utilisateur mis à jour avec succès.',
                'data' => new AdminUserResource($user),
            ], 200);

        } catch (Exception $e) {
            Log::error('Erreur lors de la mise à jour de l\'utilisateur', [
                'user_id' => $adminUser->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour de l\'utilisateur.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Supprimer un utilisateur
     */
    public function destroy(User $adminUser): JsonResponse
    {
        try {
            $this->adminUserService->delete($adminUser);

            return response()->json([
                'success' => true,
                'message' => 'Utilisateur supprimé avec succès.',
            ], 200);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression de l\'utilisateur.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Attacher un rôle à un utilisateur
     */
    public function attachRole(Request $request, User $adminUser): JsonResponse
    {
        try {
            $request->validate([
                'role_id' => 'required|exists:roles,id',
            ]);

            $this->adminUserService->attachRole($adminUser, $request->role_id);

            return response()->json([
                'success' => true,
                'message' => 'Rôle attaché avec succès.',
            ], 200);

        } catch (Exception $e) {
            Log::error('Erreur lors de l\'attachement du rôle', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'attachement du rôle.',
            ], 500);
        }
    }

    /**
     * Détacher un rôle d'un utilisateur
     */
    public function detachRole(Request $request, User $adminUser): JsonResponse
    {
        try {
            $request->validate([
                'role_id' => 'required|exists:roles,id',
            ]);

            $this->adminUserService->detachRole($adminUser, $request->role_id);

            return response()->json([
                'success' => true,
                'message' => 'Rôle détaché avec succès.',
            ], 200);

        } catch (Exception $e) {
            Log::error('Erreur lors du détachement du rôle', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du détachement du rôle.',
            ], 500);
        }
    }

    /**
     * Récupérer les statistiques des utilisateurs
     */
    public function statistics(): JsonResponse
    {
        try {
            $stats = $this->adminUserService->getStatistics();

            return response()->json([
                'success' => true,
                'data' => $stats,
            ], 200);

        } catch (Exception $e) {
            Log::error('Erreur lors de la récupération des statistiques', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des statistiques.',
            ], 500);
        }
    }
}
