@extends('layouts.app')

<style>
    .badge1 {
        /* font-size: 0.85em; */
        /* color: #000 !important; */
        color: #098ee7 !important;
    }
</style>

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Role Permissions Management</h5>
                    <a href="{{ route('users.index') }}" class="btn btn-sm btn-secondary">
                        <i class="fas fa-users"></i> Manage Users
                    </a>
                </div>

                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        System Administrators automatically have all permissions.
                        Changes made here will affect all users with the selected role.
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="thead-dark">
                                <tr>
                                    <th width="15%">Role</th>
                                    @foreach($permissionGroups as $groupName => $groupPermissions)
                                    <th colspan="{{ count($groupPermissions) }}" class="text-center bg-light">
                                        {{ $groupName }}
                                    </th>
                                    @endforeach
                                </tr>
                                <tr>
                                    <th></th>
                                    @foreach($permissionGroups as $groupPermissions)
                                    @foreach($groupPermissions as $permissionSlug)
                                    @php
                                    $permission = $permissions->where('slug', $permissionSlug)->first();
                                    @endphp
                                    @if($permission)
                                    <th class="text-center" title="{{ $permission->name }}">
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
                                    <td class="font-weight-bold text-info">
                                        <span class="badge badge1 badge-{{ $role->slug == 'system-admin' ? 'danger' : 'primary' }}">
                                            {{ ucfirst(str_replace('-', ' ', $role->name)) }}
                                        </span>
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
                                            class="permission-checkbox"
                                            data-role-id="{{ $role->id }}"
                                            data-permission-id="{{ $permission->id }}"
                                            {{ $role->permissions->contains($permission) ? 'checked' : '' }}
                                            {{ $role->slug == 'system-admin' ? 'disabled' : '' }}>
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

                    <div class="mt-4">
                        <h5>Permission Legend:</h5>
                        <div class="row">
                            @foreach($permissions as $permission)
                            <div class="col-md-3 col-sm-4 mb-2">
                                <div class="d-flex align-items-center">
                                    <span class="badge badge1 badge-info mr-2">{{ $permission->slug }}</span>
                                    <small>{{ $permission->name }}</small>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
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
                error: function(xhr) {
                    toastr.error('Error updating permissions');
                    // Revert checkboxes on error
                    $('.permission-checkbox').each(function() {
                        const checkbox = $(this);
                        const roleId = checkbox.data('role-id');
                        const permissionId = checkbox.data('permission-id');
                        // You might want to store previous state and revert here
                    });
                }
            });
        }, 500);

        $('.permission-checkbox').change(function() {
            const roleId = $(this).data('role-id');
            updatePermissions(roleId);
        });

        // Bulk permission operations
        $('#select-all-view').change(function() {
            const isChecked = $(this).is(':checked');
            $('.permission-checkbox[data-permission-id*="view-"]').prop('checked', isChecked).trigger('change');
        });

        $('#select-all-edit').change(function() {
            const isChecked = $(this).is(':checked');
            $('.permission-checkbox[data-permission-id*="edit-"], .permission-checkbox[data-permission-id*="create-"], .permission-checkbox[data-permission-id*="add-"]').prop('checked', isChecked).trigger('change');
        });
    });
</script>
@endpush