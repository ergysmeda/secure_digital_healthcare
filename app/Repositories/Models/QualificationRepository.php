<?php

namespace App\Repositories\Models;

use App\Models\Qualification;
use App\Repositories\BaseRepository;

class QualificationRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new Qualification());
    }
}




