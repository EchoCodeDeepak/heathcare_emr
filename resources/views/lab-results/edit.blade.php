@extends('layouts.app')

@section('content')
<div class="container py-3">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">
                        <i class="fas fa-edit"></i> Edit Lab Result
                    </h4>
                </div>

                <div class="card-body">
                    <div class="mb-3">
                        <strong>Patient:</strong> {{ $record->patient->name }} ({{ $record->patient->email }})
                    </div>

                    <form action="{{ route('lab-results.update', $record) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="lab_results" class="form-label fw-bold">Lab Results <span class="text-danger">*</span></label>
                            <textarea name="lab_results" id="lab_results" class="form-control @error('lab_results') is-invalid @enderror"
                                rows="8" placeholder="Enter lab test results, findings, and any relevant notes..."
                                required>{{ old('lab_results', $record->lab_results) }}</textarea>
                            @error('lab_results')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <small class="text-muted">Include test names, values, reference ranges, and interpretations.</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <a href="{{ route('lab-results.show', $record) }}" class="btn btn-secondary w-100">
                                    <i class="fas fa-arrow-left"></i> Back to Details
                                </a>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-warning w-100">
                                    <i class="fas fa-save"></i> Update Lab Result
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Auto-resize textarea
        $('#lab_results').on('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });

        // Set initial height
        $('#lab_results').trigger('input');
    });
</script>
@endpush