<?php

use App\Http\Controllers\CompareController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\VisitChecklistController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('projects.index')
        : view('marketing.home');
})->name('marketing.home');

Route::get('/robots.txt', function () {
    $url = rtrim(config('app.url'), '/');

    return response(
        "User-agent: *\n".
        "Allow: /\n".
        "Sitemap: {$url}/sitemap.xml\n",
        200,
        ['Content-Type' => 'text/plain; charset=UTF-8']
    );
})->name('robots');

Route::get('/sitemap.xml', function () {
    $url = e(rtrim(config('app.url'), '/'));

    return response(
        <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url>
    <loc>{$url}/</loc>
    <priority>1.0</priority>
  </url>
</urlset>
XML,
        200,
        ['Content-Type' => 'application/xml; charset=UTF-8']
    );
})->name('sitemap');

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
