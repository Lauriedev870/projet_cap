<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Notes\Http\Controllers\LmdGradeController;
use App\Modules\Notes\Http\Controllers\OldGradeController;
use App\Modules\Notes\Http\Controllers\ProfessorGradeController;

Route::prefix('api/notes')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        
        // Routes pour la navigation du professeur (comme l'ancien projet)
        Route::prefix('professor')->group(function () {
            Route::get('my-classes-by-semester/{semester}', [ProfessorGradeController::class, 'getClassesBySemester']);
            Route::get('programs-by-class/{class_group_id}', [ProfessorGradeController::class, 'getProgramsByClass']);
            Route::get('students-by-program/{program_uuid}', [ProfessorGradeController::class, 'getStudentsByProgram']);
        });
        
        // Routes pour les notes LMD
        Route::prefix('lmd-grades')->group(function () {
            Route::get('grade-sheet', [LmdGradeController::class, 'getGradeSheet']);
            Route::post('add-column', [LmdGradeController::class, 'addColumn']);
            Route::put('update-single', [LmdGradeController::class, 'updateSingle']);
            Route::delete('delete-column', [LmdGradeController::class, 'deleteColumn']);
            Route::post('set-weighting', [LmdGradeController::class, 'setWeighting']);
            Route::get('retake-list', [LmdGradeController::class, 'getRetakeList']);
        });

        // Routes pour l'ancien système
        Route::prefix('old-grades')->group(function () {
            Route::get('grade-sheet', [OldGradeController::class, 'getGradeSheet']);
            Route::post('add-column', [OldGradeController::class, 'addColumn']);
            Route::put('update-single', [OldGradeController::class, 'updateSingle']);
            Route::delete('delete-column', [OldGradeController::class, 'deleteColumn']);
            Route::post('set-weighting', [OldGradeController::class, 'setWeighting']);
        });
    });
});
