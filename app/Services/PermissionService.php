<?php

namespace App\Services;

class PermissionService
{
    protected $permissions = [
        // Medical Records
        'view-medical-records' => 'View Medical Records',
        'create-medical-records' => 'Create Medical Records',
        'edit-medical-records' => 'Edit Medical Records',
        'delete-medical-records' => 'Delete Medical Records',
        
        // Lab Results
        'view-lab-results' => 'View Lab Results',
        'add-lab-results' => 'Add Lab Results',
        'edit-lab-results' => 'Edit Lab Results',
        
        // Prescriptions
        'view-prescriptions' => 'View Prescriptions',
        'add-prescriptions' => 'Add Prescriptions',
        
        // Patient History
        'view-patient-history' => 'View Patient History',
        
        // User Management
        'manage-users' => 'Manage Users',
        'manage-permissions' => 'Manage Permissions',
        
        // Dashboard
        'view-dashboard' => 'View Dashboard',
        
        // Reports
        'view-reports' => 'View Reports',
        'export-data' => 'Export Data',
    ];

    /**
     * Get all permissions
     */
    public function getAllPermissions()
    {
        return $this->permissions;
    }

    /**
     * Get permission groups
     */
    public function getPermissionGroups()
    {
        return [
            'Medical Records' => [
                'view-medical-records',
                'create-medical-records', 
                'edit-medical-records',
                'delete-medical-records'
            ],
            'Lab Results' => [
                'view-lab-results',
                'add-lab-results',
                'edit-lab-results'
            ],
            'Prescriptions' => [
                'view-prescriptions',
                'add-prescriptions'
            ],
            'Patient Management' => [
                'view-patient-history'
            ],
            'Administration' => [
                'manage-users',
                'manage-permissions'
            ],
            'Dashboard & Reports' => [
                'view-dashboard',
                'view-reports',
                'export-data'
            ]
        ];
    }

    /**
     * Get default permissions for each role
     */
    public function getDefaultRolePermissions()
    {
        return [
            'system-admin' => array_keys($this->permissions), // All permissions
            'doctor' => [
                'view-medical-records',
                'create-medical-records',
                'edit-medical-records',
                'view-lab-results',
                'add-lab-results',
                'view-prescriptions',
                'add-prescriptions',
                'view-patient-history',
                'view-dashboard'
            ],
            'nurse' => [
                'view-medical-records',
                'edit-medical-records',
                'view-lab-results',
                'view-prescriptions',
                'view-patient-history',
                'view-dashboard'
            ],
            'lab-technician' => [
                'view-medical-records',
                'add-lab-results',
                'edit-lab-results',
                'view-dashboard'
            ],
            'patient' => [
                'view-medical-records',
                'view-lab-results',
                'view-prescriptions',
                'view-dashboard'
            ]
        ];
    }
}