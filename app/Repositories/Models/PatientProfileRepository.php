<?php

namespace App\Repositories\Models;

use App\Models\PatientProfile;
use App\Repositories\BaseRepository;

class PatientProfileRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new PatientProfile());
    }
}




