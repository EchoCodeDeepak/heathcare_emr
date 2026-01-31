@extends('layouts.app')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-file-medical-alt"></i> Create New Medical Record
                        </h4>
                        <a href="{{ route('medical-records.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Records
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Main Medical Record Form -->
                    <form method="POST" action="{{ route('medical-records.store') }}" id="medicalRecordForm">
                        @csrf

                        <!-- Patient Selection Section -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="card shadow-sm">
                                    <div class="card-header bg-info text-white py-2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0"><i class="fas fa-user-injured"></i> Patient Information</h6>
                                            <button type="button" class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#newPatientModal">
                                                <i class="fas fa-plus"></i> New Patient
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="patient_id" class="form-label fw-bold">
                                                        <i class="fas fa-user text-primary"></i> Select Patient *
                                                    </label>
                                                    <div class="input-group">
                                                        <select name="patient_id" id="patient_id"
                                                            class="form-control form-select @error('patient_id') is-invalid @enderror"
                                                            required>
                                                            <option value="">-- Select a Patient --</option>
                                                            @foreach($patients as $patient)
                                                            <option value="{{ $patient->id }}"
                                                                {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                                                                {{ $patient->name }} ({{ $patient->email }})
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#newPatientModal">
                                                            <i class="fas fa-user-plus"></i> Add New
                                                        </button>
                                                    </div>
                                                    @error('patient_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                    <small class="text-muted mt-1 d-block">
                                                        <i class="fas fa-info-circle"></i> Can't find the patient?
                                                        <a href="#" class="text-primary" data-bs-toggle="modal" data-bs-target="#newPatientModal">
                                                            Click here to add a new patient
                                                        </a>
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Rest of the form remains the same... -->
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
                                                    <label for="blood_pressure" class="form-label">
                                                        <i class="fas fa-tachometer-alt text-danger"></i> Blood Pressure
                                                    </label>
                                                    <input type="text" name="blood_pressure" id="blood_pressure"
                                                        class="form-control @error('blood_pressure') is-invalid @enderror"
                                                        value="{{ old('blood_pressure') }}"
                                                        placeholder="e.g., 120/80">
                                                    @error('blood_pressure')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="temperature" class="form-label">
                                                        <i class="fas fa-thermometer-half text-warning"></i> Temperature (°C)
                                                    </label>
                                                    <input type="number" step="0.1" name="temperature" id="temperature"
                                                        class="form-control @error('temperature') is-invalid @enderror"
                                                        value="{{ old('temperature') }}"
                                                        placeholder="e.g., 36.6">
                                                    @error('temperature')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="pulse_rate" class="form-label">
                                                        <i class="fas fa-heart text-danger"></i> Pulse Rate (BPM)
                                                    </label>
                                                    <input type="number" name="pulse_rate" id="pulse_rate"
                                                        class="form-control @error('pulse_rate') is-invalid @enderror"
                                                        value="{{ old('pulse_rate') }}"
                                                        placeholder="e.g., 72">
                                                    @error('pulse_rate')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="weight" class="form-label">
                                                        <i class="fas fa-weight text-info"></i> Weight (kg)
                                                    </label>
                                                    <input type="number" step="0.1" name="weight" id="weight"
                                                        class="form-control @error('weight') is-invalid @enderror"
                                                        value="{{ old('weight') }}"
                                                        placeholder="e.g., 70.5">
                                                    @error('weight')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="height" class="form-label">
                                                        <i class="fas fa-ruler-vertical text-success"></i> Height (cm)
                                                    </label>
                                                    <input type="number" step="0.1" name="height" id="height"
                                                        class="form-control @error('height') is-invalid @enderror"
                                                        value="{{ old('height') }}"
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
                                                    <label for="medical_history" class="form-label fw-bold">
                                                        <i class="fas fa-history text-primary"></i> Medical History
                                                    </label>
                                                    <textarea name="medical_history" id="medical_history"
                                                        class="form-control @error('medical_history') is-invalid @enderror"
                                                        rows="3" placeholder="Enter patient's medical history...">{{ old('medical_history') }}</textarea>
                                                    @error('medical_history')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="diagnosis" class="form-label fw-bold">
                                                        <i class="fas fa-diagnoses text-danger"></i> Diagnosis
                                                    </label>
                                                    <textarea name="diagnosis" id="diagnosis"
                                                        class="form-control @error('diagnosis') is-invalid @enderror"
                                                        rows="3" placeholder="Enter diagnosis...">{{ old('diagnosis') }}</textarea>
                                                    @error('diagnosis')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="prescription" class="form-label fw-bold">
                                                        <i class="fas fa-prescription-bottle-alt text-success"></i> Prescription
                                                    </label>
                                                    <textarea name="prescription" id="prescription"
                                                        class="form-control @error('prescription') is-invalid @enderror"
                                                        rows="3" placeholder="Enter prescription details...">{{ old('prescription') }}</textarea>
                                                    @error('prescription')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="lab_results" class="form-label fw-bold">
                                                        <i class="fas fa-flask text-info"></i> Lab Results
                                                    </label>
                                                    <textarea name="lab_results" id="lab_results"
                                                        class="form-control @error('lab_results') is-invalid @enderror"
                                                        rows="3" placeholder="Enter lab results...">{{ old('lab_results') }}</textarea>
                                                    @error('lab_results')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="allergies" class="form-label">
                                                        <i class="fas fa-allergies text-danger"></i> Allergies
                                                    </label>
                                                    <textarea name="allergies" id="allergies"
                                                        class="form-control @error('allergies') is-invalid @enderror"
                                                        rows="2" placeholder="List any allergies...">{{ old('allergies') }}</textarea>
                                                    @error('allergies')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label for="notes" class="form-label">
                                                        <i class="fas fa-notes-medical text-secondary"></i> Additional Notes
                                                    </label>
                                                    <textarea name="notes" id="notes"
                                                        class="form-control @error('notes') is-invalid @enderror"
                                                        rows="3" placeholder="Enter any additional notes...">{{ old('notes') }}</textarea>
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
                                                    <label for="visibility_level" class="form-label fw-bold">
                                                        <i class="fas fa-eye"></i> Record Visibility *
                                                    </label>
                                                    <select name="visibility_level" id="visibility_level"
                                                        class="form-control form-select @error('visibility_level') is-invalid @enderror"
                                                        required>
                                                        <option value="">-- Select Visibility Level --</option>
                                                        <option value="private" {{ old('visibility_level') == 'private' ? 'selected' : '' }}>
                                                            <i class="fas fa-lock"></i> Private (Only you and the patient)
                                                        </option>
                                                        <option value="restricted" {{ old('visibility_level') == 'restricted' ? 'selected' : '' }}>
                                                            <i class="fas fa-user-shield"></i> Restricted (Selected staff only)
                                                        </option>
                                                        <option value="public" {{ old('visibility_level') == 'public' ? 'selected' : '' }}>
                                                            <i class="fas fa-globe"></i> Public (All authorized medical staff)
                                                        </option>
                                                    </select>
                                                    @error('visibility_level')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="alert alert-warning">
                                                    <small>
                                                        <i class="fas fa-exclamation-triangle"></i>
                                                        <strong>Note:</strong> Privacy settings determine who can view this medical record.
                                                    </small>
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
                                        <a href="{{ route('medical-records.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-times"></i> Cancel
                                        </a>
                                    </div>
                                    <div>
                                        <button type="button" class="btn btn-primary" id="submitFormBtn">
                                            <i class="fas fa-save"></i> Save Medical Record
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

<!-- New Patient Modal - MOVED OUTSIDE THE MAIN FORM -->
<div class="modal fade" id="newPatientModal" tabindex="-1" aria-labelledby="newPatientModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="newPatientModalLabel">
                    <i class="fas fa-user-plus"></i> Register New Patient
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- SEPARATE FORM for new patient -->
                <div id="newPatientFormContainer">
                    <div class="row g-3">
                        <!-- Personal Information -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="new_patient_name" class="form-label">Full Name *</label>
                                <input type="text" name="name" id="new_patient_name"
                                    class="form-control" required
                                    placeholder="Enter patient's full name">
                                <div class="invalid-feedback" id="nameError"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="new_patient_email" class="form-label">Email Address *</label>
                                <input type="email" name="email" id="new_patient_email"
                                    class="form-control" required
                                    placeholder="patient@example.com">
                                <div class="invalid-feedback" id="emailError"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="new_patient_phone" class="form-label">Phone Number</label>
                                <input type="tel" name="phone" id="new_patient_phone"
                                    class="form-control"
                                    placeholder="+1 (555) 123-4567">
                                <div class="invalid-feedback" id="phoneError"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="new_patient_dob" class="form-label">Date of Birth</label>
                                <input type="date" name="date_of_birth" id="new_patient_dob"
                                    class="form-control">
                                <div class="invalid-feedback" id="dobError"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="new_patient_gender" class="form-label">Gender</label>
                                <select name="gender" id="new_patient_gender" class="form-control">
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="new_patient_blood_group" class="form-label">Blood Group</label>
                                <select name="blood_group" id="new_patient_blood_group" class="form-control">
                                    <option value="">Select Blood Group</option>
                                    <option value="A+">A+</option>
                                    <option value="A-">A-</option>
                                    <option value="B+">B+</option>
                                    <option value="B-">B-</option>
                                    <option value="O+">O+</option>
                                    <option value="O-">O-</option>
                                    <option value="AB+">AB+</option>
                                    <option value="AB-">AB-</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="new_patient_address" class="form-label">Address</label>
                                <textarea name="address" id="new_patient_address"
                                    class="form-control" rows="2"
                                    placeholder="Enter patient's address"></textarea>
                            </div>
                        </div>

                        <!-- Medical Record Fields -->
                        <div class="col-md-12">
                            <hr>
                            <h6 class="text-primary">Initial Medical Information</h6>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="new_patient_medical_history" class="form-label">Medical History</label>
                                <textarea name="medical_history" id="new_patient_medical_history"
                                    class="form-control" rows="2"
                                    placeholder="Any previous medical conditions..."></textarea>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="new_patient_blood_pressure" class="form-label">Blood Pressure</label>
                                <input type="text" name="blood_pressure" id="new_patient_blood_pressure"
                                    class="form-control"
                                    placeholder="e.g., 120/80">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="new_patient_weight" class="form-label">Weight (kg)</label>
                                <input type="number" step="0.1" name="weight" id="new_patient_weight"
                                    class="form-control"
                                    placeholder="e.g., 70.5">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="new_patient_height" class="form-label">Height (cm)</label>
                                <input type="number" step="0.1" name="height" id="new_patient_height"
                                    class="form-control"
                                    placeholder="e.g., 175">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="new_patient_allergies" class="form-label">Allergies</label>
                                <input type="text" name="allergies" id="new_patient_allergies"
                                    class="form-control"
                                    placeholder="e.g., Penicillin, Peanuts">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="new_patient_notes" class="form-label">Initial Notes</label>
                                <textarea name="notes" id="new_patient_notes"
                                    class="form-control" rows="2"
                                    placeholder="Any initial observations..."></textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="new_patient_visibility" class="form-label">Record Visibility *</label>
                                <select name="visibility_level" id="new_patient_visibility" class="form-control" required>
                                    <option value="">Select Visibility</option>
                                    <option value="private">Private (Only you and patient)</option>
                                    <option value="restricted">Restricted (Selected staff only)</option>
                                    <option value="public">Public (All authorized staff)</option>
                                </select>
                                <div class="invalid-feedback" id="visibilityError"></div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="alert alert-info">
                                <small>
                                    <i class="fas fa-info-circle"></i>
                                    A temporary password will be generated and the patient role will be automatically assigned.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="savePatientBtn">
                    <i class="fas fa-save"></i> Save Patient & Create Record
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
    .card {
        border-radius: 10px;
        border: none;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        border-radius: 10px 10px 0 0 !important;
    }

    .form-label {
        font-weight: 500;
        margin-bottom: 0.5rem;
    }

    .form-control,
    .form-select {
        border-radius: 8px;
        border: 1px solid #dee2e6;
        padding: 0.75rem 1rem;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    .btn {
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
        font-weight: 500;
    }

    .btn-primary {
        background: linear-gradient(135deg, #0d6efd, #0a58ca);
        border: none;
    }

    .alert {
        border-radius: 8px;
        border: none;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .card-header h6 {
            font-size: 1rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }

        .form-control,
        .form-select {
            padding: 0.5rem 0.75rem;
            font-size: 0.9rem;
        }
    }

    .empty-field {
        color: #6c757d;
        font-style: italic;
    }
</style>
@endpush

@push('scripts')
<script>
    $(document).ready(function() {
        // Auto-format blood pressure input
        $('#blood_pressure').on('blur', function() {
            let value = $(this).val().trim();
            if (value && !value.includes('/')) {
                // Try to auto-format if user entered something like "12080" or "120 80"
                value = value.replace(/\s+/g, '');
                if (value.length >= 4) {
                    let systolic = value.substring(0, 3);
                    let diastolic = value.substring(3);
                    $(this).val(systolic + '/' + diastolic);
                }
            }
        });

        // Calculate BMI if both weight and height are provided
        function calculateBMI() {
            let weight = parseFloat($('#weight').val());
            let height = parseFloat($('#height').val());

            if (weight && height && height > 0) {
                let heightInMeters = height / 100;
                let bmi = weight / (heightInMeters * heightInMeters);
                return bmi.toFixed(1);
            }
            return null;
        }

        // Show BMI in info
        $('#weight, #height').on('change', function() {
            let bmi = calculateBMI();
            if (bmi) {
                $(this).siblings('.bmi-info').remove();
                $(this).after('<small class="text-success bmi-info">BMI: ' + bmi + '</small>');
            }
        });

        // New Patient Creation
        $('#savePatientBtn').on('click', function() {
            let btn = $(this);
            let originalText = btn.html();

            // Show loading
            btn.html('<i class="fas fa-spinner fa-spin"></i> Saving...').prop('disabled', true);

            // Clear previous errors
            $('#newPatientFormContainer .form-control').removeClass('is-invalid');
            $('#newPatientFormContainer .invalid-feedback').text('');

            // Get form data
            let formData = {
                name: $('#new_patient_name').val(),
                email: $('#new_patient_email').val(),
                phone: $('#new_patient_phone').val(),
                date_of_birth: $('#new_patient_dob').val(),
                gender: $('#new_patient_gender').val(),
                blood_group: $('#new_patient_blood_group').val(),
                address: $('#new_patient_address').val(),

                // Medical record fields
                medical_history: $('#new_patient_medical_history').val(),
                diagnosis: '',
                prescription: '',
                lab_results: '',
                blood_pressure: $('#new_patient_blood_pressure').val(),
                temperature: null,
                pulse_rate: null,
                weight: $('#new_patient_weight').val() ? parseFloat($('#new_patient_weight').val()) : null,
                height: $('#new_patient_height').val() ? parseFloat($('#new_patient_height').val()) : null,
                allergies: $('#new_patient_allergies').val(),
                notes: $('#new_patient_notes').val(),
                visibility_level: $('#new_patient_visibility').val(),
                _token: '{{ csrf_token() }}'
            };

            // Validation
            if (!formData.name.trim()) {
                $('#new_patient_name').addClass('is-invalid');
                $('#nameError').text('Name is required');
                btn.html(originalText).prop('disabled', false);
                return;
            }

            if (!formData.email.trim()) {
                $('#new_patient_email').addClass('is-invalid');
                $('#emailError').text('Email is required');
                btn.html(originalText).prop('disabled', false);
                return;
            }

            // Email validation regex
            let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(formData.email)) {
                $('#new_patient_email').addClass('is-invalid');
                $('#emailError').text('Please enter a valid email address');
                btn.html(originalText).prop('disabled', false);
                return;
            }

            // Visibility level validation
            if (!formData.visibility_level) {
                $('#new_patient_visibility').addClass('is-invalid');
                $('#visibilityError').text('Visibility level is required');
                btn.html(originalText).prop('disabled', false);
                return;
            }

            console.log('Creating new patient with data:', formData);

            // AJAX request to create patient
            $.ajax({
                url: '{{ route("medical-records.patients.store") }}',
                method: 'POST',
                data: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                success: function(response) {
                    console.log('New patient response:', response);
                    if (response.success) {
                        // Add new patient to dropdown
                        let newOption = new Option(
                            response.patient.name + ' (' + response.patient.email + ')',
                            response.patient.id,
                            true,
                            true
                        );

                        $('#patient_id').append(newOption).trigger('change');

                        // Close modal
                        $('#newPatientModal').modal('hide');

                        // Reset form fields inside the modal
                        $('#newPatientFormContainer').find('input, textarea, select').val('');
                        $('#newPatientFormContainer').find('.form-control').removeClass('is-invalid');
                        $('.invalid-feedback').text('');

                        // Show success message
                        toastr.success('Patient and medical record created successfully!');

                        // Auto-fill some fields in the main form
                        if (formData.blood_group) {
                            $('#allergies').val(function(i, val) {
                                return val + (val ? '\n' : '') + 'Blood Group: ' + formData.blood_group;
                            });
                        }

                        if (formData.medical_history) {
                            $('#medical_history').val(formData.medical_history);
                        }

                        if (formData.allergies) {
                            $('#allergies').val(function(i, val) {
                                return (val ? val + '\n' : '') + formData.allergies;
                            });
                        }

                        if (formData.blood_pressure) {
                            $('#blood_pressure').val(formData.blood_pressure);
                        }

                        if (formData.weight) {
                            $('#weight').val(formData.weight);
                        }

                        if (formData.height) {
                            $('#height').val(formData.height);
                        }

                        if (formData.notes) {
                            $('#notes').val(formData.notes);
                        }

                        // Set visibility level
                        $('#visibility_level').val(formData.visibility_level);
                    }
                },
                error: function(xhr, status, error) {
                    console.log('New patient error:', xhr.status, error);
                    console.log('Response:', xhr.responseText);

                    let errors = xhr.responseJSON?.errors;
                    if (errors) {
                        // Display validation errors
                        $.each(errors, function(key, value) {
                            let input = $('#new_patient_' + key);
                            let errorDiv = $('#' + key + 'Error');

                            if (input.length) {
                                input.addClass('is-invalid');
                                if (errorDiv.length) {
                                    errorDiv.text(value[0]);
                                }
                            } else {
                                // If no specific input found, show general error
                                toastr.error(value[0]);
                            }
                        });
                        toastr.error('Please fix the errors in the form');
                    } else {
                        toastr.error('An error occurred while creating the patient: ' + error);
                    }
                },
                complete: function() {
                    btn.html(originalText).prop('disabled', false);
                }
            });
        });

        // Auto-format phone number
        $('#new_patient_phone').on('input', function() {
            let phone = $(this).val().replace(/\D/g, '');
            if (phone.length > 0) {
                phone = phone.replace(/(\d{3})(\d{3})(\d{4})/, '($1) $2-$3');
                $(this).val(phone);
            }
        });

        // Calculate age from DOB
        $('#new_patient_dob').on('change', function() {
            let dob = $(this).val();
            if (dob) {
                let birthDate = new Date(dob);
                let today = new Date();
                let age = today.getFullYear() - birthDate.getFullYear();
                let m = today.getMonth() - birthDate.getMonth();
                if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }

                // Show age next to DOB field
                $(this).siblings('.age-display').remove();
                $(this).after('<small class="text-muted age-display">Age: ' + age + ' years</small>');
            }
        });

        // When modal closes, reset it
        $('#newPatientModal').on('hidden.bs.modal', function() {
            $('#newPatientFormContainer').find('input, textarea, select').val('');
            $('#newPatientFormContainer').find('.form-control').removeClass('is-invalid');
            $('.invalid-feedback').text('');
            $('.age-display').remove();
        });

        // Handle submit button click for main form
        $('#submitFormBtn').on('click', function() {
            let patientId = $('#patient_id').val();
            let visibility = $('#visibility_level').val();

            if (!patientId) {
                $('#patient_id').addClass('is-invalid');
                $('#patient_id').siblings('.invalid-feedback').text('Please select a patient');
                $('#patient_id').focus();
                return;
            }

            if (!visibility) {
                $('#visibility_level').addClass('is-invalid');
                $('#visibility_level').siblings('.invalid-feedback').text('Please select a visibility level');
                $('#visibility_level').focus();
                return;
            }

            // Submit the main form
            $('#medicalRecordForm').submit();
        });

        // Form validation and submission with AJAX for main form
        $('#medicalRecordForm').on('submit', function(e) {
            e.preventDefault();
            console.log('Form submission triggered');

            // Show loading indicator
            $('#submitFormBtn').html('<i class="fas fa-spinner fa-spin"></i> Saving...').prop('disabled', true);

            // Get form data
            let formData = $(this).serializeArray();
            console.log('Form data:', formData);

            // Send AJAX request
            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                success: function(response) {
                    console.log('Success response:', response);
                    if (response.success) {
                        toastr.success(response.message || 'Medical record created successfully!');

                        // Redirect to index page
                        setTimeout(function() {
                            window.location.href = '{{ route("medical-records.index") }}';
                        }, 1500);
                    } else {
                        toastr.error(response.message || 'Failed to save medical record');
                        $('#submitFormBtn').html('<i class="fas fa-save"></i> Save Medical Record').prop('disabled', false);
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Error:', xhr.status, error);
                    console.log('Response:', xhr.responseText);

                    if (xhr.status === 422) {
                        // Validation errors
                        let errors = xhr.responseJSON.errors;
                        console.log('Validation errors:', errors);

                        let errorMessages = [];

                        // Clear previous errors
                        $('.form-control').removeClass('is-invalid');
                        $('.invalid-feedback').text('');

                        // Display errors next to fields
                        $.each(errors, function(field, messages) {
                            let input = $('[name="' + field + '"]');
                            if (input.length) {
                                input.addClass('is-invalid');
                                let errorDiv = input.siblings('.invalid-feedback');
                                if (!errorDiv.length) {
                                    errorDiv = input.parent().find('.invalid-feedback');
                                }
                                if (errorDiv.length) {
                                    errorDiv.text(messages[0]);
                                } else {
                                    // Create error div if it doesn't exist
                                    input.after('<div class="invalid-feedback">' + messages[0] + '</div>');
                                }
                            }
                            errorMessages.push(messages[0]);
                        });

                        if (errorMessages.length > 0) {
                            toastr.error(errorMessages.join('<br>'));
                        }
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        toastr.error(xhr.responseJSON.message);
                    } else if (xhr.status === 302) {
                        // Handle redirect (non-AJAX response)
                        console.log('Redirect detected, likely validation error');
                        toastr.error('Please check the form for errors');
                    } else {
                        toastr.error('An error occurred while saving the record: ' + error);
                    }

                    $('#submitFormBtn').html('<i class="fas fa-save"></i> Save Medical Record').prop('disabled', false);
                }
            });
        });
    });
</script>
@endpush