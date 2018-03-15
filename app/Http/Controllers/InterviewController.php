<?php

namespace App\Http\Controllers;

use App\CandidateBasicInfo;
use App\ClientBasicinfo;
use App\Date;
use App\Interview;
use App\JobOpen;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class InterviewController extends Controller
{
    //

    public function index(){

        $interViews = Interview::join('candidate_basicinfo','candidate_basicinfo.id','=','interview.candidate_id')
            
            ->Leftjoin('users','users.id','=','interview.interviewer_id')
            ->select('interview.*', DB::raw('CONCAT(candidate_basicinfo.fname, " ", candidate_basicinfo.lname) AS candidate_name'),
                        'users.name as interviewer_name')
            ->get();

        return view('adminlte::interview.index', array('interViews' => $interViews));
    }

    public function create(){

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

       /* $access_roles_id = array($admin_role_id,$director_role_id,$manager_role_id,$superadmin_role_id);
        if(in_array($user_role_id,$access_roles_id)){
            // get all clients
            $client_res = ClientBasicinfo::getLoggedInUserClients(0);
        }
        else{
            // get logged in user clients
            $client_res = ClientBasicinfo::getLoggedInUserClients($user_id);
        }

        $client = array();

        if (sizeof($client_res) > 0) {
            foreach ($client_res as $r) {
                $client[$r->id] = $r->name." - ".$r->coordinator_name;
            }
        }*/

        $access_roles_id = array($admin_role_id,$director_role_id,$manager_role_id,$superadmin_role_id);
        if(in_array($user_role_id,$access_roles_id)){
            $job_response = JobOpen::getAllJobs(1,$user_id);
        }
        else{
            $job_response = JobOpen::getAllJobs(0,$user_id);
        }

        $jobopen = array();
        $jobopen[0] = 'Select';
        foreach ($job_response as $k=>$v){
            $jobopen[$v['id']] = $v['company_name']." - ".$v['posting_title'].",".$v['location'];
        }


        $viewVariable = array();
        $viewVariable['candidate'] = CandidateBasicInfo::getCandidateArray();
        $viewVariable['interviewer_id'] = $user_id;
        $viewVariable['hidden_candidate_id'] = 0;
     //   $viewVariable['client'] = $client;
        $viewVariable['postingArray'] = $jobopen;
        //$viewVariable['interviewer'] = User::getInterviewerArray();
        $viewVariable['type'] = Interview::getTypeArray();
        $viewVariable['status'] = Interview::getInterviewStatus();
        $viewVariable['users'] = User::getAllUsers();
        $viewVariable['action'] = 'add';

        return view('adminlte::interview.create', $viewVariable,compact('user_id'));
    }

    public function store(Request $request){

        $user_id = \Auth::user()->id;
        $dateClass = new Date();

        $data = array();
        $data['interview_name'] = $request->get('interview_name');
        $data['candidate_id'] = $request->get('candidate_id');
        $data['interviewer_id'] = $request->get('interviewer_id');
       // $data['client'] = $request->get('client_id');
        $data['interview_date'] = $dateClass->changeDMYHMStoYMDHMS($request->get('interview_date'));
        $data['location'] = $request->get('location');
        $data['comments'] = $request->get('comments');
        $data['posting_title'] = $request->get('posting_title');
        $data['type'] = $request->get('type');
        $data['status'] = $request->get('status');
        $data['interview_owner_id'] = $user_id;

        $interview = Interview::createInterview($data);

        $validator = \Validator::make(Input::all(),$interview::$rules);

        if($validator->fails()){
            return redirect('interview/create')->withInput(Input::all())->withErrors($validator->errors());
        }

        $interviewStored = $interview->save();

        return redirect()->route('interview.index')->with('success','Interview Created Successfully');

    }

    public function edit($id){

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
            $job_response = JobOpen::getAllJobs(1,$user_id);
        }
        else{
            $job_response = JobOpen::getAllJobs(0,$user_id);
        }

        $jobopen = array();
        $jobopen[0] = 'Select';
        foreach ($job_response as $k=>$v){
           $jobopen[$v['id']] = $v['company_name']." - ".$v['posting_title'].",".$v['location'];
        }

        $dateClass = new Date();
        $interview = Interview::find($id);

        $viewVariable = array();
        $viewVariable['interview'] = $interview;
        $viewVariable['interviewer_id'] = $interview->interviewer_id;
        $viewVariable['hidden_candidate_id'] = $interview->candidate_id;
        $viewVariable['candidate'] = CandidateBasicInfo::getCandidateArray();
      //  $viewVariable['client'] = $client;
        $viewVariable['postingArray'] = $jobopen;
        $viewVariable['interviewer'] = User::getInterviewerArray();
        $viewVariable['type'] = Interview::getTypeArray();
        $viewVariable['status'] = Interview::getInterviewStatus();
        $viewVariable['users'] = User::getAllUsers();
        $viewVariable['action'] = 'edit';
        $viewVariable['fromDateTime'] = $dateClass->changeYMDHMStoDMYHMS($interview->interview_date);
        $viewVariable['toDateTime'] = $dateClass->changeYMDHMStoDMYHMS($interview->to);

        return view('adminlte::interview.edit', $viewVariable,compact('user_id'));

    }

    public function update(Request $request, $id){
        $user_id = \Auth::user()->id;
        $dateClass = new Date();

        $interview_name = $request->get('interview_name');
        $candidate_id = $request->get('candidate_id');
        $interviewer = $request->get('interviewer_id');
      //  $client = $request->get('client_id');
        $from = $dateClass->changeYMDHMStoDMYHMS($request->get('interview_date'));
        $to = $dateClass->changeYMDHMStoDMYHMS($request->get('to'));
        $location = $request->get('location');
        $comments = $request->get('comments');
        $posting_title = $request->get('posting_title');
        $type = $request->get('type');
        $status = $request->get('status');
        $interview_owner_id = $user_id;

        $interview = Interview::find($id);
        if(isset($interview_name))
            $interview->interview_name = $interview_name;
        if(isset($candidate_id))
            $interview->candidate_id = $candidate_id;
       // if(isset($client))
        //    $interview->client_id = $client;
        if(isset($posting_title))
            $interview->posting_title = $posting_title;
        if(isset($interviewer))
            $interview->interviewer_id = $interviewer;
        if(isset($type))
            $interview->type = $type;
        if(isset($interview_date))
            $interview->from = $interview_date;
        if(isset($to))
            $interview->to = $to;
        if(isset($location))
            $interview->location = $location;
        if(isset($status))
            $interview->status = $status;
        if(isset($comments))
            $interview->comments = $comments;
        $interview->interview_owner_id = $interview_owner_id;


        $validator = \Validator::make(Input::all(),$interview::$rules);

        if($validator->fails()){
            return redirect('interview/'.$id.'/edit')->withInput(Input::all())->withErrors($validator->errors());
        }

        $interviewUpdated = $interview->save();

        return redirect()->route('interview.index')->with('success','Interview Updated Successfully');
    }

    public function show($id){

        $dateClass = new Date();

        $interviewDetails = Interview::join('candidate_basicinfo','candidate_basicinfo.id','=','interview.candidate_id')
            ->join('job_openings','job_openings.id','=','interview.posting_title')
            ->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id')
            ->leftjoin('users','users.id','=','interview.interviewer_id')
            ->select('interview.*', DB::raw('CONCAT(candidate_basicinfo.fname, " ", candidate_basicinfo.lname) AS candidate_name'),
                 'job_openings.posting_title as posting_title','users.name as interviewer_name','client_basicinfo.name as company_name','job_openings.city')
            ->where('interview.id','=',$id)
            ->first();

        $interviewer_id = $interviewDetails->interviewer_id;

        if(isset($interviewer_id)){
            $interviewOwnerDetails = User::find($interviewer_id);
            $interviewOwner = $interviewOwnerDetails->name;
        } else {
            $interviewOwner = null;
        }
        
        $interview = array();
        $interview['id'] = $id;
        $interview['interview_name'] = $interviewDetails->interview_name;
        $interview['candidate'] = $interviewDetails->candidate_name;
      //  $interview['client'] = $interviewDetails->client_name;
        $interview['posting_title'] = $interviewDetails->company_name." - ".$interviewDetails->posting_title.",".$interviewDetails->city;
        $interview['interviewer'] = $interviewDetails->interviewer_name;
        $interview['type'] = $interviewDetails->type;
        $interview['interview_date'] = $dateClass->changeYMDtoDMY($interviewDetails->interview_date);
        $interview['location'] = $interviewDetails->location;
        $interview['status'] = $interviewDetails->status;
        $interview['comments'] = $interviewDetails->comments;
        $interview['interviewOwner'] = $interviewOwner;

        return view('adminlte::interview.show', $interview);
    }

    public function destroy($id){

        $interviewDelete = Interview::where('id',$id)->delete();

        return redirect()->route('interview.index')->with('success','Interview Deleted Successfully');

    }

}
