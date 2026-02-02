<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\LabResultController;

Route::get('/', function () {
    // If user is authenticated, redirect to dashboard
    // Dashboard will handle role-based redirection
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    // Show login form to guests
    return view('welcome');
});

// API Routes for Ajax calls
Route::middleware('auth')->group(function () {
    Route::get('/api/permissions', [AdminUserController::class, 'getPermissionsByRole']);
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    // Login Routes
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);

    // Registration Routes
    Route::get('register', [RegisterController::class, 'create'])->name('register');
    Route::post('register', [RegisterController::class, 'store']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');

    // Dashboard - requires view-dashboard permission
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard')
        ->middleware('permission:view-dashboard');

    // Medical Records Routes with permissions
    Route::prefix('medical-records')->name('medical-records.')->group(function () {
        // Index - requires view-medical-records
        Route::get('/', [MedicalRecordController::class, 'index'])
            ->name('index')
            ->middleware('permission:view-medical-records');

        // Create - requires create-medical-records
        Route::get('/create', [MedicalRecordController::class, 'create'])
            ->name('create')
            ->middleware('permission:create-medical-records');

        // Store - requires create-medical-records
        Route::post('/', [MedicalRecordController::class, 'store'])
            ->name('store')
            ->middleware('permission:create-medical-records');

        // Patient management - define before {id} routes
        Route::post('/patients/store', [MedicalRecordController::class, 'storePatient'])
            ->name('patients.store')
            ->middleware('permission:create-medical-records');

        Route::get('/patients/export/{format}', [MedicalRecordController::class, 'exportPatients'])
            ->name('patients.export')
            ->middleware('permission:export-data');

        // Show - requires view-medical-records
        Route::get('/{id}', [MedicalRecordController::class, 'show'])
            ->name('show')
            ->middleware('permission:view-medical-records');

        // Edit - requires edit-medical-records
        Route::get('/{id}/edit', [MedicalRecordController::class, 'edit'])
            ->name('edit')
            ->middleware('permission:edit-medical-records');

        // Update - requires edit-medical-records
        Route::put('/{id}', [MedicalRecordController::class, 'update'])
            ->name('update')
            ->middleware('permission:edit-medical-records');

        // Delete - requires delete-medical-records
        Route::delete('/{id}', [MedicalRecordController::class, 'destroy'])
            ->name('destroy')
            ->middleware('permission:delete-medical-records');

        // Permissions management - requires manage-permissions;
        // Permissions management
        Route::post('/{id}/permissions', [MedicalRecordController::class, 'updatePermissions'])
            ->name('update-permissions')
            ->middleware('permission:manage-permissions');

        Route::get('/{id}/available-users', [MedicalRecordController::class, 'getAvailableUsers'])
            ->name('available-users')
            ->middleware('permission:manage-permissions');

        // Patient management
        Route::post('/patients/store', [MedicalRecordController::class, 'storePatient'])
            ->name('patients.store')
            ->middleware('permission:create-medical-records');

        Route::get('/patients/export/{format}', [MedicalRecordController::class, 'exportPatients'])
            ->name('patients.export')
            ->middleware('permission:export-data');
    });

    // Lab Results Routes
    Route::prefix('lab-results')->name('lab-results.')->middleware('auth')->group(function () {
        Route::get('/', [LabResultController::class, 'index'])
            ->name('index')
            ->middleware('permission:view-lab-results');

        Route::post('/', [LabResultController::class, 'store'])
            ->name('store')
            ->middleware('permission:add-lab-results');
    });

    // Test route
    Route::get('/test-role', function () {
        return 'ROLE MIDDLEWARE WORKING';
    })->middleware(['auth', 'role:system-admin,doctor']);

    // Admin Routes - Only for system-admin
    Route::middleware(['auth', 'role:system-admin'])->prefix('admin')->name('admin.')->group(function () {
        // User Management
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
        Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

        // Export routes
        Route::get('/users/export/pdf', [AdminUserController::class, 'exportPDF'])->name('users.export.pdf');
        Route::get('/users/export/excel', [AdminUserController::class, 'exportExcel'])->name('users.export.excel');
        Route::get('/users/export/csv', [AdminUserController::class, 'exportCSV'])->name('users.export.csv');
    });

    // Permission Management Routes
    Route::middleware(['auth', 'permission:manage-permissions'])->group(function () {
        Route::get('/permissions', [PermissionController::class, 'index'])->name('permissions.index');
        Route::post('/permissions/role', [PermissionController::class, 'updateRolePermissions'])
            ->name('permissions.update-role');
        Route::get('/permissions/assign/{user}', [PermissionController::class, 'assignUserPermissions'])
            ->name('permissions.assign-user');
        Route::post('/permissions/assign/{user}', [PermissionController::class, 'storeUserPermissions'])
            ->name('permissions.store-user');
    });

    // User Management (for users with manage-users permission)
    Route::middleware(['auth', 'permission:manage-users'])->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    });
});
