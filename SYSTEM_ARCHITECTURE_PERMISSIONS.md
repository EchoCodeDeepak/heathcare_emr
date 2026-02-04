# System Architecture - Permissions & Role Management

## System Overview Diagram

```
┌─────────────────────────────────────────────────────────┐
│         Healthcare EMR System Dashboard                 │
│  (Admin Dashboard - /dashboard)                        │
├─────────────────────────────────────────────────────────┤
│                                                         │
│  Quick Actions:                                        │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐  │
│  │ Create New   │  │ Manage       │  │ Manage       │  │
│  │ Role         │  │ Roles        │  │ Users        │  │
│  └──────┬───────┘  └──────┬───────┘  └──────┬───────┘  │
│         │                 │                 │         │
│         ▼                 ▼                 ▼         │
│    /roles/create    /roles             /admin/users  │
└─────────────────────────────────────────────────────────┘
          │                 │
          │                 │
    ┌─────▼─────┐      ┌────▼─────┐
    │   CREATE  │      │   VIEW    │
    │ ROLE PAGE │      │ROLES PAGE │
    └─────┬─────┘      └────┬──────┘
          │                 │
          ▼                 ▼
    ┌──────────────────────────────┐
    │ Permission Checkboxes        │
    │ Grouped by 7 Categories:     │
    │                              │
    │ ☑ Medical Records            │
    │   ☐ View                     │
    │   ☐ Create                   │
    │   ☐ Edit                     │
    │   ☐ Delete                   │
    │                              │
    │ ☑ Lab Results                │
    │   ☐ View                     │
    │   ☐ Add                      │
    │   ☐ Edit                     │
    │                              │
    │ ... (5 more groups)          │
    └──────────────────────────────┘
          │
          ▼
    ┌──────────────────────────────┐
    │ RoleController::store()      │
    │ or update()                  │
    └──────────────────────────────┘
          │
          ▼
    ┌──────────────────────────────┐
    │ Role Model::permissions()    │
    │ sync() method                │
    └──────────────────────────────┘
          │
          ▼
    ┌──────────────────────────────┐
    │ role_permissions pivot table │
    │ (role_id, permission_id)     │
    └──────────────────────────────┘
          │
          ▼
    ┌──────────────────────────────┐
    │ User Model::role->          │
    │ permissions()               │
    └──────────────────────────────┘
          │
          ▼
    All Users with Role Get
    Updated Permissions
```

---

## Request/Response Flow

### Creating a Role with Permissions

```
┌─────────────────────────────────────────────────────────────────┐
│ Admin clicks "Create New Role"                                  │
│ Browser: GET /roles/create                                      │
└───────────────────────────┬─────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────────┐
│ Server: RoleController::create()                                │
│ - Fetch all permissions                                         │
│ - Get permission groups from PermissionService                  │
│ - Render roles/create.blade.php                                 │
└───────────────────────────┬─────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────────┐
│ Browser: Renders form with permission checkboxes                │
│ JavaScript: Auto-generates slug as user types name              │
│ Admin selects permissions and submits                           │
│ Form POST to /roles                                             │
│ {                                                               │
│   "name": "Lab Technician",                                     │
│   "slug": "lab-technician",                                     │
│   "permissions": [1, 3, 5, 7, 9]                               │
│ }                                                               │
└───────────────────────────┬─────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────────┐
│ Server: RoleController::store()                                 │
│ - Validate inputs                                               │
│ - Create Role record                                            │
│ - $role->permissions()->sync($request->permissions)             │
│ - Redirect to /roles with success message                       │
└───────────────────────────┬─────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────────┐
│ Database Changes:                                               │
│ INSERT roles: (id=7, name='Lab Technician', slug='lab-tech')   │
│ INSERT role_permissions: (role_id=7, permission_id=1)          │
│ INSERT role_permissions: (role_id=7, permission_id=3)          │
│ INSERT role_permissions: (role_id=7, permission_id=5)          │
│ INSERT role_permissions: (role_id=7, permission_id=7)          │
│ INSERT role_permissions: (role_id=7, permission_id=9)          │
└───────────────────────────┬─────────────────────────────────────┘
                            │
                            ▼
┌─────────────────────────────────────────────────────────────────┐
│ Browser: Redirected to /roles                                   │
│ Success message: "Role created successfully with permissions!"  │
│ Table updated to show new role with 5 permissions               │
└─────────────────────────────────────────────────────────────────┘
```

---

## Database Schema

### Tables

```sql
── roles
   ├── id (Primary Key)
   ├── name (varchar, unique)
   ├── slug (varchar, unique)
   ├── created_at
   └── updated_at

── permissions
   ├── id (Primary Key)
   ├── name (varchar)
   ├── slug (varchar, unique)
   ├── created_at
   └── updated_at

── role_permissions (Pivot Table)
   ├── id (Primary Key)
   ├── role_id (Foreign Key → roles.id)
   ├── permission_id (Foreign Key → permissions.id)
   └── [unique: (role_id, permission_id)]

── users
   ├── id (Primary Key)
   ├── name
   ├── email
   ├── password
   ├── role_id (Foreign Key → roles.id)
   ├── created_at
   └── updated_at
```

### Relationships

```
User 1 ──► 0..* role_permissions ◄── 1 Permission
            role_permissions ◄── 1 Role
Role 1 ──► 0..* Users
```

---

## Component Architecture

### MVC Pattern

```
Models/
├── Role.php
│   ├── belongsToMany(Permission, 'role_permissions')
│   ├── hasMany(User)
│   └── hasPermission(slug)
├── Permission.php
│   ├── belongsToMany(Role, 'role_permissions')
│   └── [relationships]
└── User.php
    ├── belongsTo(Role)
    └── role->permissions

Controllers/
├── RoleController.php
│   ├── index() → lists roles
│   ├── create() → show form
│   ├── store() → save role + permissions
│   ├── edit() → show edit form
│   ├── update() → update role + permissions
│   └── destroy() → delete role
├── PermissionController.php
│   └── [manages role permissions via matrix]
└── Admin/UserController.php
    └── [manages users and assigns roles]

Views/
├── roles/index.blade.php (list all roles)
├── roles/create.blade.php (create form)
├── roles/edit.blade.php (edit form)
└── permissions/index.blade.php (matrix interface)
```

---

## Permission Management Workflow

### Workflow 1: Direct Role Creation/Editing

```
User Selection
      │
      ▼
Form Input
├─ Role Name
├─ Slug (auto-generated)
└─ Permission Checkboxes (7 groups)
      │
      ▼
Form Submission
├─ Validation
├─ Role Create/Update
└─ Permission Sync
      │
      ▼
Database Update
├─ roles table
└─ role_permissions pivot
      │
      ▼
User Impact
└─ All users with this role get updated permissions
```

### Workflow 2: Permission Matrix (Alternative)

```
Admin views /permissions
      │
      ▼
Role × Permission Matrix
├─ Rows: Roles
├─ Columns: Permission Groups
└─ Cells: Checkboxes
      │
      ▼
Admin toggles checkbox
      │
      ▼
AJAX POST: permissions/role
├─ role_id
└─ permissions: [array]
      │
      ▼
Server: sync() operation
      │
      ▼
Database: role_permissions updated
      │
      ▼
Toast: Success notification
```

---

## Security Model

### Authentication & Authorization

```
Request
   │
   ▼
Middleware: auth
├─ Check session
├─ Verify user logged in
└─ Fail → Redirect to login
   │
   ▼
Middleware: role:system-admin
├─ Check user->role_id == 1
├─ Verify system-admin role
└─ Fail → 403 Unauthorized
   │
   ▼
Middleware: permission:manage-permissions
├─ Check user->permissions->contains(slug)
├─ Query role_permissions
└─ Fail → 403 Forbidden
   │
   ▼
Controller Action
└─ Process request
```

### Data Validation

```
User Input
   │
   ├─ name: required|unique:roles|string|max:255
   ├─ slug: required|unique:roles|string|max:255
   └─ permissions: array|exists:permissions,id
   │
   ▼
Eloquent Model
├─ Automatic SQL injection prevention
├─ Type casting
└─ Mass assignment protection
   │
   ▼
Database Constraints
├─ Unique indexes on name, slug
├─ Foreign key constraints
└─ Not null constraints
```

---

## Data Flow Diagram

### Permission Inheritance

```
┌──────────────────┐
│  Permission DB   │
│ (15 permissions) │
└────────┬─────────┘
         │
         ▼
┌──────────────────────────┐
│  role_permissions        │
│ (Pivot Table)            │
│                          │
│ role_id │ permission_id  │
│ ───────────────────────  │
│    1    │      1         │
│    1    │      2         │
│    2    │      3         │
│    2    │      4         │
│    3    │      1         │
│    ...  │      ...       │
└────┬─────────────────────┘
     │
     ▼
┌──────────────────────┐
│  Roles DB            │
│  (7+ roles)          │
│                      │
│ id │ name  │ slug    │
│────┼───────┼────────│
│ 1  │ Admin │ admin  │
│ 2  │ Doctor│ doctor │
│ 3  │ Nurse │ nurse  │
│ ...│ ...   │ ...    │
└────┬──────────────────┘
     │
     ▼
┌──────────────────────┐
│  Users DB            │
│  (50+ users)         │
│                      │
│ id │ name  │ role_id │
│────┼───────┼────────│
│ 1  │ John  │   2    │
│ 2  │ Sarah │   2    │
│ 3  │ Mike  │   3    │
│ ...│ ...   │ ...    │
└──────────────────────┘
```

### Runtime Permission Check

```
Request to access resource
   │
   ├─ Get authenticated user
   │     │
   │     ▼
   │  User→role→permissions (Query with eager loading)
   │     │
   │     ▼
   │  Array of permission slugs
   │     │
   └─ Check if required permission in array
         │
         ├─ YES → Allow request
         └─ NO → Deny (403 Forbidden)
```

---

## Error Handling

### Exception Flow

```
Try Action
   │
   ├─ Validation Exception
   │     │
   │     ▼
   │  Display error messages on form
   │
   ├─ Model Not Found
   │     │
   │     ▼
   │  404 Page Not Found
   │
   ├─ Authorization Exception
   │     │
   │     ▼
   │  403 Forbidden (403.blade.php)
   │
   ├─ Database Exception
   │     │
   │     ▼
   │  500 Server Error + Log error
   │
   └─ Success
         │
         ▼
      Redirect with success message
```

---

## Performance Optimization

### Database Query Optimization

```
Without Optimization:
  SELECT * FROM users WHERE role_id = 1
  FOREACH user:
    SELECT * FROM roles WHERE id = user.role_id
    FOREACH role:
      SELECT * FROM permissions WHERE...
  Result: N+1 query problem (51 queries for 50 users)

With Eager Loading:
  $users = User::with('role.permissions')
           ->where('role_id', 1)
           ->get();
  Result: 3 queries (users + roles + permissions)
```

### AJAX Debouncing

```
User toggles 10 checkboxes
   │
   ├─ Click 1: debounce() waits 500ms
   ├─ Click 2-9: Reset 500ms timer
   ├─ Click 10: Reset 500ms timer
   │
   After 500ms no clicks:
   │
   └─ Single AJAX request with all changes
      (Not 10 individual requests)
```

---

## Summary

The permission system architecture follows Laravel best practices:

✅ **Clean separation of concerns** - Models, Controllers, Views  
✅ **Efficient database queries** - Eager loading, optimized joins  
✅ **Security first** - Multiple layers of validation and authorization  
✅ **User-friendly** - Intuitive UI with real-time feedback  
✅ **Scalable** - Can handle thousands of roles and permissions  
✅ **Maintainable** - Well-documented and organized code  

This architecture ensures a reliable, secure, and performant permissions management system for the Healthcare EMR system.
