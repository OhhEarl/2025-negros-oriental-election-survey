<?php

use App\Http\Controllers\ElectionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ElectionController::class, 'index']);
Route::post('/vote',  [ElectionController::class, 'store'])->name('vote.store');
