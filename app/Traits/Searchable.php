<?php

namespace App\Traits;

trait Searchable
{
    function scopeSearch($query,$input)
    {
        return $query
            ->where(function ($query) use ($input) {

                foreach ($input['columns_required'] as $id => $column) {

                    if($column == '*'){
                        return $query;
                    }
                    $query->orWhere($column, 'like', '%' . $input['search'] . '%');
                }
                foreach ($input['with_columns']  as  $relations) {
                    $explodedRelations =  explode(':',$relations) ;
                    $table = $explodedRelations[0];



                    if(!empty($explodedRelations[1])){
                        $relationColumns = explode(',',$explodedRelations[1]);
                        foreach ($relationColumns as $relationColumn){
                            $query
                                ->orWhereHas($table, function ($query) use ($relationColumn,$input) {
                                    $query->where($relationColumn, 'like', '%' . $input['search'] . '%');
                                });
                        }
                    }



                }
            });
    }

}
