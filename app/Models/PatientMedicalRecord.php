<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PatientMedicalRecord extends Model
{
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'medical_history',
        'diagnosis',
        'prescription',
        'lab_results',
        'blood_pressure',
        'temperature',
        'pulse_rate',
        'weight',
        'height',
        'allergies',
        'notes',
        'visibility_level',
    ];

    /**
     * Get the patient.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    /**
     * Get the doctor.
     */
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    /**
     * Get the access permissions.
     */
    public function accessPermissions(): HasMany
    {
        return $this->hasMany(DataAccessPermission::class, 'record_id');
    }

    /**
     * Check if a user can view this record.
     */
    public function canUserView($userId): bool
    {
        $permission = $this->accessPermissions()
            ->where('user_id', $userId)
            ->where('can_view', true)
            ->first();

        return $permission !== null;
    }

    /**
     * Check if a user can edit this record.
     */
    public function canUserEdit($userId): bool
    {
        $permission = $this->accessPermissions()
            ->where('user_id', $userId)
            ->where('can_edit', true)
            ->first();

        return $permission !== null;
    }

    /**
     * Scope for records accessible by user.
     */
    public function scopeAccessibleBy($query, $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('patient_id', $userId)
                ->orWhere('doctor_id', $userId)
                ->orWhereHas('accessPermissions', function ($subQuery) use ($userId) {
                    $subQuery->where('user_id', $userId)->where('can_view', true);
                });
        });
    }

    /**
     * Get the visibility level as a readable string.
     */
    public function getVisibilityTextAttribute(): string
    {
        return match($this->visibility_level) {
            'private' => 'Private (Only patient and doctor)',
            'restricted' => 'Restricted (Medical staff only)',
            'public' => 'Public (All authorized staff)',
            default => 'Unknown',
        };
    }
}