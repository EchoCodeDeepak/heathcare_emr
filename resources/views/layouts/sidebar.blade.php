<!-- Responsive Sidebar Component -->
<nav id="sidebarNav" class="sidebar">
    <!-- Sidebar Header -->
    <div class="sidebar-header">
        <div class="sidebar-brand">
            <i class="fas fa-hospital"></i>
            <span class="brand-text">Healthcare EMR</span>
        </div>
        <button type="button" class="btn-close-sidebar d-lg-none" id="closeSidebarBtn">
            <i class="fas fa-times"></i>
        </button>
    </div>

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
            <li class="nav-divider"></li>
            <li class="nav-title">Administration</li>

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

            <!-- Permissions Management -->
            <!-- <li class="nav-item">
                <a class="nav-link {{ Route::is('permissions.*') ? 'active' : '' }}" href="{{ route('permissions.index') }}">
                    <span class="nav-icon">
                        <i class="fas fa-key"></i>
                    </span>
                    <span class="nav-text">Permissions</span>
                </a>
            </li> -->
            @endif

            <!-- Divider -->
            <li class="nav-divider"></li>

            <!-- User Profile -->
            <!-- <li class="nav-item">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <span class="nav-icon">
                        <i class="fas fa-user-circle"></i>
                    </span>
                    <span class="nav-text">Profile</span>
                </a>
            </li> -->

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

    <!-- Sidebar Footer -->
    <div class="sidebar-footer" id="sidebarFooter">
        @auth
        <div class="user-info">
            <div class="user-avatar">
                <i class="fas fa-user-circle"></i>
            </div>
            <div class="user-details">
                <div class="user-name">{{ Auth::user()->name }}</div>
                <div class="user-role">{{ Auth::user()->role->name }}</div>
            </div>
        </div>
        @endauth
    </div>
</nav>

<!-- Sidebar Overlay (for mobile) -->
<div id="sidebarOverlay" class="sidebar-overlay"></div>

<style>
    /* ===================================
       RESPONSIVE SIDEBAR STYLING
       =================================== */
    :root {
        --sidebar-width: 280px;
        --sidebar-width-collapsed: 80px;
        --sidebar-bg: #f8f9fa;
        --sidebar-border: #e9ecef;
        --nav-text-color: #495057;
        --nav-active-color: #0d6efd;
        --nav-active-bg: #e7f1ff;
    }

    /* Sidebar Container */
    .sidebar {
        position: fixed;
        left: 0;
        top: 56px;
        width: var(--sidebar-width);
        height: calc(100vh - 56px);
        background-color: var(--sidebar-bg);
        border-right: 1px solid var(--sidebar-border);
        overflow-y: auto;
        overflow-x: hidden;
        z-index: 999;
        transition: transform 0.3s ease, width 0.3s ease;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    /* Sidebar Header */
    .sidebar-header {
        padding: 1.5rem 1rem;
        border-bottom: 1px solid var(--sidebar-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .sidebar-brand {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-weight: 600;
        color: var(--nav-active-color);
        font-size: 1.1rem;
    }

    .sidebar-brand i {
        font-size: 1.5rem;
    }

    .brand-text {
        white-space: nowrap;
    }

    /* Close Button */
    .btn-close-sidebar {
        background: none;
        border: none;
        color: #6c757d;
        font-size: 1.5rem;
        padding: 0;
        cursor: pointer;
        transition: color 0.2s ease;
    }

    .btn-close-sidebar:hover {
        color: var(--nav-active-color);
    }

    /* Sidebar Navigation */
    .sidebar-nav {
        list-style: none;
        padding: 1rem 0;
        margin: 0;
    }

    .sidebar-nav .nav-item {
        margin: 0;
    }

    .sidebar-nav .nav-link {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.875rem 1rem;
        color: var(--nav-text-color);
        text-decoration: none;
        transition: all 0.3s ease;
        border-left: 3px solid transparent;
        margin: 0 0.5rem;
        border-radius: 0.5rem 0 0 0.5rem;
        font-weight: 500;
    }

    .sidebar-nav .nav-link:hover {
        background-color: rgba(13, 110, 253, 0.1);
        color: var(--nav-active-color);
        transform: translateX(5px);
    }

    .sidebar-nav .nav-link.active {
        background-color: var(--nav-active-bg);
        color: var(--nav-active-color);
        border-left-color: var(--nav-active-color);
    }

    .sidebar-nav .nav-link.btn-logout {
        background: none;
        border: none;
        cursor: pointer;
        width: 100%;
        text-align: left;
    }

    /* Nav Icons */
    .nav-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 1.5rem;
        color: currentColor;
    }

    .nav-text {
        flex: 1;
        white-space: nowrap;
    }

    /* Divider & Title */
    .nav-divider {
        height: 1px;
        background-color: var(--sidebar-border);
        margin: 1rem 1rem;
        list-style: none;
    }

    .nav-title {
        padding: 0.5rem 1rem;
        font-size: 0.75rem;
        text-transform: uppercase;
        color: #6c757d;
        font-weight: 700;
        letter-spacing: 0.5px;
        list-style: none;
    }

    /* Sidebar Footer */
    .sidebar-footer {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 1rem;
        border-top: 1px solid var(--sidebar-border);
        background-color: white;
    }

    .user-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: var(--nav-active-bg);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: var(--nav-active-color);
        flex-shrink: 0;
    }

    .user-details {
        flex: 1;
        min-width: 0;
    }

    .user-name {
        font-weight: 600;
        color: #212529;
        font-size: 0.9rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .user-role {
        font-size: 0.75rem;
        color: #6c757d;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Sidebar Overlay */
    .sidebar-overlay {
        display: none;
        position: fixed;
        top: 56px;
        left: 0;
        width: 100%;
        height: calc(100vh - 56px);
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 998;
    }

    /* Scrollbar Styling */
    .sidebar::-webkit-scrollbar {
        width: 6px;
    }

    .sidebar::-webkit-scrollbar-track {
        background: transparent;
    }

    .sidebar::-webkit-scrollbar-thumb {
        background-color: #dee2e6;
        border-radius: 3px;
    }

    .sidebar::-webkit-scrollbar-thumb:hover {
        background-color: #adb5bd;
    }

    /* ===================================
       TABLET & MOBILE RESPONSIVE
       =================================== */

    /* Tablet (768px - 1199px) */
    @media (max-width: 1199px) {
        .sidebar {
            transform: translateX(-100%);
        }

        .sidebar.show {
            transform: translateX(0);
        }

        .sidebar-overlay.show {
            display: block;
        }
    }

    /* Mobile (below 768px) */
    @media (max-width: 767px) {
        :root {
            --sidebar-width: 250px;
        }

        .sidebar-header {
            padding: 1.25rem 1rem;
        }

        .brand-text {
            font-size: 0.95rem;
        }

        .sidebar-nav .nav-link {
            padding: 0.75rem 0.875rem;
            margin: 0 0.375rem;
        }

        .nav-icon {
            width: 1.25rem;
            font-size: 0.95rem;
        }

        .sidebar-footer {
            position: relative;
            margin-top: 1rem;
        }
    }

    /* Desktop (1200px and above) */
    @media (min-width: 1200px) {
        .sidebar {
            transform: translateX(0);
        }

        .btn-close-sidebar {
            display: none !important;
        }

        .sidebar-overlay {
            display: none !important;
        }
    }

    /* Extra Small (below 576px) */
    @media (max-width: 575px) {
        :root {
            --sidebar-width: 100%;
        }

        .sidebar {
            width: 100% !important;
            top: 56px;
        }

        .brand-text {
            display: none;
        }

        .nav-text {
            white-space: normal;
        }

        .sidebar-header {
            padding: 1rem;
            border-bottom: none;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebarNav');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const toggleBtn = document.getElementById('sidebarToggleBtn');
        const closeSidebarBtn = document.getElementById('closeSidebarBtn');

        // Toggle sidebar on button click
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function() {
                sidebar.classList.toggle('show');
                sidebarOverlay.classList.toggle('show');
            });
        }

        // Close sidebar on close button click
        if (closeSidebarBtn) {
            closeSidebarBtn.addEventListener('click', function() {
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
            });
        }

        // Close sidebar on overlay click
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
