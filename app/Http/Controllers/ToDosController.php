<?php

namespace App\Http\Controllers;

use App\CandidateBasicInfo;
use App\CandidateStatus;
use App\ClientBasicinfo;
use App\Date;
use App\Events\NotificationEvent;
use App\Interview;
use App\JobOpen;
use App\Status;
use App\TodoAssignedUsers;
use App\ToDos;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class ToDosController extends Controller
{
    //
    public function index(){

        $todos = ToDos::all();

        return view('adminlte::toDo.index', array('todos' => $todos));

    }

    public function create(){

        $candidate = CandidateBasicInfo::getCandidateArray();
        $users = User::getAllUsers('recruiter');
        //$client = ClientBasicinfo::getClientArray();
        $status = Status::getStatusArray();
        $priority = ToDos::getPriority();

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

        $todoTypeArr = array('1' => 'Job Opening', '2' => 'Interview', '3' => 'Client','4' => 'Candidate','5' => 'Other');

        $selected_users = array();

        $viewVariable = array();
        $viewVariable['candidate'] = $candidate;
        $viewVariable['client'] = $typeArr;
        $viewVariable['status'] = $status;
        $viewVariable['users'] = $users;
        $viewVariable['type'] = $todoTypeArr;
        $viewVariable['priority'] = $priority;
        $viewVariable['selected_users'] = $selected_users;
        $viewVariable['action'] = 'add';
        $viewVariable['type_list'] ='';
        return view('adminlte::toDo.create', $viewVariable);
    }

    public function store(Request $request){

        $user_id = \Auth::user()->id;

        $dateClass = new Date();

        $subject = $request->subject;
        $candidate = $request->candidate_id;
        $due_date = $request->due_date;
        $formattedDueDate = $dateClass->changeDMYHMStoYMDHMS($due_date);
        $type = $request->type;
        $typeList = $request->typeList;
        $status = $request->status;
        $priority = $request->priority;
        $description = $request->description;
        $users = $request->user_ids;

        $toDos = new ToDos();
        $toDos->subject = $subject;
        $toDos->task_owner = $user_id;
        $toDos->due_date = $formattedDueDate;
        $toDos->candidate = $candidate;
        $toDos->status = $status;
        $toDos->type = $type;
        if(isset($typeList) && $typeList!=''){
            $toDos->typeList = $typeList;
        }

        $toDos->priority = $priority;
        $toDos->description = $description;

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

        $toDos = ToDos::find($id);

        $candidate = CandidateBasicInfo::getCandidateArray();
        $users = User::getAllUsers('recruiter');
        //$client = ClientBasicinfo::getClientArray();
        $status = Status::getStatusArray();
        $priority = ToDos::getPriority();

        // get assigned users list
        $assigned_users = TodoAssignedUsers::where('todo_id','=',$id)->get();
        $selected_users = array();
        if(isset($assigned_users) && sizeof($assigned_users)>0){
            foreach($assigned_users as $row){
                $selected_users[] = $row->user_id;
            }
        }


        $todoTypeArr = array('1' => 'Job Opening', '2' => 'Interview', '3' => 'Client','4' => 'Candidate','5' => 'Other');

        $viewVariable = array();
        $viewVariable['toDos'] = $toDos;
        $viewVariable['candidate'] = $candidate;
        $viewVariable['client'] = array();
        $viewVariable['status'] = $status;
        $viewVariable['users'] = $users;
        $viewVariable['type'] = $todoTypeArr;
        $viewVariable['priority'] = $priority;
        $viewVariable['action'] = 'edit';
        $viewVariable['selected_users'] = $selected_users;
        $viewVariable['due_date']  = $dateClass->changeYMDHMStoDMYHMS($toDos->due_date);
        $viewVariable['users'] = $users;
        $viewVariable['type_list'] = $toDos->typeList;
//echo $toDos->typeList;exit;
        return view('adminlte::toDo.edit', $viewVariable);
    }

    public function update(Request $request, $id)
    {
          $user_id = \Auth::user()->id;

        $dateClass = new Date();

        $subject = $request->get('subject');
        $candidate = $request->get('candidate_id');
        //$due_date = $request->get('due_date');
        //$formattedDueDate = $dateClass->changeDMYtoYMD($due_date);
        $due_date = $dateClass->changeDMYHMStoYMDHMS($request->get('due_date'));
        $type = $request->get('type');
        $typeList = $request->get('typeList');
        $status = $request->get('status');
        $priority = $request->get('priority');
        $description = $request->get('description');
        $users = $request->user_ids;
        
        $toDos = ToDos::find($id);
        if(isset($subject))
            $toDos->subject = $subject;
        if(isset($candidate))
            $toDos->candidate = $candidate;
        if(isset($due_date))
            $toDos->due_date = $due_date;
        if(isset($type))
            $toDos->type =$type;
        if(isset($typeList))
            $toDos->typeList = $typeList;
        if(isset($status))
            $toDos->status = $status;
        if(isset($priority))
            $toDos->priority = $priority;
        if(isset($description))
            $toDos->description = $description;



        $validator = \Validator::make(Input::all(),$toDos::$rules);

        if($validator->fails()){
            return redirect('todos/edit')->withInput(Input::all())->withErrors($validator->errors());
        }

        $toDosUpdated = $toDos->save();
        $todo_id = $toDos->id;

        TodoAssignedUsers::where('todo_id',$todo_id)->delete();
        if($todo_id){
            if(isset($users) && sizeof($users)>0){
                foreach ($users as $key=>$value){
                    $todo_users = new TodoAssignedUsers();
                    $todo_users->todo_id = $todo_id;
                    $todo_users->user_id = $value;
                    $todo_users->save();
                }
            }
        }
        return redirect()->route('todos.index')->with('success','ToDo Updated Successfully');
    }

    public function destroy($id){
        $todo = ToDos::where('id',$id)->delete();

        return redirect()->route('todos.index')->with('success','ToDo Deleted Successfully');
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
        // For Interview Details
        elseif($selectedType == 2) {
            $typeDetails = Interview::all();
            if(isset($typeDetails) && sizeof($typeDetails)>0){
                $i = 1;
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
                    $i = 1;
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
                $i=1;
                foreach ($typeDetails as $typeDetail) {
                    $typeArr[$i]['id'] = $typeDetail->id;
                    $typeArr[$i]['value'] = $typeDetail->fname." ".$typeDetail->lname;
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
}
