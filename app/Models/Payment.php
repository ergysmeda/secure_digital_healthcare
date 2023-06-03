<?php

namespace App\Models;

use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'doctor_id',
        'patient_id',
        'amount',
        'payment_time',
        'cost_id',
    ];
    protected $table = 'payments';

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    public function cost()
    {
        return $this->belongsTo(Cost::class);
    }
}
