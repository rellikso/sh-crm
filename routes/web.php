<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\WidgetController;

Route::get('/widget', WidgetController::class)->name('widget.index');

Route::get('/', function () {
    return view('welcome');
});
