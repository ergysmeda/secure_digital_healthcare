<?php

namespace App\Repositories\Models;

use App\Models\Location;
use App\Repositories\BaseRepository;

class LocationRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new Location());
    }
}




