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

    @auth
    <style>
        :root {
            --navbar-height: 56px;
        }

        .navbar-brand {
            font-family: "Josefin Sans", sans-serif;
            font-optical-sizing: auto;
            font-weight: 500;
            font-style: normal;
        }

        body {
            display: flex;
            flex-direction: column;
            padding-top: var(--navbar-height);
        }

        #app {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        nav.navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: var(--navbar-height);
            z-index: 1030;
            display: flex;
            align-items: center;
        }

        main.authenticated {
            flex: 1;
            margin-left: 280px;
            transition: margin-left 0.3s ease;
        }

        @media (max-width: 1199px) {
            main.authenticated {
                margin-left: 0;
            }
        }

        .btn-toggle-sidebar {
            background: none;
            border: none;
            color: #495057;
            font-size: 1.25rem;
            cursor: pointer;
            padding: 0.5rem;
            display: none;
        }

        .btn-toggle-sidebar:hover {
            color: #0d6efd;
        }

        @media (max-width: 1199px) {
            .btn-toggle-sidebar {
                display: block;
            }
        }
    </style>
    @endauth

    <style>
        :root {
            --banner-height-desktop: 100vh;
            --banner-height-tablet: 75vh;
            --banner-height-mobile: 60vh;
        }

        .banner-wrapper {
            position: relative;
            width: 100%;
            height: var(--banner-height-desktop);
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .banner-overlay {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(0, 0, 0, 0.25);
        }

        .overlay-box {
            background: rgba(0, 0, 0, 0.65);
            color: #fff;
            padding: 2rem 2.5rem;
            border-radius: 16px;
            max-width: 720px;
            width: 90%;
        }

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

        /* Guest-only styles */
        body.guest {
            padding-top: 0;
        }

        body.guest #app {
            min-height: 100vh;
        }
    </style>

    @stack('styles')

</head>

<body class="{{ auth()->check() ? '' : 'guest' }}">
    <div id="app">
        @auth
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container-fluid px-4">
                <button type="button" class="btn-toggle-sidebar" id="sidebarToggleBtn">
                    <i class="fas fa-bars"></i>
                </button>
                <a class="navbar-brand" href="{{ url('/') }}">
                    <i class="fas fa-hospital"></i> Healthcare EMR
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto d-lg-none">
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
                            <a class="nav-link" href="{{ route('admin.users.index') }}">
                                <i class="fas fa-users"></i> Users
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('admin.roles.index') }}">
                                <i class="fas fa-shield-alt"></i> Roles
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

        <!-- Responsive Sidebar -->
        @auth
        @include('layouts.sidebar')
        @endauth
        @endauth

        <main class="{{ auth()->check() ? 'py-0 authenticated' : 'py-0' }}">
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    @stack('scripts')
</body>

</html>