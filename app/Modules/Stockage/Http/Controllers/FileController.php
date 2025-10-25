<?php

namespace App\Modules\Stockage\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Stockage\Models\File;
use App\Modules\Stockage\Services\FileStorageService;
use App\Modules\Stockage\Services\PermissionService;
use App\Modules\Stockage\Http\Requests\UploadFileRequest;
use App\Modules\Stockage\Http\Requests\UpdateFileRequest;
use App\Modules\Stockage\Http\Resources\FileResource;
use App\Modules\Stockage\Http\Resources\FileActivityResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FileController extends Controller
{
    public function __construct(
        protected FileStorageService $storageService,
        protected PermissionService $permissionService
    ) {
        $this->middleware('auth:sanctum');
    }

    /**
     * Récupère la liste des fichiers accessibles par l'utilisateur.
     */
    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['collection', 'visibility', 'module_name', 'search']);
        
        $files = $this->permissionService->getAccessibleFiles(Auth::id(), $filters);

        return response()->json([
            'success' => true,
            'data' => FileResource::collection($files),
            'meta' => [
                'total' => $files->count(),
            ],
        ]);
    }

    /**
     * Upload un nouveau fichier.
     */
    public function store(UploadFileRequest $request): JsonResponse
    {
        try {
            $file = $this->storageService->uploadFile(
                uploadedFile: $request->file('file'),
                userId: Auth::id(),
                visibility: $request->input('visibility', 'private'),
                collection: $request->input('collection', 'default'),
                moduleName: $request->input('module_name'),
                moduleResourceType: $request->input('module_resource_type'),
                moduleResourceId: $request->input('module_resource_id'),
                metadata: $request->input('metadata', [])
            );

            return response()->json([
                'success' => true,
                'message' => 'Fichier uploadé avec succès.',
                'data' => new FileResource($file),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'upload du fichier.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Affiche les détails d'un fichier.
     */
    public function show(File $file): JsonResponse
    {
        $this->authorize('view', $file);

        return response()->json([
            'success' => true,
            'data' => new FileResource($file),
        ]);
    }

    /**
     * Met à jour un fichier.
     */
    public function update(UpdateFileRequest $request, File $file): JsonResponse
    {
        $this->authorize('update', $file);

        try {
            if ($request->has('collection')) {
                $file = $this->storageService->moveToCollection(
                    $file,
                    $request->input('collection'),
                    Auth::id()
                );
            }

            if ($request->has('metadata')) {
                $file->update(['metadata' => $request->input('metadata')]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Fichier mis à jour avec succès.',
                'data' => new FileResource($file->fresh()),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour du fichier.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Supprime un fichier (soft delete).
     */
    public function destroy(File $file): JsonResponse
    {
        $this->authorize('delete', $file);

        try {
            $this->storageService->deleteFile($file, Auth::id());

            return response()->json([
                'success' => true,
                'message' => 'Fichier supprimé avec succès.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du fichier.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Télécharge un fichier.
     */
    public function download(File $file)
    {
        $this->authorize('download', $file);

        try {
            $download = $this->storageService->downloadFile($file, Auth::id());

            return response()->stream(
                function () use ($download) {
                    echo $download['stream'];
                },
                200,
                [
                    'Content-Type' => $download['mimeType'],
                    'Content-Disposition' => 'attachment; filename="' . $download['filename'] . '"',
                ]
            );
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du téléchargement du fichier.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Change la visibilité d'un fichier.
     */
    public function changeVisibility(Request $request, File $file): JsonResponse
    {
        $this->authorize('changeVisibility', $file);

        $request->validate([
            'visibility' => 'required|in:public,private',
        ]);

        try {
            $file = $this->storageService->changeVisibility(
                $file,
                $request->input('visibility'),
                Auth::id()
            );

            return response()->json([
                'success' => true,
                'message' => 'Visibilité modifiée avec succès.',
                'data' => new FileResource($file),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du changement de visibilité.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Verrouille un fichier.
     */
    public function lock(File $file): JsonResponse
    {
        $this->authorize('lock', $file);

        try {
            $file = $this->storageService->lockFile($file, Auth::id());

            return response()->json([
                'success' => true,
                'message' => 'Fichier verrouillé avec succès.',
                'data' => new FileResource($file),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du verrouillage.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Déverrouille un fichier.
     */
    public function unlock(File $file): JsonResponse
    {
        $this->authorize('lock', $file);

        try {
            $file = $this->storageService->unlockFile($file, Auth::id());

            return response()->json([
                'success' => true,
                'message' => 'Fichier déverrouillé avec succès.',
                'data' => new FileResource($file),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors du déverrouillage.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Récupère l'historique des activités d'un fichier.
     */
    public function activities(File $file): JsonResponse
    {
        $this->authorize('view', $file);

        $activities = $file->activities()->with('user')->orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => FileActivityResource::collection($activities),
        ]);
    }

    /**
     * Récupère les fichiers publics.
     */
    public function publicFiles(Request $request): JsonResponse
    {
        $filters = $request->only(['collection', 'module_name', 'search']);
        
        $files = $this->storageService->getPublicFiles($filters);

        return response()->json([
            'success' => true,
            'data' => FileResource::collection($files),
            'meta' => [
                'total' => $files->count(),
            ],
        ]);
    }
}
