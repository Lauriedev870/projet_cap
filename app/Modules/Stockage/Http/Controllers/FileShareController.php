<?php

namespace App\Modules\Stockage\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Stockage\Models\File;
use App\Modules\Stockage\Models\FileShare;
use App\Modules\Stockage\Services\FileShareService;
use App\Modules\Stockage\Services\FileStorageService;
use App\Modules\Stockage\Http\Requests\CreateShareRequest;
use App\Modules\Stockage\Http\Resources\FileShareResource;
use App\Modules\Stockage\Http\Resources\FileResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FileShareController extends Controller
{
    public function __construct(
        protected FileShareService $shareService,
        protected FileStorageService $storageService
    ) {
        $this->middleware('auth:sanctum')->except(['access', 'download']);
    }

    /**
     * Récupère tous les partages d'un fichier.
     */
    public function index(File $file): JsonResponse
    {
        $this->authorize('share', $file);

        $shares = $this->shareService->getFileShares($file);

        return response()->json([
            'success' => true,
            'data' => FileShareResource::collection($shares),
        ]);
    }

    /**
     * Crée un nouveau partage.
     */
    public function store(CreateShareRequest $request, File $file): JsonResponse
    {
        $this->authorize('share', $file);

        try {
            $options = [
                'password' => $request->input('password'),
                'allow_download' => $request->input('allow_download', true),
                'allow_preview' => $request->input('allow_preview', true),
                'max_downloads' => $request->input('max_downloads'),
                'expires_at' => $request->input('expires_at') 
                    ? new \DateTime($request->input('expires_at'))
                    : null,
            ];

            $share = $this->shareService->createShare($file, Auth::id(), $options);

            return response()->json([
                'success' => true,
                'message' => 'Lien de partage créé avec succès.',
                'data' => new FileShareResource($share),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création du partage.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Affiche les détails d'un partage.
     */
    public function show(File $file, FileShare $share): JsonResponse
    {
        $this->authorize('share', $file);

        if ($share->file_id !== $file->id) {
            return response()->json([
                'success' => false,
                'message' => 'Ce partage n\'appartient pas à ce fichier.',
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => new FileShareResource($share),
        ]);
    }

    /**
     * Désactive un partage.
     */
    public function deactivate(File $file, FileShare $share): JsonResponse
    {
        $this->authorize('share', $file);

        if ($share->file_id !== $file->id) {
            return response()->json([
                'success' => false,
                'message' => 'Ce partage n\'appartient pas à ce fichier.',
            ], 403);
        }

        try {
            $share = $this->shareService->deactivateShare($share, Auth::id());

            return response()->json([
                'success' => true,
                'message' => 'Partage désactivé avec succès.',
                'data' => new FileShareResource($share),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la désactivation du partage.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Supprime un partage.
     */
    public function destroy(File $file, FileShare $share): JsonResponse
    {
        $this->authorize('share', $file);

        if ($share->file_id !== $file->id) {
            return response()->json([
                'success' => false,
                'message' => 'Ce partage n\'appartient pas à ce fichier.',
            ], 403);
        }

        try {
            $this->shareService->deleteShare($share, Auth::id());

            return response()->json([
                'success' => true,
                'message' => 'Partage supprimé avec succès.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression du partage.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Accède à un fichier partagé via son token (public).
     */
    public function access(Request $request, string $token): JsonResponse
    {
        $share = $this->shareService->getShareByToken($token);

        if (!$share) {
            return response()->json([
                'success' => false,
                'message' => 'Lien de partage introuvable.',
            ], 404);
        }

        try {
            $result = $this->shareService->accessSharedFile(
                $share,
                $request->input('password'),
                false
            );

            return response()->json([
                'success' => true,
                'data' => [
                    'file' => new FileResource($result['file']),
                    'share' => new FileShareResource($result['share']),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 403);
        }
    }

    /**
     * Télécharge un fichier partagé via son token (public).
     */
    public function download(Request $request, string $token)
    {
        $share = $this->shareService->getShareByToken($token);

        if (!$share) {
            return response()->json([
                'success' => false,
                'message' => 'Lien de partage introuvable.',
            ], 404);
        }

        try {
            $result = $this->shareService->accessSharedFile(
                $share,
                $request->input('password'),
                true
            );

            $file = $result['file'];
            $download = $this->storageService->downloadFile($file, null);

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
                'message' => $e->getMessage(),
            ], 403);
        }
    }
}
