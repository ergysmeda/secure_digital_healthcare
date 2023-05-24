<?php

namespace App\Repositories\Models;

use App\Models\UserProfile;
use App\Repositories\BaseRepository;

class UserProfileRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new UserProfile());
    }
}




