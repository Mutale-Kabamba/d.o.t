<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\WorksheetController;
use Illuminate\Support\Facades\Route;

// Worksheet Mobile App Routes
Route::get('/', [WorksheetController::class, 'index'])->name('worksheet.index');
Route::post('/submit', [WorksheetController::class, 'store'])->name('worksheet.store');
Route::get('/success/{token}', [WorksheetController::class, 'success'])->name('worksheet.success');
Route::match(['get', 'post'], '/export-pdf', [WorksheetController::class, 'exportPdf'])->name('worksheet.export_pdf');
Route::get('/download-pdf/{token}', [WorksheetController::class, 'exportPdfByToken'])->name('worksheet.download_pdf');

// Admin Authentication Routes
Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Protected Admin Dashboard Routes
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::redirect('/', '/admin/submissions');
    Route::get('/submissions', [AdminController::class, 'index'])->name('submissions.index');
    Route::get('/submissions/export-master-pdf', [AdminController::class, 'exportMasterPdf'])->name('submissions.export_master_pdf');
    Route::get('/submissions/export-csv', [AdminController::class, 'exportCsv'])->name('submissions.export_csv');
    Route::get('/submissions/{token}', [AdminController::class, 'show'])->name('submissions.show');
    Route::delete('/submissions/{token}', [AdminController::class, 'destroy'])->name('submissions.destroy');
});
