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

    public static function getAllTodos($ids=array(),$limit=0,$offset=0,$search=0,$order=0,$type='desc'){

        $todo_status = env('COMPLETEDSTATUS');
        //print_r($todo_status);exit;
            
        $todo_query = ToDos::query();
        $todo_query = $todo_query->join('users', 'users.id', '=', 'to_dos.task_owner');
        $todo_query = $todo_query->join('status','status.id','=', 'to_dos.status');
        $todo_query = $todo_query->select('to_dos.*', 'users.name as name', 'status.id as status_id','status.name as status');

        if(isset($ids) && sizeof($ids)>0){
            $todo_query = $todo_query->whereIn('to_dos.id',$ids);
        }

        if (isset($limit) && $limit > 0) {
            $todo_query = $todo_query->limit($limit);
        }
        if (isset($offset) && $offset > 0) {
            $todo_query = $todo_query->offset($offset);
        }
        if (isset($order) && $order !='') {
            $todo_query = $todo_query->orderBy($order,$type);
        }
        if (isset($search) && $search != '') {
            $todo_query = $todo_query->where(function($todo_query) use ($search){

                $date_search = false;
                $date_array = explode("-",$search);
                if(isset($date_array) && sizeof($date_array)>0){
                    $stamp = strtotime($search);
                    if (is_numeric($stamp)){
                        $month = date( 'm', $stamp );
                        $day   = date( 'd', $stamp );
                        $year  = date( 'Y', $stamp );

                        if(checkdate($month, $day, $year)){
                            $date_search = true;
                        }
                    }
                }

                $todo_query = $todo_query->where('users.name','like',"%$search%");
                $todo_query = $todo_query->orwhere('to_dos.subject','like',"%$search%");
                $todo_query = $todo_query->orwhere('to_dos.due_date','like',"%$search%");
                $todo_query = $todo_query->orwhere('status.name','like',"%$search%");

                if($date_search){
                    $dateClass = new Date();
                    $search_string = $dateClass->changeDMYtoYMD($search);
                    $from_date = date("Y-m-d 00:00:00",strtotime($search_string));
                    $to_date = date("Y-m-d 23:59:59",strtotime($search_string));
                    $todo_query = $todo_query->orwhere('to_dos.due_date','>=',"$from_date");
                    $todo_query = $todo_query->Where('to_dos.due_date','<=',"$to_date");
                }

            });
        }
        //$todo_query = $todo_query->orderBy('to_dos.id','desc');
        $todo_query = $todo_query->whereNotIn('to_dos.status',explode(',', $todo_status));
        $todo_res   = $todo_query->get();
//print_r($todo_res);exit;
        $todo_array = array();
        $i = 0;
        foreach($todo_res as $todos){
            $todo_array[$i]['id'] = $todos->id;
            $todo_array[$i]['subject'] = $todos->subject;
            $todo_array[$i]['am_name'] = $todos->name;
            $todo_array[$i]['due_date'] = date('d-m-Y h:i A', strtotime("$todos->due_date"));
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

    public static function getAllTodosCount($ids=array(),$search=0){

        $todo_status = env('COMPLETEDSTATUS');
        //print_r($todo_status);exit;
            
        $todo_query = ToDos::query();
        $todo_query = $todo_query->join('users', 'users.id', '=', 'to_dos.task_owner');
        $todo_query = $todo_query->join('status','status.id','=', 'to_dos.status');
        $todo_query = $todo_query->select('to_dos.*', 'users.name as name', 'status.id as status_id','status.name as status');

        if(isset($ids) && sizeof($ids)>0){
            $todo_query = $todo_query->whereIn('to_dos.id',$ids);
        }

        if (isset($search) && $search != '') {
            $todo_query = $todo_query->where(function($todo_query) use ($search){

                $date_search = false;
                $date_array = explode("-",$search);
                if(isset($date_array) && sizeof($date_array)>0){
                    $stamp = strtotime($search);
                    if (is_numeric($stamp)){
                        $month = date( 'm', $stamp );
                        $day   = date( 'd', $stamp );
                        $year  = date( 'Y', $stamp );

                        if(checkdate($month, $day, $year)){
                            $date_search = true;
                        }
                    }
                }
                
                $todo_query = $todo_query->where('users.name','like',"%$search%");
                $todo_query = $todo_query->orwhere('to_dos.subject','like',"%$search%");
                $todo_query = $todo_query->orwhere('to_dos.due_date','like',"%$search%");
                $todo_query = $todo_query->orwhere('status.name','like',"%$search%");

                if($date_search){
                    $dateClass = new Date();
                    $search_string = $dateClass->changeDMYtoYMD($search);
                    $from_date = date("Y-m-d 00:00:00",strtotime($search_string));
                    $to_date = date("Y-m-d 23:59:59",strtotime($search_string));
                    $todo_query = $todo_query->orwhere('to_dos.due_date','>=',"$from_date");
                    $todo_query = $todo_query->Where('to_dos.due_date','<=',"$to_date");
                }
                
            });
        }
        $todo_query = $todo_query->whereNotIn('to_dos.status',explode(',', $todo_status));
        $todo_count = $todo_query->count();

        return $todo_count;
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

    public static function getCompleteTodos($ids=array(),$limit=0,$offset=0,$search=0,$order=0,$type='desc'){

        $todo_status = env('COMPLETEDSTATUS');
        //print_r($todo_status);exit;
            
        $todo_query = ToDos::query();
        $todo_query = $todo_query->join('users', 'users.id', '=', 'to_dos.task_owner');
        $todo_query = $todo_query->join('status','status.id','=', 'to_dos.status');
        $todo_query = $todo_query->select('to_dos.*', 'users.name as name', 'status.id as status_id','status.name as status');

        if(isset($ids) && sizeof($ids)>0){
            $todo_query = $todo_query->whereIn('to_dos.id',$ids);
        }

        if (isset($limit) && $limit > 0) {
            $todo_query = $todo_query->limit($limit);
        }
        if (isset($offset) && $offset > 0) {
            $todo_query = $todo_query->offset($offset);
        }
        if (isset($order) && $order !='') {
            $todo_query = $todo_query->orderBy($order,$type);
        }
        if (isset($search) && $search != '') {
            $todo_query = $todo_query->where(function($todo_query) use ($search){
                $todo_query = $todo_query->where('users.name','like',"%$search%");
                $todo_query = $todo_query->orwhere('to_dos.subject','like',"%$search%");
                $todo_query = $todo_query->orwhere('to_dos.due_date','like',"%$search%");
                $todo_query = $todo_query->orwhere('status.name','like',"%$search%");
            });
        }

        $todo_query = $todo_query->whereIn('to_dos.status',explode(',', $todo_status));
        $todo_res   = $todo_query->get();
//print_r($todo_res);exit;
        $todo_array = array();
        $i = 0;
        foreach($todo_res as $todos){
            $todo_array[$i]['id'] = $todos->id;
            $todo_array[$i]['subject'] = $todos->subject;
            $todo_array[$i]['am_name'] = $todos->name;
            $todo_array[$i]['due_date'] = date('d-m-Y h:i A', strtotime("$todos->due_date"));
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

    public static function getCompleteTodosCount($ids=array(),$search=0){

        $todo_status = env('COMPLETEDSTATUS');
            
        $todo_query = ToDos::query();
        $todo_query = $todo_query->join('users', 'users.id', '=', 'to_dos.task_owner');
        $todo_query = $todo_query->join('status','status.id','=', 'to_dos.status');
        $todo_query = $todo_query->select('to_dos.*', 'users.name as name', 'status.id as status_id','status.name as status');

        if(isset($ids) && sizeof($ids)>0){
            $todo_query = $todo_query->whereIn('to_dos.id',$ids);
        }

        if (isset($search) && $search != '') {
            $todo_query = $todo_query->where(function($todo_query) use ($search){
                $todo_query = $todo_query->where('users.name','like',"%$search%");
                $todo_query = $todo_query->orwhere('to_dos.subject','like',"%$search%");
                $todo_query = $todo_query->orwhere('to_dos.due_date','like',"%$search%");
                $todo_query = $todo_query->orwhere('status.name','like',"%$search%");
            });
        }
        $todo_query = $todo_query->whereIn('to_dos.status',explode(',', $todo_status));
        $todo_count = $todo_query->count();

        return $todo_count;
    }

    public static function getMyTodos($ids=array(),$limit=0,$offset=0,$search=0,$order=0,$type='desc'){

        $todo_status = env('COMPLETEDSTATUS');
  
        $user =  \Auth::user()->id;

        $todo_query = TodoAssignedUsers::query();
        $todo_query = $todo_query->join('to_dos', 'to_dos.id', '=', 'todo_associated_users.todo_id');
        $todo_query = $todo_query->join('users', 'users.id', '=', 'to_dos.task_owner');
        $todo_query = $todo_query->join('status','status.id','=', 'to_dos.status');
        $todo_query = $todo_query->select('to_dos.*', 'users.name as name', 'status.id as status_id','status.name as status');

        if(isset($ids) && sizeof($ids)>0){
            $todo_query = $todo_query->whereIn('to_dos.id',$ids);
        }

        if (isset($limit) && $limit > 0) {
            $todo_query = $todo_query->limit($limit);
        }
        if (isset($offset) && $offset > 0) {
            $todo_query = $todo_query->offset($offset);
        }
        if (isset($order) && $order !='') {
            $todo_query = $todo_query->orderBy($order,$type);
        }
        if (isset($search) && $search != '') {
            $todo_query = $todo_query->where(function($todo_query) use ($search){
                $todo_query = $todo_query->where('users.name','like',"%$search%");
                $todo_query = $todo_query->orwhere('to_dos.subject','like',"%$search%");
                $todo_query = $todo_query->orwhere('to_dos.due_date','like',"%$search%");
                $todo_query = $todo_query->orwhere('status.name','like',"%$search%");
            });
        }
        //$todo_query = $todo_query->orderBy('to_dos.id','desc');
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
            $todo_array[$i]['due_date'] = date('d-m-Y h:i A', strtotime("$todos->due_date"));
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

    public static function getMyTodosCount($ids=array(),$search=0){

        $todo_status = env('COMPLETEDSTATUS');
  
        $user =  \Auth::user()->id;

        $todo_query = TodoAssignedUsers::query();
        $todo_query = $todo_query->join('to_dos', 'to_dos.id', '=', 'todo_associated_users.todo_id');
        $todo_query = $todo_query->join('users', 'users.id', '=', 'to_dos.task_owner');
        $todo_query = $todo_query->join('status','status.id','=', 'to_dos.status');
        $todo_query = $todo_query->select('to_dos.*', 'users.name as name', 'status.id as status_id','status.name as status');

        if(isset($ids) && sizeof($ids)>0){
            $todo_query = $todo_query->whereIn('to_dos.id',$ids);
        }

        if (isset($search) && $search != '') {
            $todo_query = $todo_query->where(function($todo_query) use ($search){
                $todo_query = $todo_query->where('users.name','like',"%$search%");
                $todo_query = $todo_query->orwhere('to_dos.subject','like',"%$search%");
                $todo_query = $todo_query->orwhere('to_dos.due_date','like',"%$search%");
                $todo_query = $todo_query->orwhere('status.name','like',"%$search%");
            });
        }
        $todo_query = $todo_query->whereNotIn('to_dos.status',explode(',', $todo_status));
        $todo_query = $todo_query->where('user_id',$user);
        $todo_count   = $todo_query->count();

        return $todo_count;
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

    public static function getAllCCtodoIds($user_id){
        $query = ToDos::query();
        $query = $query->where('cc_user',$user_id);
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

    public static function getShowTodo($id){

        $todo_show = ToDos::query();
        $todo_show = $todo_show->join('users', 'users.id', '=', 'to_dos.task_owner');
        $todo_show = $todo_show->join('status','status.id','=', 'to_dos.status');
        $todo_show = $todo_show->leftjoin('todo_frequency','todo_frequency.todo_id','=','to_dos.id');
        $todo_show = $todo_show->select('to_dos.*', 'users.name as name','status.name as status','todo_frequency.reminder as frequency_type','todo_frequency.reminder_date as frequency_date');
        $todo_show = $todo_show->where('to_dos.id',$id);
        $todo_show_res = $todo_show->first();

        $todo = array();
        if (isset($todo_show_res) && sizeof($todo_show_res)>0) {
            $todo['task_owner'] = $todo_show_res->name;
            $todo['subject'] = $todo_show_res->subject;
            $todo['due_date'] = $todo_show_res->due_date;
            $todo['start_date'] = $todo_show_res->start_date;
            $todo['status'] = $todo_show_res->status;
            $todo['type'] = $todo_show_res->type;
            $todo['description'] = $todo_show_res->description;
            $am_name = ToDos::getAssociatedusersById($todo_show_res->id);
            $name_str = '';
            foreach ($am_name as $k=>$v){
                if($name_str==''){
                    $name_str = $v;
                }
                else{
                    $name_str .= ', '. $v ;
                }
            }
            $todo['assigned_to'] = $name_str;
            $todo['frequency_type'] = $todo_show_res->frequency_type;
            $todo['frequency_date'] = $todo_show_res->frequency_date;
            $type_list = ToDos::getTypeListById($todo_show_res->id,$todo_show_res->type);
            if (isset($type_list) && $type_list != '') {
                $todo['typelist'] = $type_list;
            }
            else{
                $todo['typelist'] = '';
            }
            //print_r($todo['typelist']);exit;
        }
        
        return $todo;
    }

    public static function getTypeListById($id,$type){

        $type_list = AssociatedTypeList::getAssociatedListByTodoId($id);
        $jobopen = array();
        $todo_type = "<ol>";
        $i = 0;
        if ($type == 1) {
            $job_response = JobOpen::getJobsByIds(0,explode(',',$type_list));
            foreach ($job_response as $k=>$v){
                $jobopen[$i] =  $v['company_name']." - ".$v['posting_title']." - ".$v['location'];
                $todo_type .= "<li>".$jobopen[$i]."</li>";
                $i++;
            }   
        }
        else if ($type == 2) {
            $interview_res = Interview::getTodosInterviewsByIds(explode(',',$type_list));
            foreach ($interview_res as $k=>$v){
                $jobopen[$i] =  $v->client_name." - ".$v->posting_title." - ".$v->city;
                $todo_type .= "<li>".$jobopen[$i]."</li>";
                $i++;
            }
                
        }
        else if ($type == 3) {
            $client_res = ClientBasicinfo::getClientsByIds(0,explode(',',$type_list));
            foreach ($client_res as $k=>$v){
                $jobopen[$i] =  $v->name." - ".$v->coordinator_name;
                $todo_type .= "<li>".$jobopen[$i]."</li>";
                $i++;
            }
                
        }
        else if ($type == 4) {
            $candidate_res = CandidateBasicInfo::getAllCandidatesById(explode(',',$type_list));
            foreach ($candidate_res as $k=>$v){
                $jobopen[$i] =  $v->full_name;
                $todo_type .= "<li>".$jobopen[$i]."</li>";
                $i++;
            }
                
        }
        else{
            $jobopen = '';
        }
        $todo_type .= "</ol>";

        return $todo_type;
    }

    // function for get todo's cc
    public static function getTodosByCCValue(){

        $query = ToDos::query();
        $query = $query->select('to_dos.id');
        $query = $query->where('to_dos.cc_user','=',0);
        //$query = $query->limit(2);
        $res = $query->get();

        $id = array();
        $i = 0;
        foreach ($res as $key => $value) {
            $id[$i] = $value->id;
            $i++;
        }

        return $id;
    }

    // function for get todo's start date
    public static function getTodosByStartDateValue(){

        $date = '1970-01-01';

        $query = ToDos::query();
        $query = $query->select('to_dos.id');
        $query = $query->where('to_dos.start_date','like',"%$date%");
        $res = $query->get();

        $id = array();
        $i = 0;
        foreach ($res as $key => $value) {
            $id[$i] = $value->id;
            $i++;
        }

        return $id;
    }
}
