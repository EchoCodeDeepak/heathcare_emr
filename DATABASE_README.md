# Healthcare EMR Database Schema - Simple Explanation

This document explains how the database works in our Healthcare Electronic Medical Records (EMR) system in simple terms.

## Overview

Our database stores information about users, their roles, permissions, and medical records. It uses a system where users have roles (like Doctor, Nurse), and roles have permissions (like "can view medical records").

## Main Tables and How They Work

### 1. Users Table
```
id, name, email, password, phone, date_of_birth, gender,
blood_group, address, role_id, remember_token, created_at, updated_at
```

**What it stores:**
- Basic user information (name, email, phone, etc.)
- Login credentials (email + password)
- Which role they have (`role_id` connects to Roles table)

**How users log in:**
- User enters email and password
- System checks if email/password match in this table
- If yes, user gets logged in and can access the system
- The `role_id` tells the system what this user can do

### 2. Roles Table
```
id, name, slug, created_at, updated_at
```

**What it stores:**
- Different types of users in the system
- Examples: System Admin, Doctor, Nurse, Lab Technician, Patient

**How it works:**
- Each role has a unique ID
- Users are assigned a `role_id` in the Users table
- This determines what the user can access

### 3. Permissions Table
```
id, name, slug, created_at, updated_at
```

**What it stores:**
- Specific actions users can perform
- Examples: "view-medical-records", "create-medical-records", "manage-users"

**How it works:**
- Each permission is a specific capability
- Roles get assigned multiple permissions
- Users get permissions through their role

### 4. Role_Permissions Table (This is a Pivot Table)
```
id, role_id, permission_id, created_at, updated_at
```

**What is a Pivot Table?**
- A pivot table connects two other tables
- It stores relationships between roles and permissions
- No actual data, just connections

**How it works:**
- `role_id` connects to the Roles table
- `permission_id` connects to the Permissions table
- Example: Doctor role (id=2) can have permissions like "view-medical-records" (id=1), "create-medical-records" (id=2)

**Why use a pivot table?**
- One role can have many permissions
- One permission can belong to many roles
- This is called a "many-to-many" relationship

### 5. Patient_Medical_Records Table
```
id, patient_id, doctor_id, diagnosis, treatment_plan,
prescription, notes, visibility_level, created_at, updated_at
```

**What it stores:**
- Medical information for patients
- Who created it (`doctor_id`)
- Which patient it belongs to (`patient_id`)

**Doctor-Patient Relationship:**
- `patient_id` connects to Users table (the patient is also a user)
- `doctor_id` connects to Users table (the doctor who created the record)
- This shows which doctor treated which patient
- Patients can only see their own records
- Doctors can see records of patients they treated

### 6. Data_Access_Permissions Table
```
id, user_id, medical_record_id, can_view, can_edit, created_at
```

**What it stores:**
- Special permissions for specific medical records
- Who can view or edit specific records

**How it works:**
- `user_id` connects to Users table
- `medical_record_id` connects to Patient_Medical_Records table
- `can_view` and `can_edit` are true/false flags
- Used for fine-grained access control

## How Everything Connects

### User Login Process:
1. User enters email/password
2. System finds user in Users table
3. Gets user's `role_id`
4. Finds role in Roles table
5. Gets all permissions for that role from Role_Permissions table
6. User can now access features based on their permissions

### Example: Doctor Accessing Medical Records
1. Doctor logs in (User table)
2. System sees role_id = 2 (Doctor)
3. Finds Doctor role has permissions: view-medical-records, create-medical-records
4. Doctor can view/create records
5. When viewing records, system shows only records where doctor_id matches their user ID

### Example: Patient Viewing Records
1. Patient logs in (User table)
2. System sees role_id = 5 (Patient)
3. Patient role has permission: view-own-medical-records
4. System shows only records where patient_id matches their user ID

## Key Concepts

- **Relationships:** Tables connect through ID fields
- **Roles vs Permissions:** Roles group permissions, users get roles
- **Pivot Tables:** Connect many-to-many relationships
- **Access Control:** Everything is controlled by roles and permissions
- **Data Security:** Users only see data they're allowed to access

## Summary

The database uses a flexible permission system where:
- Users have roles
- Roles have permissions
- Permissions control what users can do
- Medical records connect doctors to patients
- Everything is secured through this role-permission system

This setup allows the system to be very flexible - you can create new roles, assign different permissions, and control exactly what each type of user can access.
