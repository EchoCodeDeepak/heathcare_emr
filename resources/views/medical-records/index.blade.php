@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-file-medical-alt"></i> Medical Records
                            @if(request()->hasAny(['search', 'visibility', 'doctor_id', 'patient_id']))
                            <span class="badge bg-light text-primary ms-2">
                                <i class="fas fa-filter"></i> Filtered
                            </span>
                            @endif
                        </h4>

                        <div>
                            <!-- Show New Record button to doctors or users with create permission -->
                            @if(Auth::user()->isDoctor() || Auth::user()->hasPermission('create-medical-records'))
                            <a href="{{ route('medical-records.create') }}" class="btn btn-light">
                                <i class="fas fa-plus"></i> New Record
                            </a>
                            @endif

                            @if($records->count() > 0)
                            <!-- Check export permission -->
                            @permission('export-data')
                            <div class="dropdown d-inline-block ms-2">
                                <button class="btn btn-success dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-download"></i> Export
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('medical-records.patients.export', 'pdf') . '?' . http_build_query(request()->query()) }}">
                                            <i class="fas fa-file-pdf text-danger"></i> Export as PDF
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('medical-records.patients.export', 'excel') . '?' . http_build_query(request()->query()) }}">
                                            <i class="fas fa-file-excel text-success"></i> Export as Excel
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('medical-records.patients.export', 'csv') . '?' . http_build_query(request()->query()) }}">
                                            <i class="fas fa-file-csv text-info"></i> Export as CSV
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            @endpermission
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Permission Alert for users without view permission -->
                    @unless(Auth::user()->hasPermission('view-medical-records'))
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        You don't have permission to view medical records. Please contact your administrator.
                    </div>
                    @endunless

                    <!-- Search and Filter Section -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <form id="searchForm" method="GET" action="{{ route('medical-records.index') }}" class="row g-3">
                                <!-- Search Input -->
                                <div class="col-md-3">
                                    <label class="form-label">Search</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                        <input type="text" name="search" class="form-control"
                                            placeholder="Name, email, diagnosis..."
                                            value="{{ request('search') }}">
                                        @if(request()->has('search') && request('search') != '')
                                        <button type="button" class="btn btn-outline-secondary clear-field"
                                            data-field="search" title="Clear search">
                                            <i class="fas fa-times"></i>
                                        </button>
                                        @endif
                                    </div>
                                </div>

                                <!-- Visibility Filter -->
                                <div class="col-md-2">
                                    <label class="form-label">Visibility</label>
                                    <select name="visibility" class="form-control">
                                        <option value="">All</option>
                                        <option value="public" {{ request('visibility') == 'public' ? 'selected' : '' }}>Public</option>
                                        <option value="restricted" {{ request('visibility') == 'restricted' ? 'selected' : '' }}>Restricted</option>
                                        <option value="private" {{ request('visibility') == 'private' ? 'selected' : '' }}>Private</option>
                                    </select>
                                </div>

                                <!-- Doctor Filter -->
                                @if(Auth::user()->hasAnyPermission(['view-all-medical-records', 'manage-permissions']) || Auth::user()->isAdmin())
                                <div class="col-md-2">
                                    <label class="form-label">Doctor</label>
                                    <select name="doctor_id" class="form-control">
                                        <option value="">All Doctors</option>
                                        @foreach($doctors as $doctor)
                                        <option value="{{ $doctor->id }}" {{ request('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                            {{ $doctor->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif

                                <!-- Patient Filter (for admins and users with special permissions) -->
                                @if(Auth::user()->hasPermission('view-all-medical-records') || Auth::user()->isAdmin())
                                <div class="col-md-2">
                                    <label class="form-label">Patient</label>
                                    <select name="patient_id" class="form-control">
                                        <option value="">All Patients</option>
                                        @foreach($patients ?? [] as $patient)
                                        <option value="{{ $patient->id }}" {{ request('patient_id') == $patient->id ? 'selected' : '' }}>
                                            {{ $patient->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif

                                <!-- Date Range -->
                                <div class="col-md-2">
                                    <label class="form-label">Date Range</label>
                                    <div class="input-group">
                                        <input type="date" name="date_from" class="form-control"
                                            placeholder="From Date"
                                            value="{{ request('date_from') }}">
                                        <input type="date" name="date_to" class="form-control"
                                            placeholder="To Date"
                                            value="{{ request('date_to') }}">
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="col-md-{{ Auth::user()->hasPermission('view-all-medical-records') || Auth::user()->isAdmin() ? '1' : '3' }} d-flex align-items-end">
                                    <div class="d-grid gap-2 w-100">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i> Search
                                        </button>
                                        @if(request()->hasAny(['search', 'visibility', 'doctor_id', 'patient_id', 'date_from', 'date_to']))
                                        <a href="{{ route('medical-records.index') }}" class="btn btn-outline-secondary">
                                            Clear Filters
                                        </a>
                                        @endif
                                    </div>
                                </div>
                            </form>

                            <!-- Advanced Filters Toggle -->
                            @if(Auth::user()->hasAnyPermission(['view-all-medical-records', 'manage-permissions']) || Auth::user()->isAdmin())
                            <div class="mt-3">
                                <a class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" href="#advancedFilters"
                                    role="button" aria-expanded="false">
                                    <i class="fas fa-sliders-h"></i> Advanced Filters
                                </a>

                                <div class="collapse mt-2" id="advancedFilters">
                                    <div class="card card-body">
                                        <form id="advancedForm" method="GET" action="{{ route('medical-records.index') }}" class="row g-3">
                                            <!-- Sort Options -->
                                            <div class="col-md-3">
                                                <label class="form-label">Sort By</label>
                                                <select name="sort_by" class="form-control">
                                                    <option value="created_at" {{ request('sort_by', 'created_at') == 'created_at' ? 'selected' : '' }}>Date Created</option>
                                                    <option value="updated_at" {{ request('sort_by') == 'updated_at' ? 'selected' : '' }}>Last Updated</option>
                                                    <option value="patient_id" {{ request('sort_by') == 'patient_id' ? 'selected' : '' }}>Patient Name</option>
                                                    <option value="doctor_id" {{ request('sort_by') == 'doctor_id' ? 'selected' : '' }}>Doctor Name</option>
                                                </select>
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label">Order</label>
                                                <select name="sort_order" class="form-control">
                                                    <option value="desc" {{ request('sort_order', 'desc') == 'desc' ? 'selected' : '' }}>Descending</option>
                                                    <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                                                </select>
                                            </div>

                                            <!-- Items Per Page -->
                                            <div class="col-md-2">
                                                <label class="form-label">Per Page</label>
                                                <select name="per_page" class="form-control" onchange="this.form.submit()">
                                                    <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                                                    <option value="25" {{ request('per_page', 10) == 25 ? 'selected' : '' }}>25</option>
                                                    <option value="50" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50</option>
                                                    <option value="100" {{ request('per_page', 10) == 100 ? 'selected' : '' }}>100</option>
                                                </select>
                                            </div>

                                            <!-- Keep existing filters -->
                                            @if(request()->has('search'))
                                            <input type="hidden" name="search" value="{{ request('search') }}">
                                            @endif
                                            @if(request()->has('visibility'))
                                            <input type="hidden" name="visibility" value="{{ request('visibility') }}">
                                            @endif
                                            @if(request()->has('doctor_id'))
                                            <input type="hidden" name="doctor_id" value="{{ request('doctor_id') }}">
                                            @endif
                                            @if(request()->has('patient_id'))
                                            <input type="hidden" name="patient_id" value="{{ request('patient_id') }}">
                                            @endif

                                            <div class="col-md-3 d-flex align-items-end">
                                                <button type="submit" class="btn btn-primary">
                                                    Apply Filters
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Active Filters Badges -->
                    @if(request()->hasAny(['search', 'visibility', 'doctor_id', 'patient_id']))
                    <div class="mb-3">
                        <div class="d-flex flex-wrap gap-2">
                            @if(request()->has('search') && request('search') != '')
                            <span class="badge bg-info d-flex align-items-center">
                                Search: "{{ request('search') }}"
                                <a href="{{ route('medical-records.index', request()->except('search')) }}"
                                    class="text-white ms-2" style="text-decoration: none;">
                                    <i class="fas fa-times"></i>
                                </a>
                            </span>
                            @endif

                            @if(request()->has('visibility') && request('visibility') != '')
                            <span class="badge bg-warning text-dark d-flex align-items-center">
                                Visibility: {{ ucfirst(request('visibility')) }}
                                <a href="{{ route('medical-records.index', request()->except('visibility')) }}"
                                    class="text-dark ms-2" style="text-decoration: none;">
                                    <i class="fas fa-times"></i>
                                </a>
                            </span>
                            @endif

                            @if(request()->has('doctor_id') && request('doctor_id') != '')
                            @php
                            $selectedDoctor = $doctors->firstWhere('id', request('doctor_id'));
                            @endphp
                            @if($selectedDoctor)
                            <span class="badge bg-success d-flex align-items-center">
                                Doctor: {{ $selectedDoctor->name }}
                                <a href="{{ route('medical-records.index', request()->except('doctor_id')) }}"
                                    class="text-white ms-2" style="text-decoration: none;">
                                    <i class="fas fa-times"></i>
                                </a>
                            </span>
                            @endif
                            @endif

                            @if(request()->has('patient_id') && request('patient_id') != '')
                            @php
                            $selectedPatient = $patients->firstWhere('id', request('patient_id'));
                            @endphp
                            @if($selectedPatient)
                            <span class="badge bg-primary d-flex align-items-center">
                                Patient: {{ $selectedPatient->name }}
                                <a href="{{ route('medical-records.index', request()->except('patient_id')) }}"
                                    class="text-white ms-2" style="text-decoration: none;">
                                    <i class="fas fa-times"></i>
                                </a>
                            </span>
                            @endif
                            @endif

                            @if(request()->hasAny(['date_from', 'date_to']))
                            <span class="badge bg-secondary d-flex align-items-center">
                                Date:
                                @if(request()->has('date_from') && request('date_from') != '')
                                {{ request('date_from') }}
                                @endif
                                @if(request()->has('date_to') && request('date_to') != '')
                                - {{ request('date_to') }}
                                @endif
                                <a href="{{ route('medical-records.index', request()->except(['date_from', 'date_to'])) }}"
                                    class="text-white ms-2" style="text-decoration: none;">
                                    <i class="fas fa-times"></i>
                                </a>
                            </span>
                            @endif

                            <a href="{{ route('medical-records.index') }}" class="btn btn-sm btn-outline-danger">
                                <i class="fas fa-times"></i> Clear All
                            </a>
                        </div>
                    </div>
                    @endif

                    <!-- Summary Stats -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body py-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0">Total Records</h6>
                                            <h4 class="mb-0">{{ $records->total() }}</h4>
                                        </div>
                                        <i class="fas fa-file-medical-alt fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body py-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0">Public</h6>
                                            <h4 class="mb-0">{{ $records->where('visibility_level', 'public')->count() }}</h4>
                                        </div>
                                        <i class="fas fa-globe fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-dark">
                                <div class="card-body py-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0">Restricted</h6>
                                            <h4 class="mb-0">{{ $records->where('visibility_level', 'restricted')->count() }}</h4>
                                        </div>
                                        <i class="fas fa-users fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-secondary text-white">
                                <div class="card-body py-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0">Private</h6>
                                            <h4 class="mb-0">{{ $records->where('visibility_level', 'private')->count() }}</h4>
                                        </div>
                                        <i class="fas fa-lock fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Success/Error Messages -->
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @if(session('info'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="fas fa-info-circle"></i> {{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <!-- Check if user has permission to view records -->
                    @if(Auth::user()->hasPermission('view-medical-records'))
                    <!-- Records Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>
                                        @if(Auth::user()->hasPermission('view-all-medical-records') || Auth::user()->isAdmin())
                                        <a href="{{ route('medical-records.index', array_merge(request()->query(), ['sort_by' => 'patient_id', 'sort_order' => request('sort_by') == 'patient_id' && request('sort_order') == 'asc' ? 'desc' : 'asc'])) }}"
                                            class="text-decoration-none text-white">
                                            Patient
                                            @if(request('sort_by') == 'patient_id')
                                            <i class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }}"></i>
                                            @else
                                            <i class="fas fa-sort"></i>
                                            @endif
                                        </a>
                                        @else
                                        Patient
                                        @endif
                                    </th>
                                    <!-- Show doctor column for users with appropriate permissions -->
                                    @if(Auth::user()->hasAnyPermission(['view-all-medical-records', 'manage-permissions']) ||
                                    Auth::user()->isAdmin() || Auth::user()->isNurse() || Auth::user()->isLabTechnician())
                                    <th>Doctor</th>
                                    @endif
                                    <th>Diagnosis</th>
                                    <th>
                                        <a href="{{ route('medical-records.index', array_merge(request()->query(), ['sort_by' => 'created_at', 'sort_order' => request('sort_by') == 'created_at' && request('sort_order') == 'asc' ? 'desc' : 'asc'])) }}"
                                            class="text-decoration-none text-dark">
                                            Created At
                                            @if(request('sort_by') == 'created_at')
                                            <i class="fas fa-sort-{{ request('sort_order') == 'asc' ? 'up' : 'down' }}"></i>
                                            @else
                                            <i class="fas fa-sort"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>Visibility</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($records as $record)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-2 avatar-{{ $record->visibility_level }}">
                                                {{ substr($record->patient->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <strong>{{ $record->patient->name }}</strong>
                                                <div class="text-muted small">{{ $record->patient->email }}</div>
                                                @if($record->patient->phone)
                                                <div class="text-muted small">
                                                    <i class="fas fa-phone"></i> {{ $record->patient->phone }}
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <!-- Doctor column with conditional display -->
                                    @if(Auth::user()->hasAnyPermission(['view-all-medical-records', 'manage-permissions']) ||
                                    Auth::user()->isAdmin() || Auth::user()->isNurse() || Auth::user()->isLabTechnician())
                                    <td>
                                        @if($record->doctor)
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle-sm me-2" style="background-color: #17a2b8;">
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
                                        <div class="diagnosis-preview" data-bs-toggle="tooltip"
                                            title="{{ $record->diagnosis }}">
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
                                        <div>
                                            {{ $record->created_at->format('M d, Y') }}
                                            <div class="text-muted small">
                                                {{ $record->created_at->format('h:i A') }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($record->visibility_level == 'public')
                                        <span class="badge bg-success">
                                            <i class="fas fa-globe"></i> Public
                                        </span>
                                        @elseif($record->visibility_level == 'restricted')
                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-users"></i> Restricted
                                        </span>
                                        @else
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-lock"></i> Private
                                        </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            <!-- View button - always visible if user has view permission -->
                                            @if($record->canUserView(Auth::id()) ||
                                            $record->patient_id == Auth::id() ||
                                            $record->doctor_id == Auth::id() ||
                                            Auth::user()->hasPermission('view-all-medical-records') ||
                                            (Auth::user()->isAdmin() && Auth::user()->hasPermission('view-medical-records')))
                                            <a href="{{ route('medical-records.show', $record->id) }}"
                                                class="btn btn-info" title="View" data-bs-toggle="tooltip">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @else
                                            <button class="btn btn-info" disabled title="No access" data-bs-toggle="tooltip">
                                                <i class="fas fa-eye-slash"></i>
                                            </button>
                                            @endif

                                            <!-- Edit button - doctors or permitted users (admins not allowed to edit) -->
                                            @if(Auth::user()->hasPermission('edit-medical-records') && !Auth::user()->isAdmin() &&
                                            ($record->canUserEdit(Auth::id()) ||
                                            (Auth::user()->isDoctor() && $record->doctor_id == Auth::id())))
                                            <a href="{{ route('medical-records.edit', $record->id) }}"
                                                class="btn btn-warning" title="Edit" data-bs-toggle="tooltip">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @endif

                                            <!-- Delete button - only doctors allowed to delete their records -->
                                            @if(Auth::user()->hasPermission('delete-medical-records') &&
                                            (Auth::user()->isDoctor() && $record->doctor_id == Auth::id()))
                                            <button type="button" class="btn btn-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $record->id }}"
                                                title="Delete" data-bs-toggle="tooltip">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            @endif

                                            <!-- Manage Permissions button (record owners and doctors only) -->
                                            @if(Auth::user()->hasPermission('manage-permissions') &&
                                            ((Auth::user()->isDoctor() && $record->doctor_id == Auth::id()) ||
                                            $record->patient_id == Auth::id()))
                                            <button type="button" class="btn btn-secondary manage-permissions-btn"
                                                data-record-id="{{ $record->id }}"
                                                data-record-patient="{{ $record->patient->name }}"
                                                title="Manage Access" data-bs-toggle="tooltip">
                                                <i class="fas fa-user-shield"></i>
                                            </button>
                                            @endif
                                        </div>

                                        <!-- Delete Modal for each record -->
                                        @if(Auth::user()->hasPermission('delete-medical-records') &&
                                        ((Auth::user()->isDoctor() && $record->doctor_id == Auth::id()) ||
                                        (Auth::user()->isAdmin() && Auth::user()->hasPermission('delete-all-medical-records'))))
                                        <div class="modal fade" id="deleteModal{{ $record->id }}" tabindex="-1">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-danger text-white">
                                                        <h5 class="modal-title">Confirm Delete</h5>
                                                        <button type="button" class="btn-close btn-close-white"
                                                            data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to delete this medical record?</p>
                                                        <div class="alert alert-warning">
                                                            <i class="fas fa-exclamation-triangle"></i>
                                                            This action cannot be undone.
                                                        </div>
                                                        <div class="card bg-light">
                                                            <div class="card-body">
                                                                <strong>Patient:</strong> {{ $record->patient->name }}<br>
                                                                <strong>Date:</strong> {{ $record->created_at->format('Y-m-d') }}<br>
                                                                @if($record->diagnosis)
                                                                <strong>Diagnosis:</strong> {{ Str::limit($record->diagnosis, 100) }}
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <form action="{{ route('medical-records.destroy', $record->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger">
                                                                <i class="fas fa-trash"></i> Delete Record
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="{{ 
                                            (Auth::user()->hasAnyPermission(['view-all-medical-records', 'manage-permissions']) || Auth::user()->isAdmin() || Auth::user()->isNurse() || Auth::user()->isLabTechnician()) 
                                            ? '6' : '5' 
                                        }}" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-folder-open fa-3x mb-3"></i>
                                            <h5>No medical records found</h5>
                                            @if(request()->hasAny(['search', 'visibility', 'doctor_id']))
                                            <p>Try clearing your filters or search for something else</p>
                                            <a href="{{ route('medical-records.index') }}" class="btn btn-primary mt-2">
                                                <i class="fas fa-times"></i> Clear Filters
                                            </a>
                                            @else
                                            <p>No medical records have been created yet.</p>
                                            @if(Auth::user()->hasPermission('create-medical-records'))
                                            <a href="{{ route('medical-records.create') }}"
                                                class="btn btn-primary mt-2">
                                                <i class="fas fa-plus"></i> Create First Record
                                            </a>
                                            @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($records->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted">
                            Showing {{ $records->firstItem() }} to {{ $records->lastItem() }} of {{ $records->total() }} records
                        </div>

                        <nav aria-label="Page navigation">
                            <ul class="pagination mb-0">
                                {{-- Previous Page Link --}}
                                @if($records->onFirstPage())
                                <li class="page-item disabled">
                                    <span class="page-link"><i class="fas fa-chevron-left"></i></span>
                                </li>
                                @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $records->previousPageUrl() . '&' . http_build_query(request()->except('page')) }}"
                                        aria-label="Previous">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                                @endif

                                {{-- Pagination Elements --}}
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

                                {{-- Next Page Link --}}
                                @if($records->hasMorePages())
                                <li class="page-item">
                                    <a class="page-link" href="{{ $records->nextPageUrl() . '&' . http_build_query(request()->except('page')) }}"
                                        aria-label="Next">
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

                        @if(Auth::user()->hasPermission('view-all-medical-records') || Auth::user()->isAdmin())
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Per Page: {{ $records->perPage() }}
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['per_page' => 10]) }}">
                                        10 per page
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['per_page' => 25]) }}">
                                        25 per page
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['per_page' => 50]) }}">
                                        50 per page
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['per_page' => 100]) }}">
                                        100 per page
                                    </a>
                                </li>
                            </ul>
                        </div>
                        @endif
                    </div>
                    @endif
                    @else
                    <!-- No permission message -->
                    <div class="text-center py-5">
                        <div class="text-muted">
                            <i class="fas fa-ban fa-3x mb-3 text-danger"></i>
                            <h5>Access Denied</h5>
                            <p>You do not have permission to view medical records.</p>
                            <a href="{{ route('dashboard') }}" class="btn btn-primary mt-2">
                                <i class="fas fa-arrow-left"></i> Back to Dashboard
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Export Modal -->
<div class="modal fade" id="exportModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-download"></i> Export Medical Records
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Select format to export medical records:</p>
                <div class="row g-3">
                    <div class="col-md-4">
                        <a href="{{ route('medical-records.patients.export', 'pdf') . '?' . http_build_query(request()->query()) }}"
                            class="btn btn-danger w-100">
                            <i class="fas fa-file-pdf"></i> PDF
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('medical-records.patients.export', 'excel') . '?' . http_build_query(request()->query()) }}"
                            class="btn btn-success w-100">
                            <i class="fas fa-file-excel"></i> Excel
                        </a>
                    </div>
                    <div class="col-md-4">
                        <a href="{{ route('medical-records.patients.export', 'csv') . '?' . http_build_query(request()->query()) }}"
                            class="btn btn-primary w-100">
                            <i class="fas fa-file-csv"></i> CSV
                        </a>
                    </div>
                </div>
                <div class="mt-3">
                    <p class="small text-muted">
                        <i class="fas fa-info-circle"></i> Current filters will be applied to the export.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Manage Permissions Modal -->
<div class="modal fade" id="managePermissionsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-user-shield"></i> Manage Record Access
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="permissionContent">
                    <!-- Content will be loaded via AJAX -->
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading permissions...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="savePermissionsBtn" style="display: none;">
                    <i class="fas fa-save"></i> Save Permissions
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .avatar-circle {
        width: 36px;
        height: 36px;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 14px;
    }

    .avatar-circle-sm {
        width: 28px;
        height: 28px;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 12px;
    }

    .table th {
        font-weight: 600;
        white-space: nowrap;
    }

    .btn-group-sm .btn {
        padding: 0.25rem 0.5rem;
    }

    .diagnosis-preview {
        cursor: help;
        max-width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
        transition: background-color 0.2s;
    }

    .badge {
        font-size: 0.75em;
        padding: 0.35em 0.65em;
    }

    .card {
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .card-header {
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .form-label {
        font-size: 0.875rem;
        font-weight: 500;
        margin-bottom: 0.25rem;
    }

    .input-group-text {
        background-color: #f8f9fa;
    }

    .avatar-private {
        background-color: #6c757d;
    }

    .avatar-restricted {
        background-color: #ffc107;
    }

    .avatar-public {
        background-color: #28a745;
    }

    .permission-checkbox {
        transform: scale(1.2);
        margin: 0 5px;
    }

    .user-permission-row:hover {
        background-color: #f8f9fa;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);

        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Auto-submit search on enter
        $('#searchForm input[name="search"]').on('keyup', function(e) {
            if (e.keyCode === 13) { // Enter key
                $('#searchForm').submit();
            }
        });

        // Clear individual search field
        $(document).on('click', '.clear-field', function() {
            var field = $(this).data('field');
            var url = new URL(window.location.href);
            url.searchParams.delete(field);
            window.location.href = url.toString();
        });

        // Clear date range
        $('.clear-date').on('click', function() {
            $('input[name="date_from"], input[name="date_to"]').val('');
            $('#searchForm').submit();
        });

        // Submit form on select change for some fields
        $('select[name="visibility"], select[name="doctor_id"], select[name="patient_id"]').on('change', function() {
            $('#searchForm').submit();
        });

        // Show loading indicator on form submit
        $('#searchForm, #advancedForm').on('submit', function() {
            $('.btn[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Loading...');
        });

        // View full diagnosis in modal
        $(document).on('click', '.diagnosis-preview', function() {
            var diagnosis = $(this).attr('title');
            if (diagnosis && diagnosis.length > 50) {
                $('#diagnosisModal .modal-body').text(diagnosis);
                $('#diagnosisModal').modal('show');
            }
        });

        // Delete confirmation with additional check
        $(document).on('submit', 'form[action*="medical-records/"][method="DELETE"]', function(e) {
            if (!confirm('Are you absolutely sure you want to delete this medical record?\n\nThis action cannot be undone and all associated data will be permanently removed.')) {
                e.preventDefault();
                return false;
            }

            // Show loading on delete button
            $(this).find('button[type="submit"]').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Deleting...');
        });

        // Quick search on patient name click
        $(document).on('click', '.patient-name-search', function(e) {
            e.preventDefault();
            var patientName = $(this).data('name');
            $('#searchForm input[name="search"]').val(patientName);
            $('#searchForm').submit();
        });

        // Toggle advanced filters
        $('#advancedFilters').on('show.bs.collapse', function() {
            $('[href="#advancedFilters"] i').removeClass('fa-sliders-h').addClass('fa-chevron-up');
        }).on('hide.bs.collapse', function() {
            $('[href="#advancedFilters"] i').removeClass('fa-chevron-up').addClass('fa-sliders-h');
        });

        // Export modal handling
        $('#exportModal').on('show.bs.modal', function() {
            // Update export links with current filters
            var queryString = window.location.search;
            $('#exportModal .btn').each(function() {
                var href = $(this).attr('href').split('?')[0];
                $(this).attr('href', href + queryString);
            });
        });

        // Manage Permissions Modal
        $(document).on('click', '.manage-permissions-btn', function() {
            var recordId = $(this).data('record-id');
            var patientName = $(this).data('record-patient');

            // Show loading in modal
            $('#permissionContent').html(`
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading permissions for ${patientName}'s record...</p>
                </div>
            `);

            // Show modal
            $('#managePermissionsModal').modal('show');

            // Load permission data via AJAX
            $.ajax({
                url: `/medical-records/${recordId}/available-users`,
                method: 'GET',
                success: function(response) {
                    if (response.success) {
                        loadPermissionsData(recordId, response.users, patientName);
                    } else {
                        $('#permissionContent').html(`
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle"></i> Error loading permissions: ${response.error}
                            </div>
                        `);
                    }
                },
                error: function(xhr) {
                    $('#permissionContent').html(`
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i> Error loading permissions. Please try again.
                        </div>
                    `);
                }
            });
        });

        // Function to load permissions data
        function loadPermissionsData(recordId, users, patientName) {
            // Get current permissions for this record
            $.ajax({
                url: `/medical-records/${recordId}/permissions`,
                method: 'GET',
                success: function(permissionsResponse) {
                    // Build the permissions management form
                    var html = `
                        <div class="mb-3">
                            <h6>Manage access for: <strong>${patientName}</strong></h6>
                            <p class="text-muted small">Select users who can view or edit this medical record.</p>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>User</th>
                                        <th>Role</th>
                                        <th class="text-center">Can View</th>
                                        <th class="text-center">Can Edit</th>
                                    </tr>
                                </thead>
                                <tbody>`;

                    // Add users to the table
                    users.forEach(function(user) {
                        var userHasView = false;
                        var userHasEdit = false;

                        // Check if user has existing permissions
                        if (permissionsResponse.permissions) {
                            var existingPermission = permissionsResponse.permissions.find(p => p.user_id == user.id);
                            if (existingPermission) {
                                userHasView = existingPermission.can_view || false;
                                userHasEdit = existingPermission.can_edit || false;
                            }
                        }

                        html += `
                            <tr class="user-permission-row" data-user-id="${user.id}">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle-sm me-2" style="background-color: #${getRandomColor(user.id)};">
                                            ${user.name.charAt(0)}
                                        </div>
                                        <div>
                                            <strong>${user.name}</strong>
                                            <div class="text-muted small">${user.email}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info">${user.role.name}</span>
                                </td>
                                <td class="text-center">
                                    <input type="checkbox" class="permission-checkbox view-permission" 
                                           data-user-id="${user.id}"
                                           ${userHasView ? 'checked' : ''}>
                                </td>
                                <td class="text-center">
                                    <input type="checkbox" class="permission-checkbox edit-permission" 
                                           data-user-id="${user.id}"
                                           ${userHasEdit ? 'checked' : ''}>
                                </td>
                            </tr>`;
                    });

                    html += `
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <small>
                                <strong>Note:</strong> The patient and doctor already have access to this record.
                                Doctors can edit records they create. Admins with special permissions can edit all records.
                            </small>
                        </div>`;

                    $('#permissionContent').html(html);
                    $('#savePermissionsBtn').show();

                    // Store record ID for saving
                    $('#savePermissionsBtn').data('record-id', recordId);
                },
                error: function() {
                    $('#permissionContent').html(`
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i> Error loading existing permissions.
                        </div>
                    `);
                }
            });
        }

        // Save permissions
        $('#savePermissionsBtn').on('click', function() {
            var recordId = $(this).data('record-id');
            var permissions = [];

            // Collect permissions from checkboxes
            $('.user-permission-row').each(function() {
                var userId = $(this).data('user-id');
                var canView = $(this).find('.view-permission').is(':checked');
                var canEdit = $(this).find('.edit-permission').is(':checked');

                permissions.push({
                    user_id: userId,
                    can_view: canView,
                    can_edit: canEdit
                });
            });

            // Show loading
            $(this).prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');

            // Save permissions
            $.ajax({
                url: `/medical-records/${recordId}/permissions`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    permissions: permissions
                },
                success: function(response) {
                    if (response.success) {
                        // Show success message
                        $('#permissionContent').html(`
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i> Permissions saved successfully!
                            </div>
                        `);

                        // Hide save button
                        $('#savePermissionsBtn').hide();

                        // Close modal after 2 seconds
                        setTimeout(function() {
                            $('#managePermissionsModal').modal('hide');
                            // Reload page to reflect changes
                            location.reload();
                        }, 2000);
                    } else {
                        $('#permissionContent').prepend(`
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-circle"></i> ${response.error}
                            </div>
                        `);
                        $('#savePermissionsBtn').prop('disabled', false).html('<i class="fas fa-save"></i> Save Permissions');
                    }
                },
                error: function(xhr) {
                    var errorMessage = xhr.responseJSON && xhr.responseJSON.error ?
                        xhr.responseJSON.error :
                        'Error saving permissions. Please try again.';

                    $('#permissionContent').prepend(`
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-circle"></i> ${errorMessage}
                        </div>
                    `);
                    $('#savePermissionsBtn').prop('disabled', false).html('<i class="fas fa-save"></i> Save Permissions');
                }
            });
        });

        // Helper function to generate consistent colors
        function getRandomColor(seed) {
            var colors = ['17a2b8', '28a745', 'dc3545', 'ffc107', '6c757d', '007bff'];
            return colors[seed % colors.length];
        }

        // Close modal reset
        $('#managePermissionsModal').on('hidden.bs.modal', function() {
            $('#permissionContent').html(`
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading permissions...</p>
                </div>
            `);
            $('#savePermissionsBtn').hide().prop('disabled', false).html('<i class="fas fa-save"></i> Save Permissions');
        });
    });
</script>
@endpush