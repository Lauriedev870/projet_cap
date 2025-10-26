<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Cours\Http\Controllers\TeachingUnitController;
use App\Modules\Cours\Http\Controllers\CourseElementController;
use App\Modules\Cours\Http\Controllers\CourseElementResourceController;

// Routes protégées par authentification
Route::middleware('auth:sanctum')->group(function () {
    
    // CRUD Unités d'enseignement (Teaching Units)
    Route::apiResource('teaching-units', TeachingUnitController::class);
    
    // CRUD Éléments de cours (Course Elements / ECUE)
    Route::apiResource('course-elements', CourseElementController::class);
    
    // Gestion des professeurs pour un élément de cours
    Route::post('course-elements/{courseElement}/professors/attach', [CourseElementController::class, 'attachProfessor']);
    Route::post('course-elements/{courseElement}/professors/detach', [CourseElementController::class, 'detachProfessor']);
    
    // CRUD Ressources pédagogiques
    Route::apiResource('course-resources', CourseElementResourceController::class);
});
