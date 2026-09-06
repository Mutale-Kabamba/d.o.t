<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProgrammesMeetingController;
use App\Http\Controllers\WorksheetController;
use Illuminate\Support\Facades\Route;

// Play It Forward Zambia: Public Project Officer Brief Routes
Route::get('/', [ProgrammesMeetingController::class, 'index'])->name('programmes.index');
Route::post('/programmes-meeting/submit', [ProgrammesMeetingController::class, 'store'])->name('programmes.store');
Route::post('/programmes-meeting/parse-pptx', [ProgrammesMeetingController::class, 'parsePptx'])->name('programmes.parse_pptx');
Route::get('/programmes-meeting/success/{token}', [ProgrammesMeetingController::class, 'success'])->name('programmes.success');
Route::get('/programmes-meeting/download-pdf/{token}', [ProgrammesMeetingController::class, 'exportSinglePdfByToken'])->name('programmes.download_single_pdf');

// Play It Forward Zambia: Password-Protected Supervisor Hub & Presentation Routes
Route::middleware('auth')->group(function () {
    Route::get('/programmes-meeting/hub', [ProgrammesMeetingController::class, 'hub'])->name('programmes.hub');
    Route::post('/programmes-meeting/import-pptx', [ProgrammesMeetingController::class, 'importPptx'])->name('programmes.import_pptx');
    Route::get('/programmes-meeting/export-consolidated-pdf', [ProgrammesMeetingController::class, 'exportConsolidatedPresentation'])->name('programmes.export_consolidated_pdf');
    Route::get('/programmes-meeting/export-consolidated-pptx', [ProgrammesMeetingController::class, 'exportConsolidatedPptx'])->name('programmes.export_consolidated_pptx');
    Route::get('/programmes-meeting/projector', [ProgrammesMeetingController::class, 'projector'])->name('programmes.projector');
    Route::get('/programmes-meeting/present', [ProgrammesMeetingController::class, 'projector'])->name('programmes.present');
    Route::delete('/programmes-meeting/submissions/{token}', [ProgrammesMeetingController::class, 'destroy'])->name('programmes.destroy');
    Route::post('/programmes-meeting/seed', [ProgrammesMeetingController::class, 'seedSample'])->name('programmes.seed');
});

// Legacy/Cohort Reflection Worksheet Routes
Route::get('/worksheet', [WorksheetController::class, 'index'])->name('worksheet.index');
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

