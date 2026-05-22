<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FormController;
use App\Http\Controllers\Api\PublicFormController;
use App\Http\Controllers\Api\SubmissionController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::get('/public/forms/{slug}', [PublicFormController::class, 'showForm']);
Route::post('/public/forms/{slug}/submit', [PublicFormController::class, 'submitForm']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('forms', FormController::class);
    Route::post('/forms/{id}/duplicate', [FormController::class, 'duplicate']);

    Route::get('/forms/{id}/submissions', [SubmissionController::class, 'index']);
    Route::get('/forms/{id}/submissions/export', [SubmissionController::class, 'export']);
});
