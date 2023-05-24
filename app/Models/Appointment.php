<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $table = 'appointments';
    public function patient() {
        return $this->belongsTo(User::class, 'patient_id');
    }
    public function healthcareProfessional() {
        return $this->belongsTo(User::class, 'healthcare_professional_id');
    }
    public function status() {
        return $this->belongsTo(AppointmentStatus::class);
    }
}
