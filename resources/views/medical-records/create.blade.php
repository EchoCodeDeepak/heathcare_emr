@extends('layouts.app')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
@section('content')
<div class="container py-3">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-file-medical-alt"></i> Create New Medical Record
                    </h4>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('medical-records.store') }}" id="medicalRecordForm">
                        @csrf

                        <!-- Patient Selection -->
                        <div class="mb-3">
                            <label for="patient_id" class="form-label fw-bold">Select Patient <span class="text-danger">*</span></label>
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
                                    <i class="fas fa-user-plus"></i> New Patient
                                </button>
                            </div>
                            @error('patient_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <!-- Vital Signs -->
                        <h5 class="mb-3"><i class="fas fa-heartbeat"></i> Vital Signs</h5>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="blood_pressure" class="form-label">Blood Pressure</label>
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
                                <div class="mb-3">
                                    <label for="temperature" class="form-label">Temperature (°C)</label>
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
                                <div class="mb-3">
                                    <label for="pulse_rate" class="form-label">Pulse Rate (BPM)</label>
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
                                <div class="mb-3">
                                    <label for="weight" class="form-label">Weight (kg)</label>
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
                                <div class="mb-3">
                                    <label for="height" class="form-label">Height (cm)</label>
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

                        <hr class="my-4">

                        <!-- Medical Information -->
                        <div class="mb-3">
                            <label for="medical_history" class="form-label fw-bold">Medical History</label>
                            <textarea name="medical_history" id="medical_history"
                                class="form-control @error('medical_history') is-invalid @enderror"
                                rows="3" placeholder="Enter patient's medical history...">{{ old('medical_history') }}</textarea>
                            @error('medical_history')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="diagnosis" class="form-label fw-bold">Diagnosis</label>
                            <textarea name="diagnosis" id="diagnosis"
                                class="form-control @error('diagnosis') is-invalid @enderror"
                                rows="3" placeholder="Enter diagnosis...">{{ old('diagnosis') }}</textarea>
                            @error('diagnosis')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="prescription" class="form-label fw-bold">Prescription</label>
                            <textarea name="prescription" id="prescription"
                                class="form-control @error('prescription') is-invalid @enderror"
                                rows="3" placeholder="Enter prescription details...">{{ old('prescription') }}</textarea>
                            @error('prescription')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="lab_results" class="form-label fw-bold">Lab Results</label>
                            <textarea name="lab_results" id="lab_results"
                                class="form-control @error('lab_results') is-invalid @enderror"
                                rows="3" placeholder="Enter lab results...">{{ old('lab_results') }}</textarea>
                            @error('lab_results')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="allergies" class="form-label">Allergies</label>
                            <textarea name="allergies" id="allergies"
                                class="form-control @error('allergies') is-invalid @enderror"
                                rows="2" placeholder="List any allergies...">{{ old('allergies') }}</textarea>
                            @error('allergies')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Additional Notes</label>
                            <textarea name="notes" id="notes"
                                class="form-control @error('notes') is-invalid @enderror"
                                rows="3" placeholder="Enter any additional notes...">{{ old('notes') }}</textarea>
                            @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <hr class="my-4">

                        <!-- Privacy Settings -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="visibility_level" class="form-label fw-bold">Record Visibility <span class="text-danger">*</span></label>
                                    <select name="visibility_level" id="visibility_level"
                                        class="form-control form-select @error('visibility_level') is-invalid @enderror"
                                        required>
                                        <option value="">-- Select Visibility Level --</option>
                                        <option value="private" {{ old('visibility_level') == 'private' ? 'selected' : '' }}>
                                            Private (Only you and the patient)
                                        </option>
                                        <option value="restricted" {{ old('visibility_level') == 'restricted' ? 'selected' : '' }}>
                                            Restricted (Selected staff only)
                                        </option>
                                        <option value="public" {{ old('visibility_level') == 'public' ? 'selected' : '' }}>
                                            Public (All authorized medical staff)
                                        </option>
                                    </select>
                                    @error('visibility_level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="alert alert-warning mb-3">
                                    <small>
                                        <i class="fas fa-exclamation-triangle"></i>
                                        <strong>Note:</strong> Privacy settings determine who can view this medical record.
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('medical-records.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="button" class="btn btn-primary" id="submitFormBtn">
                                <i class="fas fa-save"></i> Save Medical Record
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- New Patient Modal -->
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

@push('scripts')
<script>
    $(document).ready(function() {
        // Auto-format blood pressure input
        $('#blood_pressure').on('blur', function() {
            let value = $(this).val().trim();
            if (value && !value.includes('/')) {
                value = value.replace(/\s+/g, '');
                if (value.length >= 4) {
                    let systolic = value.substring(0, 3);
                    let diastolic = value.substring(3);
                    $(this).val(systolic + '/' + diastolic);
                }
            }
        });

        // New Patient Creation
        $('#savePatientBtn').on('click', function() {
            let btn = $(this);
            let originalText = btn.html();

            btn.html('<i class="fas fa-spinner fa-spin"></i> Saving...').prop('disabled', true);

            $('#newPatientFormContainer .form-control').removeClass('is-invalid');
            $('#newPatientFormContainer .invalid-feedback').text('');

            let formData = {
                name: $('#new_patient_name').val(),
                email: $('#new_patient_email').val(),
                phone: $('#new_patient_phone').val(),
                date_of_birth: $('#new_patient_dob').val(),
                gender: $('#new_patient_gender').val(),
                blood_group: $('#new_patient_blood_group').val(),
                address: $('#new_patient_address').val(),
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

            let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(formData.email)) {
                $('#new_patient_email').addClass('is-invalid');
                $('#emailError').text('Please enter a valid email address');
                btn.html(originalText).prop('disabled', false);
                return;
            }

            if (!formData.visibility_level) {
                $('#new_patient_visibility').addClass('is-invalid');
                $('#visibilityError').text('Visibility level is required');
                btn.html(originalText).prop('disabled', false);
                return;
            }

            $.ajax({
                url: '{{ route("medical-records.patients.store") }}',
                method: 'POST',
                data: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                success: function(response) {
                    if (response.success) {
                        let newOption = new Option(
                            response.patient.name + ' (' + response.patient.email + ')',
                            response.patient.id,
                            true,
                            true
                        );

                        $('#patient_id').append(newOption).trigger('change');
                        $('#newPatientModal').modal('hide');
                        $('#newPatientFormContainer').find('input, textarea, select').val('');
                        $('#newPatientFormContainer').find('.form-control').removeClass('is-invalid');
                        $('.invalid-feedback').text('');

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

                        $('#visibility_level').val(formData.visibility_level);
                    }
                },
                error: function(xhr, status, error) {
                    let errors = xhr.responseJSON?.errors;
                    if (errors) {
                        $.each(errors, function(key, value) {
                            let input = $('#new_patient_' + key);
                            let errorDiv = $('#' + key + 'Error');

                            if (input.length) {
                                input.addClass('is-invalid');
                                if (errorDiv.length) {
                                    errorDiv.text(value[0]);
                                }
                            }
                        });
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

            $('#medicalRecordForm').submit();
        });

        // Form validation and submission with AJAX for main form
        $('#medicalRecordForm').on('submit', function(e) {
            e.preventDefault();

            $('#submitFormBtn').html('<i class="fas fa-spinner fa-spin"></i> Saving...').prop('disabled', true);

            let formData = $(this).serializeArray();

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message || 'Medical record created successfully!');

                        setTimeout(function() {
                            window.location.href = '{{ route("medical-records.index") }}';
                        }, 1500);
                    } else {
                        toastr.error(response.message || 'Failed to save medical record');
                        $('#submitFormBtn').html('<i class="fas fa-save"></i> Save Medical Record').prop('disabled', false);
                    }
                },
                error: function(xhr, status, error) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;

                        $('.form-control').removeClass('is-invalid');
                        $('.invalid-feedback').text('');

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
                                }
                            }
                        });
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        toastr.error(xhr.responseJSON.message);
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