<?php

namespace App\Repositories\Models;

use App\Models\Appointment;
use App\Repositories\BaseRepository;

class AppointmentRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new Appointment());
    }
}




