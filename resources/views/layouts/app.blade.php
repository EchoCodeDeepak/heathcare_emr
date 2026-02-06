<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Healthcare EMR') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <!-- Theme CSS -->
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">



    @auth
    <style>
        :root {
            --navbar-height: 64px;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
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
            margin-left: var(--sidebar-width);
            transition: margin-left 0.3s ease;
            padding: 1.5rem;
            background-color: var(--bg-body);
        }

        @media (max-width: 1199px) {
            main.authenticated {
                margin-left: 0;
            }
        }

        @media (max-width: 767px) {
            :root {
                --navbar-height: 56px;
            }
        }
    </style>
    @endauth

    @stack('styles')
</head>

<body class="{{ auth()->check() ? '' : 'guest' }}">
    <div id="app">
        @auth
        <!-- Navbar -->
        <nav class="navbar navbar-expand-md navbar-dark">
            <div class="container-fluid px-3">
                <button type="button" class="btn-toggle-sidebar" id="sidebarToggleBtn">
                    <i class="fas fa-bars"></i>
                </button>
                <a class="navbar-brand" href="{{ url('/') }}">
                    <i class="fas fa-hospital"></i>
                    <span>Healthcare EMR</span>
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
                        <li class="nav-item dropdown profile-dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <div class="profile-avatar">
                                    @if(auth()->user()->profile_image)
                                    <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" alt="Profile" class="rounded-circle" width="32" height="32">
                                    @else
                                    <div class="avatar-placeholder rounded-circle d-flex align-items-center justify-content-center text-white">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    @endif
                                </div>
                                <!-- <span class="d-none d-md-inline text-white">{{ Auth::user()->name }}</span> -->
                                <span class="user-badge">{{ Auth::user()->role->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end profile-dropdown-menu">
                                <!-- User Info Header -->
                                <li class="dropdown-header">
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="profile-avatar-lg">
                                            @if(auth()->user()->profile_image)
                                            <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" alt="Profile" class="rounded-circle" width="48" height="48">
                                            @else
                                            <div class="avatar-placeholder-lg rounded-circle d-flex align-items-center justify-content-center text-white">
                                                <i class="fas fa-user fa-lg"></i>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="user-info-header">
                                            <h6 class="mb-0 text-dark">{{ Auth::user()->name }}</h6>
                                            <small class="text-muted">{{ Auth::user()->email }}</small>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                                <!-- My Profile -->
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.index') }}">
                                        <i class="fas fa-user-cog text-primary"></i>
                                        <div class="dropdown-item-content">
                                            <span class="dropdown-item-title">My Profile</span>
                                            <span class="dropdown-item-desc">View & edit personal information</span>
                                        </div>
                                    </a>
                                </li>

                                <!-- Change Password / Security -->
                                <li>
                                    <a class="dropdown-item" href="{{ route('password.change') }}">
                                        <i class="fas fa-shield-alt text-success"></i>
                                        <div class="dropdown-item-content">
                                            <span class="dropdown-item-title">Change Password</span>
                                            <span class="dropdown-item-desc">Secure your account</span>
                                        </div>
                                    </a>
                                </li>

                                <!-- Medical Records / Lab Reports -->
                                <li>
                                    <a class="dropdown-item" href="{{ route('medical-records.index') }}">
                                        <i class="fas fa-file-medical-alt text-info"></i>
                                        <div class="dropdown-item-content">
                                            <span class="dropdown-item-title">Medical Records</span>
                                            <span class="dropdown-item-desc">Lab reports & prescriptions</span>
                                        </div>
                                    </a>
                                </li>

                                <!-- Help / Support -->
                                <li>
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#helpSupportModal">
                                        <i class="fas fa-question-circle text-warning"></i>
                                        <div class="dropdown-item-content">
                                            <span class="dropdown-item-title">Help & Support</span>
                                            <span class="dropdown-item-desc">FAQs & contact options</span>
                                        </div>
                                    </a>
                                </li>

                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                                <!-- Logout -->
                                <li>
                                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                        @csrf
                                        <button type="submit" class="dropdown-item dropdown-item-logout">
                                            <i class="fas fa-sign-out-alt"></i>
                                            <span>Logout</span>
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

        <main class="{{ auth()->check() ? 'authenticated' : '' }}">
            <!-- Session Messages -->
            @if(session('success'))
            <div class="container-fluid">
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            </div>
            @endif

            @if(session('error'))
            <div class="container-fluid">
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <div>{{ session('error') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            </div>
            @endif

            @if(session('warning'))
            <div class="container-fluid">
                <div class="alert alert-warning alert-dismissible fade show d-flex align-items-center" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <div>{{ session('warning') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            </div>
            @endif

            @if(session('info'))
            <div class="container-fluid">
                <div class="alert alert-info alert-dismissible fade show d-flex align-items-center" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    <div>{{ session('info') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                </div>
            </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <!-- Toastr Configuration -->
    <script>
        toastr.options = {
            closeButton: true,
            progressBar: true,
            positionClass: 'toast-top-right',
            timeOut: 5000,
            extendedTimeOut: 1000,
            showEasing: 'swing',
            hideEasing: 'linear',
            showMethod: 'fadeIn',
            hideMethod: 'fadeOut'
        };
    </script>

    <!-- Initialize tooltips globally -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize all tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl, {
                    trigger: 'hover focus',
                    placement: 'top',
                    container: 'body'
                });
            });
        });
    </script>

    @stack('scripts')

    <!-- Help & Support Modal -->
    <div class="modal fade help-support-modal" id="helpSupportModal" tabindex="-1" aria-labelledby="helpSupportModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="helpSupportModalLabel">
                        <i class="fas fa-headset me-2"></i>
                        Help & Support
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Contact Options -->
                    <div class="support-section">
                        <h6 class="support-section-title">
                            <i class="fas fa-comment-dots"></i>
                            Contact Us Directly
                        </h6>

                        <!-- WhatsApp Support -->
                        <a href="https://wa.me/917558278392?text=Hello,%20I%20need%20help%20regarding%20my%20healthcare%20dashboard%20account." target="_blank" class="support-option whatsapp">
                            <i class="fab fa-whatsapp"></i>
                            <div class="support-text">
                                <span class="support-label">Chat with us on</span>
                                <span class="support-value">WhatsApp</span>
                            </div>
                        </a>

                        <!-- Phone Support -->
                        <a href="tel:7558278392" class="support-option phone">
                            <i class="fas fa-phone"></i>
                            <div class="support-text">
                                <span class="support-label">Call us at</span>
                                <span class="support-value">+91 7558278392</span>
                            </div>
                        </a>
                    </div>

                    <!-- FAQs -->
                    <div class="support-section">
                        <h6 class="support-section-title">
                            <i class="fas fa-question-circle"></i>
                            Frequently Asked Questions
                        </h6>

                        <div class="faq-item">
                            <div class="faq-question" data-bs-toggle="collapse" data-bs-target="#faq1">
                                <span>How do I update my profile information?</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="collapse" id="faq1">
                                <div class="faq-answer">
                                    <p>Click on "My Profile" in the dropdown menu to view and edit your personal information including name, contact details, and address.</p>
                                </div>
                            </div>
                        </div>

                        <div class="faq-item">
                            <div class="faq-question" data-bs-toggle="collapse" data-bs-target="#faq2">
                                <span>How can I change my password?</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="collapse" id="faq2">
                                <div class="faq-answer">
                                    <p>Select "Change Password" from the profile dropdown. You'll need to enter your current password and then create a new one following our security guidelines.</p>
                                </div>
                            </div>
                        </div>

                        <div class="faq-item">
                            <div class="faq-question" data-bs-toggle="collapse" data-bs-target="#faq3">
                                <span>Where can I view my medical records?</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="collapse" id="faq3">
                                <div class="faq-answer">
                                    <p>Access "Medical Records" from the profile dropdown to view your lab reports, prescriptions, and complete visit history.</p>
                                </div>
                            </div>
                        </div>

                        <div class="faq-item">
                            <div class="faq-question" data-bs-toggle="collapse" data-bs-target="#faq4">
                                <span>Is my data secure?</span>
                                <i class="fas fa-chevron-down"></i>
                            </div>
                            <div class="collapse" id="faq4">
                                <div class="faq-answer">
                                    <p>Yes, we use industry-standard encryption and security protocols to protect your personal health information. Your data is never shared without consent.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Email Support -->
                    <div class="support-section">
                        <h6 class="support-section-title">
                            <i class="fas fa-envelope"></i>
                            Email Support
                        </h6>
                        <a href="mailto:support@healthcareemr.com" class="support-option">
                            <i class="fas fa-envelope"></i>
                            <div class="support-text">
                                <span class="support-label">Email us at</span>
                                <span class="support-value">support@healthcareemr.com</span>
                            </div>
                        </a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const faqItems = document.querySelectorAll('.faq-question');
            faqItems.forEach(item => {
                item.addEventListener('click', function() {
                    const parent = this.parentElement;
                    parent.classList.toggle('open');
                });
            });
        });
    </script>
</body>

</html>