<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public UserService $userService;

    /**
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function list()
    {
        $pageIndex = 1;
        $pageSize = 10;
        $search = trim(request('search'));

        $input = [
            'pageIndex' => $pageIndex,
            'pageSize' => $pageSize,
            'search' => $search,
            'method' => 'listUsers',
            'type' => 'all'
        ];


        $this->userService->list($input);

    }
}
