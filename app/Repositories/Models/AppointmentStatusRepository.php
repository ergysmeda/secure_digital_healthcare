<?php

namespace App\Repositories\Models;

use App\Models\AppointmentStatus;
use App\Repositories\BaseRepository;

class AppointmentStatusRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new AppointmentStatus());
    }
}




