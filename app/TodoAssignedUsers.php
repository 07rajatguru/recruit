<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TodoAssignedUsers extends Model
{
    public $table = "todo_associated_users";


    public static function getUserListByTodoId($todo_id){

        $query = TodoAssignedUsers::query();
        $query = $query->where('todo_id','=',$todo_id);
        $res = $query->get();

        $user = array();
        $i = 0;
        foreach ($res as $k=>$v){
            $user[$i] = $v->user_id;
            $i++;
        }

        return $user;
    }
}
