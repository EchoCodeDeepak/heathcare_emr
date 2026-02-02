@extends('layouts.app')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white text-center">
                    <h3 class="mb-0">🩺 Healthcare EMR System</h3>
                    <p class="mb-0 small">Login to Your Account</p>
                </div>
                <div class="card-body p-5">
                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required autofocus>
                            @error('email')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label fw-bold">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                Remember Me
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold">Login</button>
                    </form>

                    @if ($errors->any())
                        <div class="alert alert-danger mt-3">
                            <strong>Login Failed!</strong> Please check your credentials and try again.
                        </div>
                    @endif

                    <hr class="my-4">
                    
                    <!-- Demo Credentials Info -->
                    <div class="alert alert-info small">
                        <h6 class="mb-2"><i class="fas fa-info-circle"></i> Demo Credentials</h6>
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>👨‍💼 Admin:</strong> admin@emr.com</p>
                                <p class="mb-1"><strong>👨‍⚕️ Doctor:</strong> doctor@emr.com</p>
                                <p class="mb-0"><strong>👩‍⚕️ Nurse:</strong> nurse@emr.com</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>🧪 Lab Tech:</strong> lab@emr.com</p>
                                <p class="mb-1"><strong>👤 Patient:</strong> patient@emr.com</p>
                                <p class="mb-0"><strong>🔑 Password:</strong> password123</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection