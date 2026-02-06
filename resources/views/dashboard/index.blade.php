@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="main-header">
    <h1 class="page-title">Dashboard</h1>
    <p class="page-subtitle">Welcome back, {{ Auth::user()->name }}!</p>
</div>

<!-- Summary Stats -->
<div class="summary-stats">
    <div class="summary-stat-card">
        <div class="stat-icon-wrapper primary">
            <i class="fas fa-key"></i>
        </div>
        <div class="stat-content">
            <div class="stat-number">{{ Auth::user()->permissions->count() }}</div>
            <div class="stat-label">Permissions</div>
        </div>
    </div>

    <div class="summary-stat-card">
        <div class="stat-icon-wrapper success">
            <i class="fas fa-bolt"></i>
        </div>
        <div class="stat-content">
            <div class="stat-number">3</div>
            <div class="stat-label">Quick Actions</div>
        </div>
    </div>

    <div class="summary-stat-card">
        <div class="stat-icon-wrapper info">
            <i class="fas fa-user-shield"></i>
        </div>
        <div class="stat-content">
            <div class="stat-number">{{ Auth::user()->role->name }}</div>
            <div class="stat-label">Your Role</div>
        </div>
    </div>

    <div class="summary-stat-card">
        <div class="stat-icon-wrapper warning">
            <i class="fas fa-question-circle"></i>
        </div>
        <div class="stat-content">
            <div class="stat-number">Help</div>
            <div class="stat-label">Support</div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Your Permissions Card -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-key"></i>
                    Your Permissions
                </h5>
            </div>
            <div class="card-body">
                @php
                $user = Auth::user();
                $permissions = $user->permissions ?? collect([]);
                @endphp

                @if($permissions->count() > 0)
                <ul class="list-unstyled mb-0">
                    @foreach($permissions as $permission)
                    <li class="mb-2 d-flex align-items-center">
                        <i class="fas fa-check text-success me-2"></i>
                        <span>{{ $permission->name }}</span>
                    </li>
                    @endforeach
                </ul>
                @else
                <div class="alert alert-warning mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    No permissions assigned to your role.
                </div>
                <p class="text-muted small mt-2 mb-0">Please contact administrator to assign permissions.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions Card -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-bolt"></i>
                    Quick Actions
                </h5>
            </div>
            <div class="card-body">
                @if($permissions->contains('slug', 'view-medical-records'))
                <a href="{{ route('medical-records.index') }}" class="btn btn-primary w-100 mb-2">
                    <i class="fas fa-file-medical me-2"></i> View Medical Records
                </a>
                @endif

                @if($permissions->contains('slug', 'view-lab-results'))
                <a href="{{ route('lab-results.index') }}" class="btn btn-primary w-100 mb-2">
                    <i class="fas fa-flask me-2"></i> View Lab Results
                </a>
                @endif

                @if(auth()->user()->isAdmin())
                <a href="{{ route('admin.users.index') }}" class="btn btn-success w-100 mb-2">
                    <i class="fas fa-users me-2"></i> Manage Users
                </a>
                <a href="{{ route('admin.roles.index') }}" class="btn btn-success w-100 mb-2">
                    <i class="fas fa-shield-alt me-2"></i> Manage Roles
                </a>
                @endif

                @if($permissions->count() == 0)
                <p class="text-muted mb-0">No quick actions available.</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Help & Support Card -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-question-circle"></i>
                    Help & Support
                </h5>
            </div>
            <div class="card-body">
                <p class="mb-3">Need assistance with the Healthcare EMR system?</p>
                <div class="mb-3">
                    <strong>Your Role:</strong>
                    <span class="badge badge-primary ms-1">{{ Auth::user()->role->slug ?? 'Unknown' }}</span>
                </div>
                <p class="text-muted small mb-3">Contact your system administrator if you cannot access certain features.</p>
                <a href="#" class="btn btn-outline-secondary w-100">
                    <i class="fas fa-life-ring me-2"></i> Contact Support
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Role Permissions Overview (for admins) -->
@if(auth()->user()->isAdmin())
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">
                    <i class="fas fa-shield-alt"></i>
                    System Overview
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="text-center p-3">
                            <i class="fas fa-users fa-2x text-primary mb-2"></i>
                            <h4>{{ \App\Models\User::count() }}</h4>
                            <p class="text-muted mb-0">Total Users</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3">
                            <i class="fas fa-shield-alt fa-2x text-success mb-2"></i>
                            <h4>{{ \App\Models\Role::count() }}</h4>
                            <p class="text-muted mb-0">Total Roles</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3">
                            <i class="fas fa-key fa-2x text-warning mb-2"></i>
                            <h4>{{ \App\Models\Permission::count() }}</h4>
                            <p class="text-muted mb-0">Total Permissions</p>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="text-center p-3">
                            <i class="fas fa-file-medical-alt fa-2x text-info mb-2"></i>
                            <h4>{{ \App\Models\PatientMedicalRecord::count() }}</h4>
                            <p class="text-muted mb-0">Medical Records</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection