@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h4 class="mb-0"><i class="fas fa-plus-circle text-success"></i> Create New Role</h4>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.roles.store') }}" id="createRoleForm">
                        @csrf

                        <!-- Role Name and Slug -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name" class="form-label fw-bold">Role Name <span class="text-danger">*</span></label>
                                    <input type="text"
                                        class="form-control @error('name') is-invalid @enderror"
                                        id="name"
                                        name="name"
                                        value="{{ old('name') }}"
                                        placeholder="e.g., Doctor, Nurse"
                                        required
                                        autofocus>
                                    @error('name')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="slug" class="form-label fw-bold">Role Slug <span class="text-danger">*</span></label>
                                    <input type="text"
                                        class="form-control @error('slug') is-invalid @enderror"
                                        id="slug"
                                        name="slug"
                                        value="{{ old('slug') }}"
                                        placeholder="e.g., doctor, nurse"
                                        required>
                                    @error('slug')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Use lowercase letters and hyphens</small>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Permissions Section -->
                        <h5 class="mb-4 card-title fw-bold">
                            <i class="fas fa-lock text-primary"></i> Permissions
                        </h5>

                        @if($permissionGroups && count($permissionGroups) > 0)
                        <div class="row">
                            @foreach($permissionGroups as $groupName => $groupPermissions)
                            <div class="col-md-6 mb-4">
                                <div class="card h-100 border">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0 fw-bold">
                                            <i class="fas fa-folder-open text-warning"></i> {{ $groupName }}
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        @foreach($groupPermissions as $permissionSlug)
                                        @php
                                        $permission = $permissions->where('slug', $permissionSlug)->first();
                                        $isChecked = old('permissions') && in_array($permission->id, old('permissions'));
                                        @endphp
                                        @if($permission)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input permission-checkbox"
                                                type="checkbox"
                                                name="permissions[]"
                                                value="{{ $permission->id }}"
                                                id="perm_{{ $permission->id }}"
                                                {{ $isChecked ? 'checked' : '' }}>
                                            <label class="form-check-label" for="perm_{{ $permission->id }}">
                                                {{ str_replace('-', ' ', ucfirst($permissionSlug)) }}
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
                        <div class="alert alert-warning" role="alert">
                            <i class="fas fa-exclamation-triangle"></i> No permission groups available. Please seed permissions first.
                        </div>
                        @endif

                        <hr class="my-4">

                        <!-- Buttons -->
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Roles
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Create Role
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
    // Auto-generate slug from name
    document.getElementById('name').addEventListener('keyup', function() {
        const name = this.value;
        const slug = name
            .toLowerCase()
            .trim()
            .replace(/[^\w\s-]/g, '')
            .replace(/[\s_-]+/g, '-')
            .replace(/^-+|-+$/g, '');
        document.getElementById('slug').value = slug;
    });

    // Add visual feedback for checked checkboxes
    document.addEventListener('DOMContentLoaded', function() {
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