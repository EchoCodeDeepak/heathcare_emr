<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Healthcare EMR') }}</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&display=swap" rel="stylesheet">
    <style>
        .navbar-brand {
            font-family: "Josefin Sans", sans-serif;
            font-optical-sizing: auto;
            font-weight: 500;
            font-style: normal;
        }
    </style>
    @stack('styles')

    <style>

        :root {
            /* Easy height control */
            --banner-height-desktop: 100vh;
            --banner-height-tablet: 75vh;
            --banner-height-mobile: 60vh;
        }

        /* Main banner wrapper */
        .banner-wrapper {
            position: relative;
            width: 100%;
            height: var(--banner-height-desktop);
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            /* background-image: url('{{ asset(' images/banner.jpg') }}'); */
            /* display: flex; */
        }

        /* Overlay */
        .banner-overlay {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.25);
            /* optional dark overlay */
        }

        /* Content box */
        .overlay-box {
            background: rgba(0, 0, 0, 0.65);
            color: #fff;
            padding: 2rem 2.5rem;
            border-radius: 16px;
            max-width: 720px;
            width: 90%;
        }

        /* ===============================
   RESPONSIVE HEIGHT CONTROL
   =============================== */

        @media (max-width: 992px) {
            .banner-wrapper {
                height: var(--banner-height-tablet);
            }
        }

        @media (max-width: 576px) {
            .banner-wrapper {
                height: var(--banner-height-mobile);
            }

            .overlay-box {
                padding: 1.25rem 1.5rem;
            }
        }
    </style>


</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container-fluid px-4">
                <a class="navbar-brand  " href="{{ url('/') }}">
                    <i class="fas fa-hospital "></i> Healthcare EMR
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto">
                        @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        @if(auth()->user()->hasPermission('view-medical-records'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('medical-records.index') }}">
                                <i class="fas fa-file-medical"></i> Medical Records
                            </a>
                        </li>
                        @endif
                        @if(auth()->user()->isAdmin())
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('users.index') }}">
                                <i class="fas fa-users"></i> Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('permissions.index') }}">
                                <i class="fas fa-key"></i> Permissions
                            </a>
                        </li>
                        @endif
                        @endauth
                    </ul>

                    <ul class="navbar-nav ms-auto">
                        @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                        @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle"></i> {{ Auth::user()->name }}
                                <span class="badge bg-primary">{{ Auth::user()->role->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('dashboard') }}">
                                        <i class="fas fa-tachometer-alt"></i> Dashboard
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-0 ">
            @if(session('success'))
            <div class="container">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="container">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
            @endif

            @yield('content')
        </main>
    </div>
    <!-- <footer class="bg-dark text-white text-center py-3 mt-auto">
        <div class="container">
            <small>
                © {{ date('Y') }} Healthcare EMR System | Secure Medical Records Management
            </small>
        </div>
    </footer> -->


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    @stack('scripts')
</body>

</html>