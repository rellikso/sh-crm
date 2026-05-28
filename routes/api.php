<?php

use App\Http\Controllers\Api\V1\TicketController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - Version 1
|--------------------------------------------------------------------------
*/
Route::prefix('v1')->group(function () {
    Route::prefix('tickets')->group(function () {
        Route::post('/', [TicketController::class, 'store']);
        Route::get('/statistics', [TicketController::class, 'statistics']);
    });
});
