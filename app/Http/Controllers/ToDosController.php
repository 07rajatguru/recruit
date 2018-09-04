<?php

namespace App\Http\Controllers;

use App\AssociatedTypeList;
use App\CandidateBasicInfo;
use App\CandidateStatus;
use App\ClientBasicinfo;
use App\Date;
use App\Events\NotificationEvent;
use App\Events\NotificationMail;
use App\Interview;
use App\JobOpen;
use App\Status;
use App\TodoAssignedUsers;
use App\TodoFrequency;
use App\ToDos;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class ToDosController extends Controller
{

    public function daily(){

        $todo_frequency = TodoFrequency::gettodobyfrequency(1);

        //echo '<pre>'; print_r($todo_frequency);exit;

        foreach ($todo_frequency as $key => $value) {

            $inprogress = env('INPROGRESS');

            $todo_frequency_data = ToDos::join('todo_frequency','todo_frequency.todo_id','=','to_dos.id')
                                   ->select('to_dos.id','todo_frequency.reminder as reminder','to_dos.due_date','to_dos.start_date')
                                   ->where('todo_frequency.todo_id','=',$value['id'])
                                   ->get();

            $i = 0;
            foreach ($todo_frequency_data as $key1 => $value1) {
                $reminder[$i] = $value1->reminder;
                $due_date[$i] = $value1->due_date;
                $start_date[$i] = $value1->start_date;
                
                if ($reminder[$i] == 1) {

                    $todos = ToDos::find($value['id']);
                    $todos->status = $inprogress;
                    $todos->due_date = date('Y-m-d',strtotime("$due_date[$i] tomorrow"));
                    $todos->start_date = date('Y-m-d',strtotime("$start_date[$i] tomorrow"));
                    $todos->save();

                    $reminder_date = date('Y-m-d',strtotime("$start_date[$i] tomorrow"));

                    $todo_reminder = TodoFrequency::where('todo_id',$value['id'])->select('todo_frequency.todo_id')->first();
                    $todo_reminder_id = $todo_reminder->todo_id;

                    DB::statement("UPDATE todo_frequency SET reminder_date = '$reminder_date' where todo_id=$todo_reminder_id");
                    //print_r($todo_reminder_id);exit;
                }

                if ($reminder[$i] == 2) {

                    $todos = ToDos::find($value['id']);
                    $todos->status = $inprogress;
                    $todos->due_date = date('Y-m-d',strtotime("$due_date[$i] +1 week"));
                    $todos->start_date = date('Y-m-d',strtotime("$start_date[$i] +1 week"));
                    $todos->save();

                    $reminder_date = date('Y-m-d',strtotime("$start_date[$i] +1 week"));

                    $todo_reminder = TodoFrequency::where('todo_id',$value['id'])->select('todo_frequency.todo_id')->first();
                    $todo_reminder_id = $todo_reminder->todo_id;

                    DB::statement("UPDATE todo_frequency SET reminder_date = '$reminder_date' where todo_id=$todo_reminder_id");
                    //print_r($todo_reminder_id);exit;
                }

                if ($reminder[$i] == 3) {

                    $todos = ToDos::find($value['id']);
                    $todos->status = $inprogress;
                    $todos->due_date = date('Y-m-d',strtotime("$due_date[$i] +1 month"));
                    $todos->start_date = date('Y-m-d',strtotime("$start_date[$i] +1 month"));
                    $todos->save();

                    $reminder_date = date('Y-m-d',strtotime("$start_date[$i] +1 month"));

                    $todo_reminder = TodoFrequency::where('todo_id',$value['id'])->select('todo_frequency.todo_id')->first();
                    $todo_reminder_id = $todo_reminder->todo_id;

                    DB::statement("UPDATE todo_frequency SET reminder_date = '$reminder_date' where todo_id=$todo_reminder_id");
                    //print_r($todo_reminder_id);exit;
                }
                $i++;
            }
        }
            /*$toDos = new ToDos();
            $toDos->subject = $value['subject'];
            $toDos->task_owner = $value['task_owner'];
            $toDos->due_date = date("Y-m-d h:i:s");
            $toDos->status = $value['status'];
            $toDos->type = $value['type'];
            $toDos->description = $value['desc'];

            //echo '<pre>'; print_r($toDos);exit;
            $toDosStored = $toDos->save();
            $toDos_id = $toDos->id;

            $todo_typelist = ToDos::join('todo_associated_typelist','todo_associated_typelist.todo_id','=','to_dos.id')
                                  ->select('todo_associated_typelist.typelist_id as typelistid')
                                  ->where('todo_associated_typelist.todo_id','=',$value['id'])
                                  ->get();

            $i=0;
            foreach ($todo_typelist as $k => $v) {
                $typelistid[$i] = $v->typelistid;
                $typelist_id = $typelistid[$i];
                $i++;
                //echo '<pre>'; print_r($typelistid);exit;

                if(isset($typelist_id) && sizeof($typelist_id)>0){
                //foreach ($typelistid as $key=>$value){
                    $todo_ass_list = new AssociatedTypeList();
                    $todo_ass_list->todo_id = $toDos_id;
                    $todo_ass_list->typelist_id = $typelist_id;

                    //echo '<pre>'; print_r($todo_ass_list);exit;
                    $todo_ass_list->save();
                //}
                }
            }

            $todo_users = ToDos::join('todo_associated_users','todo_associated_users.todo_id','=','to_dos.id')
                                ->select('to_dos.id','todo_associated_users.user_id as userid')
                                ->where('todo_associated_users.todo_id','=',$value['id'])
                                ->get();
            $i=0;
            foreach ($todo_users as $k1 => $v1) {
                $user_id[$i] = $v1->userid;
                $user_ids = $user_id[$i];
                $i++;

                if(isset($user_ids) && sizeof($user_ids)>0){
                //foreach ($user_ids as $key=>$value){
                    $todo_user_list = new TodoAssignedUsers();
                    $todo_user_list->todo_id = $toDos_id;
                    $todo_user_list->user_id = $user_ids;

                    //echo '<pre>'; print_r($todo_user_list);exit;
                    $todo_user_list->save();
               // }
                }
            }
        }*/
        


        /*$todo_users = ToDos::join('todo_associated_users','todo_associated_users.todo_id','=','to_dos.id')
                            ->join('todo_frequency','todo_frequency.todo_id','=','to_dos.id')
                            ->select('to_dos.id','todo_associated_users.user_id as userid')
                            ->where('todo_frequency.reminder','=',1)
                            ->groupby('todo_associated_users.todo_id')
                            ->get();

        $users = array();
        $i=0;
        foreach ($todo_users as $key => $value) {
            $user = ToDos::getAssociatedusersById($value->id);
            $id = '';
            foreach ($user as $key => $value) {
                if ($id == '') {
                    $id = $key;
                }
                else{
                    $id .= ', ' . $key;
                }
            }
            $users[$i] = $id;
            $user_id = $users[$i];
            $i++;
        
        echo '<pre>'; print_r($user_id);exit;



        foreach ($todo_frequency as $todo) {
            $toDos = new ToDos();
            $toDos->subject = $todo->subject;
            $toDos->task_owner = $todo->task_owner;
            $toDos->due_date = $today;
            $toDos->status = $todo->status;
            $toDos->type = $todo->type;
            $toDos->description = $todo->description;

            // print_r($toDos);exit;
            $toDosStored = $toDos->save();
            $toDos_id = $toDos->id;
            if($toDos_id){
                if(isset($user_id) && sizeof($user_id)>0){
                    foreach ($user_id as $k => $v) {
                        $todo_users = new TodoAssignedUsers();
                        $todo_users->todo_id = $toDos_id;
                        $todo_users->user_id = $k;
                        $todo_users->save();
                    }
                }

                if(isset($todo->typelistid) && sizeof($todo->typelistid)>0){
                        $todo_ass_list = new AssociatedTypeList();
                        $todo_ass_list->todo_id = $toDos_id;
                        $todo_ass_list->typelist_id = $todo->typelistid;
                        $todo_ass_list->save();
                }*/

                /*if (isset($reminder) && $reminder!='') {
                    $todo_reminder = new TodoFrequency();
                    $todo_reminder->todo_id = $toDos_id;
                    $todo_reminder->reminder = $reminder;
                    if ($reminder == 1) {
                        $todo_reminder->reminder_date = date("Y-m-d", strtotime('tomorrow'));
                    }
                    elseif ($reminder == 2) {
                        $todo_reminder->reminder_date = date("Y-m-d", strtotime('+1 week'));
                    }
                    elseif ($reminder == 3) {
                        $todo_reminder->reminder_date = date("Y-m-d", strtotime('+1 month'));
                    }
                    $todo_reminder->save();
                }
            }
        }
        }*/
    }

    public function weekly(){

        $today = date("Y-m-d h:i:s");

        $todo_frequency = TodoFrequency::gettodobyfrequency(2);

        //echo '<pre>'; print_r($todo_frequency);exit;

        foreach ($todo_frequency as $key => $value) {
            $toDos = new ToDos();
            $toDos->subject = $value['subject'];
            $toDos->task_owner = $value['task_owner'];
            $toDos->due_date = date("Y-m-d h:i:s");
            $toDos->status = $value['status'];
            $toDos->type = $value['type'];
            $toDos->description = $value['desc'];

            //echo '<pre>'; print_r($toDos);exit;
            $toDosStored = $toDos->save();
            $toDos_id = $toDos->id;

            $todo_typelist = ToDos::join('todo_associated_typelist','todo_associated_typelist.todo_id','=','to_dos.id')
                                  ->select('todo_associated_typelist.typelist_id as typelistid')
                                  ->where('todo_associated_typelist.todo_id','=',$value['id'])
                                  ->get();

            $i=0;
            foreach ($todo_typelist as $k => $v) {
                $typelistid[$i] = $v->typelistid;
                $typelist_id = $typelistid[$i];
                $i++;
                //echo '<pre>'; print_r($typelistid);exit;

                if(isset($typelist_id) && sizeof($typelist_id)>0){
                //foreach ($typelistid as $key=>$value){
                    $todo_ass_list = new AssociatedTypeList();
                    $todo_ass_list->todo_id = $toDos_id;
                    $todo_ass_list->typelist_id = $typelist_id;

                    //echo '<pre>'; print_r($todo_ass_list);exit;
                    $todo_ass_list->save();
                //}
                }
            }

            $todo_users = ToDos::join('todo_associated_users','todo_associated_users.todo_id','=','to_dos.id')
                                ->select('to_dos.id','todo_associated_users.user_id as userid')
                                ->where('todo_associated_users.todo_id','=',$value['id'])
                                ->get();
            $i=0;
            foreach ($todo_users as $k1 => $v1) {
                $user_id[$i] = $v1->userid;
                $user_ids = $user_id[$i];
                $i++;

                if(isset($user_ids) && sizeof($user_ids)>0){
                //foreach ($user_ids as $key=>$value){
                    $todo_user_list = new TodoAssignedUsers();
                    $todo_user_list->todo_id = $toDos_id;
                    $todo_user_list->user_id = $user_ids;

                    //echo '<pre>'; print_r($todo_user_list);exit;
                    $todo_user_list->save();
                //}
                }
            }
        }


        /*foreach ($todo_frequency as $todo) {
            $toDos = new ToDos();
            $toDos->subject = $todo->subject;
            $toDos->task_owner = $todo->task_owner;
            $toDos->due_date = $today;
            $toDos->status = $todo->status;
            $toDos->type = $todo->type;
            $toDos->description = $todo->description;

            // print_r($toDos);exit;
            $toDosStored = $toDos->save();
            $toDos_id = $toDos->id;
            if($toDos_id){
                if(isset($todo->userid) && sizeof($todo->userid)>0){
                        $todo_users = new TodoAssignedUsers();
                        $todo_users->todo_id = $toDos_id;
                        $todo_users->user_id = $todo->userid;
                        $todo_users->save();
                }

                if(isset($todo->typelistid) && sizeof($todo->typelistid)>0){
                        $todo_ass_list = new AssociatedTypeList();
                        $todo_ass_list->todo_id = $toDos_id;
                        $todo_ass_list->typelist_id = $todo->typelistid;
                        $todo_ass_list->save();
                }

               /* if (isset($reminder) && $reminder!='') {
                    $todo_reminder = new TodoFrequency();
                    $todo_reminder->todo_id = $toDos_id;
                    $todo_reminder->reminder = $reminder;
                    if ($reminder == 1) {
                        $todo_reminder->reminder_date = date("Y-m-d", strtotime('tomorrow'));
                    }
                    elseif ($reminder == 2) {
                        $todo_reminder->reminder_date = date("Y-m-d", strtotime('+1 week'));
                    }
                    elseif ($reminder == 3) {
                        $todo_reminder->reminder_date = date("Y-m-d", strtotime('+1 month'));
                    }
                    $todo_reminder->save();
                }
            }
        }*/
    }

    public function monthly(Request $request){

        $today = date("Y-m-d h:i:s");

        $todo_frequency = TodoFrequency::gettodobyfrequency(3);

        //echo '<pre>'; print_r($todo_frequency);exit;

         foreach ($todo_frequency as $key => $value) {
            $toDos = new ToDos();
            $toDos->subject = $value['subject'];
            $toDos->task_owner = $value['task_owner'];
            $toDos->due_date = date("Y-m-d h:i:s");
            $toDos->status = $value['status'];
            $toDos->type = $value['type'];
            $toDos->description = $value['desc'];

            //echo '<pre>'; print_r($toDos);exit;
            $toDosStored = $toDos->save();
            $toDos_id = $toDos->id;

            $todo_typelist = ToDos::join('todo_associated_typelist','todo_associated_typelist.todo_id','=','to_dos.id')
                                  ->select('todo_associated_typelist.typelist_id as typelistid')
                                  ->where('todo_associated_typelist.todo_id','=',$value['id'])
                                  ->get();

            $i=0;
            foreach ($todo_typelist as $k => $v) {
                $typelistid[$i] = $v->typelistid;
                $typelist_id = $typelistid[$i];
                $i++;
                //echo '<pre>'; print_r($typelist_id);exit;

                if(isset($typelist_id) && sizeof($typelist_id)>0){
                //foreach ($typelistid as $key=>$value){
                    $todo_ass_list = new AssociatedTypeList();
                    $todo_ass_list->todo_id = $toDos_id;
                    $todo_ass_list->typelist_id = $typelist_id;

                    //echo '<pre>'; print_r($todo_ass_list);exit;
                    $todo_ass_list->save();
                //}
                }
            }

            $todo_users = ToDos::join('todo_associated_users','todo_associated_users.todo_id','=','to_dos.id')
                                ->select('to_dos.id','todo_associated_users.user_id as userid')
                                ->where('todo_associated_users.todo_id','=',$value['id'])
                                ->get();
            $i=0;
            foreach ($todo_users as $k1 => $v1) {
                $user_id[$i] = $v1->userid;
                $user_ids = $user_id[$i];
                $i++;

                if(isset($user_ids) && sizeof($user_ids)>0){
                //foreach ($user_ids as $key=>$value){
                    $todo_user_list = new TodoAssignedUsers();
                    $todo_user_list->todo_id = $toDos_id;
                    $todo_user_list->user_id = $user_ids;

                    //echo '<pre>'; print_r($todo_user_list);exit;
                    $todo_user_list->save();
                //}
                }
            }
        }

        /*foreach ($todo_frequency as $todo) {
            $toDos = new ToDos();
            $toDos->subject = $todo->subject;
            $toDos->task_owner = $todo->task_owner;
            $toDos->due_date = $today;
            $toDos->status = $todo->status;
            $toDos->type = $todo->type;
            $toDos->description = $todo->description;

            // print_r($toDos);exit;
            $toDosStored = $toDos->save();
            $toDos_id = $toDos->id;
            if($toDos_id){
                if(isset($todo->userid) && sizeof($todo->userid)>0){
                        $todo_users = new TodoAssignedUsers();
                        $todo_users->todo_id = $toDos_id;
                        $todo_users->user_id = $todo->userid;
                        $todo_users->save();
                }

                if(isset($todo->typelistid) && sizeof($todo->typelistid)>0){
                        $todo_ass_list = new AssociatedTypeList();
                        $todo_ass_list->todo_id = $toDos_id;
                        $todo_ass_list->typelist_id = $todo->typelistid;
                        $todo_ass_list->save();
                }

                /*if (isset($reminder) && $reminder!='') {
                    $todo_reminder = new TodoFrequency();
                    $todo_reminder->todo_id = $toDos_id;
                    $todo_reminder->reminder = $reminder;
                    if ($reminder == 1) {
                        $todo_reminder->reminder_date = date("Y-m-d", strtotime('tomorrow'));
                    }
                    elseif ($reminder == 2) {
                        $todo_reminder->reminder_date = date("Y-m-d", strtotime('+1 week'));
                    }
                    elseif ($reminder == 3) {
                        $todo_reminder->reminder_date = date("Y-m-d", strtotime('+1 month'));
                    }
                    $todo_reminder->save();
                }
            }
        }*/
    }

    public function index(){

        $todo_status = env('COMPLETEDSTATUS');

        $user = \Auth::user();
        $user_id = $user->id;
        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);
        $user_obj = new User();
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);
        $isStrategyCoordination = $user_obj::isStrategyCoordination($role_id);
        // get assigned to todos
        $assigned_todo_ids = ToDos::getTodoIdsByUserId($user->id);
        $owner_todo_ids = ToDos::getAllTaskOwnertodoIds($user->id);

        $todo_ids = array_merge($assigned_todo_ids,$owner_todo_ids);

        $todos = array();
        if(isset($todo_ids) && sizeof($todo_ids)>0){
            $todos = ToDos::getAllTodos($todo_ids);
        }

        $status = Status::getStatusArray();

        $count = sizeof($todos);

        return view('adminlte::toDo.index', array('todos' => $todos),compact('todo_status','user_id','isSuperAdmin','status','count','isStrategyCoordination'));

    }

    public function create(){

       // $candidate = CandidateBasicInfo::getCandidateArray();
        $users = User::getAllUsers();
        $status = Status::getStatusArray();
        $priority = ToDos::getPriority();
        $frequency_type = ToDos::getReminder();
        $in_progress = env('INPROGRESS');
        $yet_to_start = env('YETTOSTART');

        $user = \Auth::user();
        $user_id = $user->id;

        $user_role_id = User::getLoggedinUserRole($user);
        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $manager_role_id = env('MANAGER');
        $superadmin_role_id = env('SUPERADMIN');

        $selectedType = Input::get('selectedType');

        $typeArr[0] = array('id' => '','value'=>'Select' );

        // For Job Opening Details
        if($selectedType == 1){
            $access_roles_id = array($admin_role_id,$director_role_id,$manager_role_id,$superadmin_role_id);
            if(in_array($user_role_id,$access_roles_id)){
                $job_response = JobOpen::getAllJobs(1,$user_id);
            }
            else{
                $job_response = JobOpen::getAllJobs(0,$user_id);
            }

            $jobopen = array();
            $jobopen[0] = 'Select';
            $i = 1;
            foreach ($job_response as $k=>$v){
                $typeArr[$i]['id'] = $v['id'];
                $typeArr[$i]['value'] = $v['client']." - ".$v['location'];
                $i++;
            }

        }

        $todoTypeArr = array('1' => 'Job Opening', '2' =>  'Interview','3' => 'Client','4' => 'Candidate', '5' => 'Other');

        $selected_users = array();

        $viewVariable = array();
       // $viewVariable['candidate'] = $candidate;
        $viewVariable['client'] = $typeArr;
        $viewVariable['status'] = $status;
        $viewVariable['users'] = $users;
        $viewVariable['type'] = $todoTypeArr;
        $viewVariable['priority'] = $priority;
        $viewVariable['frequency_type'] = $frequency_type;
        $viewVariable['selected_users'] = $selected_users;
        $viewVariable['assigned_by'] = $users;
        $viewVariable['assigned_by_id'] = $user_id;
        $viewVariable['action'] = 'add';
        $viewVariable['type_list'] ='';
        $viewVariable['status_id'] = $yet_to_start;
        $viewVariable['reminder_id'] = '';
        $viewVariable['cc_user_id'] = '' ;

        return view('adminlte::toDo.create', $viewVariable);
    }

    public function store(Request $request){

        $user_id = \Auth::user()->id;

        $dateClass = new Date();

        $task_owner = $request->assigned_by;
        $subject = $request->subject;
        $candidate = $request->candidate_id;
        $due_date = $request->due_date;
        $formattedDueDate = $dateClass->changeDMYHMStoYMDHMS($due_date);
        $type = $request->type;
       // $typeList = $request->typeList;
        $typelist = $request->to;
        $status = $request->status;

        $cc_user_id=$request->cc_user;
        $priority = $request->priority;
        $description = $request->description;
        $users = $request->user_ids;

        $frequency_type = $request->frequency_type;
        $start_date = $dateClass->changeDMYHMStoYMDHMS($request->start_date);
      //  $assigned_by = $request->assigned_by;

/*
        $cc_email = User::getUserSecondaryEmailById($task_owner);

        $cc_user_email=User::getUserSecondaryEmailById($cc_user_id);

        $cc_users_array=array($cc_email,$cc_user_email);


        $i=0;

        if(isset($cc_users_array) && sizeof($cc_users_array) > 0)
        {
            foreach($cc_users_array as $key => $val)
            {
                $cc_array[$i] = trim($val);
                $i++;
            }
        }

        print_r($cc_array);
        exit;
*/
        $toDos = new ToDos();
        $toDos->subject = $subject;
        $toDos->task_owner = $task_owner;
        $toDos->due_date = $formattedDueDate;
        $toDos->candidate = $candidate;
        $toDos->status = $status;
        $toDos->type = $type;

        $toDos->priority = $priority;
        $toDos->description = $description;
        $toDos->start_date = $start_date;
        $toDos->cc_user = $cc_user_id;
        $validator = \Validator::make(Input::all(),$toDos::$rules);

        if($validator->fails()){
            return redirect('todos/create')->withInput(Input::all())->withErrors($validator->errors());
        }

        $toDosStored = $toDos->save();
        $toDos_id = $toDos->id;
        if($toDos_id){
            if(isset($users) && sizeof($users)>0){
                foreach ($users as $key=>$value){
                    $todo_users = new TodoAssignedUsers();
                    $todo_users->todo_id = $toDos_id;
                    $todo_users->user_id = $value;
                    $todo_users->save();
                }
            }

            if(isset($typelist) && sizeof($typelist)>0){
                foreach ($typelist as $key=>$value){
                    $todo_ass_list = new AssociatedTypeList();
                    $todo_ass_list->todo_id = $toDos_id;
                    $todo_ass_list->typelist_id = $value;
                    $todo_ass_list->save();
                }
            }

            if (isset($frequency_type) && $frequency_type!='') {
                    $todo_reminder = new TodoFrequency();
                    $todo_reminder->todo_id = $toDos_id;
                    $todo_reminder->reminder = $frequency_type;
                    if ($frequency_type == 1) {
                        $todo_reminder->reminder_date = date("Y-m-d", strtotime("$start_date tomorrow"));
                    }
                    elseif ($frequency_type == 2) {
                        $todo_reminder->reminder_date = date("Y-m-d", strtotime("$start_date +1 week"));
                    }
                    elseif ($frequency_type == 3) {
                        $todo_reminder->reminder_date = date("Y-m-d", strtotime("$start_date +1 month"));
                    }
                    elseif ($frequency_type == 4) {
                        $todo_reminder->reminder_date = date("Y-m-d", strtotime("$start_date  +3 month"));
                    }
                    elseif ($frequency_type == 5) {
                        $todo_reminder->reminder_date = date("Y-m-d", strtotime("$start_date +1 year"));   
                    }
                    $todo_reminder->save();
            }

        }

        if($toDos_id>0) {
            $toDos_id = $toDos->id;


            foreach ($users as $key=>$value){
                if($value!=$task_owner){
                    $user_arr = trim($value);

                    $assigned_to = User::getUserNameById($value);
                    $assigned_to_array = explode(" ", $assigned_to);
                    $assigned_to_name = $assigned_to_array[0];

                    if(isset($user_arr))
                    {
                        $module_id = $toDos_id;
                        $module = 'Todos';
                        $message = "$assigned_to_name: New task has been assigned to you";
                        $link = route('todos.index');

                        event(new NotificationEvent($module_id, $module, $message, $link, $user_arr));

                        // TODO : Email Notification : data store in database
                        //$user_email = User::getUserEmailById($value);
                        //$cc_email = User::getUserEmailById($task_owner);

                        $user_email = User::getUserSecondaryEmailById($value);

                        $cc_email = User::getUserSecondaryEmailById($task_owner);

                        $cc_user_email=User::getUserSecondaryEmailById($cc_user_id);

                        $cc_users_array=array($cc_email,$cc_user_email);


                        /*$i=0;

                        if(isset($cc_users_array) && sizeof($cc_users_array) > 0)
                        {
                            foreach($cc_users_array as $key => $val)
                            {
                                $cc_array[$i] = trim($val);
                                $i++;
                            }
                        }*/

                        $module = "Todos";
                        $sender_name = $user_id;
                        $to = $user_email;
                        $cc = implode(",",$cc_users_array);
                        $subject = $message;
                        $body_message = "";
                        $module_id = $toDos_id;

                        event(new NotificationMail($module,$sender_name,$to,$subject,$body_message,$module_id,$cc));
                    }
                }
            }
        }

        return redirect()->route('todos.index')->with('success','ToDo Created Successfully');
    }

    public function show($id)
    {
       $dateClass = new Date();

       $toDos = ToDos::getShowTodo($id);
       $frequency_type = $toDos['frequency_type'];
       if ($frequency_type == 1) {
           $frequency_type = 'Daily';
       }
       else if ($frequency_type == 2) {
           $frequency_type = 'Weekly';
       }
       else if ($frequency_type == 3) {
           $frequency_type = 'Monthly';
       }
       else if ($frequency_type == 4) {
           $frequency_type = 'Quarterly';
       }
       else if ($frequency_type == 5) {
           $frequency_type = 'Yearly';
       }

       $type = $toDos['type'];
       if ($type == 1) {
           $type = 'Job Opening';
       }
       else if ($type == 2) {
           $type = 'Interview';
       }
       else if ($type == 3) {
           $type = 'Client';
       }
       else if ($type == 4) {
           $type = 'Candidate';
       }
       else if ($type == 5) {
           $type = 'Other';
       }
       
       return view('adminlte::toDo.show',compact('toDos','frequency_type','type'));
    }

    public function edit($id)
    {
        $dateClass = new Date();
        $user = \Auth::user();
        $user_id = $user->id;

        $toDos = ToDos::find($id);

        //$candidate = CandidateBasicInfo::getCandidateArray();
        $users = User::getAllUsers();
        //$client = ClientBasicinfo::getClientArray();
        $status = Status::getStatusArray();
        $priority = ToDos::getPriority();
        $frequency_type = ToDos::getReminder();

        // get assigned users list
        $assigned_users = TodoAssignedUsers::where('todo_id','=',$id)->get();
        $selected_users = array();
        if(isset($assigned_users) && sizeof($assigned_users)>0){
            foreach($assigned_users as $row){
                $selected_users[] = $row->user_id;
            }
        }

        $todo_frequency = TodoFrequency::where('todo_id','=',$id)->first();
        $reminder_id = array();
        if (isset($todo_frequency) && sizeof($todo_frequency)>0) {
            $reminder_id = $todo_frequency->reminder;
        }

        $todoTypeArr = array('1' => 'Job Opening', '2' =>  'Interview','3' => 'Client','4' => 'Candidate', '5' => 'Other');

        $viewVariable = array();
        $viewVariable['toDos'] = $toDos;
        $viewVariable['task_owner'] = $toDos->task_owner;
        //$viewVariable['candidate'] = $candidate;
        $viewVariable['client'] = array();
        $viewVariable['status'] = $status;
        $viewVariable['users'] = $users;
        $viewVariable['type'] = $todoTypeArr;
        $viewVariable['priority'] = $priority;
        $viewVariable['frequency_type'] = $frequency_type;
        $viewVariable['assigned_by'] = $users;
        $viewVariable['assigned_by_id'] = $toDos->task_owner;
        $viewVariable['action'] = 'edit';
        $viewVariable['selected_users'] = $selected_users;
       // $viewVariable['selected_typelist'] = $selected_typelist;
        $viewVariable['due_date']  = $dateClass->changeYMDHMStoDMYHMS($toDos->due_date);
        $viewVariable['users'] = $users;
        $viewVariable['type_list'] = $toDos->typeList;
        $viewVariable['status_id'] = $toDos->status;
        $viewVariable['cc_user_id'] = $toDos->cc_user;
        $viewVariable['reminder_id'] = $reminder_id;
        $viewVariable['start_date']  = $dateClass->changeYMDHMStoDMYHMS($toDos->start_date);
        //echo $reminder_id;exit;
//echo $toDos->typeList;exit;
        return view('adminlte::toDo.edit', $viewVariable);
    }

    public function update(Request $request, $id)
    {
          $user_id = \Auth::user()->id;

        $dateClass = new Date();

        $task_owner = $request->get('assigned_by');
        $subject = $request->get('subject');
        $candidate = $request->get('candidate_id');
        //$due_date = $request->get('due_date');
        //$formattedDueDate = $dateClass->changeDMYtoYMD($due_date);
        $due_date = $dateClass->changeDMYHMStoYMDHMS($request->get('due_date'));
        $type = $request->get('type');
        $typelist = $request->get('to');
        $status = $request->get('status');
        $cc_user_id = $request->get('cc_user');
        $priority = $request->get('priority');
        $description = $request->get('description');
        $frequency_type = $request->get('frequency_type');
       // $assigned_by = $request->get('assigned_by');
        $users = $request->user_ids;
        $start_date = $dateClass->changeDMYHMStoYMDHMS($request->get('start_date'));
        
        $toDos = ToDos::find($id);
        if(isset($task_owner))
            $toDos->task_owner = $task_owner;
        if(isset($subject))
            $toDos->subject = $subject;
        if(isset($candidate))
            $toDos->candidate = $candidate;
        if(isset($due_date))
            $toDos->due_date = $due_date;
        if(isset($type))
            $toDos->type =$type;
        if(isset($status))
            $toDos->status = $status;
        if(isset($cc_user_id))
            $toDos->cc_user = $cc_user_id;
        if(isset($priority))
            $toDos->priority = $priority;
        //if(isset($reminder))
           // $toDos->reminder = $reminder;
        if(isset($description))
            $toDos->description = $description;
       /* if(isset($assigned_by))
            $toDos->assigned_by =$assigned_by;*/
        if (isset($start_date)) 
            $toDos->start_date = $start_date;

        $validator = \Validator::make(Input::all(),$toDos::$rules);

        if($validator->fails()){
            return redirect('todos/'.$id.'/edit')->withInput(Input::all())->withErrors($validator->errors());
        }

        $toDosUpdated = $toDos->save();
        $todo_id = $toDos->id;

        TodoAssignedUsers::where('todo_id',$todo_id)->delete();
        AssociatedTypeList::where('todo_id',$todo_id)->delete();
        TodoFrequency::where('todo_id',$todo_id)->delete();
        if($todo_id){
            if(isset($users) && sizeof($users)>0){
                foreach ($users as $key=>$value){
                    $todo_users = new TodoAssignedUsers();
                    $todo_users->todo_id = $todo_id;
                    $todo_users->user_id = $value;
                    $todo_users->save();
                }
            }

            if(isset($typelist) && sizeof($typelist)>0){
                foreach ($typelist as $key=>$value){
                    $todo_ass_list = new AssociatedTypeList();
                    $todo_ass_list->todo_id = $todo_id;
                    $todo_ass_list->typelist_id = $value;
                    $todo_ass_list->save();
                }
            }

            if (isset($frequency_type) && $frequency_type!='') {
                    $todo_reminder = new TodoFrequency();
                    $todo_reminder->todo_id = $todo_id;
                    $todo_reminder->reminder = $frequency_type;
                    if ($frequency_type == 1) {
                        $todo_reminder->reminder_date = date("Y-m-d", strtotime("$start_date tomorrow"));
                    }
                    elseif ($frequency_type == 2) {
                        $todo_reminder->reminder_date = date("Y-m-d", strtotime("$start_date  +1 week"));
                    }
                    elseif ($frequency_type == 3) {
                        $todo_reminder->reminder_date = date("Y-m-d", strtotime("$start_date  +1 month"));
                    }
                    elseif ($frequency_type == 4) {
                        $todo_reminder->reminder_date = date("Y-m-d", strtotime("$start_date  +3 month"));
                    }
                    elseif ($frequency_type == 5) {
                        $todo_reminder->reminder_date = date("Y-m-d", strtotime("$start_date +1 year"));   
                    }
                    $todo_reminder->save();
            }




        }
        return redirect()->route('todos.index')->with('success','ToDo Updated Successfully');
    }

    public function destroy($id){
        TodoFrequency::where('todo_id',$id)->delete();
        AssociatedTypeList::where('todo_id',$id)->delete();
        TodoAssignedUsers::where('todo_id',$id)->delete();
        $todo = ToDos::where('id',$id)->delete();

        return redirect()->route('todos.index')->with('success','ToDo Deleted Successfully');
    }

    public function complete(Request $request, $id){

        $complete = env('COMPLETEDSTATUS');

        $todo_id = $request->get('id');
        $todo_status = ToDos::find($id);

        $todo = $complete;

        $todo_status->status = $todo;
        $todo_status->save();
       // print_r($todo_status);exit;

        return redirect()->route('todos.index')->with('success','ToDo Completed Successfully');
    }

    public function completetodo(Request $request){
        $todo_status = env('COMPLETEDSTATUS');

        $user = \Auth::user();
        $user_id = $user->id;
        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);
        $user_obj = new User();
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);
        $isStrategyCoordination = $user_obj::isStrategyCoordination($role_id);
        
        // get assigned to todos
        $assigned_todo_ids = ToDos::getTodoIdsByUserId($user->id);
        $owner_todo_ids = ToDos::getAllTaskOwnertodoIds($user->id);

        $todo_ids = array_merge($assigned_todo_ids,$owner_todo_ids);

        $todos = array();
        if(isset($todo_ids) && sizeof($todo_ids)>0){
            $todos = ToDos::getCompleteTodos($todo_ids);
        }

        $count = sizeof($todos);
        return view('adminlte::toDo.complete', array('todos' => $todos),compact('todo_status','user_id','count','isSuperAdmin','isStrategyCoordination'));

    }

    public function mytask(Request $request){
        $todo_status = env('COMPLETEDSTATUS');

        $user = \Auth::user();
        $user_id = $user->id;
        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);
        $user_obj = new User();
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);
        $isStrategyCoordination = $user_obj::isStrategyCoordination($role_id);
        
        // get assigned to todos
        $assigned_todo_ids = ToDos::getTodoIdsByUserId($user->id);
        $owner_todo_ids = ToDos::getAllTaskOwnertodoIds($user->id);

        $todo_ids = array_merge($assigned_todo_ids,$owner_todo_ids);

        $todos = array();
        if(isset($todo_ids) && sizeof($todo_ids)>0){
            $todos = ToDos::getMyTodos($todo_ids);
        }

        $count = sizeof($todos);

        return view('adminlte::toDo.mytask', array('todos' => $todos),compact('todo_status','user_id','count','isSuperAdmin','isStrategyCoordination'));
    }

    public function status(Request $request){
        $todostatus = $request->get('todostatus');
        $id = $request->get('id');
        //print_r($todostatus);exit;

        $status_todo = ToDos::find($id);

        $todos = '';
        if (isset($todostatus) && sizeof($todostatus)>0){

                 $todos = $todostatus;
            
        }
        $status_todo->status = $todos;
         
        $status_todo->save();

        return redirect()->route('todos.index')->with('success', 'Todo Status Updated successfully');
       
    }

    public function getType(){

        $user = \Auth::user();
        $user_id = $user->id;

        $user_role_id = User::getLoggedinUserRole($user);
        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $manager_role_id = env('MANAGER');
        $superadmin_role_id = env('SUPERADMIN');

        $selectedType = Input::get('selectedType');

        //$typeArr[0] = array('id' => '','value'=>'Select' );

        //$typeArr = array();
        // For Job Opening Details
        if($selectedType == 1){
            $access_roles_id = array($admin_role_id,$director_role_id,$manager_role_id,$superadmin_role_id);
            if(in_array($user_role_id,$access_roles_id)){
                $job_response = JobOpen::getAllJobs(1,$user_id);
            }
            else{
                $job_response = JobOpen::getAllJobs(0,$user_id);
            }

            $jobopen = array();
            $jobopen[0] = 'Select';
            $i = 0;
            foreach ($job_response as $k=>$v){
                $typeArr[$i]['id'] = $v['id'];
                $typeArr[$i]['value'] = $v['company_name']." - ".$v['posting_title']." - ".$v['location'];
                $i++;
            }

        } 
        // For Interview Details
        elseif($selectedType == 2) {
            $access_roles_id = array($admin_role_id,$director_role_id,$manager_role_id,$superadmin_role_id);
            if(in_array($user_role_id,$access_roles_id)){
                $typeDetails = Interview::getAllInterviews(1,$user_id);
            }
            else{
                $typeDetails = Interview::getAllInterviews(0,$user_id);
            }
            if(isset($typeDetails) && sizeof($typeDetails)>0){
                $i = 0;
                foreach ($typeDetails as $typeDetail) {
                    $typeArr[$i]['id'] = $typeDetail['id'];
                    $typeArr[$i]['value'] = $typeDetail['client_name']." - ".$typeDetail['posting_title']." - ".$typeDetail['city'];
                    $i++;
                }
            } else {
                $typeArr[0] = array('id' => '','value'=>'Select Type' );
            }
        } 
        // For Client Details
        elseif($selectedType == 3) {

                $user = \Auth::user();
                $userRole = $user->roles->pluck('id','id')->toArray();
                $role_id = key($userRole);

                $user_obj = new User();
                $user_id = $user->id;

                $user_role_id = User::getLoggedinUserRole($user);
                $admin_role_id = env('ADMIN');
                $director_role_id = env('DIRECTOR');
                $manager_role_id = env('MANAGER');
                $superadmin_role_id = env('SUPERADMIN');

                $access_roles_id = array($admin_role_id,$director_role_id,$manager_role_id,$superadmin_role_id);
                if(in_array($user_role_id,$access_roles_id)){
                    // get all clients
                    $typeDetails = ClientBasicinfo::getLoggedInUserClients(0);
                }
                else{
                    // get logged in user clients
                    $typeDetails = ClientBasicinfo::getLoggedInUserClients($user_id);
                }

                // $typeDetails = ClientBasicinfo::all();
                if(isset($typeDetails) && sizeof($typeDetails)>0){
                    $i = 0;
                    foreach ($typeDetails as $typeDetail) {
                        $typeArr[$i]['id'] = $typeDetail->id;
                        $typeArr[$i]['value'] = $typeDetail->name."-".$typeDetail->coordinator_name;
                        $i++;
                    }
            } else {
                $typeArr[0] = array('id' => '','value'=>'Select Type' );
            }
        }

        // For Candidate Details
         elseif($selectedType == 4) {
            $typeDetails = CandidateBasicInfo::all();
            if(isset($typeDetails) && sizeof($typeDetails)>0){
                $i = 0;
                foreach ($typeDetails as $typeDetail) {
                    $typeArr[$i]['id'] = $typeDetail->id;
                    $typeArr[$i]['value'] = $typeDetail->full_name;
                    $i++;
                }
            } else{
                $typeArr[0] = array('id' => '','value'=>'Select Type');
            }

         }

         else {
            $typeArr[0] = array('id' => '','value'=>'Select Type' );
        }

        return json_encode($typeArr);
    }

    public function getSelectedTypeList(){

        $user = \Auth::user();
        $user_id = $user->id;

        $user_role_id = User::getLoggedinUserRole($user);
        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $manager_role_id = env('MANAGER');
        $superadmin_role_id = env('SUPERADMIN');

        $selectedType = Input::get('selectedType');
        $toDoId = Input::get('toDoId');

        $selected_typeList = AssociatedTypeList::getAssociatedListByTodoId($toDoId);

        // For Job Opening Details
        $typeArr = array();
        if($selectedType == 1){
            $access_roles_id = array($admin_role_id,$director_role_id,$manager_role_id,$superadmin_role_id);
            if(in_array($user_role_id,$access_roles_id)){
                $job_response = JobOpen::getJobsByIds(1,explode(',',$selected_typeList));
            }
            else{
                $job_response = JobOpen::getJobsByIds(0,explode(',',$selected_typeList));
            }

            $jobopen = array();
            $jobopen[0] = 'Select';
            $i = 0;
            foreach ($job_response as $k=>$v){
                $typeArr[$i]['id'] = $v['id'];
                $typeArr[$i]['value'] =  $v['company_name']." - ".$v['posting_title']." - ".$v['location'];//$v['client']." - ".$v['location'];
                $i++;
            }
        }

        // For Interview Details
        elseif($selectedType == 2) {
            $typeDetails = Interview::getInterviewsByIds(explode(',',$selected_typeList));
            if(isset($typeDetails) && sizeof($typeDetails)>0){
                $i = 0;
                foreach ($typeDetails as $typeDetail) {
                    $typeArr[$i]['id'] = $typeDetail->id;
                    $typeArr[$i]['value'] = $typeDetail->client_name." - ".$typeDetail->posting_title." - ".$typeDetail->city;
                    $i++;
                }
            } /*else {
                $typeArr[0] = array('id' => '','value'=>'Select Type' );
            }*/
        }

        // For Client Details
        elseif($selectedType == 3) {

            $access_roles_id = array($admin_role_id,$director_role_id,$manager_role_id,$superadmin_role_id);
            if(in_array($user_role_id,$access_roles_id)){
                // get all clients
                $typeDetails = ClientBasicinfo::getClientsByIds(0,explode(',',$selected_typeList));
            }
            else{
                // get logged in user clients
                $typeDetails = ClientBasicinfo::getClientsByIds($user_id,explode(',',$selected_typeList));
            }

            if(isset($typeDetails) && sizeof($typeDetails)>0){
                $i = 0;
                foreach ($typeDetails as $typeDetail) {
                    $typeArr[$i]['id'] = $typeDetail->id;
                    $typeArr[$i]['value'] = $typeDetail->name."-".$typeDetail->coordinator_name;
                    $i++;
                }
            } /*else {
                $typeArr[0] = array('id' => '','value'=>'Select Type' );
            }*/
        }

        // For Candidate Details
        elseif($selectedType == 4) {
            $typeDetails = CandidateBasicInfo::getAllCandidatesById(explode(',',$selected_typeList));
            //print_r($typeDetails);exit;
            if(isset($typeDetails) && sizeof($typeDetails)>0){
                $i = 0;
                foreach ($typeDetails as $typeDetail) {
                    $typeArr[$i]['id'] = $typeDetail->id;
                    $typeArr[$i]['value'] = $typeDetail->full_name;
                    $i++;
                }
            }/* else{
                $typeArr[0] = array('id' => '','value'=>'Select Type');
            }*/

        }

        else {
            $typeArr[0] = array('id' => '','value'=>'Select Type' );
        }

        return json_encode($typeArr);

    }

    public function readTodos(){

        $user_id = \Auth::user()->id;

        ToDos::where('task_owner','=',$user_id);

        $response['returnvalue'] = 'valid';

        return json_encode($response);
    }

    public function getAjaxtodo(){

        $user_id = \Auth::user()->id;

        $todos = array();
        $assigned_todo_ids = ToDos::getTodoIdsByUserId($user_id);
        $owner_todo_ids = ToDos::getAllTaskOwnertodoIds($user_id);

        $todo_ids = array_merge($assigned_todo_ids,$owner_todo_ids);

        if(isset($todo_ids) && sizeof($todo_ids)>0){
            $todos = Todos::getAllTodosdash($todo_ids,15);
        }
//print_r($todos);exit;
        return json_encode($todos);
    }
}
