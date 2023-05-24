<?php

namespace App\Repositories\Models;

use App\Models\Specialty;
use App\Repositories\BaseRepository;

class SpecialityRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new Specialty());
    }
}




