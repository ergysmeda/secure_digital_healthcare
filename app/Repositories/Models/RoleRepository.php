<?php

namespace App\Repositories\Models;

use App\Models\Role;
use App\Repositories\BaseRepository;

class RoleRepository extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        parent::__construct(new Role());
    }

    public function getIdByRoleName($rolename)
    {
         return $this->model::where('role_name', $rolename)->first()->id;
    }
}




