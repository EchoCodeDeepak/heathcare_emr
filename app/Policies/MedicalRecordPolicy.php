<?php

namespace App\Policies;

use App\Models\PatientMedicalRecord;
use App\Models\User;

class MedicalRecordPolicy
{
    public function view(User $user, PatientMedicalRecord $record)
    {
        // Check all access conditions
        if ($record->patient_id == $user->id) {
            return true;
        }
        
        if ($record->doctor_id == $user->id) {
            return true;
        }
        
        if ($user->isAdmin()) {
            return true;
        }
        
        if ($record->canUserView($user->id)) {
            return true;
        }
        
        if ($record->visibility_level == 'public') {
            return true;
        }
        
        if ($record->visibility_level == 'restricted' && 
            ($user->isDoctor() || $user->isNurse() || $user->isLabTechnician())) {
            return true;
        }
        
        return false;
    }

    public function create(User $user)
    {
        return $user->isAdmin() || $user->isDoctor();
    }

    public function update(User $user, PatientMedicalRecord $record)
    {
        if ($user->isAdmin()) {
            return true;
        }
        
        if ($record->doctor_id == $user->id) {
            return true;
        }
        
        if ($record->canUserEdit($user->id)) {
            return true;
        }
        
        return false;
    }

    public function managePermissions(User $user, PatientMedicalRecord $record)
    {
        return $user->isAdmin() || $record->doctor_id == $user->id;
    }
}