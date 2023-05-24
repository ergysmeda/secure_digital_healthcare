<?php

namespace App\Repositories\Models;

use App\Models\Message;
use App\Repositories\BaseRepository;

class MessageRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new Message());
    }
}




