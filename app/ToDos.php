<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\TodoAssignedUsers;

class ToDos extends Model
{
    //
    public $table = "to_dos";

    public static $rules = array(
        'subject' => 'required',
        'due_date' => 'required',
        'type' => 'required',
    );

    public function messages()
    {
        return [
            'subject.required' => 'Subject is required field',
            'due_date.required' => 'Due Date is required field',
            'type.required' => 'Type is required field',
        ];
    }

    public static function getPriority(){
        $priority = array();
        $priority['Normal'] = 'Normal';
        $priority['High'] = 'High';
        $priority['Highest'] = 'Highest';
        $priority['Low'] = 'Low';
        $priority['Lowest'] = 'Lowest';

        return $priority;
    }

    public static function getAllTodos($ids=array()){
            
        $todo_query = ToDos::query();
        $todo_query = $todo_query->join('users', 'users.id', '=', 'to_dos.task_owner');
        $todo_query = $todo_query->select('to_dos.*', 'users.name as name');

        if(isset($ids) && sizeof($ids)>0){
            $todo_query = $todo_query->whereIn('to_dos.id',$ids);
        }

        $todo_query = $todo_query->orderBy('to_dos.id','desc');
        $todo_res   = $todo_query->get();
//print_r($todo_res);exit;
        $todo_array = array();
        $i = 0;
        foreach($todo_res as $todos){
            $todo_array[$i]['id'] = $todos->id;
            $todo_array[$i]['subject'] = $todos->subject;
            $todo_array[$i]['am_name'] = $todos->name;
        

            $am_name = ToDos::getAssociatedusersById($todos->id);
            $name_str = '';
            foreach ($am_name as $k=>$v){
                if($name_str==''){
                    $name_str = $v;
                }
                else{
                    $name_str .= ', '. $v ;
                }
            }
            $todo_array[$i]['assigned_to'] = $name_str;
            $i++;
        }

        return $todo_array;
    }

    public static function getAssociatedusersById($id){

        $todo_user_query = ToDos::query();
        $todo_user_query = $todo_user_query->join('todo_associated_users','todo_associated_users.todo_id', '=', 'to_dos.id');
        $todo_user_query = $todo_user_query->join('users', 'users.id', '=', 'todo_associated_users.user_id');
        $todo_user_query = $todo_user_query->select('to_dos.*','users.id as userid','users.name as am_name');
        $todo_user_query = $todo_user_query->where('todo_id',$id);
        $todo_user_query = $todo_user_query->get();

        $todos_array = array();
        $i = 0;
        foreach($todo_user_query as $k => $value){
            $todos_array[$value->userid] = $value->am_name;
            $i++;

        }
        return $todos_array;

    }

    public static function getTodoIdsByUserId($user_id){

        $query = TodoAssignedUsers::query();
        $query = $query->where('user_id',$user_id);
        $response = $query->get();

        $todo_ids = array();
        $i = 0;
        foreach ($response as $k=>$v){
            $todo_ids[$i] = $v->todo_id;
            $i++;
        }

        return $todo_ids;

    }
}
