@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Nurse Dashboard</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <div class="card text-white bg-primary">
                                <div class="card-body">
                                    <h5 class="card-title">Total Users</h5>
                                    <p class="card-text display-6">{{ App\Models\User::count() }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="card text-white bg-success">
                                <div class="card-body">
                                    <h5 class="card-title">Total Records</h5>
                                    <p class="card-text display-6">{{ App\Models\PatientMedicalRecord::count() }}</p>
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
@endsection