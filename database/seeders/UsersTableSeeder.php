<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        // Create Admin
        User::create([
            'name' => 'System Admin',
            'email' => 'admin@emr.com',
            'password' => Hash::make('password123'),
            'role_id' => 1,
            'email_verified_at' => now(),
        ]);

        // Create Doctor
        User::create([
            'name' => 'Dr. John Smith',
            'email' => 'doctor@emr.com',
            'password' => Hash::make('password123'),
            'role_id' => 2,
            'email_verified_at' => now(),
        ]);

        // Create Nurse
        User::create([
            'name' => 'Nurse Jane Williams',
            'email' => 'nurse@emr.com',
            'password' => Hash::make('password123'),
            'role_id' => 3,
            'email_verified_at' => now(),
        ]);

        // Create Lab Technician
        User::create([
            'name' => 'Lab Tech Mike Brown',
            'email' => 'lab@emr.com',
            'password' => Hash::make('password123'),
            'role_id' => 4,
            'email_verified_at' => now(),
        ]);

        // Create Patient
        User::create([
            'name' => 'Patient Sarah Johnson',
            'email' => 'patient@emr.com',
            'password' => Hash::make('password123'),
            'role_id' => 5,
            'email_verified_at' => now(),
        ]);
    }
}
