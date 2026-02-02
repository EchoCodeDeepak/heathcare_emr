# Healthcare EMR - Admin System Setup Guide

## System Overview

This Healthcare Electronic Medical Records (EMR) System is now configured for **Admin-Only Access** with role-based permissions management.

---

## 🔐 Login Flow

### Step 1: Initial Page Load
When you run the project and visit the home page (`/`), you will see:
- **Admin Login Form** (not a welcome page)
- Only email and password fields
- Demo credentials display

### Step 2: Login
**Admin Credentials:**
- **Email:** `admin@emr.com`
- **Password:** `password123`

**Important:** Only users with the **System Admin** role can login. Any attempt by non-admin users (Doctor, Nurse, Lab Technician, Patient) will result in:
- Automatic logout after successful authentication
- Error message: "Only admin users can access this system."

### Step 3: Admin Dashboard Access
After successful admin login, you'll be redirected to:
- **Route:** `/dashboard`
- **View:** Admin Dashboard with complete overview

---

## 📊 Admin Dashboard Features

The Admin Dashboard displays:

### 1. **Statistics Cards**
- **Total Users:** Count of all users in the system
- **Total Records:** Count of medical records
- **Total Roles:** Number of roles (System Admin, Doctor, Nurse, Lab Technician, Patient)
- **Permissions:** Number of permissions configured

### 2. **Quick Actions**
- 🆕 **Add New User** → Create new user with role and permissions
- 👥 **Manage Users** → View, edit, delete users
- ⚙️ **Manage Permissions** → Configure role-based permissions
- 📄 **View Records** → View all medical records

### 3. **Analytics**
- Users by Role (pie chart view)
- System Information (Laravel version, PHP version, Database type)

---

## 👤 User Management

### Creating a New User

**Path:** Admin Dashboard → Quick Actions → **Add New User**

**Form Fields:**

1. **Full Name** *(required)*
   - Enter the user's complete name
   - Example: "Dr. John Smith"

2. **Email Address** *(required)*
   - Must be unique
   - Example: "doctor@example.com"

3. **Password** *(required)*
   - Must be confirmed
   - Minimum 8 characters

4. **Confirm Password** *(required)*
   - Must match password field

5. **Select User Role** *(required)*
   - Dropdown with available roles:
     - Doctor
     - Nurse
     - Lab Technician
     - Patient

### 6. **Assign Permissions** *(Optional but Recommended)*

After selecting a role, permission checkboxes appear automatically, organized by category:

**Permission Categories:**

#### Medical Records
- ✅ View Medical Records
- ✅ Create Medical Records
- ✅ Edit Medical Records
- ✅ Delete Medical Records
- ✅ Manage Medical Record Permissions

#### Dashboard
- ✅ View Dashboard

#### Lab Results
- ✅ View Lab Results
- ✅ Add Lab Results

#### Data Export
- ✅ Export Data (PDF, Excel, CSV)

#### Role & Permissions Management
- ✅ Manage Permissions

### How Permissions Work

1. **Role-Based Permissions:**
   - Each role has default permissions
   - When creating a user, you can override role permissions
   - User-specific permissions override role permissions

2. **Permission Checkboxes:**
   - ☑️ Checked = User has access
   - ☐ Unchecked = User does not have access

3. **Example Scenarios:**
   - **Doctor:** Can view & create medical records, view dashboard
   - **Lab Technician:** Can add lab results, view records
   - **Patient:** Can view own records only
   - **Nurse:** Can view & edit records, manage patient data

---

## 🔒 Permission Management System

### Managing Role Permissions

**Path:** Admin Dashboard → Quick Actions → **Manage Permissions**

**Features:**

1. **Role Permission Matrix**
   - View all roles and their permissions in a table
   - Rows = Roles
   - Columns = Permissions (grouped by category)
   - System Admin role = Auto all permissions (cannot change)

2. **Assigning Permissions**
   - Check/uncheck permission checkboxes
   - Changes apply in real-time (auto-save with 1 second delay)
   - Success notification appears

3. **Permission Groups**
   - Medical Records
   - Dashboard Access
   - Lab Results
   - Data Management
   - Administrative

---

## 🎯 Role Configuration

### Default Roles

1. **System Admin** *(ID: 1)*
   - Full access to all features
   - Can manage users, roles, permissions
   - Cannot be deleted
   - Only admins can login

2. **Doctor** *(ID: 2)*
   - Default: View/Create/Edit medical records
   - Can view dashboard
   - Cannot manage users

3. **Nurse** *(ID: 3)*
   - Default: View medical records
   - Can update patient information
   - Cannot create new roles

4. **Lab Technician** *(ID: 4)*
   - Default: Add lab results
   - Can view associated records
   - Limited dashboard access

5. **Patient** *(ID: 5)*
   - Default: View own medical records
   - Limited record access
   - Cannot access admin features

---

## 🔄 User Access & Visibility

### After User Login (Non-Admin)

When a non-admin user logs in with their credentials:

1. **Authentication Check:**
   - User credentials verified
   - User role retrieved
   - Role permissions loaded

2. **Role-Based Redirection:**
   - **Doctor** → Doctor Dashboard
   - **Nurse** → Nurse Dashboard
   - **Lab Technician** → Lab Dashboard
   - **Patient** → Patient Dashboard

3. **Permission-Based Visibility:**
   - Menu items appear based on permissions
   - Features disabled if user lacks permission
   - Records filtered by user's access level
   - Data hidden based on role/permission

4. **Example Access Control:**
   ```
   Doctor Login
   ├── Can view all medical records (permission: view-medical-records)
   ├── Can create new records (permission: create-medical-records)
   ├── Cannot delete records (no permission: delete-medical-records)
   └── Cannot manage users (no permission: manage-users)
   ```

---

## 📋 User Management Interface

### Users List

**Path:** Admin Dashboard → Quick Actions → **Manage Users**

**Features:**

1. **User Table**
   - All users with details
   - Role display
   - Creation date
   - Last updated

2. **Search & Filter**
   - Search by name or email
   - Filter by role
   - Sort by column

3. **Actions**
   - 📝 Edit user
   - 🗑️ Delete user
   - 📊 Export users (PDF, Excel, CSV)

4. **Bulk Operations**
   - Export filtered results
   - Multiple export formats

### Editing User

**Path:** Users List → Edit Icon

**Editable Fields:**
- Name
- Email
- Password (optional)
- Role

---

## 🚀 Workflow Example: Complete User Creation Process

### Scenario: Create a Doctor Account

**Step 1:** Admin logs in with `admin@emr.com` / `password123`

**Step 2:** Click "Add New User" from dashboard

**Step 3:** Fill form:
```
Full Name: Dr. Michael Johnson
Email: dr.michael@hospital.com
Password: SecurePass123
Confirm: SecurePass123
Role: Doctor
```

**Step 4:** System loads permissions for "Doctor" role:
```
Medical Records
  ☑ View Medical Records
  ☑ Create Medical Records
  ☑ Edit Medical Records
  ☐ Delete Medical Records
  ☐ Manage Permissions

Dashboard
  ☑ View Dashboard

Lab Results
  ☐ View Lab Results
  ☐ Add Lab Results

Data Export
  ☑ Export Data
```

**Step 5:** (Optional) Uncheck "Export Data" if doctor shouldn't export

**Step 6:** Click "Create User"

### User Can Now Login:
```
Email: dr.michael@hospital.com
Password: SecurePass123
```

**Result:**
- Redirected to Doctor Dashboard
- Can view all medical records
- Can create new records
- Can edit records
- Cannot delete records
- Cannot manage permissions
- Can export data
- Can only access features with permissions

---

## 🛡️ Security Features

1. **Admin-Only System**
   - Only System Admin role can access main system
   - Non-admin login attempts are rejected

2. **Password Security**
   - Passwords hashed with bcrypt
   - Minimum password requirements enforced
   - Password confirmation required

3. **Permission Verification**
   - Every action checked against user permissions
   - Middleware protection on all routes
   - Database-level permission enforcement

4. **CSRF Protection**
   - All forms include CSRF tokens
   - AJAX requests validated
   - Token in meta tag for JavaScript access

5. **Role-Based Access Control**
   - Routes protected by role middleware
   - Views rendered based on permissions
   - Database queries filtered by access level

---

## 📱 Responsive Design

- Desktop (1200px+): Full layout
- Tablet (768px-1199px): Optimized layout
- Mobile (< 768px): Mobile-friendly interface

---

## 🔧 Troubleshooting

### Issue: "Only admin users can access this system"
**Solution:** Ensure you're logging in with System Admin role (admin@emr.com)

### Issue: Permissions not loading in create user form
**Solution:** Ensure role is selected before permissions section appears

### Issue: User cannot see specific features after login
**Solution:** Check user permissions in Manage Permissions page

### Issue: AJAX permissions endpoint returning error
**Solution:** Verify `/api/permissions` route is accessible and CSRF token is valid

---

## 📚 API Endpoints

### Get Permissions by Role
```
GET /api/permissions?role_id=2
Authorization: Bearer {token}
```

**Response:**
```json
{
  "permissionGroups": {...},
  "permissions": [...],
  "rolePermissions": [...]
}
```

---

## 📝 Database Tables Used

- `users` - User accounts
- `roles` - User roles
- `permissions` - System permissions
- `role_permissions` - Role-permission mapping
- `patient_medical_records` - Medical records
- `data_access_permissions` - User-level permissions

---

## 🎓 Next Steps

1. ✅ Login as admin: `admin@emr.com` / `password123`
2. ✅ Create test users with different roles
3. ✅ Assign permissions to roles
4. ✅ Test user login with different roles
5. ✅ Verify role-based access and visibility

---

**System Ready for Use!** 🎉
