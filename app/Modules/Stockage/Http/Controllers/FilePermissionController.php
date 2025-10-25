<?php

namespace App\Modules\Stockage\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Stockage\Models\File;
use App\Modules\Stockage\Services\PermissionService;
use App\Modules\Stockage\Http\Requests\GrantPermissionRequest;
use App\Modules\Stockage\Http\Resources\FilePermissionResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FilePermissionController extends Controller
{
    public function __construct(
        protected PermissionService $permissionService
    ) {
        $this->middleware('auth:sanctum');
    }

    /**
     * Récupère toutes les permissions d'un fichier.
     */
    public function index(File $file): JsonResponse
    {
        $this->authorize('managePermissions', $file);

        $permissions = $this->permissionService->getFilePermissions($file);

        return response()->json([
            'success' => true,
            'data' => FilePermissionResource::collection($permissions),
        ]);
    }

    /**
     * Accorde une permission.
     */
    public function grant(GrantPermissionRequest $request, File $file): JsonResponse
    {
        $this->authorize('managePermissions', $file);

        try {
            $expiresAt = $request->input('expires_at') 
                ? new \DateTime($request->input('expires_at'))
                : null;

            if ($request->input('user_id')) {
                $permission = $this->permissionService->grantUserPermission(
                    $file,
                    $request->input('user_id'),
                    $request->input('permission_type'),
                    Auth::id(),
                    $expiresAt
                );
            } else {
                $permission = $this->permissionService->grantRolePermission(
                    $file,
                    $request->input('role_id'),
                    $request->input('permission_type'),
                    Auth::id(),
                    $expiresAt
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Permission accordée avec succès.',
                'data' => new FilePermissionResource($permission),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'attribution de la permission.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Révoque une permission.
     */
    public function revoke(Request $request, File $file): JsonResponse
    {
        $this->authorize('managePermissions', $file);

        $request->validate([
            'user_id' => 'nullable|integer|exists:users,id',
            'role_id' => 'nullable|integer|exists:roles,id',
            'permission_type' => 'required|in:read,write,delete,share,admin',
        ]);

        try {
            if ($request->input('user_id')) {
                $success = $this->permissionService->revokeUserPermission(
                    $file,
                    $request->input('user_id'),
                    $request->input('permission_type'),
                    Auth::id()
                );
            } else {
                $success = $this->permissionService->revokeRolePermission(
                    $file,
                    $request->input('role_id'),
                    $request->input('permission_type'),
                    Auth::id()
                );
            }

            if ($success) {
                return response()->json([
                    'success' => true,
                    'message' => 'Permission révoquée avec succès.',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Permission non trouvée.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la révocation de la permission.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Vérifie si l'utilisateur a une permission spécifique sur un fichier.
     */
    public function check(Request $request, File $file): JsonResponse
    {
        $request->validate([
            'permission_type' => 'required|in:read,write,delete,share,admin',
        ]);

        $hasPermission = $this->permissionService->userCan(
            $file,
            Auth::id(),
            $request->input('permission_type')
        );

        return response()->json([
            'success' => true,
            'data' => [
                'has_permission' => $hasPermission,
                'permission_type' => $request->input('permission_type'),
            ],
        ]);
    }
}
