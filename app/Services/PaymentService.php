<?php

namespace App\Services;

use App\Repositories\Models\PaymentRepository;

class PaymentService extends ListService
{

    /**
     * @param ListService $listService
     */
    public function __construct()
    {
        parent::__construct(new PaymentRepository());
    }

}
