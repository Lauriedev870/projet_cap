<?php

use Illuminate\Support\Facades\Route;
use App\Modules\Cours\Http\Controllers\TeachingUnitController;
use App\Modules\Cours\Http\Controllers\CourseElementController;
use App\Modules\Cours\Http\Controllers\CourseElementResourceController;

Route::prefix('api/cours')->group(function () {
    Route::middleware('auth:sanctum')->group(function () {
        Route::apiResource('teaching-units', TeachingUnitController::class);
        Route::apiResource('course-elements', CourseElementController::class);
        Route::post('course-elements/{courseElement}/professors/attach', [CourseElementController::class, 'attachProfessor']);
        Route::post('course-elements/{courseElement}/professors/detach', [CourseElementController::class, 'detachProfessor']);
        Route::apiResource('course-resources', CourseElementResourceController::class);
    });
});
