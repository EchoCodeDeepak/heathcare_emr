<?php

use App\Models\PatientMedicalRecord;
use App\Policies\MedicalRecordPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        PatientMedicalRecord::class => MedicalRecordPolicy::class,
    ];

    //     protected $policies = [
    //     PatientMedicalRecord::class => MedicalRecordPolicy::class,
    // ];
    public function boot(): void
    {
        $this->registerPolicies(); // ← REQUIRED
    }
}
