<?php

namespace App\Services;



class ListService
{
    protected $repository;
    public array $queryHelper = [
        'all' => [
            'columns_required' => [
                'name',
                'email',
            ],
            'with_columns' => [
                'roles:role_name',
            ],
        ],
    ];
    /**
     */
    public function __construct($repository)
    {
        $this->repository = $repository;
    }

    public function list($input)
    {
        $input = array_merge($input,$this->queryHelper[$input['type']]);

        $this->repository->list($input);
    }

}
