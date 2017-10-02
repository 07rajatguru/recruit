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

        if($toDosStored) {
            $toDos_id = $toDos->id;
            if($candidate != $user_id){
                $module_id = $toDos_id;
                $module = 'Task is created for you';
                $message = "Create New Task";
                $link = route('todos.index');
//                $link = route('jobopen.show',$job_id);


                event(new NotificationEvent($module_id, $module, $message, $link, $candidate));
            }
        }

        return redirect()->route('todos.index')->with('success','ToDo Created Successfully');
    }

    public function getType(){
        $selectedType = Input::get('selectedType');

//        $typeArr = array();
        $typeArr[0] = array('id' => '','value'=>'Select' );

        if($selectedType == 1){
            $typeDetails = JobOpen::all();
            if(isset($typeDetails) && sizeof($typeDetails)>0){
                $i = 1;
                foreach ($typeDetails as $typeDetail) {
                    $typeArr[$i]['id'] = $typeDetail->id;
                    $typeArr[$i]['value'] = $typeDetail->posting_title;
                    $i++;
                }
            } else {
                $typeArr[0] = array('id' => '','value'=>'Select Type' );
            }
        } elseif($selectedType == 2) {
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
        } elseif($selectedType == 3) {
            $typeDetails = ClientBasicinfo::all();
            if(isset($typeDetails) && sizeof($typeDetails)>0){
                $i = 1;
                foreach ($typeDetails as $typeDetail) {
                    $typeArr[$i]['id'] = $typeDetail->id;
                    $typeArr[$i]['value'] = $typeDetail->name;
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
