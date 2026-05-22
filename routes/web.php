<?php

use App\Http\Controllers\Api\FormController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\AdminWebController;
use App\Http\Controllers\Web\PublicWebController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::get('/forms/{slug}', [PublicWebController::class, 'renderPublicForm'])->name('forms.render');
Route::post('/forms/{slug}', [PublicWebController::class, 'submitForm'])->name('forms.submit');

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminWebController::class, 'dashboard'])->name('admin.dashboard');

    Route::get('/forms/create', [AdminWebController::class, 'createBuilder'])->name('admin.forms.create');
    Route::post('/forms', [FormController::class, 'store'])->name('admin.forms.store');
    Route::put('/forms/{id}', [FormController::class, 'update'])->name('admin.forms.update');
    Route::get('/forms/{id}/edit', [AdminWebController::class, 'editForm'])->name('admin.forms.edit');
    Route::post('/forms/{id}/duplicate', [AdminWebController::class, 'duplicateForm'])->name('admin.forms.duplicate');
    Route::delete('/forms/{id}', [AdminWebController::class, 'deleteForm'])->name('admin.forms.delete');

    Route::get('/forms/{id}/submissions', [AdminWebController::class, 'viewSubmissions'])->name('admin.forms.submissions');
    Route::get('/forms/{id}/submissions/export', [AdminWebController::class, 'exportSubmissions'])->name('admin.forms.submissions.export');
});

Route::middleware('auth')->prefix('profile')->group(function () {
    Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
