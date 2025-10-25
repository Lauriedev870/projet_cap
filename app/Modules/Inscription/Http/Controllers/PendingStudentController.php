<?php

namespace App\Modules\Inscription\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Inscription\Models\PendingStudent;
use App\Modules\Inscription\Models\EntryLevel;
use App\Modules\Inscription\Models\EntryDiploma;
use App\Modules\Inscription\Models\SubmissionPeriod;
use App\Modules\Inscription\Http\Requests\CreatePendingStudentRequest;
use App\Modules\Inscription\Http\Resources\PendingStudentResource;
use App\Modules\Stockage\Services\FileStorageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PendingStudentController extends Controller
{
    public function __construct(
        protected FileStorageService $fileStorageService
    ) {
        $this->middleware('auth:sanctum');
    }

    /**
     * Liste des étudiants en attente.
     */
    public function index(Request $request): JsonResponse
    {
        $query = PendingStudent::with(['entryLevel', 'entryDiploma']);

        // Filtres
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('entry_level_id')) {
            $query->where('entry_level_id', $request->entry_level_id);
        }

        $pendingStudents = $query->orderBy('created_at', 'desc')->paginate(15);

        return response()->json([
            'success' => true,
            'data' => PendingStudentResource::collection($pendingStudents),
            'meta' => [
                'total' => $pendingStudents->total(),
                'per_page' => $pendingStudents->perPage(),
                'current_page' => $pendingStudents->currentPage(),
                'last_page' => $pendingStudents->lastPage(),
            ],
        ]);
    }

    /**
     * Créer un nouvel étudiant en attente.
     */
    public function store(CreatePendingStudentRequest $request): JsonResponse
    {
        try {
            $pendingStudent = DB::transaction(function () use ($request) {
                return PendingStudent::create([
                    'email' => $request->validated()['email'],
                    'first_name' => $request->validated()['first_name'],
                    'last_name' => $request->validated()['last_name'],
                    'phone' => $request->validated()['phone'],
                    'entry_level_id' => $request->validated()['entry_level_id'],
                    'entry_diploma_id' => $request->validated()['entry_diploma_id'],
                    'status' => 'pending',
                    'submitted_at' => now(),
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Étudiant en attente créé avec succès.',
                'data' => new PendingStudentResource($pendingStudent),
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la création de l\'étudiant en attente.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Afficher un étudiant en attente.
     */
    public function show(PendingStudent $pendingStudent): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => new PendingStudentResource($pendingStudent->load(['entryLevel', 'entryDiploma', 'studentPendingStudents.student'])),
        ]);
    }

    /**
     * Mettre à jour un étudiant en attente.
     */
    public function update(CreatePendingStudentRequest $request, PendingStudent $pendingStudent): JsonResponse
    {
        try {
            $pendingStudent->update($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Étudiant en attente mis à jour avec succès.',
                'data' => new PendingStudentResource($pendingStudent->fresh()),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la mise à jour.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Supprimer un étudiant en attente.
     */
    public function destroy(PendingStudent $pendingStudent): JsonResponse
    {
        try {
            $pendingStudent->delete();

            return response()->json([
                'success' => true,
                'message' => 'Étudiant en attente supprimé avec succès.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la suppression.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Soumettre des documents pour un étudiant en attente.
     * Accessible sans authentification pour permettre la soumission anonyme.
     */
    public function submitDocuments(Request $request, PendingStudent $pendingStudent): JsonResponse
    {
        $request->validate([
            'documents' => 'required|array',
            'documents.*' => 'required|file|max:51200', // 50MB max par fichier
            'document_types' => 'required|array',
            'document_types.*' => 'required|string',
        ]);

        try {
            $uploadedFiles = [];

            DB::transaction(function () use ($request, $pendingStudent, &$uploadedFiles) {
                foreach ($request->file('documents') as $index => $file) {
                    $documentType = $request->document_types[$index] ?? 'unknown';

                    $uploadedFile = $this->fileStorageService->uploadFile(
                        $file,
                        auth()->id() ?? null, // Peut être null si non authentifié
                        'private',
                        'inscription_documents',
                        'inscription',
                        'pending_student',
                        $pendingStudent->id,
                        [
                            'document_type' => $documentType,
                            'submitted_by' => auth()->id() ?? null,
                        ]
                    );

                    $uploadedFiles[] = $uploadedFile;
                }

                // Mettre à jour le statut si nécessaire
                if ($pendingStudent->status === 'pending') {
                    $pendingStudent->update(['status' => 'documents_submitted']);
                }
            });

            return response()->json([
                'success' => true,
                'message' => 'Documents soumis avec succès.',
                'data' => [
                    'pending_student' => new PendingStudentResource($pendingStudent->fresh()),
                    'uploaded_files' => collect($uploadedFiles)->map(function ($file) {
                        return [
                            'id' => $file->id,
                            'original_name' => $file->original_name,
                            'size' => $file->size,
                            'mime_type' => $file->mime_type,
                        ];
                    }),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la soumission des documents.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Récupérer les documents d'un étudiant en attente.
     */
    public function getDocuments(Request $request, PendingStudent $pendingStudent): JsonResponse
    {
        // Vérifier que l'utilisateur est authentifié pour récupérer les documents
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Authentification requise pour récupérer les documents.',
            ], 401);
        }

        $files = $this->fileStorageService->getUserFiles(
            auth()->id(),
            [
                'module_name' => 'inscription',
                'module_resource_type' => 'pending_student',
                'module_resource_id' => $pendingStudent->id,
            ]
        );

        return response()->json([
            'success' => true,
            'data' => $files,
        ]);
    }
}
