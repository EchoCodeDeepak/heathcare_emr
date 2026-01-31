@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-file-medical-alt me-2"></i>Medical Record - {{ $record->patient->name }}
                    </h4>
                    <div>
                        <span class="badge bg-{{ 
                            $record->visibility_level == 'public' ? 'success' : 
                            ($record->visibility_level == 'restricted' ? 'warning' : 'secondary')
                        }} fs-6">
                            {{ ucfirst($record->visibility_level) }}
                        </span>
                        @if(Auth::user()->can('edit', $record) || Auth::user()->isAdmin() || $record->doctor_id == Auth::id())
                        <button type="button" class="btn btn-sm btn-danger ms-2" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    <!-- Action Buttons -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-between mb-3">
                                <a href="{{ route('medical-records.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Back to Records
                                </a>
                                <div>
                                    @if(Auth::user()->can('edit', $record) || Auth::user()->isAdmin() || $record->doctor_id == Auth::id())
                                    <a href="{{ route('medical-records.edit', $record->id) }}" class="btn btn-primary">
                                        <i class="fas fa-edit me-1"></i> Edit Record
                                    </a>
                                    @endif
                                    @if(auth()->user()->isAdmin() || auth()->user()->isDoctor())
                                    <button type="button" class="btn btn-info ms-2" data-bs-toggle="modal" data-bs-target="#addPermissionModal">
                                        <i class="fas fa-user-shield me-1"></i> Manage Access
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Patient and Doctor Information -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-info text-white d-flex align-items-center">
                                    <i class="fas fa-user-injured me-2"></i>
                                    <h6 class="mb-0">Patient Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <label class="text-muted small mb-1">Full Name</label>
                                        <p class="mb-0 fs-6">{{ $record->patient->name }}</p>
                                    </div>
                                    <div class="mb-2">
                                        <label class="text-muted small mb-1">Email Address</label>
                                        <p class="mb-0 fs-6">{{ $record->patient->email }}</p>
                                    </div>
                                    @if($record->patient->phone)
                                    <div class="mb-2">
                                        <label class="text-muted small mb-1">Phone Number</label>
                                        <p class="mb-0 fs-6">{{ $record->patient->phone }}</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-success text-white d-flex align-items-center">
                                    <i class="fas fa-user-md me-2"></i>
                                    <h6 class="mb-0">Doctor Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <label class="text-muted small mb-1">Attending Doctor</label>
                                        <p class="mb-0 fs-6">{{ $record->doctor->name ?? 'N/A' }}</p>
                                    </div>
                                    <div class="mb-2">
                                        <label class="text-muted small mb-1">Record Created</label>
                                        <p class="mb-0 fs-6">{{ $record->created_at->format('F d, Y h:i A') }}</p>
                                    </div>
                                    <div class="mb-2">
                                        <label class="text-muted small mb-1">Last Updated</label>
                                        <p class="mb-0 fs-6">{{ $record->updated_at->format('F d, Y h:i A') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Vital Signs -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-warning text-dark d-flex align-items-center">
                                    <i class="fas fa-heartbeat me-2"></i>
                                    <h6 class="mb-0">Vital Signs</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-2 col-sm-4 mb-3">
                                            <div class="border rounded p-3 text-center bg-light">
                                                <label class="text-muted small mb-1">Blood Pressure</label>
                                                <p class="mb-0 fs-5 fw-bold text-danger">{{ $record->blood_pressure ?? '--/--' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-sm-4 mb-3">
                                            <div class="border rounded p-3 text-center bg-light">
                                                <label class="text-muted small mb-1">Temperature</label>
                                                <p class="mb-0 fs-5 fw-bold text-warning">{{ $record->temperature ? $record->temperature . '°C' : '--' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-sm-4 mb-3">
                                            <div class="border rounded p-3 text-center bg-light">
                                                <label class="text-muted small mb-1">Pulse Rate</label>
                                                <p class="mb-0 fs-5 fw-bold text-danger">{{ $record->pulse_rate ? $record->pulse_rate . ' BPM' : '--' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-sm-4 mb-3">
                                            <div class="border rounded p-3 text-center bg-light">
                                                <label class="text-muted small mb-1">Weight</label>
                                                <p class="mb-0 fs-5 fw-bold text-primary">{{ $record->weight ? $record->weight . ' kg' : '--' }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-sm-4 mb-3">
                                            <div class="border rounded p-3 text-center bg-light">
                                                <label class="text-muted small mb-1">Height</label>
                                                <p class="mb-0 fs-5 fw-bold text-success">{{ $record->height ? $record->height . ' cm' : '--' }}</p>
                                            </div>
                                        </div>
                                        @php
                                            $bmi = null;
                                            if($record->weight && $record->height) {
                                                $heightInMeters = $record->height / 100;
                                                $bmi = $record->weight / ($heightInMeters * $heightInMeters);
                                            }
                                        @endphp
                                        <div class="col-md-2 col-sm-4 mb-3">
                                            <div class="border rounded p-3 text-center bg-light">
                                                <label class="text-muted small mb-1">BMI</label>
                                                <p class="mb-0 fs-5 fw-bold {{ $bmi ? ($bmi < 18.5 ? 'text-info' : ($bmi < 25 ? 'text-success' : ($bmi < 30 ? 'text-warning' : 'text-danger'))) : 'text-secondary' }}">
                                                    {{ $bmi ? number_format($bmi, 1) : '--' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Allergies -->
                    @if($record->allergies)
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card border-danger">
                                <div class="card-header bg-danger text-white d-flex align-items-center">
                                    <i class="fas fa-allergies me-2"></i>
                                    <h6 class="mb-0">Allergies</h6>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-danger mb-0">
                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                        {{ $record->allergies }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Medical History -->
                    @if($record->medical_history)
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-secondary text-white d-flex align-items-center">
                                    <i class="fas fa-history me-2"></i>
                                    <h6 class="mb-0">Medical History</h6>
                                </div>
                                <div class="card-body">
                                    <div class="p-3 bg-light rounded">
                                        {!! nl2br(e($record->medical_history)) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Diagnosis and Prescription -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-success text-white d-flex align-items-center">
                                    <i class="fas fa-diagnoses me-2"></i>
                                    <h6 class="mb-0">Diagnosis</h6>
                                </div>
                                <div class="card-body">
                                    @if($record->diagnosis)
                                    <div class="p-3 bg-light rounded">
                                        {!! nl2br(e($record->diagnosis)) !!}
                                    </div>
                                    @else
                                    <div class="text-center text-muted py-4">
                                        <i class="fas fa-stethoscope fa-2x mb-2"></i>
                                        <p class="mb-0">No diagnosis recorded</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header bg-primary text-white d-flex align-items-center">
                                    <i class="fas fa-prescription-bottle-alt me-2"></i>
                                    <h6 class="mb-0">Prescription</h6>
                                </div>
                                <div class="card-body">
                                    @if($record->prescription)
                                    <div class="p-3 bg-light rounded">
                                        {!! nl2br(e($record->prescription)) !!}
                                    </div>
                                    @else
                                    <div class="text-center text-muted py-4">
                                        <i class="fas fa-pills fa-2x mb-2"></i>
                                        <p class="mb-0">No prescription</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Lab Results -->
                    @if($record->lab_results)
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-info text-white d-flex align-items-center">
                                    <i class="fas fa-flask me-2"></i>
                                    <h6 class="mb-0">Lab Results</h6>
                                </div>
                                <div class="card-body">
                                    <div class="p-3 bg-light rounded">
                                        {!! nl2br(e($record->lab_results)) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Additional Notes -->
                    @if($record->notes)
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-dark text-white d-flex align-items-center">
                                    <i class="fas fa-notes-medical me-2"></i>
                                    <h6 class="mb-0">Additional Notes</h6>
                                </div>
                                <div class="card-body">
                                    <div class="p-3 bg-light rounded">
                                        {!! nl2br(e($record->notes)) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Access Control Section (Only visible to Admin and Doctor) -->
                    @if(auth()->user()->isAdmin() || auth()->user()->isDoctor())
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-secondary text-white d-flex align-items-center justify-content-between">
                                    <div>
                                        <i class="fas fa-shield-alt me-2"></i>
                                        <h6 class="mb-0 d-inline">Access Control</h6>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#addPermissionModal">
                                        <i class="fas fa-plus me-1"></i> Grant Access
                                    </button>
                                </div>
                                <div class="card-body">
                                    @if($record->accessPermissions->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>User</th>
                                                    <th>Role</th>
                                                    <th class="text-center">Can View</th>
                                                    <th class="text-center">Can Edit</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($record->accessPermissions as $permission)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                                                {{ substr($permission->user->name, 0, 1) }}
                                                            </div>
                                                            {{ $permission->user->name }}
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-info">{{ $permission->user->role->name }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="form-check form-switch d-inline-block">
                                                            <input class="form-check-input view-checkbox" type="checkbox" 
                                                                   data-user-id="{{ $permission->user_id }}"
                                                                   {{ $permission->can_view ? 'checked' : '' }}>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="form-check form-switch d-inline-block">
                                                            <input class="form-check-input edit-checkbox" type="checkbox" 
                                                                   data-user-id="{{ $permission->user_id }}"
                                                                   {{ $permission->can_edit ? 'checked' : '' }}>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-sm btn-outline-danger remove-permission" 
                                                                data-user-id="{{ $permission->user_id }}"
                                                                title="Remove Access">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @else
                                    <div class="text-center text-muted py-4">
                                        <i class="fas fa-user-lock fa-2x mb-2"></i>
                                        <p class="mb-0">No additional users have access to this record</p>
                                        <small>Click "Grant Access" to share this record with other users</small>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
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
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirm Deletion
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <strong>Warning:</strong> This action cannot be undone!
                </div>
                <p>Are you sure you want to delete this medical record?</p>
                <div class="card border-danger">
                    <div class="card-body">
                        <p class="mb-1"><strong>Patient:</strong> {{ $record->patient->name }}</p>
                        <p class="mb-1"><strong>Doctor:</strong> {{ $record->doctor->name ?? 'N/A' }}</p>
                        <p class="mb-0"><strong>Date:</strong> {{ $record->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Cancel
                </button>
                <form action="{{ route('medical-records.destroy', $record->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i> Delete Permanently
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Permission Modal -->
@if(auth()->user()->isAdmin() || auth()->user()->isDoctor())
<div class="modal fade" id="addPermissionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fas fa-user-plus me-2"></i>Grant Access to User
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="addPermissionForm">
                    @csrf
                    <div class="mb-3">
                        <label for="user_id" class="form-label">Select User</label>
                        <select name="user_id" id="user_id" class="form-select" required>
                            <option value="">Choose a user...</option>
                            @foreach(App\Models\User::where('id', '!=', auth()->id())->get() as $user)
                            <option value="{{ $user->id }}">
                                {{ $user->name }} ({{ $user->role->name }} - {{ $user->email }})
                            </option>
                            @endforeach
                        </select>
                        <div class="form-text">Select a user to grant access to this medical record</div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="can_view" id="can_view" checked>
                            <label class="form-check-label" for="can_view">
                                <strong>Can View Record</strong>
                            </label>
                        </div>
                        <div class="form-text">User will be able to view this medical record</div>
                    </div>
                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="can_edit" id="can_edit">
                            <label class="form-check-label" for="can_edit">
                                <strong>Can Edit Record</strong>
                            </label>
                        </div>
                        <div class="form-text">User will be able to edit this medical record (Admin/Doctors only)</div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" form="addPermissionForm" class="btn btn-primary">
                    <i class="fas fa-check me-1"></i> Grant Access
                </button>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
@if(auth()->user()->isAdmin() || auth()->user()->isDoctor())
<script>
$(document).ready(function() {
    // Toastr notifications
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "3000"
    };

    // Update permission via AJAX
    $('.view-checkbox, .edit-checkbox').change(function() {
        const userId = $(this).data('user-id');
        const canView = $(`.view-checkbox[data-user-id="${userId}"]`).is(':checked');
        const canEdit = $(`.edit-checkbox[data-user-id="${userId}"]`).is(':checked');
        const checkbox = $(this);
        
        // Disable checkbox during AJAX request
        checkbox.prop('disabled', true);
        
        $.ajax({
            url: '{{ route("medical-records.update-permissions", $record->id) }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                user_id: userId,
                can_view: canView,
                can_edit: canEdit
            },
            success: function(response) {
                toastr.success('Permission updated successfully');
                checkbox.prop('disabled', false);
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.error || 'Error updating permission');
                checkbox.prop('disabled', false);
                // Revert checkbox state
                checkbox.prop('checked', !checkbox.is(':checked'));
            }
        });
    });
    
    // Remove permission
    $('.remove-permission').click(function() {
        const userId = $(this).data('user-id');
        const button = $(this);
        
        if(confirm('Are you sure you want to remove this user\'s access?')) {
            button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
            
            $.ajax({
                url: '{{ route("medical-records.update-permissions", $record->id) }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    user_id: userId,
                    can_view: false,
                    can_edit: false
                },
                success: function(response) {
                    toastr.success('Access removed successfully');
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                },
                error: function() {
                    toastr.error('Error removing access');
                    button.prop('disabled', false).html('<i class="fas fa-times"></i>');
                }
            });
        }
    });
    
    // Add new permission
    $('#addPermissionForm').submit(function(e) {
        e.preventDefault();
        const submitBtn = $(this).find('button[type="submit"]');
        
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Processing...');
        
        // Prepare form data with proper boolean values
        const formData = {
            _token: '{{ csrf_token() }}',
            user_id: $('#user_id').val(),
            can_view: $('#can_view').is(':checked') ? 1 : 0,
            can_edit: $('#can_edit').is(':checked') ? 1 : 0
        };
        
        $.ajax({
            url: '{{ route("medical-records.update-permissions", $record->id) }}',
            method: 'POST',
            data: formData,
            success: function(response) {
                toastr.success('Access granted successfully');
                $('#addPermissionModal').modal('hide');
                setTimeout(() => {
                    location.reload();
                }, 1000);
            },
            error: function(xhr) {
                console.log('Error response:', xhr.responseJSON);
                toastr.error(xhr.responseJSON?.error || 'Error granting access');
                submitBtn.prop('disabled', false).html('<i class="fas fa-check me-1"></i> Grant Access');
            }
        });
    });
    
    // Reset form when modal is closed
    $('#addPermissionModal').on('hidden.bs.modal', function () {
        $('#addPermissionForm')[0].reset();
        $('#addPermissionForm button[type="submit"]').prop('disabled', false).html('<i class="fas fa-check me-1"></i> Grant Access');
    });
});
</script>
@endif

<!-- Delete confirmation script -->
<script>
    $('#deleteModal').on('shown.bs.modal', function () {
        $('#deleteModal').trigger('focus');
    });
</script>
@endpush