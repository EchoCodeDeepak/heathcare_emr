@extends('layouts.app')

@section('content')
<div class="container py-3">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-eye"></i> Lab Result Details
                    </h4>
                    <div>
                        <a href="{{ route('lab-results.edit', $record) }}" class="btn btn-primary btn-sm me-2">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('lab-results.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Patient Information -->
                    <div class="card mb-4 border-primary">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-user"></i> Patient Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Name:</strong> {{ $record->patient->name }}</p>
                                    <p><strong>Email:</strong> {{ $record->patient->email }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Role:</strong>
                                        <span class="badge bg-{{ 
                                            $record->patient->role_id == 1 ? 'danger' : 
                                            ($record->patient->role_id == 2 ? 'info' : 
                                            ($record->patient->role_id == 3 ? 'secondary' : 
                                            ($record->patient->role_id == 4 ? 'warning' : 'success')))
                                        }}">
                                            {{ $record->patient->role->name ?? 'N/A' }}
                                        </span>
                                    </p>
                                    <p><strong>Record ID:</strong> {{ $record->id }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Lab Results -->
                    <div class="card mb-4 border-success">
                        <div class="card-header bg-success text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-flask"></i> Lab Results
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="bg-light p-3 rounded">
                                        <pre class="mb-0" style="white-space: pre-wrap; font-family: 'Courier New', monospace;">{{ $record->lab_results }}</pre>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="card border-info">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-info-circle"></i> Additional Information
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Created At:</strong> {{ $record->created_at->format('M d, Y \a\t H:i') }}</p>
                                    <p><strong>Last Updated:</strong> {{ $record->updated_at->format('M d, Y \a\t H:i') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Doctor/Lab Tech:</strong> {{ $record->doctor->name ?? 'N/A' }}</p>
                                    <p><strong>Visibility:</strong>
                                        <span class="badge bg-{{ $record->visibility_level == 'public' ? 'success' : ($record->visibility_level == 'restricted' ? 'warning' : 'secondary') }}">
                                            {{ ucfirst($record->visibility_level) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="row mt-4">
                        <div class="col-md-4">
                            <a href="{{ route('lab-results.index') }}" class="btn btn-secondary w-100">
                                <i class="fas fa-list"></i> Back to Lab Results
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('lab-results.edit', $record) }}" class="btn btn-primary w-100">
                                <i class="fas fa-edit"></i> Edit Lab Result
                            </a>
                        </div>
                        <div class="col-md-4">
                            <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <i class="fas fa-trash"></i> Remove Lab Result
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
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
</div>
@endsection