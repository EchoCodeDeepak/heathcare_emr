@extends('layouts.app')

@section('content')
<div class="container py-3">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-user-plus"></i> Create New User</h4>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.users.store') }}" id="createUserForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label fw-bold">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required autofocus>
                                    @error('name')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label fw-bold">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label fw-bold">Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" required>
                                    @error('password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label fw-bold">Confirm Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" 
                                           id="password_confirmation" name="password_confirmation" required>
                                    @error('password_confirmation')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-4">
                                    <label for="role_id" class="form-label fw-bold">Select User Role <span class="text-danger">*</span></label>
                                    <select class="form-select @error('role_id') is-invalid @enderror" 
                                            id="role_id" name="role_id" required onchange="loadRolePermissions()">
                                        <option value="">-- Choose a Role --</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" data-role-name="{{ $role->name }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('role_id')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <!-- Permissions Section -->
                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="mb-3"><i class="fas fa-lock"></i> Assign Permissions</h5>
                                <p class="text-muted small mb-3">Select which features and actions this user can access:</p>
                                
                                <div id="permissionsContainer" class="row">
                                    <div class="col-12">
                                        <p class="text-warning"><i class="fas fa-info-circle"></i> Please select a role first to view available permissions.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function loadRolePermissions() {
    const roleId = document.getElementById('role_id').value;
    const permissionsContainer = document.getElementById('permissionsContainer');
    
    if (!roleId) {
        permissionsContainer.innerHTML = '<div class="col-12"><p class="text-warning"><i class="fas fa-info-circle"></i> Please select a role first to view available permissions.</p></div>';
        return;
    }

    // Fetch permissions for this role
    fetch(`/api/permissions?role_id=${roleId}`, {
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.permissionGroups && Object.keys(data.permissionGroups).length > 0) {
            let html = '';
            
            Object.entries(data.permissionGroups).forEach(([groupName, permissions]) => {
                html += `
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">${groupName}</h6>
                            </div>
                            <div class="card-body">
                `;
                
                permissions.forEach(permissionSlug => {
                    const permission = data.permissions.find(p => p.slug === permissionSlug);
                    if (permission) {
                        html += `
                            <div class="form-check mb-2">
                                <input class="form-check-input role-permission-checkbox" type="checkbox" 
                                       name="permissions[]" value="${permission.id}" id="perm_${permission.id}">
                                <label class="form-check-label" for="perm_${permission.id}">
                                    ${permission.name}
                                </label>
                            </div>
                        `;
                    }
                });
                
                html += `
                            </div>
                        </div>
                    </div>
                `;
            });
            
            permissionsContainer.innerHTML = html;
        } else {
            permissionsContainer.innerHTML = '<div class="col-12"><p class="text-info"><i class="fas fa-info-circle"></i> No specific permissions for this role.</p></div>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        permissionsContainer.innerHTML = '<div class="col-12"><p class="text-danger"><i class="fas fa-exclamation-circle"></i> Error loading permissions.</p></div>';
    });
}

// Initialize permissions on page load if role is already selected
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('role_id').value) {
        loadRolePermissions();
    }
});
</script>
@endsection
