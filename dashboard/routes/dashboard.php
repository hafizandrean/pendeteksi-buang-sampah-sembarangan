<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DetectionController;

Route::prefix('dashboard')
    ->name('dashboard.')
    ->middleware('web')
    ->group(function () {

        Route::get('/', [DetectionController::class, 'index'])
            ->name('index');

        Route::get('/upload', [DetectionController::class, 'create'])
            ->name('create');

        Route::post('/upload', [DetectionController::class, 'store'])
            ->name('store');

        Route::get('/detections/{detection}', [DetectionController::class, 'show'])
            ->whereNumber('detection')
            ->name('show');

        Route::patch('/detections/{detection}/validation', [DetectionController::class, 'updateValidation'])
            ->whereNumber('detection')
            ->name('validation');

        Route::patch('/detections/{detection}/validation-ajax', [DetectionController::class, 'updateValidationAjax'])
            ->whereNumber('detection')
            ->name('validation.ajax');

        Route::post('/send-summary', [DetectionController::class, 'sendSummaryToTelegram'])
            ->name('send-summary');

        Route::get('/export', [DetectionController::class, 'exportCsv'])
            ->name('export');

        Route::post('/detections/{detection}/send-telegram', [DetectionController::class, 'sendSingleTelegram'])
            ->name('send.telegram');
    });