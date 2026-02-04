@extends('layouts.app')

@section('content')
<div class="container-fluid my-4">
    <div class="row">
        <!-- Header -->
        <div class="col-12 mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-0"><i class="fas fa-tachometer-alt"></i> Admin Dashboard</h2>
                    <p class="text-muted mb-0">Welcome, {{ Auth::user()->name }}!</p>
                </div>
                <div>
                    <span class="badge bg-success">System Admin</span>
                </div>
            </div>
            <hr>
        </div>

        <!-- Statistics Cards -->
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card text-white bg-primary shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-white-50 mb-0">Total Users</h6>
                            <h2 class="mb-0">{{ App\Models\User::count() }}</h2>
                        </div>
                        <div style="font-size: 2rem; opacity: 0.5;">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                </div>
                <a href="{{ route('admin.users.index') }}" class="card-footer bg-transparent border-top text-white text-decoration-none">
                    View Users <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card text-white bg-success shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-white-50 mb-0">Total Records</h6>
                            <h2 class="mb-0">{{ App\Models\PatientMedicalRecord::count() }}</h2>
                        </div>
                        <div style="font-size: 2rem; opacity: 0.5;">
                            <i class="fas fa-file-medical"></i>
                        </div>
                    </div>
                </div>
                <a href="{{ route('medical-records.index') }}" class="card-footer bg-transparent border-top text-white text-decoration-none">
                    View Medical Records <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card text-white bg-warning shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-white-50 mb-0">Total Roles</h6>
                            <h2 class="mb-0">{{ App\Models\Role::count() }}</h2>
                        </div>
                        <div style="font-size: 2rem; opacity: 0.5;">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                    </div>
                </div>
                <a href="{{ route('admin.roles.index') }}" class="card-footer bg-transparent border-top text-white text-decoration-none">
                    Manage Roles <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card text-white bg-info shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-white-50 mb-0">Permissions</h6>
                            <h2 class="mb-0">{{ App\Models\Permission::count() }}</h2>
                        </div>
                        <div style="font-size: 2rem; opacity: 0.5;">
                            <i class="fas fa-lock"></i>
                        </div>
                    </div>
                </div>
                <a href="{{ route('permissions.index') }}" class="card-footer bg-transparent border-top text-white text-decoration-none">
                    Manage <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-light border-bottom">
                    <h5 class="mb-0"><i class="fas fa-lightning-bolt"></i> Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-6 mb-2">
                            <a href="{{ route('admin.users.create', ['type' => 'user']) }}" class="btn btn-success btn-block w-100">
                                <i class="fas fa-user-plus"></i> Create New User
                            </a>
                        </div>
                        <!-- Create Role button removed: role creation is managed separately -->
                        <div class="col-md-3 col-sm-6 mb-2">
                            <a href="{{ route('admin.roles.index') }}" class="btn btn-warning btn-block w-100">
                                <i class="fas fa-shield-alt"></i> Manage Roles
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-2">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-info btn-block w-100">
                                <i class="fas fa-users"></i> Manage Users
                            </a>
                        </div>
                        <div class="col-md-3 col-sm-6 mb-2">
                            <a href="{{ route('permissions.index') }}" class="btn btn-secondary btn-block w-100">
                                <i class="fas fa-lock"></i> Permissions
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.btn-block {
    display: block;
    width: 100%;
}
</style>
@endsection
