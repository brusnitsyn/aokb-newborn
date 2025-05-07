<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\NewbornController::class, 'index'])->name('newborn.index');
