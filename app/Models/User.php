<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;


    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'google2fa_secret',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function isEmailConfirmed()
    {
        return $this->google2fa_enable;
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
    /**
     * Check if the user has any of the specified roles.
     *
     * @param array|string $roles
     * @return bool
     */
    public function hasAnyRole($roles): bool
    {

        if (is_array($roles)) {
            return $this->roles()->whereIn('role_name', $roles)->exists();
        }

        return $this->roles()->where('role_name', $roles)->exists();
    }
    public function userProfile() {
        return $this->hasOne(UserProfile::class);
    }
    public function patientProfile() {
        return $this->hasOne(PatientProfile::class);
    }
    public function providerProfile() {
        return $this->hasOne(ProviderProfile::class);
    }
    public function appointments() {
        return $this->hasMany(Appointment::class);
    }
    public function files() {
        return $this->hasMany(File::class);
    }
    public function medicalRecords() {
        return $this->hasMany(MedicalRecord::class);
    }
    public function alerts() {
        return $this->hasMany(Alert::class);
    }
    public function notifications() {
        return $this->hasMany(Notification::class);
    }
}
