<!-- Healthcare EMR Sidebar -->
<nav id="sidebarNav" class="sidebar">
    <!-- Sidebar Navigation -->
    <ul class="sidebar-nav">
        @auth
        <!-- Dashboard -->
        <li class="nav-item">
            <a class="nav-link {{ Route::is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <span class="nav-icon">
                    <i class="fas fa-tachometer-alt"></i>
                </span>
                <span class="nav-text">Dashboard</span>
            </a>
        </li>

        <!-- Medical Records -->
        @if(auth()->user()->hasPermission('view-medical-records'))
        <li class="nav-item">
            <a class="nav-link {{ Route::is('medical-records.*') ? 'active' : '' }}" href="{{ route('medical-records.index') }}">
                <span class="nav-icon">
                    <i class="fas fa-file-medical-alt"></i>
                </span>
                <span class="nav-text">Medical Records</span>
            </a>
        </li>
        @endif

        <!-- Lab Results -->
        @if(auth()->user()->hasPermission('view-lab-results'))
        <li class="nav-item">
            <a class="nav-link {{ Route::is('lab-results.*') ? 'active' : '' }}" href="{{ route('lab-results.index') }}">
                <span class="nav-icon">
                    <i class="fas fa-flask-vial"></i>
                </span>
                <span class="nav-text">Lab Results</span>
            </a>
        </li>
        @endif

        <!-- Admin Section -->
        @if(auth()->user()->isAdmin())
        <li class="nav-section">
            <div class="nav-section-title">Administration</div>
        </li>

        <!-- User Management -->
        <li class="nav-item">
            <a class="nav-link {{ Route::is('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                <span class="nav-icon">
                    <i class="fas fa-users"></i>
                </span>
                <span class="nav-text">Users</span>
            </a>
        </li>

        <!-- Role Management -->
        <li class="nav-item">
            <a class="nav-link {{ Route::is('admin.roles.*') ? 'active' : '' }}" href="{{ route('admin.roles.index') }}">
                <span class="nav-icon">
                    <i class="fas fa-shield-alt"></i>
                </span>
                <span class="nav-text">Roles</span>
            </a>
        </li>

        <!-- Permissions -->
        <li class="nav-item">
            <a class="nav-link {{ Route::is('permissions.*') ? 'active' : '' }}" href="{{ route('permissions.index') }}">
                <span class="nav-icon">
                    <i class="fas fa-key"></i>
                </span>
                <span class="nav-text">Permissions</span>
            </a>
        </li>
        @endif

        <!-- Logout -->
        <li class="nav-item">
            <form method="POST" action="{{ route('logout') }}" id="logout-form-sidebar">
                @csrf
                <button type="submit" class="nav-link btn-logout">
                    <span class="nav-icon">
                        <i class="fas fa-sign-out-alt"></i>
                    </span>
                    <span class="nav-text">Logout</span>
                </button>
            </form>
        </li>
        @endauth
    </ul>
    <!-- Sidebar Footer (User Info) - Mobile Only -->
    @auth
    <li class="nav-item mobile-profile-section d-block d-lg-none">

        <!-- Profile Toggle -->
        <a class="nav-link sidebar-profile-toggle d-flex align-items-center justify-content-between" href="#" data-bs-toggle="collapse" data-bs-target="#mobileProfileMenu">
            <span class="d-flex align-items-center gap-3">
                <div class="sidebar-profile-avatar">
                    @if(auth()->user()->profile_image)
                    <img src="{{ asset('storage/' . auth()->user()->profile_image) }}" alt="Profile" class="rounded-circle" width="36" height="36">
                    @else
                    <div class="avatar-placeholder rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 36px; height: 36px;">
                        <i class="fas fa-user"></i>
                    </div>
                    @endif
                </div>
                <div class="sidebar-profile-info">
                    <span class="sidebar-profile-name">{{ Auth::user()->name }}</span>
                    <span class="sidebar-profile-role">{{ Auth::user()->role->name }}</span>
                </div>
            </span>
            <i class="fas fa-chevron-down chevron-icon"></i>
        </a>

        <!-- Profile Dropdown -->
        <div class="collapse" id="mobileProfileMenu">
            <ul class="nav flex-column mobile-profile-menu">
                <li class="nav-item">
                    <a class="nav-link {{ Route::is('profile.*') ? 'active' : '' }}" href="{{ route('profile.index') }}">
                        <i class="fas fa-user-cog"></i>
                        <span>My Profile</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ Route::is('password.*') ? 'active' : '' }}" href="{{ route('password.change') }}">
                        <i class="fas fa-shield-alt"></i>
                        <span>Change Password</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ Route::is('medical-records.*') ? 'active' : '' }}" href="{{ route('medical-records.index') }}">
                        <i class="fas fa-file-medical-alt"></i>
                        <span>Medical Records</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#helpSupportModal">
                        <i class="fas fa-question-circle"></i>
                        <span>Help & Support</span>
                    </a>
                </li>

                <!-- <li class="nav-item mobile-logout">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="nav-link btn-logout-mobile">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </li> -->
            </ul>
        </div>

    </li>
    @endauth




</nav>

<!-- Sidebar Overlay (for mobile) -->
<div id="sidebarOverlay" class="sidebar-overlay"></div>

<!-- Sidebar Toggle Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebarNav');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const toggleBtn = document.getElementById('sidebarToggleBtn');

        if (toggleBtn && sidebar) {
            toggleBtn.addEventListener('click', function() {
                sidebar.classList.toggle('show');
                sidebarOverlay.classList.toggle('show');
            });
        }

        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', function() {
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
            });
        }

        // Close sidebar when clicking on a link (for mobile)
        const navLinks = sidebar.querySelectorAll('.nav-link');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth < 1200) {
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                }
            });
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1200) {
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
            }
        });
    });
</script>