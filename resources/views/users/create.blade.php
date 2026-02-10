@extends('layouts.app')

@section('content')
<div class="container py-3">
    <div class="row justify-content-center">
        <div class="col-md-11">
            <div class="card shadow-sm">
                <div class="card-header  text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-user-plus"></i> Create New User
                    </h4>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('admin.users.store') }}" id="createEntityForm">
                        @csrf

                        <hr class="my-4">

                        <!-- USER FIELDS -->
                        <div id="userFields">
                            <h5 class="mb-3"><i class="fas fa-user-circle"></i> User Information</h5>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="user_name" class="form-label fw-bold">Full Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="user_name" name="name" value="{{ old('name') }}" placeholder="e.g., John Doe">
                                        @error('name')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label fw-bold">Email Address <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email') }}" placeholder="user@example.com">
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
                                            id="password" name="password" placeholder="Min 8 characters">
                                        @error('password')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label fw-bold">Confirm Password <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                                            id="password_confirmation" name="password_confirmation" placeholder="Confirm password">
                                        @error('password_confirmation')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-4">
                                        <label for="role_id" class="form-label fw-bold">Assign Role <span class="text-danger">*</span></label>
                                        <select class="form-select @error('role_id') is-invalid @enderror"
                                            id="role_id" name="role_id">
                                            <option value="">-- Choose a Role --</option>
                                            @foreach($allRoles as $role)
                                            <option value="{{ $role->id }}"
                                                data-role-name="{{ $role->name }}"
                                                {{ old('role_id') == $role->id ? 'selected' : '' }}>
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

                            <hr class="my-3">
                        </div>

                        <!-- Role creation removed: roles are managed separately -->



                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection