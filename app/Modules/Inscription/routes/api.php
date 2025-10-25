<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Modules\Inscription\Http\Controllers\PendingStudentController;
use App\Modules\Inscription\Http\Controllers\SubmissionController;

// Routes for Inscription module

// Pending Students routes
Route::prefix('pending-students')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/', [PendingStudentController::class, 'index']);
        Route::get('/{pendingStudent}', [PendingStudentController::class, 'show']);
        Route::put('/{pendingStudent}', [PendingStudentController::class, 'update']);
        Route::delete('/{pendingStudent}', [PendingStudentController::class, 'destroy']);
        Route::get('/{pendingStudent}/documents', [PendingStudentController::class, 'getDocuments']);
    });

    // Routes publiques pour la soumission anonyme
    Route::post('/', [PendingStudentController::class, 'store']);
    Route::post('/{pendingStudent}/documents', [PendingStudentController::class, 'submitDocuments']);
});

// Submission periods routes
Route::prefix('submissions')->group(function () {
    Route::get('/active-periods', [SubmissionController::class, 'getActiveSubmissionPeriods']);
    Route::get('/active-reclamation-periods', [SubmissionController::class, 'getActiveReclamationPeriods']);
    Route::post('/check-status', [SubmissionController::class, 'checkSubmissionStatus']);
    Route::post('/check-reclamation-status', [SubmissionController::class, 'checkReclamationStatus']);
});

// Academic years routes
Route::prefix('academic-years')->group(function () {
    Route::get('/', [SubmissionController::class, 'getAcademicYears']);
    Route::get('/{academicYear}', [SubmissionController::class, 'getAcademicYear']);
});
