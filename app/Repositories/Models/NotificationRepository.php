<?php

namespace App\Repositories\Models;

use App\Models\Notification;
use App\Repositories\BaseRepository;

class NotificationRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new Notification());
    }
}




