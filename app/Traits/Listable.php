<?php

namespace App\Traits;

trait Listable
{
    function list($input)
    {

        return  $this->model->
        select($input['columns_required'])->
        with($input['with_columns'])->whereRaw('1=1')
                ->search($input) ;



    }

}
