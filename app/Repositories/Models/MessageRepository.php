<?php

namespace App\Repositories\Models;

use App\Models\Message;
use App\Repositories\BaseRepository;
use App\Traits\Listable;

class MessageRepository extends BaseRepository
{
    use Listable;
    public function __construct()
    {
        parent::__construct(new Message());
    }
}




