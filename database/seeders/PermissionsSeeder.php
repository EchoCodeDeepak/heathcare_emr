<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
{
    public function run()
    {
        // Create all permissions
        $permissions = [
            // Dashboard
            ['name' => 'View Dashboard', 'slug' => 'view-dashboard'],

            // Medical Records
            ['name' => 'View Medical Records', 'slug' => 'view-medical-records'],
            ['name' => 'View All Medical Records', 'slug' => 'view-all-medical-records'],
            ['name' => 'Create Medical Records', 'slug' => 'create-medical-records'],
            ['name' => 'Edit Medical Records', 'slug' => 'edit-medical-records'],
            ['name' => 'Delete Medical Records', 'slug' => 'delete-medical-records'],
            ['name' => 'Manage Medical Records Permissions', 'slug' => 'manage-medical-records-permissions'],

            // Lab Results
            ['name' => 'View Lab Results', 'slug' => 'view-lab-results'],
            ['name' => 'Add Lab Results', 'slug' => 'add-lab-results'],
            ['name' => 'Edit Lab Results', 'slug' => 'edit-lab-results'],
            ['name' => 'Delete Lab Results', 'slug' => 'delete-lab-results'],

            // Data Export
            ['name' => 'Export Data', 'slug' => 'export-data'],

            // Administrative
            ['name' => 'Manage Users', 'slug' => 'manage-users'],
            ['name' => 'Manage Permissions', 'slug' => 'manage-permissions'],
            ['name' => 'View System Analytics', 'slug' => 'view-system-analytics'],
            ['name' => 'Manage Data Access', 'slug' => 'manage-data-access'],
        ];

        $createdPermissions = [];
        foreach ($permissions as $perm) {
            $createdPermissions[$perm['slug']] = Permission::firstOrCreate(
                ['slug' => $perm['slug']],
                ['name' => $perm['name']]
            );
        }

        // Get roles
        $admin = Role::where('slug', 'system-admin')->first();
        $doctor = Role::where('slug', 'doctor')->first();
        $nurse = Role::where('slug', 'nurse')->first();
        $labTech = Role::where('slug', 'lab-technician')->first();
        $patient = Role::where('slug', 'patient')->first();

        if (!$admin || !$doctor || !$nurse || !$labTech || !$patient) {
            $this->command->error('Roles not found. Please run RolesSeeder first.');
            return;
        }

        // Assign permissions to Admin (all permissions)
        $adminPermissions = [
            'view-dashboard',
            'view-medical-records',
            'view-all-medical-records',
            'create-medical-records',
            'edit-medical-records',
            'delete-medical-records',
            'manage-medical-records-permissions',
            'view-lab-results',
            'add-lab-results',
            'edit-lab-results',
            'delete-lab-results',
            'export-data',
            'manage-users',
            'manage-permissions',
            'view-system-analytics',
            'manage-data-access',
        ];
        foreach ($adminPermissions as $slug) {
            $admin->permissions()->syncWithoutDetaching($createdPermissions[$slug]->id);
        }

        // Assign permissions to Doctor
        $doctorPermissions = [
            'view-dashboard',
            'view-medical-records',
            'create-medical-records',
            'edit-medical-records',
            'view-lab-results',
            'export-data',
        ];
        foreach ($doctorPermissions as $slug) {
            $doctor->permissions()->syncWithoutDetaching($createdPermissions[$slug]->id);
        }

        // Assign permissions to Nurse
        $nursePermissions = [
            'view-dashboard',
            'view-medical-records',
            'edit-medical-records',
            'view-lab-results',
            'export-data',
        ];
        foreach ($nursePermissions as $slug) {
            $nurse->permissions()->syncWithoutDetaching($createdPermissions[$slug]->id);
        }

        // Assign permissions to Lab Technician
        $labTechPermissions = [
            'view-dashboard',
            'view-lab-results',
            'add-lab-results',
            'edit-lab-results',
            'export-data',
        ];
        foreach ($labTechPermissions as $slug) {
            $labTech->permissions()->syncWithoutDetaching($createdPermissions[$slug]->id);
        }

        // Assign permissions to Patient (view own data)
        $patientPermissions = [
            'view-dashboard',
            'view-medical-records',
            'view-lab-results',
        ];
        foreach ($patientPermissions as $slug) {
            $patient->permissions()->syncWithoutDetaching($createdPermissions[$slug]->id);
        }

        $this->command->info('Permissions seeded successfully!');
    }
}
