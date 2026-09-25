<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProgrammesMeetingController;
use Illuminate\Support\Facades\Route;

// Root Landing: Strictly Authenticated Entry Flow
Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('programmes.hub')
        : redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Authenticated Application Routes (Scoped to User & Assigned Projects)
Route::middleware('auth')->group(function () {
    // Project Dashboard / Hub
    Route::get('/programmes-meeting/hub', [ProgrammesMeetingController::class, 'hub'])->name('programmes.hub');
    Route::get('/dashboard', [ProgrammesMeetingController::class, 'hub'])->name('dashboard');

    // Continuous Activity Logging CRUD
    Route::get('/programmes-meeting/activities/create', [ProgrammesMeetingController::class, 'createActivity'])->name('programmes.activities.create');
    Route::post('/programmes-meeting/activities', [ProgrammesMeetingController::class, 'storeActivity'])->name('programmes.activities.store');
    Route::get('/programmes-meeting/activities/{token}', [ProgrammesMeetingController::class, 'showActivity'])->name('programmes.activities.show');
    Route::get('/programmes-meeting/activities/{token}/edit', [ProgrammesMeetingController::class, 'editActivity'])->name('programmes.activities.edit');
    Route::put('/programmes-meeting/activities/{token}', [ProgrammesMeetingController::class, 'updateActivity'])->name('programmes.activities.update');
    Route::delete('/programmes-meeting/activities/{token}', [ProgrammesMeetingController::class, 'destroyActivity'])->name('programmes.activities.destroy');

    // Dedicated Authenticated Project-Scoped Entries Routes
    Route::get('/projects/{project}/entries/create', [ProgrammesMeetingController::class, 'createActivityForProject'])->name('projects.entries.create');
    Route::post('/projects/{project}/entries', [ProgrammesMeetingController::class, 'storeActivityForProject'])->name('projects.entries.store');
    Route::get('/projects/{project}/entries/{token}/edit', [ProgrammesMeetingController::class, 'editActivity'])->name('projects.entries.edit');
    Route::put('/projects/{project}/entries/{token}', [ProgrammesMeetingController::class, 'updateActivity'])->name('projects.entries.update');

    // PowerPoint (.pptx) Import
    Route::post('/programmes-meeting/import-pptx', [ProgrammesMeetingController::class, 'importPptx'])->name('programmes.import_pptx');
    Route::post('/programmes-meeting/parse-pptx', [ProgrammesMeetingController::class, 'parsePptx'])->name('programmes.parse_pptx');

    // Consolidated Master Presentations (Transposed Matrix Layout)
    Route::get('/programmes-meeting/export-consolidated-pdf', [ProgrammesMeetingController::class, 'exportConsolidatedPresentation'])->name('programmes.export_consolidated_pdf');
    Route::get('/programmes-meeting/export-consolidated-pptx', [ProgrammesMeetingController::class, 'exportConsolidatedPptx'])->name('programmes.export_consolidated_pptx');
    Route::get('/programmes-meeting/projector', [ProgrammesMeetingController::class, 'projector'])->name('programmes.projector');
    Route::get('/programmes-meeting/present', [ProgrammesMeetingController::class, 'projector'])->name('programmes.present');

    // Dedicated Isolated Single Project Exports & Presentations
    Route::get('/programmes-meeting/projects/{project}/export-pdf', [ProgrammesMeetingController::class, 'exportSingleProjectPdf'])->name('programmes.projects.export_pdf');
    Route::get('/programmes-meeting/projects/{project}/export-pptx', [ProgrammesMeetingController::class, 'exportSingleProjectPptx'])->name('programmes.projects.export_pptx');
    Route::get('/programmes-meeting/projects/{project}/projector', [ProgrammesMeetingController::class, 'singleProjector'])->name('programmes.projects.projector');

    // Super Admin Project & Team Management
    Route::post('/programmes-meeting/projects', [ProgrammesMeetingController::class, 'storeProject'])->name('programmes.projects.store');
    Route::put('/programmes-meeting/projects/{project}', [ProgrammesMeetingController::class, 'updateProject'])->name('programmes.projects.update');
    Route::post('/programmes-meeting/projects/{project}/toggle-status', [ProgrammesMeetingController::class, 'toggleProjectStatus'])->name('programmes.projects.toggle_status');
    Route::delete('/programmes-meeting/projects/{project}', [ProgrammesMeetingController::class, 'destroyProject'])->name('programmes.projects.destroy');
    Route::post('/programmes-meeting/projects/assign-user', [ProgrammesMeetingController::class, 'assignUserToProject'])->name('programmes.projects.assign_user');

    // Super Admin User Account Management
    Route::post('/programmes-meeting/users', [ProgrammesMeetingController::class, 'storeUser'])->name('programmes.users.store');
    Route::put('/programmes-meeting/users/{user}', [ProgrammesMeetingController::class, 'updateUser'])->name('programmes.users.update');
    Route::delete('/programmes-meeting/users/{user}', [ProgrammesMeetingController::class, 'destroyUser'])->name('programmes.users.destroy');

    // Seeding action for quick setup
    Route::post('/programmes-meeting/seed', [ProgrammesMeetingController::class, 'seedSample'])->name('programmes.seed');
});

// Protected Admin Dashboard Routes (Super Admin)
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::redirect('/', '/admin/submissions');
    
    // Submissions Management
    Route::get('/submissions', [AdminController::class, 'index'])->name('submissions.index');
    Route::get('/submissions/export-master-pdf', [AdminController::class, 'exportMasterPdf'])->name('submissions.export_master_pdf');
    Route::get('/submissions/export-csv', [AdminController::class, 'exportCsv'])->name('submissions.export_csv');
    Route::get('/submissions/{token}/pdf', [AdminController::class, 'exportSinglePdf'])->name('submissions.download_pdf');
    Route::get('/submissions/{token}', [AdminController::class, 'show'])->name('submissions.show');
    Route::delete('/submissions/{token}', [AdminController::class, 'destroy'])->name('submissions.destroy');

    // Team & Staff Management (Full CRUD)
    Route::get('/staff', [AdminController::class, 'staffIndex'])->name('staff.index');
    Route::post('/staff', [AdminController::class, 'staffStore'])->name('staff.store');
    Route::get('/staff/{user}', [AdminController::class, 'staffShow'])->name('staff.show');
    Route::put('/staff/{user}', [AdminController::class, 'staffUpdate'])->name('staff.update');
    Route::delete('/staff/{user}', [AdminController::class, 'staffDestroy'])->name('staff.destroy');

    // Projects & Teams Management (Full CRUD)
    Route::get('/teams', [AdminController::class, 'teamsIndex'])->name('teams.index');
    Route::post('/teams', [AdminController::class, 'teamStore'])->name('teams.store');
    Route::put('/teams/{project}', [AdminController::class, 'teamUpdate'])->name('teams.update');
    Route::post('/teams/{project}/toggle-status', [AdminController::class, 'teamToggleStatus'])->name('teams.toggle_status');
    Route::delete('/teams/{project}', [AdminController::class, 'teamDestroy'])->name('teams.destroy');
});
