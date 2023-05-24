<?php

namespace App\Repositories\Models;

use App\Models\Alert;
use App\Repositories\BaseRepository;

class AlertRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new Alert());
    }
}




