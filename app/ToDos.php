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

    public static function getReminder(){
        $repetition = array('' => 'Select');
        $repetition['1'] = 'Daily';
        $repetition['2'] = 'Weekly';
        $repetition['3'] = 'Monthly';
        $repetition['4'] = 'Quarterly';
        $repetition['5'] = 'Yearly';

        return $repetition;
    }

    public static function getAllTodos($ids=array()){

        $todo_status = env('COMPLETEDSTATUS');
        //print_r($todo_status);exit;
            
        $todo_query = ToDos::query();
        $todo_query = $todo_query->join('users', 'users.id', '=', 'to_dos.task_owner');
        $todo_query = $todo_query->join('status','status.id','=', 'to_dos.status');
        $todo_query = $todo_query->select('to_dos.*', 'users.name as name', 'status.id as status_id','status.name as status');

        if(isset($ids) && sizeof($ids)>0){
            $todo_query = $todo_query->whereIn('to_dos.id',$ids);
        }

        $todo_query = $todo_query->orderBy('to_dos.id','desc');
        $todo_query = $todo_query->whereNotIn('to_dos.status',explode(',', $todo_status));
        $todo_res   = $todo_query->get();
//print_r($todo_res);exit;
        $todo_array = array();
        $i = 0;
        foreach($todo_res as $todos){
            $todo_array[$i]['id'] = $todos->id;
            $todo_array[$i]['subject'] = $todos->subject;
            $todo_array[$i]['am_name'] = $todos->name;
            $todo_array[$i]['due_date'] = $todos->due_date;
            $todo_array[$i]['due_date_ts'] = $todos->due_date;
            $todo_array[$i]['status'] = $todos->status;
            $todo_array[$i]['status_ids'] = $todos->status_id;
            $todo_array[$i]['task_owner'] = $todos->task_owner;           

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

    public static function getAllTodosdash($ids=array(),$limit=0){

        $todo_status = env('COMPLETEDSTATUS');
        //print_r($todo_status);exit;
            
        $todo_query = ToDos::query();
        $todo_query = $todo_query->join('users', 'users.id', '=', 'to_dos.task_owner');
        $todo_query = $todo_query->select('to_dos.*', 'users.name as name','to_dos.status');

        if(isset($ids) && sizeof($ids)>0){
            $todo_query = $todo_query->whereIn('to_dos.id',$ids);
        }

        $todo_query = $todo_query->orderBy('to_dos.id','desc');
        $todo_query = $todo_query->whereNotIn('to_dos.status',explode(',', $todo_status));

        if($limit>0)
            $todo_query   = $todo_query->limit($limit);

        $todo_res = $todo_query->get();

//print_r($todo_res);exit;
        $todo_array = array();
        $i = 0;
        foreach($todo_res as $todos){
            $todo_array[$i]['id'] = $todos->id;
            $todo_array[$i]['subject'] = $todos->subject;
            $todo_array[$i]['am_name'] = $todos->name;
            $todo_array[$i]['due_date'] = $todos->due_date;
            $todo_array[$i]['status'] = $todos->status;

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

    public static function getCompleteTodos($ids=array()){

        $todo_status = env('COMPLETEDSTATUS');
        //print_r($todo_status);exit;
            
        $todo_query = ToDos::query();
        $todo_query = $todo_query->join('users', 'users.id', '=', 'to_dos.task_owner');
        $todo_query = $todo_query->join('status','status.id','=', 'to_dos.status');
        $todo_query = $todo_query->select('to_dos.*', 'users.name as name', 'status.id as status_id','status.name as status');

        if(isset($ids) && sizeof($ids)>0){
            $todo_query = $todo_query->whereIn('to_dos.id',$ids);
        }

        $todo_query = $todo_query->orderBy('to_dos.id','desc');
        $todo_query = $todo_query->whereIn('to_dos.status',explode(',', $todo_status));
        $todo_res   = $todo_query->get();
//print_r($todo_res);exit;
        $todo_array = array();
        $i = 0;
        foreach($todo_res as $todos){
            $todo_array[$i]['id'] = $todos->id;
            $todo_array[$i]['subject'] = $todos->subject;
            $todo_array[$i]['am_name'] = $todos->name;
            $todo_array[$i]['due_date'] = $todos->due_date;
            $todo_array[$i]['due_date_ts'] = $todos->due_date;
            $todo_array[$i]['status'] = $todos->status;
            $todo_array[$i]['status_ids'] = $todos->status_id;
            $todo_array[$i]['task_owner'] = $todos->task_owner;     

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

        public static function getMyTodos($ids=array()){

        $todo_status = env('COMPLETEDSTATUS');
        //print_r($todo_status);exit;
        
        $user =  \Auth::user()->id;

        $todo_query = TodoAssignedUsers::query();
        $todo_query = $todo_query->join('to_dos', 'to_dos.id', '=', 'todo_associated_users.todo_id');
        $todo_query = $todo_query->join('users', 'users.id', '=', 'to_dos.task_owner');
       // $todo_query = $todo_query->join('users', 'users.id', '=', 'todo_associated_users.user_id');
        $todo_query = $todo_query->join('status','status.id','=', 'to_dos.status');
        $todo_query = $todo_query->select('to_dos.*', 'users.name as name', 'status.id as status_id','status.name as status');

        if(isset($ids) && sizeof($ids)>0){
            $todo_query = $todo_query->whereIn('to_dos.id',$ids);
        }

        $todo_query = $todo_query->orderBy('to_dos.id','desc');
        $todo_query = $todo_query->whereNotIn('to_dos.status',explode(',', $todo_status));
        $todo_query = $todo_query->where('user_id',$user);
        $todo_res   = $todo_query->get();
//print_r($todo_res);exit;
        $todo_array = array();
        $i = 0;
        foreach($todo_res as $todos){
            $todo_array[$i]['id'] = $todos->id;
            $todo_array[$i]['subject'] = $todos->subject;
            $todo_array[$i]['am_name'] = $todos->name;
            $todo_array[$i]['due_date'] = $todos->due_date;
            $todo_array[$i]['due_date_ts'] = $todos->due_date;
            $todo_array[$i]['status'] = $todos->status;
            $todo_array[$i]['status_ids'] = $todos->status_id;
            $todo_array[$i]['task_owner'] = $todos->task_owner;           

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

    public static function getAllTaskOwnertodoIds($user_id){
        $query = ToDos::query();
        $query = $query->where('task_owner',$user_id);
        $response = $query->get();

        $todo_ids = array();
        $i = 0;
        foreach ($response as $k=>$v){
            $todo_ids[$i] = $v->id;
            $i++;
        }

        return $todo_ids;

    }

    public static function getTodoFrequencyCheck(){

        $in_progress = env('INPROGRESS');
        $yet_to_start = env('YETTOSTART');
        $todo_status = array($in_progress,$yet_to_start);

        $status_check = ToDos::query();
        //$status_check = $status_check->join('todo_associated_users', 'todo_associated_users.todo_id', '=','to_dos.id');
        $status_check = $status_check->select('to_dos.*');
        $status_check = $status_check->whereIn('to_dos.status',$todo_status);
        //$status_check = $status_check->limit(1);
        $status_check_res = $status_check->get();

        $todos = array();
        $i = 0;
        foreach ($status_check_res as $key => $value) {
            $todos[$i]['id'] = $value->id;
            $todos[$i]['task_owner'] = $value->task_owner;
            $am_name = ToDos::getAssociatedusersById($value->id);
            $name_str = '';
            foreach ($am_name as $k=>$v){
                if($name_str==''){
                    $name_str = $k;
                }
                else{
                    $name_str .= ', '. $k ;
                }
            }
            $todos[$i]['assigned_to'] = $name_str;
            $i++;
        }

        //echo "<pre>"; print_r($todos);exit;
        return $todos;
    }
}
