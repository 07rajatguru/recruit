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
        $users = User::getAllUsers();
        $client = ClientBasicinfo::getClientArray();
        $status = Status::getStatusArray();
        $priority = ToDos::getPriority();


        $todoTypeArr = array('1' => 'Job Opening', '2' => 'Interview', '3' => 'Client','4' => 'Other');

        $viewVariable = array();
        $viewVariable['candidate'] = $candidate;
        $viewVariable['client'] = $client;
        $viewVariable['status'] = $status;
        $viewVariable['users'] = $users;
        $viewVariable['type'] = $todoTypeArr;
        $viewVariable['priority'] = $priority;
        $viewVariable['action'] = 'add';

        return view('adminlte::toDo.create', $viewVariable);
    }

    public function store(Request $request){

        $user_id = \Auth::user()->id;

        $dateClass = new Date();

        $subject = $request->subject;
        $candidate = $request->candidate;
        $due_date = $request->due_date;
        $formattedDueDate = $dateClass->changeDMYtoYMD($due_date);
        $type = $request->type;
        $typeList = $request->typeList;
        $status = $request->status;
        $priority = $request->priority;
        $description = $request->description;

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
//print_r($toDos);exit;
        $validator = \Validator::make(Input::all(),$toDos::$rules);

        if($validator->fails()){
//            print_r($validator->errors());exit;
            return redirect('todos/create')->withInput(Input::all())->withErrors($validator->errors());
        }

        $toDosStored = $toDos->save();

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

        $candidate = CandidateBasicInfo::getCandidateArray();
        $users = User::getAllUsers();
        $client = ClientBasicinfo::getClientArray();
        $status = Status::getStatusArray();
        $priority = ToDos::getPriority();


        $todoTypeArr = array('1' => 'Job Opening', '2' => 'Interview', '3' => 'Client','4' => 'Other');

        $toDos = ToDos::find($id);

        $viewVariable = array();
        $viewVariable['toDos'] = $toDos;
        $viewVariable['candidate'] = $candidate;
        $viewVariable['client'] = $client;
        $viewVariable['status'] = $status;
        $viewVariable['users'] = $users;
        $viewVariable['type'] = $todoTypeArr;
        $viewVariable['priority'] = $priority;
        $viewVariable['action'] = 'edit';
        $due_date = $dateClass->changeYMDtoDMY($toDos->due_date);
        $viewVariable['due_date'] = $due_date;

        return view('adminlte::toDo.edit', $viewVariable);
    }

    public function update(Request $request, $id)
    {
          $user_id = \Auth::user()->id;

        $dateClass = new Date();

        $subject = $request->get('subject');
        $candidate = $request->get('candidate');
        //$due_date = $request->get('due_date');
        //$formattedDueDate = $dateClass->changeDMYtoYMD($due_date);
        $due_date = $dateClass->changeDMYtoYMD($request->get('due_date'));
        $type = $request->get('type');
        $typeList = $request->get('typeList');
        $status = $request->get('status');
        $priority = $request->get('priority');
        $description = $request->get('description');

        
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

        return redirect()->route('todos.index')->with('success','ToDo Updated Successfully');
    }

    public function destroy($id){
        $todo = ToDos::where('id',$id)->delete();

        return redirect()->route('todos.index')->with('success','ToDo Deleted Successfully');
    }

    public function getType(){
        $selectedType = Input::get('selectedType');

//        $typeArr = array();
        $typeArr[0] = array('id' => '','value'=>'Select' );

        // For Job Opening Details
        if($selectedType == 1){

            
            $typeDetails = JobOpen::all();
            if(isset($typeDetails) && sizeof($typeDetails)>0){
                $i = 1;
                foreach ($typeDetails as $typeDetail) {
                    $typeArr[$i]['id'] = $typeDetail->id;
                    $typeArr[$i]['value'] = $typeDetail->posting_title."-".$typeDetail->company_name;
                    $i++;
                }
            } else {
                $typeArr[0] = array('id' => '','value'=>'Select Type' );
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
        } else {
            $typeArr[0] = array('id' => '','value'=>'Select Type' );
        }

        return json_encode($typeArr);
    }
}
