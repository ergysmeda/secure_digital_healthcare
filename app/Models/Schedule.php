<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $table = 'schedules';
    public function provider() {
        return $this->belongsTo(User::class, 'healthcare_professional_id');
    }
    public function location() {
        return $this->belongsTo(Location::class);
    }
}
