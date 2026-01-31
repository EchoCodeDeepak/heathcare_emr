# TODO: Fix Kernel.php Error

- [x] Fix syntax error in Kernel.php api middleware group
- [ ] Create TrustProxies middleware
- [ ] Create PreventRequestsDuringMaintenance middleware
- [ ] Create TrimStrings middleware
- [ ] Create EncryptCookies middleware
- [ ] Create VerifyCsrfToken middleware




1. Admin Role – System & Access Controller

Admin has full system control.

<!-- • Login & access admin dashboard -->
<!-- • Create / update / delete users (doctor, nurse, lab technician, patient) -->
<!-- • Assign roles to users -->
• Enable / disable user accounts
• Manage departments (Cardiology, Pathology, etc.)
• Assign doctors to departments
• Assign staff to patients
• Manage hospital/clinic profile
• View all patient records (read-only recommended)
• View system-wide reports
• Manage permissions (role-based access control)
• View activity logs (who accessed what)
• Manage appointments (override access)

2. Doctor Role – Clinical Data Manager

Doctor manages medical data, not system data.

• Login & access doctor dashboard
• View assigned patients
• View complete medical history of assigned patients
• Create / update diagnosis records
• Create prescriptions
• Update treatment plans
• Request lab tests for patients
• View lab test reports
• Add clinical notes
• View appointment schedule
• Mark patient visit status (completed / follow-up)
• Upload medical documents (optional)

❌ Cannot create users
❌ Cannot change system settings

3. Nurse Role – Patient Care Support

Nurse handles patient vitals & support tasks.

• Login & access nurse dashboard
• View assigned patients
• Record patient vitals (BP, temperature, pulse, weight)
• Update nursing notes
• View doctor instructions
• Assist in patient admission / discharge process
• Update patient care status
• View appointment details

❌ Cannot prescribe medicines
❌ Cannot edit diagnosis
❌ Cannot access lab modules

4. Lab Technician Role – Investigation Handler

Lab technician handles tests & reports only.

• Login & access lab dashboard
• View lab test requests
• Update test status (pending / processing / completed)
• Enter test results
• Upload lab reports (PDF / image)
• View limited patient details (name, age, test type)
• Notify doctor after report upload

❌ Cannot view full patient history
❌ Cannot edit prescriptions or diagnosis

5. Patient Role – Self Access Only

Patient has read-only personal access.

• Login & access patient dashboard
• View own profile
• View appointments
• View prescriptions
• View lab reports
• View diagnosis summary
• Download medical reports
• Request appointment (optional)

❌ Cannot edit medical data
❌ Cannot view other patients

6. Technical Implementation Mapping (Laravel Friendly)

You should implement:

• users table with role column
• Role-based middleware (role:admin, role:doctor, etc.)
• Separate dashboards per role
• Route groups protected by role middleware
• Controller-level authorization checks
• Policy or permission checks for sensitive actions
• Activity logs for admin auditing

7. Optional Advanced (For Strong Project)

• Use Spatie Laravel Permission for permissions
• Role-wise menu rendering in Blade
• API-based access control (if REST API)
• Soft deletes for medical records
• Audit logs for medical changes