<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'date_of_birth',
        'gender',
        'blood_group',
        'address',
        'role_id',
        'profile_image',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the role that owns the user.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get user's permissions via role
     */
    public function permissions()
    {
        if (!$this->role) {
            return collect([]);
        }
        return $this->role->permissions();
    }

    /**
     * Check if user is system admin
     */
    public function isAdmin(): bool
    {
        return $this->role_id == 1; // system-admin has ID 1
    }

    /**
     * Check if user is doctor
     */
    public function isDoctor(): bool
    {
        return $this->role_id == 2; // doctor has ID 2
    }

    /**
     * Check if user is nurse
     */
    public function isNurse(): bool
    {
        return $this->role_id == 3; // nurse has ID 3
    }

    /**
     * Check if user is lab technician
     */
    public function isLabTechnician(): bool
    {
        return $this->role_id == 4; // lab-technician has ID 4
    }

    /**
     * Check if user is patient
     */
    public function isPatient(): bool
    {
        return $this->role_id == 5; // patient has ID 5
    }

    /**
     * Check if user has specific permission
     */
    public function hasPermission(string $permissionSlug): bool
    {
        if (!$this->role) {
            return false;
        }

        // If user is system admin, they have all permissions
        if ($this->isAdmin()) {
            return true;
        }

        return $this->role->permissions()->where('slug', $permissionSlug)->exists();
    }

    /**
     * Check if user has any of the given permissions
     */
    public function hasAnyPermission(array $permissionSlugs): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        foreach ($permissionSlugs as $permissionSlug) {
            if ($this->hasPermission($permissionSlug)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if user has all of the given permissions
     */
    public function hasAllPermissions(array $permissionSlugs): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        foreach ($permissionSlugs as $permissionSlug) {
            if (!$this->hasPermission($permissionSlug)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get medical records where this user is the patient
     */
    public function medicalRecords(): HasMany
    {
        return $this->hasMany(PatientMedicalRecord::class, 'patient_id');
    }

    /**
     * Get medical records that this user has access to
     */
    public function accessibleRecords()
    {
        // Records where user is the patient
        $patientRecords = $this->medicalRecords();

        // Records where user is the doctor
        $doctorRecords = PatientMedicalRecord::where('doctor_id', $this->id);

        // Records where user has been granted access via permissions
        $permittedRecords = PatientMedicalRecord::whereHas('accessPermissions', function ($query) {
            $query->where('user_id', $this->id)->where('can_view', true);
        });

        // Combine all queries
        return PatientMedicalRecord::where(function ($query) {
            $query->where('patient_id', $this->id)
                ->orWhere('doctor_id', $this->id)
                ->orWhereHas('accessPermissions', function ($q) {
                    $q->where('user_id', $this->id)->where('can_view', true);
                });
        });
    }

    /**
     * Get records created by this user (as doctor)
     */
    public function createdRecords(): HasMany
    {
        return $this->hasMany(PatientMedicalRecord::class, 'doctor_id');
    }
}
