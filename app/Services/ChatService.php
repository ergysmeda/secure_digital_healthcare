<?php

namespace App\Services;

use App\Repositories\Models\MessageRepository;

class ChatService extends ListService
{

    /**
     * @param ListService $listService
     */
    public function __construct()
    {
        parent::__construct(new MessageRepository());
    }

}
