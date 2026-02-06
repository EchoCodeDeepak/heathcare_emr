@extends('layouts.app')

@section('title', 'Permissions Management')

@section('content')
<div class="main-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1 class="page-title">Permissions Management</h1>
            <p class="page-subtitle">Manage role permissions across the system</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="fas fa-users me-2"></i> Manage Users
        </a>
    </div>
</div>

<!-- Info Alert -->
<div class="alert alert-info">
    <i class="fas fa-info-circle me-2"></i>
    <strong>Note:</strong> System Administrators automatically have all permissions.
    Changes made here will affect all users with the selected role.
</div>

<!-- Permissions Card -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th width="15%" class="bg-subtle">Role</th>
                        @foreach($permissionGroups as $groupName => $groupPermissions)
                        <th colspan="{{ count($groupPermissions) }}" class="text-center bg-subtle" style="min-width: 100px;">
                            {{ $groupName }}
                        </th>
                        @endforeach
                    </tr>
                    <tr>
                        <th class="bg-subtle"></th>
                        @foreach($permissionGroups as $groupPermissions)
                        @foreach($groupPermissions as $permissionSlug)
                        @php
                        $permission = $permissions->where('slug', $permissionSlug)->first();
                        @endphp
                        @if($permission)
                        <th class="text-center bg-subtle" title="{{ $permission->name }}">
                            <small>{{ str_replace('-', ' ', $permissionSlug) }}</small>
                        </th>
                        @endif
                        @endforeach
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $role)
                    <tr>
                        <td>
                            @if($role->slug == 'system-admin')
                            <span class="badge badge-danger">Admin</span>
                            @else
                            <span class="badge badge-primary">{{ ucfirst(str_replace('-', ' ', $role->name)) }}</span>
                            @endif
                        </td>
                        @foreach($permissionGroups as $groupPermissions)
                        @foreach($groupPermissions as $permissionSlug)
                        @php
                        $permission = $permissions->where('slug', $permissionSlug)->first();
                        @endphp
                        @if($permission)
                        <td class="text-center">
                            @if($role->slug == 'system-admin')
                            <span class="text-success">
                                <i class="fas fa-check-circle"></i>
                            </span>
                            @else
                            <input type="checkbox"
                                class="permission-checkbox form-check-input"
                                style="width: 18px; height: 18px; cursor: pointer;"
                                data-role-id="{{ $role->id }}"
                                data-permission-id="{{ $permission->id }}"
                                {{ $role->permissions->contains($permission) ? 'checked' : '' }}>
                            @endif
                        </td>
                        @endif
                        @endforeach
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Permission Legend -->
        <div class="p-3 border-top bg-subtle">
            <h6 class="mb-3">Permission Legend:</h6>
            <div class="row">
                @foreach($permissions as $permission)
                <div class="col-md-3 col-sm-4 mb-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge badge-info">{{ $permission->slug }}</span>
                        <small class="text-secondary">{{ $permission->name }}</small>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Debounce function to prevent too many AJAX calls
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        const updatePermissions = debounce(function(roleId) {
            const checkedPermissions = [];
            $(`.permission-checkbox[data-role-id="${roleId}"]:checked`).each(function() {
                checkedPermissions.push($(this).data('permission-id'));
            });

            $.ajax({
                url: '{{ route("permissions.update-role") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    role_id: roleId,
                    permissions: checkedPermissions
                },
                success: function(response) {
                    toastr.success('Permissions updated successfully for ' + response.role.name);
                },
                error: function() {
                    toastr.error('Error updating permissions');
                }
            });
        }, 500);

        $('.permission-checkbox').change(function() {
            const roleId = $(this).data('role-id');
            updatePermissions(roleId);
        });
    });
</script>
@endpush