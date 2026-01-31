<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;

class MedicalRecordTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_patient_success()
    {
        // Create a doctor user
        $role = Role::create(['name' => 'doctor', 'slug' => 'doctor']);
        $doctor = User::factory()->create([
            'role_id' => $role->id,
        ]);

        // Create patient role
        $patientRole = Role::create(['name' => 'patient', 'slug' => 'patient']);

        // Test data
        $data = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'phone' => '+1234567890',
            'date_of_birth' => '1990-01-01',
            'gender' => 'male',
            'blood_group' => 'A+',
            'address' => '123 Main St',
            'medical_history' => 'No known allergies',
            'diagnosis' => 'Healthy',
            'prescription' => 'None',
            'lab_results' => 'Normal',
            'blood_pressure' => '120/80',
            'temperature' => '36.6',
            'pulse_rate' => '72',
            'weight' => '70.5',
            'height' => '175',
            'allergies' => 'None',
            'notes' => 'Initial checkup',
            'visibility_level' => 'private',
        ];

        // Act as the doctor and make the request
        $response = $this->actingAs($doctor)
            ->postJson('/medical-records/patients/store', $data);

        // Assert successful response
        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Patient and initial medical record created successfully'
            ]);

        // Assert patient was created
        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'role_id' => $patientRole->id,
        ]);

        // Assert medical record was created
        $patient = User::where('email', 'john.doe@example.com')->first();
        $this->assertDatabaseHas('patient_medical_records', [
            'patient_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'visibility_level' => 'private',
        ]);
    }

    public function test_store_patient_validation_error()
    {
        // Create a doctor user
        $role = Role::create(['name' => 'doctor', 'slug' => 'doctor']);
        $doctor = User::factory()->create([
            'role_id' => $role->id,
        ]);

        // Test with missing required fields
        $data = [
            'name' => '', // Required field empty
            'email' => 'invalid-email', // Invalid email
            'visibility_level' => 'invalid', // Invalid visibility
        ];

        // Act as the doctor and make the request
        $response = $this->actingAs($doctor)
            ->postJson('/medical-records/patients/store', $data);

        // Assert validation error (422)
        $response->assertStatus(422);
    }

    public function test_store_patient_unauthorized()
    {
        // Create a patient user (not authorized)
        $role = Role::create(['name' => 'patient']);
        $patient = User::factory()->create([
            'role_id' => $role->id,
        ]);

        $data = [
            'name' => 'Jane Doe',
            'email' => 'jane.doe@example.com',
            'visibility_level' => 'private',
        ];

        // Act as the patient and make the request
        $response = $this->actingAs($patient)
            ->postJson('/medical-records/patients/store', $data);

        // Assert unauthorized
        $response->assertStatus(403);
    }
}
