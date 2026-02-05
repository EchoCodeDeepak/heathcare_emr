@extends('layouts.app')

@section('content')
<div class="container py-3">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header  text-white d-flex justify-content-between align-items-center" style="background-color: #0891b2;">
                    <h4 class="mb-0">
                        <i class="fas fa-flask-vial"></i> Lab Results Management
                    </h4>
                    @if(auth()->user()->hasPermission('add-lab-results'))
                    <a href="{{ route('lab-results.create') }}" class="btn btn-light btn-sm">
                        <i class="fas fa-plus"></i> Add Lab Result
                    </a>
                    @endif
                </div>

                <div class="card-body">
                    <!-- Search and Filter Section -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <form id="searchForm" method="GET" action="{{ route('lab-results.index') }}" class="row g-3">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                        <input type="text" name="search" class="form-control"
                                            placeholder="Search by patient name, email, or lab results..."
                                            value="{{ request('search') }}">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <select name="patient_id" class="form-control" onchange="this.form.submit()">
                                        <option value="">All Patients</option>
                                        @foreach($patients as $patient)
                                        <option value="{{ $patient->id }}" {{ request('patient_id') == $patient->id ? 'selected' : '' }}>
                                            {{ $patient->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-filter"></i> Filter
                                    </button>
                                </div>

                                <div class="col-md-3 text-end">
                                    <a href="{{ route('lab-results.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Clear Filters
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Results Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th><i class="fas fa-user"></i> Patient</th>
                                    <th><i class="fas fa-flask"></i> Lab Results</th>
                                    <th><i class="fas fa-user-md"></i> Lab Technician</th>
                                    <th><i class="fas fa-calendar"></i> Updated</th>
                                    <th><i class="fas fa-cogs"></i> Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($labResults as $record)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-2">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $record->patient->name }}</div>
                                                <small class="text-muted">{{ $record->patient->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-truncate" style="max-width: 300px;" title="{{ $record->lab_results }}">
                                            {{ Str::limit($record->lab_results, 50) }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($record->doctor)
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle me-2">
                                                <i class="fas fa-user-md"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold">{{ $record->doctor->name }}</div>
                                                <small class="text-muted">Lab Technician</small>
                                            </div>
                                        </div>
                                        @else
                                        <span class="text-muted">Not assigned</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div>
                                            <div>{{ $record->updated_at->format('M d, Y') }}</div>
                                            <small class="text-muted">{{ $record->updated_at->format('H:i') }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            @if(auth()->user()->hasPermission('view-lab-results'))
                                            <a href="{{ route('lab-results.show', $record) }}" class="btn btn-sm btn-outline-primary" title="View Lab Result" data-bs-toggle="tooltip">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @endif
                                            @if(auth()->user()->hasPermission('edit-lab-results'))
                                            <a href="{{ route('lab-results.edit', $record) }}" class="btn btn-sm btn-outline-primary" title="Edit Lab Result" data-bs-toggle="tooltip">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @endif
                                            @if(auth()->user()->hasPermission('delete-lab-results'))
                                            <button type="button" class="btn btn-sm btn-outline-danger" title="Delete Lab Result"
                                                data-bs-toggle="modal" data-bs-target="#deleteModal{{ $record->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                <!-- Delete Confirmation Modal -->
                                @if(auth()->user()->hasPermission('delete-lab-results'))
                                <div class="modal fade" id="deleteModal{{ $record->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">Confirm Delete</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Are you sure you want to remove lab results for <strong>{{ $record->patient->name }}</strong>?</p>
                                                <p class="text-danger"><i class="fas fa-exclamation-triangle"></i> This action cannot be undone!</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{ route('lab-results.destroy', $record) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="fas fa-trash"></i> Remove Lab Results
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-flask-vial fa-3x mb-3"></i>
                                            <h5>No Lab Results Found</h5>
                                            <p>There are no lab results matching your criteria.</p>
                                            @if(auth()->user()->hasPermission('add-lab-results'))
                                            <a href="{{ route('lab-results.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus"></i> Add First Lab Result
                                            </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($labResults->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $labResults->appends(request()->query())->links() }}
                    </div>
                    @endif

                    <!-- Results Summary -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="text-muted text-center">
                                <small>
                                    Showing {{ $labResults->firstItem() ?? 0 }} to {{ $labResults->lastItem() ?? 0 }}
                                    of {{ $labResults->total() }} lab results
                                    @if(request('search') || request('patient_id'))
                                    (filtered)
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .avatar-circle {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background-color: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
        font-size: 0.875rem;
    }

    .table th {
        border-top: none;
        font-weight: 600;
    }

    .btn-group .btn {
        margin-right: 2px;
    }

    .text-truncate {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-submit search form on enter
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    this.form.submit();
                }
            });
        }

        // Clear search on escape key
        if (searchInput) {
            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    this.value = '';
                    this.form.submit();
                }
            });
        }
    });
</script>
@endpush