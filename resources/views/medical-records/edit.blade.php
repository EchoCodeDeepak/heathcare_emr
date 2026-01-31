@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-edit"></i> Edit Medical Record
                        </h4>
                        <a href="{{ route('medical-records.show', $record->id) }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Record
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('medical-records.update', $record->id) }}" id="medicalRecordForm">
                        @csrf
                        @method('PUT')

                        <!-- Patient Information (Read-only) -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="card shadow-sm">
                                    <div class="card-header bg-info text-white py-2">
                                        <h6 class="mb-0"><i class="fas fa-user-injured"></i> Patient Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label fw-bold">Patient</label>
                                                    <input type="text" class="form-control" 
                                                           value="{{ $record->patient->name }} ({{ $record->patient->email }})" 
                                                           readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="alert alert-info">
                                                    <small>
                                                        <i class="fas fa-info-circle"></i> 
                                                        Record created by <strong>{{ $record->doctor->name ?? 'N/A' }}</strong><br>
                                                        Last updated: {{ $record->updated_at->format('M d, Y h:i A') }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Vital Signs -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="card shadow-sm">
                                    <div class="card-header bg-success text-white py-2">
                                        <h6 class="mb-0"><i class="fas fa-heartbeat"></i> Vital Signs</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="blood_pressure" class="form-label">Blood Pressure</label>
                                                    <input type="text" name="blood_pressure" id="blood_pressure"
                                                        class="form-control @error('blood_pressure') is-invalid @enderror"
                                                        value="{{ old('blood_pressure', $record->blood_pressure) }}"
                                                        placeholder="e.g., 120/80">
                                                    @error('blood_pressure')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="temperature" class="form-label">Temperature (°C)</label>
                                                    <input type="number" step="0.1" name="temperature" id="temperature"
                                                        class="form-control @error('temperature') is-invalid @enderror"
                                                        value="{{ old('temperature', $record->temperature) }}"
                                                        placeholder="e.g., 36.6">
                                                    @error('temperature')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="pulse_rate" class="form-label">Pulse Rate (BPM)</label>
                                                    <input type="number" name="pulse_rate" id="pulse_rate"
                                                        class="form-control @error('pulse_rate') is-invalid @enderror"
                                                        value="{{ old('pulse_rate', $record->pulse_rate) }}"
                                                        placeholder="e.g., 72">
                                                    @error('pulse_rate')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="weight" class="form-label">Weight (kg)</label>
                                                    <input type="number" step="0.1" name="weight" id="weight"
                                                        class="form-control @error('weight') is-invalid @enderror"
                                                        value="{{ old('weight', $record->weight) }}"
                                                        placeholder="e.g., 70.5">
                                                    @error('weight')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="height" class="form-label">Height (cm)</label>
                                                    <input type="number" step="0.1" name="height" id="height"
                                                        class="form-control @error('height') is-invalid @enderror"
                                                        value="{{ old('height', $record->height) }}"
                                                        placeholder="e.g., 175">
                                                    @error('height')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Medical Information -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="card shadow-sm">
                                    <div class="card-header bg-warning text-dark py-2">
                                        <h6 class="mb-0"><i class="fas fa-stethoscope"></i> Medical Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="medical_history" class="form-label fw-bold">Medical History</label>
                                                    <textarea name="medical_history" id="medical_history"
                                                        class="form-control @error('medical_history') is-invalid @enderror"
                                                        rows="3">{{ old('medical_history', $record->medical_history) }}</textarea>
                                                    @error('medical_history')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="diagnosis" class="form-label fw-bold">Diagnosis</label>
                                                    <textarea name="diagnosis" id="diagnosis"
                                                        class="form-control @error('diagnosis') is-invalid @enderror"
                                                        rows="3">{{ old('diagnosis', $record->diagnosis) }}</textarea>
                                                    @error('diagnosis')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="prescription" class="form-label fw-bold">Prescription</label>
                                                    <textarea name="prescription" id="prescription"
                                                        class="form-control @error('prescription') is-invalid @enderror"
                                                        rows="3">{{ old('prescription', $record->prescription) }}</textarea>
                                                    @error('prescription')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="lab_results" class="form-label fw-bold">Lab Results</label>
                                                    <textarea name="lab_results" id="lab_results"
                                                        class="form-control @error('lab_results') is-invalid @enderror"
                                                        rows="3">{{ old('lab_results', $record->lab_results) }}</textarea>
                                                    @error('lab_results')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="allergies" class="form-label">Allergies</label>
                                                    <textarea name="allergies" id="allergies"
                                                        class="form-control @error('allergies') is-invalid @enderror"
                                                        rows="2">{{ old('allergies', $record->allergies) }}</textarea>
                                                    @error('allergies')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="notes" class="form-label">Additional Notes</label>
                                                    <textarea name="notes" id="notes"
                                                        class="form-control @error('notes') is-invalid @enderror"
                                                        rows="3">{{ old('notes', $record->notes) }}</textarea>
                                                    @error('notes')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Privacy Settings -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="card shadow-sm">
                                    <div class="card-header bg-secondary text-white py-2">
                                        <h6 class="mb-0"><i class="fas fa-shield-alt"></i> Privacy Settings</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="visibility_level" class="form-label fw-bold">Record Visibility *</label>
                                                    <select name="visibility_level" id="visibility_level"
                                                        class="form-control form-select @error('visibility_level') is-invalid @enderror"
                                                        required>
                                                        <option value="">-- Select Visibility Level --</option>
                                                        <option value="private" {{ old('visibility_level', $record->visibility_level) == 'private' ? 'selected' : '' }}>Private (Only you and the patient)</option>
                                                        <option value="restricted" {{ old('visibility_level', $record->visibility_level) == 'restricted' ? 'selected' : '' }}>Restricted (Selected staff only)</option>
                                                        <option value="public" {{ old('visibility_level', $record->visibility_level) == 'public' ? 'selected' : '' }}>Public (All authorized medical staff)</option>
                                                    </select>
                                                    @error('visibility_level')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <a href="{{ route('medical-records.show', $record->id) }}" class="btn btn-secondary">
                                            <i class="fas fa-times"></i> Cancel
                                        </a>
                                        @if(Auth::user()->isAdmin() || $record->doctor_id == Auth::id())
                                        <button type="button" class="btn btn-danger ms-2" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                            <i class="fas fa-trash"></i> Delete Record
                                        </button>
                                        @endif
                                    </div>
                                    <div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Update Medical Record
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle"></i> Confirm Deletion
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this medical record?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-circle"></i> 
                    <strong>Warning:</strong> This action cannot be undone. All associated data including access permissions will be permanently deleted.
                </div>
                <p><strong>Patient:</strong> {{ $record->patient->name }}</p>
                <p><strong>Diagnosis:</strong> {{ Str::limit($record->diagnosis, 100) }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('medical-records.destroy', $record->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Delete Permanently
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Form validation
        $('#medicalRecordForm').on('submit', function(e) {
            let visibility = $('#visibility_level').val();

            if (!visibility) {
                e.preventDefault();
                toastr.error('Please select a visibility level');
                $('#visibility_level').focus();
                return false;
            }

            // Show loading indicator
            $('button[type="submit"]').html('<i class="fas fa-spinner fa-spin"></i> Updating...').prop('disabled', true);
        });

        // Calculate BMI
        function calculateBMI() {
            let weight = parseFloat($('#weight').val()) || parseFloat('{{ $record->weight }}');
            let height = parseFloat($('#height').val()) || parseFloat('{{ $record->height }}');

            if (weight && height && height > 0) {
                let heightInMeters = height / 100;
                let bmi = weight / (heightInMeters * heightInMeters);
                return bmi.toFixed(1);
            }
            return null;
        }

        // Show BMI
        $('#weight, #height').on('change', function() {
            let bmi = calculateBMI();
            if (bmi) {
                $(this).siblings('.bmi-info').remove();
                $(this).after('<small class="text-success bmi-info">BMI: ' + bmi + '</small>');
            }
        });

        // Initialize BMI display
        let initialBMI = calculateBMI();
        if (initialBMI) {
            $('#weight').after('<small class="text-success bmi-info">BMI: ' + initialBMI + '</small>');
        }
    });
</script>
@endpush