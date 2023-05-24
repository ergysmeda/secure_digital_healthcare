<?php

namespace App\Repositories\Models;

use App\Models\Schedule;
use App\Repositories\BaseRepository;

class ScheduleRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new Schedule());
    }
}




