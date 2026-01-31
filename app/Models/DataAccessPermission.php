<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataAccessPermission extends Model
{
    protected $fillable = ['record_id', 'user_id', 'can_view', 'can_edit'];

    public function record()
    {
        return $this->belongsTo(PatientMedicalRecord::class, 'record_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}