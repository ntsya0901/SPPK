<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SPPKController;

Route::get('/sppk', [SPPKController::class, 'index'])->name('sppk.index');
Route::post('/sppk/hitung', [SPPKController::class, 'hitung'])->name('sppk.hitung');
Route::get('/sppk/download/{filename}', [SPPKController::class, 'download'])->name('sppk.download');