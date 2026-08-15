<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root ke dashboard
Route::redirect('/', '/dashboard');

// API Ingest Route for External AI Detector
Route::post('/api/detections/ingest', [App\Http\Controllers\DetectionController::class, 'ingest'])
    ->withoutMiddleware([
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \Illuminate\Cookie\Middleware\EncryptCookies::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
    ])
    ->name('api.detections.ingest');

// Load dashboard routes
require __DIR__.'/dashboard.php';

// Fallback (404)
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});