@extends('layouts.app')

@section('content')
<div class="container py-3">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-plus"></i> Add Lab Result
                    </h4>
                </div>

                <div class="card-body">
                    <form action="{{ route('lab-results.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="patient_id" class="form-label fw-bold">Patient <span class="text-danger">*</span></label>
                            <select name="patient_id" id="patient_id" class="form-control @error('patient_id') is-invalid @enderror" required>
                                <option value="">Select a patient...</option>
                                @foreach($patients as $patient)
                                <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                    {{ $patient->name }} ({{ $patient->email }})
                                </option>
                                @endforeach
                            </select>
                            @error('patient_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="lab_results" class="form-label fw-bold">Lab Results <span class="text-danger">*</span></label>
                            <textarea name="lab_results" id="lab_results" class="form-control @error('lab_results') is-invalid @enderror"
                                rows="8" placeholder="Enter lab test results, findings, and any relevant notes..."
                                required>{{ old('lab_results') }}</textarea>
                            @error('lab_results')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                <small class="text-muted">Include test names, values, reference ranges, and interpretations.</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <a href="{{ route('lab-results.index') }}" class="btn btn-secondary w-100">
                                    <i class="fas fa-arrow-left"></i> Back to Lab Results
                                </a>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-success w-100">
                                    <i class="fas fa-save"></i> Save Lab Result
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
    });
</script>
@endpush