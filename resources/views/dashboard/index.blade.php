@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Dashboard</h3>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <h4><i class="icon fas fa-info"></i> Welcome, {{ Auth::user()->name }}!</h4>
                        <p>Your role: <strong>{{ Auth::user()->role->name ?? 'Unknown' }}</strong></p>
                        <p>This is your dashboard. You have limited access based on your role permissions.</p>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="card bg-primary">
                                <div class="card-body">
                                    <h5 class="card-title">Your Permissions</h5>
                                    @php
                                    $user = Auth::user();
                                    $permissions = $user->permissions ?? collect([]);
                                    @endphp
                                    @if($permissions->count() > 0)
                                    <ul class="list-unstyled">
                                        @foreach($permissions as $permission)
                                        <li><i class="fas fa-check"></i> {{ $permission->name }}</li>
                                        @endforeach
                                    </ul>
                                    @else
                                    <p class="text-warning">No permissions assigned to your role.</p>
                                    <p class="small">Please contact administrator to assign permissions.</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card bg-success">
                                <div class="card-body">
                                    <h5 class="card-title">Quick Actions</h5>
                                    @if($permissions->contains('slug', 'view-medical-records'))
                                    <a href="{{ route('medical-records.index') }}" class="btn btn-outline-light btn-block">
                                        <i class="fas fa-file-medical"></i> View Medical Records
                                    </a>
                                    @endif
                                    @if($permissions->contains('slug', 'view-lab-results'))
                                    <a href="{{ route('lab-results.index') }}" class="btn btn-outline-light btn-block">
                                        <i class="fas fa-flask"></i> View Lab Results
                                    </a>
                                    @endif
                                    @if($permissions->count() == 0)
                                    <p class="text-muted">No quick actions available.</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card bg-warning">
                                <div class="card-body">
                                    <h5 class="card-title">Need Help?</h5>
                                    <p>If you cannot access certain features, please contact your system administrator.</p>
                                    <p class="small text-muted">
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
</div>
@endsection