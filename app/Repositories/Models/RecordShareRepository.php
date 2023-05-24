<?php

namespace App\Repositories\Models;

use App\Models\RecordShare;
use App\Repositories\BaseRepository;

class RecordShareRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new RecordShare());
    }
}




