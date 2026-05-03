<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root ke dashboard
Route::redirect('/', '/dashboard');

// Load dashboard routes
require __DIR__.'/dashboard.php';

// Fallback (404)
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});