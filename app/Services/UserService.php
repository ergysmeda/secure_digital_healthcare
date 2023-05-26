<?php

namespace App\Services;


use App\Repositories\Models\UserRepository;

class UserService extends ListService
{
    public ListService $listService;

    /**
     * @param ListService $listService
     */
    public function __construct()
    {
        parent::__construct(new UserRepository());
    }


}
