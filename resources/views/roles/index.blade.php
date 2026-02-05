@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-md-12">
            <!-- Header Section -->
            <div class="page-header mb-4">
                <div class="d-flex justify-content-between align-items-center px-4">
                    <div>
                        <h3 class="mb-0"><i class="fas fa-shield-alt me-2"></i>Roles Management</h3>
                    </div>
                    <a href="{{ route('admin.roles.create') }}" class="btn btn-light">
                        <i class="fas fa-plus-circle me-1"></i> Add Role
                    </a>
                </div>
            </div>

            <!-- Alerts -->
            @if($message = Session::get('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>{{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if($message = Session::get('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>{{ $message }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <!-- Main Content Card -->
            <div class="card">
                <!-- Info Alert -->
                <div class="p-3" style="background-color: var(--info-soft);">
                    <div class="alert alert-info mb-0" style="background: transparent; border: none; color: #1e40af;">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Note:</strong> System Admin role is protected and cannot be modified. All other roles can be created, edited, or deleted.
                    </div>
                </div>

                <!-- Search Bar -->
                <div class="p-3 border-bottom">
                    <div class="row">
                        <div class="col-md-9">
                            <input type="text" id="searchRoles" class="form-control"
                                placeholder="Search by role name..." />
                        </div>
                        <div class="col-md-3 text-end">
                            <button class="btn btn-primary" id="searchBtn">
                                <i class="fas fa-search me-1"></i> Search
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Roles Table -->
                @if($roles->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped table-hover mb-0">
                        <thead>
                            <tr>
                                <th style="width: 25%;">Role Name</th>
                                <th style="width: 20%;">Slug</th>
                                <th style="width: 35%;">Permissions</th>
                                <th style="width: 20%;" class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roles as $role)
                            <tr>
                                <td>
                                    <span class="badge badge-primary fs-6">
                                        {{ ucfirst(str_replace('-', ' ', $role->name)) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="text-secondary">
                                        @if($role->slug === 'system-admin')
                                        Administrator
                                        @else
                                        {{ ucfirst(str_replace('-', ' ', $role->slug)) }}
                                        @endif
                                    </span>
                                </td>
                                <td>
                                    @if($role->slug === 'system-admin')
                                    <span class="badge badge-success fs-6">
                                        <i class="fas fa-check-circle me-1"></i> All Permissions
                                    </span>
                                    @else
                                    @if($role->permissions->count() > 0)
                                    <div class="d-flex flex-wrap gap-1">
                                        @foreach($role->permissions->take(4) as $permission)
                                        <span class="badge badge-info fs-6">
                                            {{ str_replace('-', ' ', $permission->slug) }}
                                        </span>
                                        @endforeach
                                        @if($role->permissions->count() > 4)
                                        <span class="badge badge-secondary fs-6">
                                            +{{ $role->permissions->count() - 4 }} more
                                        </span>
                                        @endif
                                    </div>
                                    @else
                                    <span class="text-muted small">
                                        <i class="fas fa-ban me-1"></i> No permissions
                                    </span>
                                    @endif
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if($role->slug !== 'system-admin')
                                    <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm btn-outline-primary" title="Edit Role" data-bs-toggle="tooltip">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($role->users()->count() === 0)
                                    <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Are you sure you want to delete this role?')" title="Delete Role" data-bs-toggle="tooltip">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @else
                                    <button class="btn btn-sm btn-outline-secondary" disabled title="Cannot delete role with assigned users" data-bs-toggle="tooltip">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    @endif
                                    @else
                                    <span class="text-muted small">
                                        <i class="fas fa-lock"></i> Protected
                                    </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($roles->hasPages())
                <div class="p-3 border-top">
                    {{ $roles->links() }}
                </div>
                @endif
                @else
                <div class="card-body">
                    <div class="alert alert-warning mb-0">
                        <i class="fas fa-exclamation-triangle"></i> No roles found.
                        <a href="{{ route('admin.roles.create') }}">Create a new role</a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    // Simple search functionality
    document.getElementById('searchRoles')?.addEventListener('keyup', function(e) {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('table tbody tr');

        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
    });
</script>
@endsection