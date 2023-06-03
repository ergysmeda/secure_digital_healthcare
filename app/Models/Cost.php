<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cost extends Model
{
    use HasFactory;

    protected $fillable = ['amount']; // Add other fillable fields as needed

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
