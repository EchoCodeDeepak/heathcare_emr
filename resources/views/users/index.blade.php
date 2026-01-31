@extends('layouts.app')

@section('content')
<div class="container py-3">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">User Management</h4>
                </div>

                <div class="card-body">
                    <!-- Search and Filter Section -->
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <form id="searchForm" method="GET" action="{{ route('admin.users.index') }}" class="row g-3">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                        <input type="text" name="search" class="form-control"
                                            placeholder="Search by name, email, or role..."
                                            value="{{ request('search') }}">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <select name="role" class="form-control" onchange="this.form.submit()">
                                        <option value="">All Roles</option>
                                        @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ request('role') == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-filter"></i> Filter
                                    </button>
                                </div>

                                <div class="col-md-3 text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-success dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-download"></i> Export
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin.users.export.pdf') . '?' . http_build_query(request()->query()) }}">
                                                    <i class="fas fa-file-pdf text-danger"></i> Export as PDF
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin.users.export.excel') . '?' . http_build_query(request()->query()) }}">
                                                    <i class="fas fa-file-excel text-success"></i> Export as Excel
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin.users.export.csv') . '?' . http_build_query(request()->query()) }}">
                                                    <i class="fas fa-file-csv text-info"></i> Export as CSV
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="mb-3">
                        <a href="{{ route('admin.users.create') }}" class="btn btn-success">
                            <i class="fas fa-user-plus"></i> Add New User
                        </a>

                        @if(request()->has('search') || request()->has('role'))
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Clear Filters
                        </a>
                        @endif
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>
                                        <a href="{{ route('admin.users.index', array_merge(request()->query(), ['sort' => 'id', 'direction' => request('sort') == 'id' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}"
                                            class="text-decoration-none text-dark">
                                            ID
                                            @if(request('sort') == 'id')
                                            <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                            @else
                                            <i class="fas fa-sort"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ route('admin.users.index', array_merge(request()->query(), ['sort' => 'name', 'direction' => request('sort') == 'name' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}"
                                            class="text-decoration-none text-dark">
                                            Name
                                            @if(request('sort') == 'name')
                                            <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                            @else
                                            <i class="fas fa-sort"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>
                                        <a href="{{ route('admin.users.index', array_merge(request()->query(), ['sort' => 'email', 'direction' => request('sort') == 'email' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}"
                                            class="text-decoration-none text-dark">
                                            Email
                                            @if(request('sort') == 'email')
                                            <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                            @else
                                            <i class="fas fa-sort"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>Role</th>
                                    <th>
                                        <a href="{{ route('admin.users.index', array_merge(request()->query(), ['sort' => 'created_at', 'direction' => request('sort') == 'created_at' && request('direction') == 'asc' ? 'desc' : 'asc'])) }}"
                                            class="text-decoration-none text-dark">
                                            Created At
                                            @if(request('sort') == 'created_at')
                                            <i class="fas fa-sort-{{ request('direction') == 'asc' ? 'up' : 'down' }}"></i>
                                            @else
                                            <i class="fas fa-sort"></i>
                                            @endif
                                        </a>
                                    </th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge bg-{{ 
                            $user->role_id == 1 ? 'danger' : 
                            ($user->role_id == 2 ? 'info' : 
                            ($user->role_id == 3 ? 'secondary' : 
                            ($user->role_id == 4 ? 'warning' : 'success')))
                        }}">
                                            {{ $user->role->name }}
                                        </span>
                                    </td>
                                    <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                    <td>
                                        @if($user->id != auth()->id())
                                        <div class="btn-group" role="group">
                                            <!-- Change Role Button -->
                                            <!-- <button class="btn btn-sm btn-info me-1" data-bs-toggle="modal" data-bs-target="#editRoleModal{{ $user->id }}">
                                                <i class="fas fa-user-edit"></i> Role
                                            </button> -->

                                            <!-- Update Button -->
                                            <a href="{{ route('admin.users.edit', $user) }}" title="Edit"  class="btn btn-sm btn-warning me-1">
                                                <i class="fas fa-edit"></i> 
                                            </a>

                                            <!-- Delete Button -->
                                            <button type="button" class="btn btn-sm btn-danger" title="Delete"
                                                data-bs-toggle="modal" data-bs-target="#deleteModal{{ $user->id }}">
                                                <i class="fas fa-trash"></i> 
                                            </button>
                                        </div>
                                        @endif
                                    </td>
                                </tr>

                                <!-- Edit Role Modal -->
                                <!-- <div class="modal fade" id="editRoleModal{{ $user->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Change Role for {{ $user->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form id="roleForm{{ $user->id }}">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="mb-3">
                                                        <label for="role_id" class="form-label">Select Role</label>
                                                        <select name="role_id" class="form-control" required>
                                                            @foreach($roles as $role)
                                                            <option value="{{ $role->id }}" {{ $user->role_id == $role->id ? 'selected' : '' }}>
                                                                {{ $role->name }}
                                                                @if($user->role_id == $role->id)
                                                                (Current)
                                                                @endif
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="alert alert-info">
                                                        <small>
                                                            <strong>Current Role:</strong> {{ $user->role->name }}<br>
                                                            <strong>Role ID:</strong> {{ $user->role_id }}
                                                        </small>
                                                    </div>
                                                    <button type="button" class="btn btn-primary update-role"
                                                        data-user-id="{{ $user->id }}"
                                                        data-role-id="{{ $user->role_id }}">Update Role</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->

                                <!-- Delete Confirmation Modal -->
                                <div class="modal fade" id="deleteModal{{ $user->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header bg-danger text-white">
                                                <h5 class="modal-title">Confirm Delete</h5>
                                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <p>Are you sure you want to delete user <strong>{{ $user->name }}</strong>?</p>
                                                <p class="text-danger"><i class="fas fa-exclamation-triangle"></i> This action cannot be undone!</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">
                                                        <i class="fas fa-trash"></i> Delete User
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle"></i> No users found.
                                            @if(request()->has('search') || request()->has('role'))
                                            <a href="{{ route('admin.users.index') }}" class="alert-link">Clear filters</a> to see all users.
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($users->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-4">
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

                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Per Page: {{ $users->perPage() }}
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['per_page' => 10]) }}">10</a></li>
                                <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['per_page' => 25]) }}">25</a></li>
                                <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['per_page' => 50]) }}">50</a></li>
                                <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['per_page' => 100]) }}">100</a></li>
                            </ul>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-submit search on enter (optional)
    $('#searchForm input[name="search"]').on('keyup', function(e) {
        if (e.keyCode === 13) { // Enter key
            $('#searchForm').submit();
        }
    });

    // Clear search button
    $('.btn-clear-search').on('click', function() {
        $('#searchForm input[name="search"]').val('');
        $('#searchForm').submit();
    });

    // Update role functionality
    $(document).on('click', '.update-role', function() {
        const userId = $(this).data('user-id');
        const modalId = $(this).closest('.modal').attr('id');
        const roleId = $(`#${modalId} select[name="role_id"]`).val();

        if (!roleId) {
            toastr.error('Please select a role');
            return;
        }

        $.ajax({
            url: '{{ url("users") }}/' + userId + '/role',
            method: 'PUT',
            data: {
                _token: '{{ csrf_token() }}',
                role_id: roleId
            },
            success: function(response) {
                toastr.success('Role updated!');
                location.reload();
            },
            error: function() {
                toastr.error('Error updating role');
            }
        });
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
                    location.reload(); // Reload to refresh pagination
                },
                error: function() {
                    toastr.error('Error deleting user');
                }
            });
        }
    });
});
</script>
<!-- <script>
    $(document).ready(function() {
        $(document).on('click', '.update-role', function() {
            const userId = $(this).data('user-id');

            // Get role ID from the select in the same modal
            const modalId = $(this).closest('.modal').attr('id');
            const roleId = $(`#${modalId} select[name="role_id"]`).val();
            alert(roleId);

            if (!roleId) {
                toastr.error('Please select a role');
                return;
            }

            $.ajax({
                url: '{{ url("users") }}/' + userId + '/role',
                method: 'PUT',
                data: {
                    _token: '{{ csrf_token() }}',
                    role_id: roleId
                },
                success: function(response) {
                    toastr.success('Role updated!');
                    location.reload();
                },
                error: function() {
                    toastr.error('Error updating role');
                }
            });
        });
    });

    // Add delete confirmation with AJAX (optional)
    $(document).ready(function() {
        // Handle delete with AJAX for better UX
        $(document).on('submit', 'form[action*="users/"][method="DELETE"]', function(e) {
            e.preventDefault();

            const form = $(this);
            const userId = form.data('user-id') || form.attr('action').split('/').pop();

            if (confirm('Are you sure you want to delete this user?')) {
                $.ajax({
                    url: form.attr('action'),
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        toastr.success('User deleted successfully!');
                        // Remove the table row
                        $(`tr:has(td:contains("${userId}"))`).fadeOut(300, function() {
                            $(this).remove();
                        });
                        // Close modal if open
                        $(`#deleteModal${userId}`).modal('hide');
                    },
                    error: function() {
                        toastr.error('Error deleting user');
                    }
                });
            }
        });
    });
</script> -->
@endpush