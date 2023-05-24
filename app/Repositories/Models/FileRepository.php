<?php

namespace App\Repositories\Models;

use App\Models\File;
use App\Repositories\BaseRepository;

class FileRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(new File());
    }
}




