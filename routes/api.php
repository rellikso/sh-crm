<?php

use App\Http\Controllers\Api\TicketController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes - Version 1
|--------------------------------------------------------------------------
*/
Route::prefix('v1')->name('api.v1.')->group(function () {

    // Public endpoint for cross-origin widget ticket submissions
    Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');

});
