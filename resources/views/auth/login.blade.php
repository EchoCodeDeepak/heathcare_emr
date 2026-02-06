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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-500: #0891b2;
            --primary-600: #0e7490;
            --primary-700: #0b566e;
            --success-500: #22c55e;
            --success-600: #16a34a;
            --warning-500: #f59e0b;
            --warning-600: #d97706;
            --info-500: #3b82f6;
            --info-600: #2563eb;
            --text-primary: #334155;
            --text-secondary: #64748b;
            --font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.06);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            --radius-lg: 0.75rem;
            --radius-xl: 1rem;
        }

        body {
            font-family: var(--font-family);
            font-size: 0.875rem;
            line-height: 1.5;
            color: var(--text-primary);
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background: linear-gradient(135deg, var(--primary-600) 0%, var(--primary-700) 100%);
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

        .banner-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(55deg,
                    rgb(52 84 180 / 88%) 0%,
                    rgb(72 170 168 / 92%) 100%);
        }

        .login-card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: var(--radius-xl);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            max-width: 420px;
            width: 90%;
            position: relative;
            z-index: 1;
        }

        .login-header {
            color: var(--text-primary);
            padding: 1.5rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .login-body {
            padding: 1.5rem;
        }

        .login-footer {
            background: #f8fafc;
            padding: 1rem;
            border-top: 1px solid #e2e8f0;
            border-radius: 0 0 var(--radius-xl) var(--radius-xl);
        }

        .btn-login {
            background: linear-gradient(135deg, var(--primary-500) 0%, var(--primary-600) 100%);
            border: none;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            color: white;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, var(--primary-600) 0%, var(--primary-700) 100%);
            color: white;
        }

        .form-control {
            border-radius: var(--radius-lg);
            border: 1px solid #e2e8f0;
            padding: 0.625rem 0.875rem;
            font-size: 0.875rem;
        }

        .form-control:focus {
            border-color: var(--primary-500);
            box-shadow: 0 0 0 3px rgba(8, 145, 178, 0.15);
        }

        .form-label {
            font-weight: 500;
            font-size: 0.875rem;
            color: var(--text-primary);
            margin-bottom: 0.375rem;
        }

        .form-check-input:checked {
            background-color: var(--primary-500);
            border-color: var(--primary-500);
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
        <div class="banner-overlay"></div>
        <div class="login-card shadow-sm border-0">
            <!-- Header -->
            <div class="login-header text-center">
                <h4 class="mb-0">
                    <i class="fas fa-hospital" style="color: var(--primary-600);"></i> Healthcare EMR
                </h4>
                <small class="text-muted">Secure Medical Records System</small>
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