# Healthcare EMR System - Complete Documentation

## Table of Contents
1. [Project Overview](#project-overview)
2. [User Roles](#user-roles)
3. [Authentication & Sessions](#authentication--sessions)
4. [Permission System](#permission-system)
5. [Project Flow](#project-flow)
6. [Essential Files & Their Functions](#essential-files--their-functions)
7. [Database Structure](#database-structure)
8. [Route Protection](#route-protection)
9. [View Layer](#view-layer)

---

## Project Overview

This is a **Healthcare Electronic Medical Records (EMR) System** built with Laravel 12.x and PHP 8.2.x. It manages:
- Patient Medical Records
- Lab Results
- User Management
- Role-Based Access Control
- Permission-Based Data Access

---

## User Roles

| Role | ID | Description |
|------|-----|-------------|
| **System Admin** | 1 | Full access to all features, user/role management |
| **Doctor** | 2 | Create/edit medical records, view lab results |
| **Nurse** | 3 | View/edit medical records, view lab results |
| **Lab Technician** | 4 | Add/edit lab results, view medical records |
| **Patient** | 5 | View own medical records and lab results |

---

## Authentication & Sessions

### How Authentication Works

1. **Login Process** (`app/Http/Controllers/Auth/LoginController.php`)
   - User submits email/password
   - Laravel authenticates using `config/auth.php`
   - On success, session is created
   - User is redirected to dashboard

2. **Session Management**
   - Sessions stored in database (Laravel default)
   - Session lifetime: configured in `config/session.php`
   - User data accessible via `Auth::user()` or `auth()->user()`

3. **Auth Middleware** (`app/Http/Kernel.php`)
   ```php
   'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
   ```
   - Protects routes from unauthenticated users
   - Redirects to `/login` if not authenticated

4. **Auth Helper Functions**
   ```php
   auth()->user()        // Get current logged-in user
   auth()->check()      // Check if user is logged in
   auth()->id()         // Get current user ID
   Auth::logout()       // Logout user
   ```

### Session Configuration (`config/session.php`)
- **Driver**: database
- **Lifetime**: 120 minutes (configurable)
- **Secure**: false (set true for HTTPS)
- **SameSite**: lax

---

## Permission System

### Permission Types

| Permission | Description |
|------------|-------------|
| `view-medical-records` | View patient medical records |
| `create-medical-records` | Create new medical records |
| `edit-medical-records` | Edit existing medical records |
| `delete-medical-records` | Delete medical records |
| `view-all-medical-records` | View all records (not just own) |
| `view-lab-results` | View lab results |
| `add-lab-results` | Add new lab results |
| `edit-lab-results` | Edit lab results |
| `delete-lab-results` | Delete lab results |
| `manage-users` | Create/edit/delete users |
| `manage-permissions` | Manage user permissions |
| `export-data` | Export data to PDF/Excel/CSV |
| `view-dashboard` | Access dashboard |
| `view-patient-history` | View patient history |

### How Permission Checking Works

1. **Model Level** (`app/Models/User.php`)
   ```php
   public function hasPermission(string $permissionSlug): bool
   {
       if ($this->isAdmin()) return true;
       return $this->role->permissions()->where('slug', $permissionSlug)->exists();
   }
   ```

2. **Middleware Level** (`app/Http/Middleware/CheckPermission.php`)
   - Applied to routes via `->middleware('permission:view-medical-records')`
   - Checks if user has required permission
   - Redirects to dashboard with error if denied

3. **View Level**
   ```php
   @if(auth()->user()->hasPermission('create-medical-records'))
       <a href="...">Create Record</a>
   @endif
   ```

### Role-Permission Relationship
- **Many-to-Many**: A role can have many permissions
- A user gets permissions through their assigned role
- System admin bypasses all permission checks

---

## Project Flow

### 1. User Authentication Flow
```
User Login Form (/login)
    ↓
LoginController@login
    ↓
Validate credentials
    ↓
Auth::attempt()
    ↓
Create Session
    ↓
Redirect to Dashboard (/dashboard)
    ↓
DashboardController@index
    ↓
Check user role & redirect to role-specific dashboard
```

### 2. Request Lifecycle
```
HTTP Request
    ↓
web.php (Routes)
    ↓
Middleware (auth, role, permission)
    ↓
Controller
    ↓
Model/Database
    ↓
View (Blade Template)
    ↓
HTTP Response
```

### 3. Medical Record Creation Flow
```
MedicalRecordsController@create (GET)
    ↓
Show create form (if has 'create-medical-records' permission)
    ↓
User submits form
    ↓
MedicalRecordsController@store (POST)
    ↓
Validate input
    ↓
Create record in database
    ↓
Attach doctor/patient relationships
    ↓
Redirect with success message
```

### 4. Role-Permission Assignment Flow
```
Admin goes to /admin/roles/create
    ↓
Select permissions for role
    ↓
RoleController@store
    ↓
Create role in roles table
    ↓
Sync permissions to role_permissions pivot table
    ↓
Redirect to roles index
```

### 5. User Creation with Role Flow
```
Admin goes to /admin/users/create
    ↓
Select role for new user
    ↓
UserController@store
    ↓
Create user with role_id
    ↓
User gets all permissions of assigned role
```

---

## Essential Files & Their Functions

### Authentication Files
| File | Function |
|------|----------|
| `app/Http/Controllers/Auth/LoginController.php` | Handles login/logout |
| `app/Http/Controllers/Auth/RegisterController.php` | Handles user registration |
| `config/auth.php` | Authentication configuration |

### Controller Files
| File | Function |
|------|----------|
| `app/Http/Controllers/DashboardController.php` | Role-based dashboard routing |
| `app/Http/Controllers/MedicalRecordController.php` | CRUD for medical records |
| `app/Http/Controllers/LabResultController.php` | CRUD for lab results |
| `app/Http/Controllers/RoleController.php` | CRUD for roles |
| `app/Http/Controllers/UserController.php` | CRUD for users |
| `app/Http/Controllers/Admin/UserController.php` | Admin user management |

### Middleware Files
| File | Function |
|------|----------|
| `app/Http/Middleware/Authenticate.php` | Checks if user is logged in |
| `app/Http/Middleware/CheckRole.php` | Checks if user has specific role |
| `app/Http/Middleware/CheckPermission.php` | Checks if user has permission |

### Model Files
| File | Function |
|------|----------|
| `app/Models/User.php` | User model with auth/permission methods |
| `app/Models/Role.php` | Role model with permissions relationship |
| `app/Models/Permission.php` | Permission model |
| `app/Models/PatientMedicalRecord.php` | Medical records data |

### Service Files
| File | Function |
|------|----------|
| `app/Services/PermissionService.php` | Defines all permissions & groups |

### View Files
| File | Function |
|------|----------|
| `resources/views/layouts/app.blade.php` | Main layout template |
| `resources/views/layouts/sidebar.blade.php` | Sidebar navigation with permission checks |
| `resources/views/dashboard/index.blade.php` | Dashboard (role-based) |
| `resources/views/medical-records/index.blade.php` | Medical records list |
| `resources/views/lab-results/index.blade.php` | Lab results list |
| `resources/views/roles/index.blade.php` | Role management |
| `resources/views/users/index.blade.php` | User management |

### Route Files
| File | Function |
|------|----------|
| `routes/web.php` | All web routes with middleware |

### Database Files
| File | Function |
|------|----------|
| `database/migrations/` | Database table structures |
| `database/seeders/` | Seed data (roles, permissions, users) |
| `database/heathcare_emr.sql` | Full database dump |

### Configuration Files
| File | Function |
|------|----------|
| `config/app.php` | Application configuration |
| `config/auth.php` | Auth drivers & settings |
| `config/database.php` | Database connection |
| `config/session.php` | Session settings |

---

## Database Structure

### Users Table
```sql
id, name, email, password, phone, date_of_birth, gender,
blood_group, address, role_id, remember_token, created_at, updated_at
```

### Roles Table
```sql
id, name, slug, created_at, updated_at
```

### Permissions Table
```sql
id, name, slug, created_at, updated_at
```

### Role_Permissions Table (Pivot)
```sql
id, role_id, permission_id, created_at, updated_at
```

### Patient_Medical_Records Table
```sql
id, patient_id, doctor_id, diagnosis, treatment_plan,
prescription, notes, visibility_level, created_at, updated_at
```

### Data_Access_Permissions Table
```sql
id, user_id, medical_record_id, can_view, can_edit, created_at
```

---

## Route Protection

### Middleware Stack (web.php)
```php
Route::middleware(['auth', 'role:system-admin'])->prefix('admin')
```

### Permission Middleware Examples
```php
// Single permission
Route::get('/medical-records', [...])
    ->middleware('permission:view-medical-records');

// Multiple permissions (any)
Route::get('/something', [...])
    ->middleware('permission:view-medical-records|create-medical-records');

// Role middleware
Route::get('/admin', [...])
    ->middleware('role:system-admin');
```

---

## View Layer

### Sidebar Navigation (`resources/views/layouts/sidebar.blade.php`)
- Shows/hides menu items based on permissions
- Uses `@if(auth()->user()->hasPermission(...))`

### Action Buttons
- Edit/Delete buttons hidden if no permission
- Uses `@can` or `@if` directives

### Flash Messages
```php
// Controller
return redirect()->route('...')->with('success', 'Message');

// View
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
```

---

## Quick Reference

### Check User Role
```php
auth()->user()->isAdmin()      // System Admin
auth()->user()->isDoctor()     // Doctor
auth()->user()->isNurse()      // Nurse
auth()->user()->isLabTechnician() // Lab Technician
auth()->user()->isPatient()    // Patient
```

### Check Permissions
```php
auth()->user()->hasPermission('view-medical-records')
auth()->user()->hasAnyPermission(['view', 'edit'])
auth()->user()->hasAllPermissions(['view', 'edit'])
```

### Get User's Role
```php
auth()->user()->role           // Role object
auth()->user()->role->name     // Role name
auth()->user()->role->slug     // Role slug
```

### Get User's Permissions
```php
auth()->user()->permissions    // Collection of permissions
```

---

## Important URLs

| URL | Purpose |
|-----|---------|
| `/login` | Login page |
| `/dashboard` | Main dashboard |
| `/admin/users` | User management (admin) |
| `/admin/roles` | Role management (admin) |
| `/medical-records` | Medical records |
| `/lab-results` | Lab results |
| `/logout` | Logout |

---

## Testing the System

1. **Login as System Admin**
   - Email: admin@healthcare.com
   - Password: password123
   - Has all permissions

2. **Create New Role**
   - Go to `/admin/roles/create`
   - Add specific permissions
   - Save

3. **Create New User**
   - Go to `/admin/users/create`
   - Assign role
   - Login as new user
   - Test permissions

---

## Support & Troubleshooting

### Common Issues
1. **"You do not have permission"** - User lacks required permission
2. **Login not working** - Check session configuration
3. **Sidebar not updating** - Clear cache: `php artisan cache:clear`

### Clear Cache Commands
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

---

*Document generated for Healthcare EMR System*
*For questions, refer to the code comments or Laravel documentation*
