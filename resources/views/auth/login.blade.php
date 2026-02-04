<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Healthcare EMR') }} - Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&display=swap" rel="stylesheet">

    <style>
        :root {
            --banner-height-desktop: 100vh;
            --banner-height-tablet: 75vh;
            --banner-height-mobile: 60vh;
        }

        body {
            font-family: "Josefin Sans", sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        .banner-wrapper {
            position: relative;
            width: 100%;
            height: 100vh;
            background-image: url("{{ asset('images/banner.jpg') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 35px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            max-width: 450px;
            width: 90%;
        }

        .login-header {
            background: linear-gradient(135deg, #0d6efd 0%, #0aabf1 100%);
            color: white;
            padding: 1.5rem;
            border-radius: 16px 16px 0 0;
        }

        .login-footer {
            background: #f8f9fa;
            padding: 1rem;
            border-radius: 0 0 16px 16px;
        }

        .btn-login {
            background: linear-gradient(135deg, #0d6efd 0%, #0aabf1 100%);
            border: none;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #0aabf1 0%, #0d6efd 100%);
        }

        @media (max-width: 576px) {
            .login-card {
                width: 95%;
            }

            .login-header {
                padding: 1rem;
            }

            .login-body {
                padding: 1.25rem;
            }
        }
    </style>
</head>

<body>
    <div class="banner-wrapper">
        <div class="login-card shadow-sm border-0 rounded-3 bg-white bg-opacity-75 backdrop-blur">
            <!-- Header -->
            <div class="login-header text-center">
                <h4 class="mb-0">
                    <i class="fas fa-hospital"></i> Healthcare EMR
                </h4>
                <small>Secure Medical Records System</small>
            </div>

            <!-- Body -->
            <div class="login-body p-4">
                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

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
                        <button type="submit" class="btn btn-primary btn-login text-light btn-lg">
                            Login
                        </button>
                    </div>
                </form>
            </div>

            <!-- Footer -->
            <div class="login-footer text-center">
                <small class="text-muted">
                    © {{ date('Y') }} Healthcare EMR System
                </small>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</body>

</html>