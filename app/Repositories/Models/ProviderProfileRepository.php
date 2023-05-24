<?php

namespace App\Repositories\Models;

use App\Models\ProviderProfile;
use App\Repositories\BaseRepository;

class ProviderProfileRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new ProviderProfile());
    }
}




