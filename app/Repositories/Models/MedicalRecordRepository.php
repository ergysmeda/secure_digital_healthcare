<?php

namespace App\Repositories\Models;

use App\Models\MedicalRecord;
use App\Repositories\BaseRepository;

class MedicalRecordRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new MedicalRecord());
    }
}




