# Quick Reference - Unified User & Role Form

## 🚀 Quick Start

### Access the Form
```
URL: http://127.0.0.1:8000/admin/users/create
```

### Create a User
1. Click **"Create User Account"** button
2. Enter: Name, Email, Password (2x)
3. Select Role
4. Permissions auto-load
5. Click **Create**

### Create a Role
1. Click **"Create User Role"** button
2. Enter: Role Name, Role Slug
3. Select Permissions
4. Click **Create**

---

## 📋 Form Fields

### User Mode
| Field | Required | Type | Notes |
|-------|----------|------|-------|
| Full Name | ✓ | Text | Max 255 chars |
| Email | ✓ | Email | Must be unique |
| Password | ✓ | Password | Min 8 chars |
| Confirm Password | ✓ | Password | Must match password |
| Select Role | ✓ | Dropdown | Loads permissions |
| Permissions | ✗ | Checkboxes | Multiple select |

### Role Mode
| Field | Required | Type | Notes |
|-------|----------|------|-------|
| Role Name | ✓ | Text | Max 255 chars, unique |
| Role Slug | ✓ | Text | Lowercase, hyphens only, unique |
| Permissions | ✗ | Checkboxes | Multiple select |

---

## 🔄 Form Behavior

### User Mode
```
User fills form
    ↓
Selects role
    ↓
API loads permissions for role
    ↓
User checks/unchecks permissions
    ↓
Submits form
    ↓
User created with role + permissions
```

### Role Mode
```
User fills form
    ↓
Sees all available permissions
    ↓
User checks/unchecks permissions
    ↓
Submits form
    ↓
Role created with permissions
```

---

## 🎯 Permission Groups

Permissions are organized by feature:

```
📁 Dashboard Permissions
   • View Dashboard
   • View Statistics

📁 Medical Record Management
   • View Medical Records
   • Create Medical Records
   • Edit Medical Records
   • Delete Medical Records
   • Export Data

📁 Lab Result Management
   • View Lab Results
   • Add Lab Results

📁 User Management
   • Manage Users
   • View User Permissions

📁 Permission Management
   • Manage Permissions
```

---

## ✅ Validation Rules

### Errors You Might See

| Error | Solution |
|-------|----------|
| "Email has already been taken" | Use a different email |
| "The role name has already been taken" | Use a different role name |
| "Password must be at least 8 characters" | Make password longer |
| "The password confirmation does not match" | Passwords must be identical |
| "The selected role is invalid" | Choose a role from the dropdown |
| "Required field" | Fill in all fields marked with * |

---

## 🔐 Access Control

| User Role | Access |
|-----------|--------|
| System Admin | ✅ Full access |
| Doctor | ❌ No access |
| Receptionist | ❌ No access |
| Lab Technician | ❌ No access |
| Other Roles | ❌ No access |

---

## 🔗 Related URLs

| Action | URL | Route |
|--------|-----|-------|
| Create User/Role | `/admin/users/create` | `admin.users.create` |
| List Users | `/admin/users` | `admin.users.index` |
| Edit User | `/admin/users/{id}/edit` | `admin.users.edit` |
| List Roles | `/admin/roles` | `admin.roles.index` |
| Edit Role | `/admin/roles/{id}/edit` | `admin.roles.edit` |
| API: Get Permissions | `/api/permissions?role_id=X` | — |

---

## 💾 What Gets Saved

### When Creating User
```
users table:
- name
- email
- password (hashed)
- role_id
- created_at
- updated_at

permission_role table:
- role_id
- permission_id
(Only if permissions selected)
```

### When Creating Role
```
roles table:
- name
- slug
- created_at
- updated_at

permission_role table:
- role_id
- permission_id
(For each selected permission)
```

---

## 🎨 Form Sections

```
┌─────────────────────────────────────┐
│ Create New [User Account/Role]      │
├─────────────────────────────────────┤
│ [Entity Type Toggle]                │
├─────────────────────────────────────┤
│ [User/Role Fields - Conditional]    │
├─────────────────────────────────────┤
│ [Permissions Selection]              │
├─────────────────────────────────────┤
│ [Cancel] [Create]                   │
└─────────────────────────────────────┘
```

---

## 📝 Input Examples

### User Example
```
Name:        Sarah Johnson
Email:       sarah.johnson@hospital.com
Password:    MySecurePass123
Confirm:     MySecurePass123
Role:        Doctor
Permissions: 
  ✓ View Dashboard
  ✓ View Medical Records
  ✓ Create Medical Records
  ✓ Edit Medical Records
```

### Role Example
```
Name:        Senior Consultant
Slug:        senior-consultant
Permissions:
  ✓ View Dashboard
  ✓ View Statistics
  ✓ View Medical Records
  ✓ Create Medical Records
  ✓ Edit Medical Records
  ✓ Delete Medical Records
  ✓ Manage Permissions
```

---

## 🐛 Troubleshooting

### Form Not Toggling?
- Ensure JavaScript is enabled
- Try refreshing the page
- Check browser console for errors

### Permissions Not Loading?
- Select a role from dropdown
- Wait for AJAX call to complete
- Check API endpoint: `/api/permissions?role_id=X`

### Form Won't Submit?
- Check red error messages below fields
- Ensure all fields with * are filled
- Verify password matches confirmation
- Check email is unique

### Missing Permissions?
- Go to `/admin/roles` to view all roles
- Role may not have those permissions assigned
- Create role with needed permissions first

---

## 📚 Documentation Files

- **UNIFIED_FORM_IMPLEMENTATION.md** - Full implementation details
- **UNIFIED_FORM_USAGE_GUIDE.md** - Detailed usage guide with diagrams
- **CODE_CHANGES_SUMMARY.md** - Code changes and technical details
- **QUICK_START.md** - Basic setup instructions

---

## 💡 Tips & Tricks

### Auto-Generate Slug
When creating a role, the slug auto-generates from the name:
```
"Senior Doctor" → "senior-doctor"
"Lab Manager" → "lab-manager"
"Chief Admin" → "chief-admin"
```

### Modify Auto-Generated Slug
Just click in the Slug field and edit it:
```
"Senior Doctor" → "senior-doc"
"Lab Manager" → "lab-mgr"
```

### Permission Groups
Use the organized permission groups to find what you need:
- Grouped by feature (Dashboard, Medical Records, etc.)
- Easy to see what each group controls
- Check/uncheck whole groups at once (if needed)

### Test Mode
Create test users/roles first before deploying:
```
User: testdoctor@hospital.com
Role: test-role
```

---

## 🔄 Permission Assignment Methods

### For Users
```
Method 1: Select role → Auto-load permissions → Modify
Method 2: Select role → Sync entire role's permissions (default)
```

### For Roles
```
Method: Check/uncheck desired permissions → Create role
```

---

## ⚠️ Important Notes

1. **System Admin Role**: Cannot be edited or deleted
2. **Email**: Must be unique (can't have duplicates)
3. **Role Slug**: Must be unique, lowercase, use hyphens
4. **Passwords**: Minimum 8 characters required
5. **Permissions**: Optional (can create user/role without selecting any)

---

## 🎯 Common Tasks

### Create Doctor User
```
1. /admin/users/create
2. Select "Create User Account"
3. Name: Dr. John Smith
4. Email: john.smith@hospital.com
5. Password: SecurePass123
6. Role: Doctor
7. Select permissions for doctors
8. Click Create
```

### Create Manager Role
```
1. /admin/users/create
2. Select "Create User Role"
3. Name: Department Manager
4. Slug: manager (auto-generated: dept-manager)
5. Select all admin permissions
6. Click Create
```

### Add Permissions to Existing Role
```
1. Go to /admin/roles
2. Click Edit on desired role
3. Check new permissions
4. Click Update
```

---

## 📞 Support

### File Issues
- Check error message in red text on form
- Review validation rules in this guide
- Check browser console (F12) for JavaScript errors

### Database Issues
- Verify role exists in `/admin/roles`
- Check database permissions table
- Verify permission_role pivot table has entries

### Permission Issues
- Verify user's role has correct permissions
- Check permission_role table for permission entries
- Verify permission slug matches in code

---

## Version History

| Version | Date | Changes |
|---------|------|---------|
| 1.0 | 2024-01 | Initial unified form implementation |
| | | - Single form for user & role creation |
| | | - Dynamic permission loading |
| | | - Permission assignment for both entities |
| | | - Auto-slug generation |

---

## 🎓 Learning Resources

### Understanding the Form
1. User → Role → Permissions hierarchy
2. Each user has one role
3. Each role has many permissions
4. Users inherit role permissions

### Understanding Routes
- `/admin/users/create` - Form page
- `POST /admin/users` - Form submission
- `/api/permissions?role_id=X` - Permission data

### Understanding Database
- `users` table - User accounts
- `roles` table - User roles
- `permissions` table - Available permissions
- `permission_role` table - Role↔Permission relationships

---

## ✨ Key Features

✅ **Single Form** - Create users or roles from one place
✅ **Smart Permissions** - Auto-load permissions by role
✅ **Easy Selection** - Toggle buttons for user/role
✅ **Auto-Slug** - Generate slug from role name
✅ **Organized** - Permissions grouped by feature
✅ **Secure** - Full validation and access control
✅ **Responsive** - Works on mobile and desktop
✅ **Fast** - AJAX loading for permissions

---

## 🚫 What Changed

### Removed
- `/roles/create` endpoint
- `POST /roles` endpoint (was separate)
- Separate role creation form

### Moved
- Role management now under `/admin/roles`
- Role creation now through `/admin/users/create`

### Added
- Entity type toggle (User/Role)
- Unified form for both
- Dynamic permission loading
- Auto-slug generation
