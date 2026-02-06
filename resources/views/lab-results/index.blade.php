@extends('layouts.app')

@section('title', 'Lab Results')

@section('content')
<div class="main-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1 class="page-title">Lab Results</h1>
            <p class="page-subtitle">View and manage patient lab results</p>
        </div>
        @if(auth()->user()->hasPermission('add-lab-results'))
        <a href="{{ route('lab-results.create') }}" class="btn btn-success">
            <i class="fas fa-plus me-2"></i> Add Lab Result
        </a>
        @endif
    </div>
</div>

<!-- Search and Filter Section -->
<div class="filter-section">
    <form id="searchForm" method="GET" action="{{ route('lab-results.index') }}" class="filter-row">
        <div class="filter-group">
            <label class="form-label">Search</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input type="text" name="search" class="form-control"
                    placeholder="Search by patient name, email, or lab results..."
                    value="{{ request('search') }}">
            </div>
        </div>

        <div class="filter-group">
            <label class="form-label">Patient</label>
            <select name="patient_id" class="form-select" onchange="this.form.submit()">
                <option value="">All Patients</option>
                @foreach($patients as $patient)
                <option value="{{ $patient->id }}" {{ request('patient_id') == $patient->id ? 'selected' : '' }}>
                    {{ $patient->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="filter-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-filter me-1"></i> Filter
            </button>
            @if(request()->has('search') || request()->has('patient_id'))
            <a href="{{ route('lab-results.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-times me-1"></i> Clear
            </a>
            @endif
        </div>
    </form>
</div>

<!-- Results Table Card -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Patient</th>
                        <th>Lab Results</th>
                        <th>Lab Technician</th>
                        <th>Updated</th>
                        <th style="width: 140px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($labResults as $record)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-circle primary">
                                    {{ substr($record->patient->name, 0, 1) }}
                                </div>
                                <div>
                                    <strong>{{ $record->patient->name }}</strong>
                                    <div class="text-muted small">{{ $record->patient->email }}</div>
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
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-circle" style="background: linear-gradient(135deg, var(--primary-400), var(--primary-500));">
                                    {{ substr($record->doctor->name, 0, 1) }}
                                </div>
                                <div>
                                    <strong>{{ $record->doctor->name }}</strong>
                                    <div class="text-muted small">Lab Technician</div>
                                </div>
                            </div>
                            @else
                            <span class="text-muted">Not assigned</span>
                            @endif
                        </td>
                        <td>
                            <div>{{ $record->updated_at->format('M d, Y') }}</div>
                            <div class="text-muted small">{{ $record->updated_at->format('h:i A') }}</div>
                        </td>
                        <td>
                            <div class="table-actions">
                                @if(auth()->user()->hasPermission('view-lab-results'))
                                <a href="{{ route('lab-results.show', $record) }}" class="btn btn-icon btn-primary" title="View Lab Result" data-bs-toggle="tooltip">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @endif
                                @if(auth()->user()->hasPermission('edit-lab-results'))
                                <a href="{{ route('lab-results.edit', $record) }}" class="btn btn-icon btn-success" title="Edit Lab Result" data-bs-toggle="tooltip">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endif
                                @if(auth()->user()->hasPermission('delete-lab-results'))
                                <button type="button" class="btn btn-icon btn-danger" title="Delete Lab Result"
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
                                <div class="modal-header">
                                    <h5 class="modal-title">Confirm Delete</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to remove lab results for <strong>{{ $record->patient->name }}</strong>?</p>
                                    <p class="text-danger mb-0"><i class="fas fa-exclamation-triangle me-2"></i>This action cannot be undone!</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <form action="{{ route('lab-results.destroy', $record) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-trash me-1"></i> Remove Lab Results
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-flask-vial"></i>
                                </div>
                                <h5 class="empty-state-title">No Lab Results Found</h5>
                                <p class="empty-state-text">
                                    @if(request()->has('search') || request()->has('patient_id'))
                                    No lab results match your criteria.
                                    @else
                                    No lab results have been added yet.
                                    @endif
                                </p>
                                @if(auth()->user()->hasPermission('add-lab-results'))
                                <a href="{{ route('lab-results.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i> Add First Lab Result
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
@if($labResults->hasPages())
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-4">
    <div class="text-muted">
        Showing {{ $labResults->firstItem() ?? 0 }} to {{ $labResults->lastItem() ?? 0 }} of {{ $labResults->total() }} lab results
    </div>
    <nav aria-label="Page navigation">
        {{ $labResults->appends(request()->query())->links() }}
    </nav>
</div>
@endif
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.querySelector('input[name="search"]');
        if (searchInput) {
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    this.form.submit();
                }
            });
        }
    });
</script>
@endpush
