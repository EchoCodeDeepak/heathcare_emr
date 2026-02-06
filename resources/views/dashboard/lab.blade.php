@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Lab technician Dashboard</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <div class="stat-card stat-card-primary shadow-sm">
                                <div class="stat-card-body">
                                    <h5 class="stat-card-title">Total Users</h5>
                                    <p class="stat-card-number">{{ App\Models\User::count() }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="stat-card stat-card-success shadow-sm">
                                <div class="stat-card-body">
                                    <h5 class="stat-card-title">Total Records</h5>
                                    <p class="stat-card-number">{{ App\Models\PatientMedicalRecord::count() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h4>Quick Actions</h4>
                        <div class="d-flex gap-2">
                            <a href="{{ route('users.index') }}" class="btn btn-primary">Manage Users</a>
                            <a href="{{ route('permissions.index') }}" class="btn btn-warning">Manage Permissions</a>
                            <a href="{{ route('medical-records.index') }}" class="btn btn-info">View All Records</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Lighter Stat Cards */
    .stat-card {
        border-radius: var(--radius-lg);
        overflow: hidden;
        transition: all var(--transition-normal);
        background: #ffffff;
        border: 1px solid var(--border-color);
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .stat-card-body {
        padding: 1.25rem;
    }

    .stat-card-title {
        font-size: 0.875rem;
        opacity: 0.85;
        font-weight: 500;
        margin-bottom: 0.5rem;
        color: var(--text-primary);
    }

    .stat-card-number {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0;
        color: var(--text-primary);
    }

    /* Primary - Teal */
    .stat-card-primary {
        border-left: 4px solid var(--primary-500);
    }

    .stat-card-primary .stat-card-number {
        color: var(--primary-600);
    }

    /* Success - Green */
    .stat-card-success {
        border-left: 4px solid var(--success-500);
    }

    .stat-card-success .stat-card-number {
        color: var(--success-600);
    }
</style>
@endsection