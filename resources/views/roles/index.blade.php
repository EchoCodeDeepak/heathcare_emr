@extends('layouts.app')

@section('title', 'Roles Management')

@section('content')
<div class="main-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1 class="page-title">Roles Management</h1>
            <p class="page-subtitle">Manage system roles and their permissions</p>
        </div>
        <a href="{{ route('admin.roles.create') }}" class="btn btn-success">
            <i class="fas fa-plus-circle me-2"></i> Add Role
        </a>
    </div>
</div>

<!-- Info Alert -->
<div class="alert alert-info">
    <i class="fas fa-info-circle me-2"></i>
    <strong>Note:</strong> System Admin role is protected and cannot be modified. All other roles can be created, edited, or deleted.
</div>

<!-- Roles Table Card -->
<div class="card">
    <div class="card-body p-0">
        <!-- Search Bar -->
        <div class="p-3 border-bottom">
            <div class="row">
                <div class="col-md-9">
                    <input type="text" id="searchRoles" class="form-control"
                        placeholder="Search by role name..." />
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary w-100" id="searchBtn">
                        <i class="fas fa-search me-1"></i> Search
                    </button>
                </div>
            </div>
        </div>

        @if($roles->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover mb-0">
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
                            <span class="badge badge-primary">
                                {{ ucfirst(str_replace('-', ' ', $role->name)) }}
                            </span>
                        </td>
                        <td>
                            @if($role->slug === 'system-admin')
                            <span class="text-secondary">Administrator</span>
                            @else
                            <span class="text-secondary">{{ ucfirst(str_replace('-', ' ', $role->slug)) }}</span>
                            @endif
                        </td>
                        <td>
                            @if($role->slug === 'system-admin')
                            <span class="badge badge-success">
                                <i class="fas fa-check-circle me-1"></i> All Permissions
                            </span>
                            @else
                            @if($role->permissions->count() > 0)
                            <div class="d-flex flex-wrap gap-1">
                                @foreach($role->permissions->take(4) as $permission)
                                <span class="badge badge-info">
                                    {{ str_replace('-', ' ', $permission->slug) }}
                                </span>
                                @endforeach
                                @if($role->permissions->count() > 4)
                                <span class="badge badge-secondary">
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
                            <div class="table-actions d-inline-flex">
                                <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-icon btn-primary" title="Edit Role" data-bs-toggle="tooltip">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if($role->users()->count() === 0)
                                <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-icon btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this role?')" title="Delete Role" data-bs-toggle="tooltip">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                                @else
                                <button class="btn btn-icon btn-secondary" disabled title="Cannot delete role with assigned users" data-bs-toggle="tooltip">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endif
                            </div>
                            @else
                            <span class="text-muted small">
                                <i class="fas fa-lock me-1"></i> Protected
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
            <div class="empty-state">
                <div class="empty-state-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h5 class="empty-state-title">No Roles Found</h5>
                <p class="empty-state-text">No roles have been created yet.</p>
                <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Create First Role
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

<script>
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