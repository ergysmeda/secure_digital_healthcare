<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientProfile extends Model
{
    use HasFactory;

    protected $table = 'patient_profiles';
    protected $fillable = [
        'user_id',
        'dob',
        'gender',
        'blood_group',
        'allergies',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
