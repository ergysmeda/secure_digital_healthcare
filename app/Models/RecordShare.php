<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecordShare extends Model
{
    use HasFactory;

    protected $table = 'record_shares';
    public function record() {
        return $this->belongsTo(MedicalRecord::class, 'record_id');
    }
    public function sharedWith() {
        return $this->belongsTo(User::class, 'shared_with_user_id');
    }
}
