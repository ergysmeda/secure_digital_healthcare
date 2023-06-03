<?php

namespace App\Services;


use App\Repositories\Models\AppointmentRepository;
use App\Repositories\Models\UserRepository;

class AppointmentService extends ListService
{

    /**
     * @param ListService $listService
     */
    public function __construct()
    {
        parent::__construct(new AppointmentRepository());
    }

}
