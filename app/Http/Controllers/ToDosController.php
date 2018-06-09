<?php

namespace App\Http\Controllers;

use App\AssociatedTypeList;
use App\CandidateBasicInfo;
use App\CandidateStatus;
use App\ClientBasicinfo;
use App\Date;
use App\Events\NotificationEvent;
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

        $today = date("Y-m-d h:i:s");
        $complete = env('COMPLETEDSTATUS');  

        $todo_frequency = TodoFrequency::join('to_dos','to_dos.id','=','todo_frequency.todo_id')
                                       //->join('todo_associated_users','todo_associated_users.todo_id','=','to_dos.id')
                                       ->leftjoin('todo_associated_typelist','todo_associated_typelist.todo_id','=','to_dos.id')
                                       ->select('todo_frequency.*','to_dos.id as id','to_dos.task_owner as task_owner','to_dos.subject as subject','to_dos.due_date as due_date','to_dos.status as status','to_dos.description as desc','to_dos.type as type','todo_associated_typelist.typelist_id as typelistid')
                                       //->where('todo_frequency.reminder_date',$today)
                                       ->where('todo_frequency.reminder','=',1)
                                       //->where('status','!=',$complete)
                                       ->get();

        /*$todo_users = ToDos::join('to_dos','to_dos.id','=','todo_associated_users.todo_id')
                                        ->select('todo_associated_users.user_id as userid')
                                        ->get();*/


        echo '<pre>'; print_r($todo_frequency);exit;

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
                }*/
            }
        }

        /*$todos = ToDos::join('todo_associated_users','todo_associated_users.todo_id', '=', 'to_dos.id')
                        ->leftjoin('todo_associated_typelist', 'todo_associated_typelist.todo_id', '=', 'to_dos.id')
                        ->select('to_dos.*', 'todo_associated_users.user_id as userid', 'todo_associated_typelist.typelist_id as typelistid')
                        ->where('to_dos.reminder','=',1)
                        ->get();

        //print_r($todos);exit;

        foreach ($todos as $todo) {
        $toDos = new ToDos();
        $toDos->subject = $todo->subject;
        $toDos->task_owner = $todo->task_owner;
        $toDos->due_date = $todo->due_date;
        $toDos->candidate = $todo->candidate;
        $toDos->status = $todo->status;
        $toDos->type = $todo->type;

        $toDos->reminder = $todo->reminder;
        $toDos->priority = $todo->priority;
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

        }
      }*/
    }

    public function weekly(){

        $today = date("Y-m-d h:i:s");

        $todo_frequency = TodoFrequency::join('to_dos','to_dos.id','=','todo_frequency.todo_id')
                                       ->join('todo_associated_users','todo_associated_users.todo_id','=','to_dos.id')
                                       ->leftjoin('todo_associated_typelist','todo_associated_typelist.todo_id','=','to_dos.id')
                                       ->select('todo_frequency.*','to_dos.id as id','to_dos.task_owner as task_owner','to_dos.subject as subject','to_dos.due_date as due_date','to_dos.status as status','to_dos.description as desc','to_dos.type as type','todo_associated_users.user_id as userid','todo_associated_typelist.typelist_id as typelistid')
                                       //->where('todo_frequency.reminder_date',$today)
                                       ->where('todo_frequency.reminder','=',2)
                                       ->get();
        echo '<pre>'; print_r($todo_frequency);exit;

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
                }*/
            }
        }

        /*$todos = ToDos::join('todo_associated_users','todo_associated_users.todo_id', '=', 'to_dos.id')
                        ->leftjoin('todo_associated_typelist', 'todo_associated_typelist.todo_id', '=', 'to_dos.id')
                        ->select('to_dos.*', 'todo_associated_users.user_id as userid', 'todo_associated_typelist.typelist_id as typelistid')
                        ->where('to_dos.reminder','=',2)
                        ->get();

       // print_r($todos);exit;

        foreach ($todos as $todo) {
        $toDos = new ToDos();
        $toDos->subject = $todo->subject;
        $toDos->task_owner = $todo->task_owner;
        $toDos->due_date = $todo->due_date;
        $toDos->candidate = $todo->candidate;
        $toDos->status = $todo->status;
        $toDos->type = $todo->type;

        $toDos->reminder = $todo->reminder;
        $toDos->priority = $todo->priority;
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

        }
      }*/
    }

    public function monthly(Request $request){

        $today = date("Y-m-d h:i:s");

        $todo_frequency = TodoFrequency::join('to_dos','to_dos.id','=','todo_frequency.todo_id')
                                       ->join('todo_associated_users','todo_associated_users.todo_id','=','to_dos.id')
                                       ->leftjoin('todo_associated_typelist','todo_associated_typelist.todo_id','=','to_dos.id')
                                       ->select('todo_frequency.*','to_dos.id as id','to_dos.task_owner as task_owner','to_dos.subject as subject','to_dos.due_date as due_date','to_dos.status as status','to_dos.description as desc','to_dos.type as type','todo_associated_users.user_id as userid','todo_associated_typelist.typelist_id as typelistid')
                                       //->where('todo_frequency.reminder_date',$today)
                                       ->where('todo_frequency.reminder','=',3)
                                       ->get();
        echo '<pre>'; print_r($todo_frequency);exit;

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
                }*/
            }
        }

        /*$todos = ToDos::join('todo_associated_users','todo_associated_users.todo_id', '=', 'to_dos.id')
                        ->leftjoin('todo_associated_typelist', 'todo_associated_typelist.todo_id', '=', 'to_dos.id')
                        ->select('to_dos.*', 'to_dos.task_owner as task_owner', 'to_dos.due_date as duedate', 'to_dos.candidate as candidate', 'to_dos.status as status', 'to_dos.type as type', 'to_dos.subject as subject', 'to_dos.priority as priority', 'to_dos.description as description', 'to_dos.reminder as reminder', 'todo_associated_users.user_id as userid', 'todo_associated_typelist.typelist_id as typelistid')
                        ->where('to_dos.reminder','=',3)
                        ->get();

        print_r($todos);exit;

       foreach ($todos as $todo) {
        $toDos = new ToDos();
        $toDos->subject = $todo->subject;
        $toDos->task_owner = $todo->task_owner;
        $toDos->due_date = $todo->due_date;
        $toDos->candidate = $todo->candidate;
        $toDos->status = $todo->status;
        $toDos->type = $todo->type;

        $toDos->reminder = $todo->reminder;
        $toDos->priority = $todo->priority;
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

        return view('adminlte::toDo.index', array('todos' => $todos),compact('todo_status','user_id','isSuperAdmin','status','count'));

    }

    public function create(){

        $candidate = CandidateBasicInfo::getCandidateArray();
        $users = User::getAllUsers();
        $status = Status::getStatusArray();
        $priority = ToDos::getPriority();
        $reminder = ToDos::getReminder();
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
                $typeArr[$i]['value'] = $v['posting_title']." - ".$v['company_name'];
                $i++;
            }

        }

        $todoTypeArr = array('1' => 'Job Opening', '2' =>  'Interview','3' => 'Client','4' => 'Candidate', '5' => 'Other');

        $selected_users = array();

        $viewVariable = array();
        $viewVariable['candidate'] = $candidate;
        $viewVariable['client'] = $typeArr;
        $viewVariable['status'] = $status;
        $viewVariable['users'] = $users;
        $viewVariable['type'] = $todoTypeArr;
        $viewVariable['priority'] = $priority;
        $viewVariable['reminder'] = $reminder;
        $viewVariable['selected_users'] = $selected_users;
        $viewVariable['assigned_by'] = $users;
        $viewVariable['assigned_by_id'] = $user_id;
        $viewVariable['action'] = 'add';
        $viewVariable['type_list'] ='';
        $viewVariable['status_id'] = $yet_to_start;
        $viewVariable['reminder_id'] = '';

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
        $priority = $request->priority;
        $description = $request->description;
        $users = $request->user_ids;
        $reminder = $request->reminder;
      //  $assigned_by = $request->assigned_by;

        $toDos = new ToDos();
        $toDos->subject = $subject;
        $toDos->task_owner = $task_owner;
        $toDos->due_date = $formattedDueDate;
        $toDos->candidate = $candidate;
        $toDos->status = $status;
        $toDos->type = $type;

        //$toDos-//>reminder = //$reminder;
        $toDos->priority = $priority;
        $toDos->description = $description;
        //$toDos->assigned_by = $assigned_by;

        $validator = \Validator::make(Input::all(),$toDos::$rules);

        if($validator->fails()){
            //print_r($validator->errors());exit;
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

            if (isset($reminder) && $reminder!='') {
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

        /*if($toDosStored) {
            $toDos_id = $toDos->id;
            if($candidate != $user_id){
                $module_id = $toDos_id;
                $module = 'Task is created for you';
                $message = "Create New Task";
                $link = route('todos.index');
//                $link = route('jobopen.show',$job_id);


                event(new NotificationEvent($module_id, $module, $message, $link, $candidate));
            }
        }*/

        return redirect()->route('todos.index')->with('success','ToDo Created Successfully');
    }

    public function show($id)
    {
       $dateClass = new Date();

       $todo =ToDos::find($id);
       return view('adminlte::toDo.show')->with('toDos', $todo);
    }

    public function edit($id)
    {
        $dateClass = new Date();
        $user = \Auth::user();
        $user_id = $user->id;

        $toDos = ToDos::find($id);

        $candidate = CandidateBasicInfo::getCandidateArray();
        $users = User::getAllUsers();
        //$client = ClientBasicinfo::getClientArray();
        $status = Status::getStatusArray();
        $priority = ToDos::getPriority();
        $reminder = ToDos::getReminder();

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
        $viewVariable['candidate'] = $candidate;
        $viewVariable['client'] = array();
        $viewVariable['status'] = $status;
        $viewVariable['users'] = $users;
        $viewVariable['type'] = $todoTypeArr;
        $viewVariable['priority'] = $priority;
        $viewVariable['reminder'] = $reminder;
        $viewVariable['assigned_by'] = $users;
        $viewVariable['assigned_by_id'] = $toDos->task_owner;
        $viewVariable['action'] = 'edit';
        $viewVariable['selected_users'] = $selected_users;
       // $viewVariable['selected_typelist'] = $selected_typelist;
        $viewVariable['due_date']  = $dateClass->changeYMDHMStoDMYHMS($toDos->due_date);
        $viewVariable['users'] = $users;
        $viewVariable['type_list'] = $toDos->typeList;
        $viewVariable['status_id'] = $toDos->status;
        $viewVariable['reminder_id'] = $reminder_id;
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
        $priority = $request->get('priority');
        $description = $request->get('description');
        $reminder = $request->get('reminder');
       // $assigned_by = $request->get('assigned_by');
        $users = $request->user_ids;
        
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
        if(isset($priority))
            $toDos->priority = $priority;
        //if(isset($reminder))
           // $toDos->reminder = $reminder;
        if(isset($description))
            $toDos->description = $description;
       /* if(isset($assigned_by))
            $toDos->assigned_by =$assigned_by;*/

        $validator = \Validator::make(Input::all(),$toDos::$rules);

        if($validator->fails()){
            return redirect('todos/edit')->withInput(Input::all())->withErrors($validator->errors());
        }

        $toDosUpdated = $toDos->save();
        $todo_id = $toDos->id;

        TodoAssignedUsers::where('todo_id',$todo_id)->delete();
        AssociatedTypeList::where('todo_id',$todo_id)->delete();
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

            /*if (isset($reminder) && $reminder!='') {
                    $todo_reminder = new TodoFrequency();
                    $todo_reminder->todo_id = $todo_id;
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
            }*/

        }
        return redirect()->route('todos.index')->with('success','ToDo Updated Successfully');
    }

    public function destroy($id){
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
        
        // get assigned to todos
        $assigned_todo_ids = ToDos::getTodoIdsByUserId($user->id);
        $owner_todo_ids = ToDos::getAllTaskOwnertodoIds($user->id);

        $todo_ids = array_merge($assigned_todo_ids,$owner_todo_ids);

        $todos = array();
        if(isset($todo_ids) && sizeof($todo_ids)>0){
            $todos = ToDos::getCompleteTodos($todo_ids);
        }

        $count = sizeof($todos);
        return view('adminlte::toDo.complete', array('todos' => $todos),compact('todo_status','user_id','count'));

    }

    public function mytask(Request $request){
        $todo_status = env('COMPLETEDSTATUS');

        $user = \Auth::user();
        $user_id = $user->id;
        
        // get assigned to todos
        $assigned_todo_ids = ToDos::getTodoIdsByUserId($user->id);
        $owner_todo_ids = ToDos::getAllTaskOwnertodoIds($user->id);

        $todo_ids = array_merge($assigned_todo_ids,$owner_todo_ids);

        $todos = array();
        if(isset($todo_ids) && sizeof($todo_ids)>0){
            $todos = ToDos::getMyTodos($todo_ids);
        }

        $count = sizeof($todos);

        return view('adminlte::toDo.mytask', array('todos' => $todos),compact('todo_status','user_id','count'));
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
                $typeArr[$i]['value'] = $v['posting_title']." - ".$v['company_name'];
                $i++;
            }

        } 
        // For Interview Details
        elseif($selectedType == 2) {
            $typeDetails = Interview::all();
            if(isset($typeDetails) && sizeof($typeDetails)>0){
                $i = 0;
                foreach ($typeDetails as $typeDetail) {
                    $typeArr[$i]['id'] = $typeDetail->id;
                    $typeArr[$i]['value'] = $typeDetail->interview_name;
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
                $typeArr[$i]['value'] = $v['posting_title']." - ".$v['company_name'];
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
                    $typeArr[$i]['value'] = $typeDetail->interview_name;
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
