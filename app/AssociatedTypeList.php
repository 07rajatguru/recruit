<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssociatedTypeList extends Model
{
    public $timestamps = false;
    public $table = "todo_associated_typelist";


    public static function getAssociatedListByTodoId($todo_id){

        $query = AssociatedTypeList::query();
        $query = $query->where('todo_id','=',$todo_id);
        $response = $query->get();

        $list = '';
        foreach ($response as $k=>$v){
            if($list==''){
                $list = $v->typelist_id;
            }
            else{
                $list .= ','.$v->typelist_id;
            }
        }

        return $list;
    }
}
