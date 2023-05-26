<?php

namespace App\Repositories\Models;

use App\Models\User;
use App\Repositories\BaseRepository;
use App\Traits\Listable;

class UserRepository extends BaseRepository
{
    use Listable;

    public function __construct()
    {
        parent::__construct(new User());
    }
}




