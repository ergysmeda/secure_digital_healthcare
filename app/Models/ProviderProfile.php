<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'qualification',
        'specialty',
        'years_of_experience',
        'allergies'
    ];

    protected $table = 'provider_profiles';
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function qualifications()
    {
        return $this->hasMany(Qualification::class);
    }
    public function specialties()
    {
        return $this->hasMany(Specialty::class);
    }
}
