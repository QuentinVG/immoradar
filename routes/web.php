<?php

use App\Http\Controllers\CompareController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\VisitChecklistController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('projects.index');
});

Route::get('/dashboard', function () {
    return redirect()->route('projects.index');
})->middleware('auth')->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('projects', ProjectController::class);
    Route::get('projects/{project}/compare', CompareController::class)->name('projects.compare');
    Route::get('projects/{project}/report', [ReportController::class, 'project'])->name('projects.report');
    Route::resource('projects.properties', PropertyController::class);
    Route::get('projects/{project}/properties/{property}/visit', [VisitChecklistController::class, 'edit'])->name('projects.properties.visit');
    Route::post('projects/{project}/properties/{property}/visit', [VisitChecklistController::class, 'update'])->name('projects.properties.visit.update');
    Route::post('projects/{project}/properties/{property}/visit/answer', [VisitChecklistController::class, 'updateAnswer'])->name('projects.properties.visit.answer');
    Route::get('projects/{project}/properties/{property}/report', [ReportController::class, 'property'])->name('projects.properties.report');
});

require __DIR__.'/auth.php';
