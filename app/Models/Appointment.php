<?php

namespace App\Models;

use App\Traits\Searchable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory,Searchable;

    protected $table = 'appointments';

    protected $fillable = [
        'description',
        'patient_id',
        'healthcare_professional_id',
        'appointment_time',
        'status_id',
        'online_or_in_presence',
        'notes',
        'schedule_id'];
    public static function getUpcomingAppointments($userId, $userType)
    {
        $now = Carbon::now();
        $nextFifteenMinutes = $now->copy()->addMinutes(59);
        $now =  $now->copy()->subMinutes(59);



        if($userType == '2') {
            $appointments = Appointment::where('patient_id', $userId)
                ->whereBetween('appointment_time', [$now, $nextFifteenMinutes])
                ->first();

        } else if($userType == '3') {

            $appointments = Appointment::where('healthcare_professional_id', $userId)
                ->whereBetween('appointment_time', [$now, $nextFifteenMinutes])
                ->first();
        }

        return $appointments;
    }

    public function patient() {
        return $this->belongsTo(User::class, 'patient_id');
    }
    protected $casts = [
        'online_or_in_presence' => 'boolean',
        'schedule_id' => 'integer',
    ];

    public function cost()
    {
        return $this->hasOne(Cost::class, 'appointment_id');
    }
    public function healthcareProfessional() {
        return $this->belongsTo(User::class, 'healthcare_professional_id');
    }
    public function status() {
        return $this->belongsTo(AppointmentStatus::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

}
