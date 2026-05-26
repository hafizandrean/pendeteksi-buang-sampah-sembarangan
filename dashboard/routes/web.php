<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root ke dashboard
Route::redirect('/', '/dashboard');
Route::patch('/dashboard/detections/{detection}/validasi', [App\Http\Controllers\DetectionController::class, 'updateValidation'])->name('dashboard.updateValidation');
// Load dashboard routes
require __DIR__.'/dashboard.php';

// Fallback (404)
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});