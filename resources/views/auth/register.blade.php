@extends('layouts.app')

@section('content')
<div class="container-fluid py-3  banner-wrapper" style="background-image: url('{{ asset('images/banner.jpg') }}'); width:100%; height:110vh;">
    <div class="row justify-content-center align-items-center">
        <div class="col-lg-5 col-md-7 col-sm-10">
            <div class="card  shadow-sm border-0 rounded-3 bg-white bg-opacity-75 backdrop-blur">

                <!-- Header -->
                <div class="card-header bg-info text-white text-center rounded-top">
                    <h4 class="mb-0">Create Account</h4>
                    <small>Healthcare EMR System</small>
                </div>

                <!-- Body -->
                <div class="card-body p-4">

                    {{-- Validation Errors --}}
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <!-- Full Name -->
                        <div class="mb-2">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text"
                                class="form-control"
                                id="name"
                                name="name"
                                value="{{ old('name') }}"
                                required
                                autofocus>
                        </div>

                        <!-- Email -->
                        <div class="mb-2">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email"
                                class="form-control"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                required>
                        </div>

                        <!-- Password -->
                        <div class="mb-2">
                            <label for="password" class="form-label">Password</label>
                            <input type="password"
                                class="form-control"
                                id="password"
                                name="password"
                                required>
                            <small class="text-muted">
                                Must be at least 8 characters long.
                            </small>
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-2">
                            <label for="password_confirmation" class="form-label">
                                Confirm Password
                            </label>
                            <input type="password"
                                class="form-control"
                                id="password_confirmation"
                                name="password_confirmation"
                                required>
                        </div>

                        <!-- Role (Admin Only) -->
                        @auth
                        @if(Auth::user()->isAdmin())
                        <div class="mb-2">
                            <label for="role_id" class="form-label">User Role</label>
                            <select class="form-select" id="role_id" name="role_id" required>
                                <option value="">Select Role</option>
                                @foreach($roles as $role)
                                <option value="{{ $role->id }}"
                                    {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                                @endforeach
                            </select>
                            <small class="text-muted">
                                Public registration defaults to Patient role.
                            </small>
                        </div>
                        @endif
                        @endauth

                        <!-- Button -->
                        <div class="d-grid mt-3">
                            <button type="submit" class="btn text-light btn-info btn-lg">
                                @auth
                                Create User
                                @else
                                Register
                                @endauth
                            </button>
                        </div>

                    </form>

                    <!-- Footer Link -->
                    @guest
                    <div class="text-center mt-3">
                        <a href="{{ route('login') }}" class="text-decoration-none">
                            Already have an account? Login
                        </a>
                    </div>
                    @endguest

                </div>

                <!-- Footer -->
                <!-- <div class="card-footer mb-5 text-center bg-light"> -->
                    <!-- <small class="text-muted"> -->
                        <!-- Secure Healthcare EMR System -->
                    <!-- </small> -->
                <!-- </div> -->
            

            </div>

        </div>

    </div>
</div>
@endsection