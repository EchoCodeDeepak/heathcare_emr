<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MedicalRecordController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\LabResultController;

Route::get('/', function () {
    // If user is authenticated, redirect to dashboard
    // Dashboard will handle role-based redirection
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    // Redirect guests to login
    return redirect()->route('login');
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

    // Profile Routes
    Route::get('/profile', [UserController::class, 'profile'])->name('profile.index');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/image', [UserController::class, 'updateProfileImage'])->name('profile.image.update');

    // Password Change Route
    Route::get('/password/change', [UserController::class, 'showChangePasswordForm'])->name('password.change');
    Route::put('/password/change', [UserController::class, 'changePassword'])->name('password.update');

    // Dashboard - accessible by all authenticated users (role-based redirection)
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

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

        Route::get('/create', [LabResultController::class, 'create'])
            ->name('create')
            ->middleware('permission:add-lab-results');

        Route::post('/', [LabResultController::class, 'store'])
            ->name('store')
            ->middleware('permission:add-lab-results');

        Route::get('/{id}', [LabResultController::class, 'show'])
            ->name('show')
            ->middleware('permission:view-lab-results');

        Route::get('/{id}/edit', [LabResultController::class, 'edit'])
            ->name('edit')
            ->middleware('permission:edit-lab-results');

        Route::put('/{id}', [LabResultController::class, 'update'])
            ->name('update')
            ->middleware('permission:edit-lab-results');

        Route::delete('/{id}', [LabResultController::class, 'destroy'])
            ->name('destroy')
            ->middleware('permission:delete-lab-results');
    });

    // Test route
    Route::get('/test-role', function () {
        return 'ROLE MIDDLEWARE WORKING';
    })->middleware(['auth', 'role:system-admin,doctor']);

    // Admin Routes - Only for system-admin
    Route::middleware(['auth', 'role:system-admin'])->prefix('admin')->name('admin.')->group(function () {
        // User & Role Management (Unified)
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
        Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

        // Role Management (now also under admin, using same store method)
        Route::get('/roles', [RoleController::class, 'index'])->name('roles.index');
        Route::get('/roles/create', [RoleController::class, 'create'])->name('roles.create');
        Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
        Route::get('/roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
        Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
        Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');

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

    // Role Management Routes - DEPRECATED (use admin routes instead)
    Route::middleware(['auth', 'permission:manage-permissions'])->group(function () {
        // Redirect to admin role management
        Route::get('/roles', function () {
            return redirect()->route('admin.roles.index');
        })->name('roles.index');
    });

    // User Management (for users with manage-users permission)
    Route::middleware(['auth', 'permission:manage-users'])->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    });
});
