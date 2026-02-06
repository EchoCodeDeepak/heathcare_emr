@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<div class="main-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h1 class="page-title">User Management</h1>
            <p class="page-subtitle">Manage system users and their roles</p>
        </div>
        @if(auth()->user()->hasPermission('manage-users'))
        <a href="{{ route('admin.users.create') }}" class="btn btn-success">
            <i class="fas fa-user-plus me-2"></i> Add New User
        </a>
        @endif
    </div>
</div>

<!-- Search and Filter Section -->
<div class="filter-section">
    <form id="searchForm" method="GET" action="{{ route('admin.users.index') }}" class="filter-row">
        <div class="filter-group">
            <label class="form-label">Search</label>
            <div class="input-group">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
                <input type="text" name="search" class="form-control"
                    placeholder="Search by name, email, or role..."
                    value="{{ request('search') }}">
            </div>
        </div>

        <div class="filter-group">
            <label class="form-label">Role</label>
            <select name="role" class="form-select" onchange="this.form.submit()">
                <option value="">All Roles</option>
                @foreach($roles as $role)
                <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>
                    {{ $role->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="filter-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-filter me-1"></i> Filter
            </button>
            @if(request()->has('search') || request()->has('role'))
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-times me-1"></i> Clear
            </a>
            @endif
        </div>
    </form>
</div>

<!-- Users Table Card -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th style="width: 60px;">ID</th>
                        <th>
                            <a href="{{ route('admin.users.index', array_merge(request()->query(), ['sort' => 'name', 'direction' => request('sort') == 'name' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}"
                                class="text-decoration-none text-dark d-flex align-items-center gap-1">
                                Name
                                @if(request('sort') == 'name')
                                <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                @else
                                <i class="fas fa-sort text-muted"></i>
                                @endif
                            </a>
                        </th>
                        <th>
                            <a href="{{ route('admin.users.index', array_merge(request()->query(), ['sort' => 'email', 'direction' => request('sort') == 'email' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}"
                                class="text-decoration-none text-dark d-flex align-items-center gap-1">
                                Email
                                @if(request('sort') == 'email')
                                <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                @else
                                <i class="fas fa-sort text-muted"></i>
                                @endif
                            </a>
                        </th>
                        <th>Role</th>
                        <th>
                            <a href="{{ route('admin.users.index', array_merge(request()->query(), ['sort' => 'created_at', 'direction' => request('sort') == 'created_at' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}"
                                class="text-decoration-none text-dark d-flex align-items-center gap-1">
                                Created At
                                @if(request('sort') == 'created_at')
                                <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                @else
                                <i class="fas fa-sort text-muted"></i>
                                @endif
                            </a>
                        </th>
                        <th style="width: 120px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>
                            <span class="text-muted">#{{ $user->id }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar-circle primary">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <strong>{{ $user->name }}</strong>
                                </div>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->role_id == 1)
                            <span class="badge badge-danger">Admin</span>
                            @elseif($user->role_id == 2)
                            <span class="badge badge-info">Doctor</span>
                            @elseif($user->role_id == 3)
                            <span class="badge badge-secondary">Nurse</span>
                            @elseif($user->role_id == 4)
                            <span class="badge badge-warning">Patient</span>
                            @else
                            <span class="badge badge-secondary">{{ $user->role->name }}</span>
                            @endif
                        </td>
                        <td>
                            <div>{{ $user->created_at->format('M d, Y') }}</div>
                            <div class="text-muted small">{{ $user->created_at->format('h:i A') }}</div>
                        </td>
                        <td>
                            @if($user->id != auth()->id())
                            <div class="table-actions">
                                @if(auth()->user()->hasPermission('manage-users'))
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-icon btn-primary" title="Edit User" data-bs-toggle="tooltip">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @endif

                                @if(auth()->user()->hasPermission('manage-users'))
                                <button type="button" class="btn btn-icon btn-danger" title="Delete User"
                                    data-bs-toggle="modal" data-bs-target="#deleteModal{{ $user->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                                @endif
                            </div>
                            @else
                            <span class="badge badge-secondary">You</span>
                            @endif
                        </td>
                    </tr>

                    <!-- Delete Confirmation Modal -->
                    <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Confirm Delete</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to delete user <strong>{{ $user->name }}</strong>?</p>
                                    <p class="text-danger mb-0"><i class="fas fa-exclamation-triangle me-2"></i>This action cannot be undone!</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-trash me-1"></i> Delete User
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <h5 class="empty-state-title">No Users Found</h5>
                                <p class="empty-state-text">
                                    @if(request()->has('search') || request()->has('role'))
                                    No users match your search criteria.
                                    @else
                                    No users have been added yet.
                                    @endif
                                </p>
                                @if(request()->has('search') || request()->has('role'))
                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i> Clear Filters
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Pagination -->
@if($users->hasPages())
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-4">
    <div class="text-muted">
        Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} users
    </div>
    <nav aria-label="Page navigation">
        <ul class="pagination mb-0">
            {{-- Previous Page Link --}}
            @if($users->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link"><i class="fas fa-chevron-left"></i></span>
            </li>
            @else
            <li class="page-item">
                <a class="page-link" href="{{ $users->previousPageUrl() . '&' . http_build_query(request()->except('page')) }}" aria-label="Previous">
                    <i class="fas fa-chevron-left"></i>
                </a>
            </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach($users->getUrlRange(1, $users->lastPage()) as $page => $url)
            @if($page == $users->currentPage())
            <li class="page-item active">
                <span class="page-link">{{ $page }}</span>
            </li>
            @else
            <li class="page-item">
                <a class="page-link" href="{{ $url . '&' . http_build_query(request()->except('page')) }}">{{ $page }}</a>
            </li>
            @endif
            @endforeach

            {{-- Next Page Link --}}
            @if($users->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="{{ $users->nextPageUrl() . '&' . http_build_query(request()->except('page')) }}" aria-label="Next">
                    <i class="fas fa-chevron-right"></i>
                </a>
            </li>
            @else
            <li class="page-item disabled">
                <span class="page-link"><i class="fas fa-chevron-right"></i></span>
            </li>
            @endif
        </ul>
    </nav>
</div>
@endif
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Auto-submit search on enter
        $('#searchForm input[name="search"]').on('keyup', function(e) {
            if (e.keyCode === 13) {
                $('#searchForm').submit();
            }
        });

        // Delete confirmation with AJAX
        $(document).on('submit', 'form[action*="users/"][method="DELETE"]', function(e) {
            e.preventDefault();
            const form = $(this);
            const modal = form.closest('.modal');

            if (confirm('Are you sure you want to delete this user?')) {
                $.ajax({
                    url: form.attr('action'),
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        toastr.success('User deleted successfully!');
                        modal.modal('hide');
                        location.reload();
                    },
                    error: function() {
                        toastr.error('Error deleting user');
                    }
                });
            }
        });
    });
</script>
@endpush