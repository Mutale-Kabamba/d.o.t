<?php

use App\Http\Controllers\WorksheetController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WorksheetController::class, 'index'])->name('worksheet.index');
Route::post('/submit', [WorksheetController::class, 'store'])->name('worksheet.store');
Route::get('/success/{token}', [WorksheetController::class, 'success'])->name('worksheet.success');
