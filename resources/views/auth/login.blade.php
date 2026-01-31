@extends('layouts.app')

@push('styles')
<style>
    .banner-wrapper {
        background-image: url('{{asset('images/banner.jpg')}}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        height: 100vh;
    }
</style>
@endpush

@section('content')
<div class="container-fluid banner-wrapper pt-3">
    <div class="row justify-content-center align-items-center">
        <div class="col-lg-5 col-md-7 col-sm-10">
            <div class="card shadow-sm border-0 rounded-3 bg-white bg-opacity-75 backdrop-blur">
                <!-- Header -->
                <div class="card-header bg-info text-white text-center rounded-top">
                    <h4 class="mb-0">Welcome Back</h4>
                    <small>Login to your EMR account</small>
                </div>
                <!-- Body -->
                <div class="card-body p-4">

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input id="email"
                                type="email"
                                class="form-control @error('email') is-invalid @enderror"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus>

                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input id="password"
                                type="password"
                                class="form-control @error('password') is-invalid @enderror"
                                name="password"
                                required>

                            @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Remember -->
                        <div class="mb-3 form-check">
                            <input class="form-check-input"
                                type="checkbox"
                                name="remember"
                                id="remember"
                                {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                Remember me
                            </label>
                        </div>

                        <!-- Button -->
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn text-light btn-info btn-lg">
                                Login
                            </button>
                        </div>

                        <!-- Forgot -->
                        @if (Route::has('password.request'))
                        <div class="text-center">
                            <a href="{{ route('password.request') }}" class="text-decoration-none">
                                Forgot your password?
                            </a>
                        </div>
                        @endif

                    </form>
                </div>

                <!-- Footer -->
                <div class="card-footer text-center bg-light">
                    <small class="text-muted">
                        Secure Healthcare EMR System
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection