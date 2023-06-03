<?php

namespace App\Repositories\Models;

use App\Models\Payment;
use App\Repositories\BaseRepository;
use App\Traits\Listable;

class PaymentRepository extends BaseRepository
{
    use Listable;
    public function __construct()
    {
        parent::__construct(new Payment());
    }
}




