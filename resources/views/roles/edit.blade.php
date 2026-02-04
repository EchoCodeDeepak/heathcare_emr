@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h4 class="mb-0"><i class="fas fa-shield-alt text-primary"></i> Edit Role: {{ $role->name }}</h4>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.roles.update', $role) }}" id="editRoleForm">
                        @csrf
                        @method('PUT')

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-label fw-bold">Role Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name', $role->name) }}" required autofocus>
                                    @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="slug" class="form-label fw-bold">Role Slug <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                        id="slug" name="slug" value="{{ old('slug', $role->slug) }}" required>
                                    @error('slug')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Use lowercase letters and hyphens</small>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Permissions Section -->
                        <div class="mb-4">
                            <h5 class="mb-3 card-title fw-bold">
                                <i class="fas fa-lock text-primary"></i> Manage Permissions
                            </h5>
                            <p class="text-muted small mb-4">Check or uncheck permissions for this role:</p>

                            @if($permissionGroups && count($permissionGroups) > 0)
                            <div class="row">
                                @foreach($permissionGroups as $groupName => $groupPermissions)
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100 border">
                                        <div class="card-header bg-light">
                                            <h6 class="mb-0 fw-bold">
                                                <i class="fas fa-folder text-warning"></i> {{ $groupName }}
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            @foreach($groupPermissions as $permissionSlug)
                                            @php
                                            $permission = $permissions->where('slug', $permissionSlug)->first();
                                            @endphp
                                            @if($permission)
                                            @php
                                            $isChecked = in_array($permission->id, $rolePermissions);
                                            @endphp
                                            <div class="form-check mb-2">
                                                <input class="form-check-input permission-checkbox"
                                                    type="checkbox"
                                                    name="permissions[]"
                                                    value="{{ $permission->id }}"
                                                    id="perm_{{ $permission->id }}"
                                                    {{ $isChecked ? 'checked' : '' }}>
                                                <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                    {{ $permission->name }}
                                                    <small class="text-muted d-block">{{ $permissionSlug }}</small>
                                                </label>
                                            </div>
                                            @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i> No permission groups available.
                            </div>
                            @endif
                        </div>

                        <hr class="my-4">

                        <!-- Info Alert -->
                        <div class="alert alert-info mb-4">
                            <i class="fas fa-info-circle"></i>
                            <strong>Note:</strong> Changes to permissions will affect all users assigned to this role.
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Role
                            </button>
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
    document.addEventListener('DOMContentLoaded', function() {
        // Add visual feedback for checked checkboxes
        const checkboxes = document.querySelectorAll('.permission-checkbox');
        checkboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    this.parentElement.classList.add('bg-light');
                } else {
                    this.parentElement.classList.remove('bg-light');
                }
            });
        });
    });
</script>
@endpush