<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $table = 'schedules';
    protected $fillable = [
        'healthcare_professional_id',
        'location_id',
        'start_time',
        'end_time',
    ];

    public function provider()
    {
        return $this->belongsTo(User::class, 'healthcare_professional_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function appointment()
    {
        return $this->hasOne(Appointment::class);
    }
}
