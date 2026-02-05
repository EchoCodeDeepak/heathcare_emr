@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h4><i class="fas fa-info-circle me-2"></i>Welcome, {{ Auth::user()->name }}!</h4>
                        <p class="mb-1">Your role: <strong>{{ Auth::user()->role->name ?? 'Unknown' }}</strong></p>
                        <p class="mb-0">This is your dashboard. You have limited access based on your role permissions.</p>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="stats-card">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="stats-icon primary">
                                            <i class="fas fa-key"></i>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        @php
                                        $user = Auth::user();
                                        $permissions = $user->permissions ?? collect([]);
                                        @endphp
                                        <div class="stats-value">{{ $permissions->count() }}</div>
                                        <div class="stats-label">Permissions</div>
                                    </div>
                                </div>
                                <hr class="my-3" style="border-color: var(--border-color);">
                                <h6 class="mb-2" style="color: var(--text-primary); font-weight: 600;">Your Permissions</h6>
                                @if($permissions->count() > 0)
                                <ul class="list-unstyled mb-0" style="font-size: 0.9rem;">
                                    @foreach($permissions as $permission)
                                    <li class="mb-1"><i class="fas fa-check text-success me-2"></i>{{ $permission->name }}</li>
                                    @endforeach
                                </ul>
                                @else
                                <p class="text-warning mb-0" style="font-size: 0.9rem;">No permissions assigned to your role.</p>
                                <p class="small text-muted mb-0">Please contact administrator to assign permissions.</p>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="stats-card">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="stats-icon success">
                                            <i class="fas fa-bolt"></i>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="stats-value">3</div>
                                        <div class="stats-label">Quick Actions</div>
                                    </div>
                                </div>
                                <hr class="my-3" style="border-color: var(--border-color);">
                                <h6 class="mb-2" style="color: var(--text-primary); font-weight: 600;">Quick Actions</h6>
                                @if($permissions->contains('slug', 'view-medical-records'))
                                <a href="{{ route('medical-records.index') }}" class="btn btn-primary btn-sm d-block mb-2">
                                    <i class="fas fa-file-medical me-1"></i> View Medical Records
                                </a>
                                @endif
                                @if($permissions->contains('slug', 'view-lab-results'))
                                <a href="{{ route('lab-results.index') }}" class="btn btn-primary btn-sm d-block mb-2">
                                    <i class="fas fa-flask me-1"></i> View Lab Results
                                </a>
                                @endif
                                @if($permissions->count() == 0)
                                <p class="text-muted mb-0" style="font-size: 0.9rem;">No quick actions available.</p>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="stats-card">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <div class="stats-icon warning">
                                            <i class="fas fa-question-circle"></i>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <div class="stats-value">Help</div>
                                        <div class="stats-label">Support</div>
                                    </div>
                                </div>
                                <hr class="my-3" style="border-color: var(--border-color);">
                                <h6 class="mb-2" style="color: var(--text-primary); font-weight: 600;">Need Help?</h6>
                                <p class="mb-2" style="font-size: 0.9rem;">If you cannot access certain features, please contact your system administrator.</p>
                                <p class="small text-muted mb-0">
                                    Role: {{ Auth::user()->role->slug ?? 'Unknown' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection