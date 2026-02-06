@extends('layouts.app')

@section('title', 'Medical Records')

@section('content')
<div class="main-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1 class="page-title">Medical Records</h1>
            <p class="page-subtitle">View and manage patient medical records</p>
        </div>
        <div class="d-flex gap-2">
            @if(Auth::user()->isDoctor() || Auth::user()->hasPermission('create-medical-records'))
            <a href="{{ route('medical-records.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-2"></i> New Record
            </a>
            @endif
        </div>
    </div>
</div>

<!-- Permission Alert -->
@unless(Auth::user()->hasPermission('view-medical-records'))
<div class="alert alert-warning">
    <i class="fas fa-exclamation-triangle me-2"></i>
    You don't have permission to view medical records. Please contact your administrator.
</div>
@endunless

@if(Auth::user()->hasPermission('view-medical-records'))
<!-- Search and Filter Section -->
<div class="filter-section">
    <form id="searchForm" method="GET" action="{{ route('medical-records.index') }}" class="filter-row">
        <div class="filter-group">
            <label class="form-label">Search</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input type="text" name="search" class="form-control"
                    placeholder="Name, email, diagnosis..."
                    value="{{ request('search') }}">
            </div>
        </div>

        <!-- <div class="filter-group">
            <label class="form-label">Visibility</label>
            <select name="visibility" class="form-select">
                <option value="">All</option>
                <option value="public" {{ request('visibility') == 'public' ? 'selected' : '' }}>Public</option>
                <option value="restricted" {{ request('visibility') == 'restricted' ? 'selected' : '' }}>Restricted</option>
                <option value="private" {{ request('visibility') == 'private' ? 'selected' : '' }}>Private</option>
            </select>
        </div> -->

        <!-- @if(Auth::user()->hasAnyPermission(['view-all-medical-records', 'manage-permissions']) || Auth::user()->isAdmin())
        <div class="filter-group">
            <label class="form-label">Doctor</label>
            <select name="doctor_id" class="form-select">
                <option value="">All Doctors</option>
                @foreach($doctors as $doctor)
                <option value="{{ $doctor->id }}" {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
                    {{ $doctor->name }}
                </option>
                @endforeach
            </select>
        </div>
        @endif -->

        <!-- @if(Auth::user()->hasPermission('view-all-medical-records') || Auth::user()->isAdmin())
        <div class="filter-group">
            <label class="form-label">Patient</label>
            <select name="patient_id" class="form-select">
                <option value="">All Patients</option>
                @foreach($patients ?? [] as $patient)
                <option value="{{ $patient->id }}" {{ request('patient_id') == $patient->id ? 'selected' : '' }}>
                    {{ $patient->name }}
                </option>
                @endforeach
            </select>
        </div>
        @endif -->

        <div class="filter-group" style="max-width: 200px;">
            <label class="form-label">Date From</label>
            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
        </div>

        <div class="filter-group" style="max-width: 200px;">
            <label class="form-label">Date To</label>
            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
        </div>

        <div class="filter-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search me-1"></i> Search
            </button>
            @if(request()->hasAny(['search', 'visibility', 'doctor_id', 'patient_id', 'date_from', 'date_to']))
            <a href="{{ route('medical-records.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-times me-1"></i> Clear
            </a>
            @endif
        </div>
    </form>
</div>

<!-- Summary Stats -->
<div class="summary-stats">
    <div class="summary-stat-card">
        <div class="stat-icon-wrapper primary">
            <i class="fas fa-file-medical-alt"></i>
        </div>
        <div class="stat-content">
            <div class="stat-number">{{ $records->total() }}</div>
            <div class="stat-label">Total Records</div>
        </div>
    </div>

    <div class="summary-stat-card">
        <div class="stat-icon-wrapper success">
            <i class="fas fa-globe"></i>
        </div>
        <div class="stat-content">
            <div class="stat-number">{{ $records->where('visibility_level', 'public')->count() }}</div>
            <div class="stat-label">Public</div>
        </div>
    </div>

    <div class="summary-stat-card">
        <div class="stat-icon-wrapper warning">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-content">
            <div class="stat-number">{{ $records->where('visibility_level', 'restricted')->count() }}</div>
            <div class="stat-label">Restricted</div>
        </div>
    </div>

    <div class="summary-stat-card">
        <div class="stat-icon-wrapper secondary">
            <i class="fas fa-lock"></i>
        </div>
        <div class="stat-content">
            <div class="stat-number">{{ $records->where('visibility_level', 'private')->count() }}</div>
            <div class="stat-label">Private</div>
        </div>
    </div>
</div>

<!-- Records Table Card -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Patient</th>
                        @if(Auth::user()->hasAnyPermission(['view-all-medical-records', 'manage-permissions']) || Auth::user()->isAdmin() || Auth::user()->isNurse() || Auth::user()->isLabTechnician())
                        <th>Doctor</th>
                        @endif
                        <th>Diagnosis</th>
                        <th>Created</th>
                        <th>Visibility</th>
                        <th style="width: 100px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $record)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-circle {{ $record->visibility_level == 'public' ? 'success' : ($record->visibility_level == 'restricted' ? 'warning' : 'info') }}">
                                    {{ substr($record->patient->name, 0, 1) }}
                                </div>
                                <div>
                                    <strong>{{ $record->patient->name }}</strong>
                                    <div class="text-muted small">{{ $record->patient->email }}</div>
                                </div>
                            </div>
                        </td>

                        @if(Auth::user()->hasAnyPermission(['view-all-medical-records', 'manage-permissions']) || Auth::user()->isAdmin() || Auth::user()->isNurse() || Auth::user()->isLabTechnician())
                        <td>
                            @if($record->doctor)
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-circle" style="background: linear-gradient(135deg, var(--primary-400), var(--primary-500));">
                                    {{ substr($record->doctor->name, 0, 1) }}
                                </div>
                                <div>
                                    <strong>{{ $record->doctor->name }}</strong>
                                    <div class="text-muted small">Doctor</div>
                                </div>
                            </div>
                            @else
                            <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        @endif

                        <td>
                            @if($record->diagnosis)
                            <div class="diagnosis-preview" data-bs-toggle="tooltip" title="{{ $record->diagnosis }}">
                                {{ Str::limit($record->diagnosis, 50) }}
                                @if(strlen($record->diagnosis) > 50)
                                <span class="text-primary small">...</span>
                                @endif
                            </div>
                            @else
                            <span class="text-muted">No diagnosis</span>
                            @endif
                        </td>

                        <td>
                            <div>{{ $record->created_at->format('M d, Y') }}</div>
                            <div class="text-muted small">{{ $record->created_at->format('h:i A') }}</div>
                        </td>

                        <td>
                            @if($record->visibility_level == 'public')
                            <span class="visibility-badge public">
                                <i class="fas fa-globe"></i> Public
                            </span>
                            @elseif($record->visibility_level == 'restricted')
                            <span class="visibility-badge restricted">
                                <i class="fas fa-users"></i> Restricted
                            </span>
                            @else
                            <span class="visibility-badge private">
                                <i class="fas fa-lock"></i> Private
                            </span>
                            @endif
                        </td>

                        <td>
                            <div class="table-actions">
                                <a href="{{ route('medical-records.show', $record) }}" class="btn btn-icon btn-primary" title="View Record" data-bs-toggle="tooltip">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if(Auth::user()->isDoctor() || Auth::user()->hasPermission('edit-medical-records'))
                                <a href="{{ route('medical-records.edit', $record) }}" class="btn btn-icon btn-success" title="Edit Record" data-bs-toggle="tooltip">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ Auth::user()->hasAnyPermission(['view-all-medical-records', 'manage-permissions']) || Auth::user()->isAdmin() || Auth::user()->isNurse() || Auth::user()->isLabTechnician() ? '6' : '5' }}" class="text-center py-5">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-file-medical-alt"></i>
                                </div>
                                <h5 class="empty-state-title">No Records Found</h5>
                                <p class="empty-state-text">
                                    @if(request()->hasAny(['search', 'visibility', 'doctor_id', 'patient_id']))
                                    No records match your search criteria.
                                    @else
                                    No medical records have been added yet.
                                    @endif
                                </p>
                                @if(request()->hasAny(['search', 'visibility', 'doctor_id', 'patient_id']))
                                <a href="{{ route('medical-records.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i> Clear Filters
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Pagination -->
@if($records->hasPages())
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-4">
    <div class="text-muted">
        Showing {{ $records->firstItem() }} to {{ $records->lastItem() }} of {{ $records->total() }} records
    </div>
    <nav aria-label="Page navigation">
        <ul class="pagination mb-0">
            @if($records->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link"><i class="fas fa-chevron-left"></i></span>
            </li>
            @else
            <li class="page-item">
                <a class="page-link" href="{{ $records->previousPageUrl() . '&' . http_build_query(request()->except('page')) }}" aria-label="Previous">
                    <i class="fas fa-chevron-left"></i>
                </a>
            </li>
            @endif

            @foreach($records->getUrlRange(1, $records->lastPage()) as $page => $url)
            @if($page == $records->currentPage())
            <li class="page-item active">
                <span class="page-link">{{ $page }}</span>
            </li>
            @else
            <li class="page-item">
                <a class="page-link" href="{{ $url . '&' . http_build_query(request()->except('page')) }}">{{ $page }}</a>
            </li>
            @endif
            @endforeach

            @if($records->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $records->nextPageUrl() . '&' . http_build_query(request()->except('page')) }}" aria-label="Next">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
            @else
            <li class="page-item disabled">
                <span class="page-link"><i class="fas fa-chevron-right"></i></span>
            </li>
            @endif
        </ul>
    </nav>
</div>
@endif
@endif
@endsection