<?php

namespace App\Http\Controllers;

use App\CandidateBasicInfo;
use App\ClientBasicinfo;
use App\Date;
use App\Interview;
use App\JobOpen;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Queue\Jobs\Job;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class InterviewController extends Controller
{
    //

    public function index(){

        $user = \Auth::user();
        $user_role_id = User::getLoggedinUserRole($user);

        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $superadmin_role_id =  env('SUPERADMIN');
        $access_roles_id = array($admin_role_id,$director_role_id,$superadmin_role_id);
        if(in_array($user_role_id,$access_roles_id)){
            $interViews = Interview::getAllInterviews(1,$user->id);
        }
        else{
            $interViews = Interview::getAllInterviews(0,$user->id);
        }

        $count = sizeof($interViews);

        return view('adminlte::interview.index', array('interViews' => $interViews),compact('count'));
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
        $user_email = \Auth::user()->email;
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
        $data['about'] = $request->get('about');
        $data['interview_owner_id'] = $user_id;

        $interview = Interview::createInterview($data);

        $validator = \Validator::make(Input::all(),$interview::$rules);

        if($validator->fails()){
            return redirect('interview/create')->withInput(Input::all())->withErrors($validator->errors());
        }

        /**/

        $interviewStored = $interview->save();



/*      $from_name = getenv('FROM_NAME');
        $from_address = getenv('FROM_ADDRESS');

        $input['from_name'] = $from_name;
        $input['from_address'] = $from_address;
        $input['to'] = $user_email;

        // Candidate details
        $candidate_response  = CandidateBasicInfo::find($candidate_id);
        $cname = $candidate_response->full_name;

        // job Details
        $job_details = JobOpen::getJobById($posting_title);

        $input['cname'] = $cname;
        $input['city'] = $job_details['city'];
        $input['company_name'] = $job_details['company_name'];
        $input['company_url'] =$job_details['company_url'];
        $input['client_desc'] = $job_details['client_desc'];
        $input['job_designation'] = $job_details['posting_title'];
        $input['job_location'] = $job_details['job_location'];
        $input['job_description'] = $job_details['job_description'];
        $input['interview_date'] = $job_details['interview_date'];
        $input['interview_day'] = '';
        $input['interview_time'] = $job_details['interview_time'];
        $input['interview_location'] = $job_details['interview_location'];
        $input['contact_person'] = $job_details['contact_person'];

        \Mail::send('adminlte::emails.interviewcandidate', $input, function ($message) use($input) {
            $message->from($input['from_address'], $input['from_name']);
            $message->to($input['to'])->subject('Interview Details - '.$input['company_name'].' - '. $input['city']);
        });*/


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
        $viewVariable['about'] = $interview->about;
        $viewVariable['action'] = 'edit';
        $viewVariable['fromDateTime'] = $dateClass->changeYMDHMStoDMYHMS($interview->interview_date);
        $viewVariable['toDateTime'] = $dateClass->changeYMDHMStoDMYHMS($interview->to);

        return view('adminlte::interview.edit', $viewVariable,compact('user_id'));

    }

    public function update(Request $request, $id){
        $user = \Auth::user();

        $user_id = $user->id;
        $user_email = $user->email;

        $dateClass = new Date();

        $interview_name = $request->get('interview_name');
        $candidate_id = $request->get('candidate_id');
        $interviewer = $request->get('interviewer_id');
      //  $client = $request->get('client_id');
        $interview_date = $dateClass->changeDMYHMStoYMDHMS($request->get('interview_date'));
        //$to = $dateClass->changeYMDHMStoDMYHMS($request->get('to'));
        $location = $request->get('location');
        $comments = $request->get('comments');
        $posting_title = $request->get('posting_title');
        $type = $request->get('type');
        $status = $request->get('status');
        $about = $request->get('about');
        $interview_owner_id = $user_id;

        $interview = Interview::find($id);
        if(isset($interview_name))
            $interview->interview_name = $interview_name;
        if(isset($candidate_id))
            $interview->candidate_id = $candidate_id;
        if(isset($posting_title))
            $interview->posting_title = $posting_title;
        if(isset($interviewer))
            $interview->interviewer_id = $interviewer;
        if(isset($type))
            $interview->type = $type;
        if(isset($location))
            $interview->location = $location;
        if(isset($status))
            $interview->status = $status;
        if(isset($about))
            $interview->about = $about;
        if(isset($comments))
            $interview->comments = $comments;
        $interview->interview_owner_id = $interview_owner_id;
        $interview->interview_date = $interview_date;

        $validator = \Validator::make(Input::all(),$interview::$rules);

        if($validator->fails()){
            return redirect('interview/'.$id.'/edit')->withInput(Input::all())->withErrors($validator->errors());
        }

        $interviewUpdated = $interview->save();

        // Interview Schedule Mail

       /* $from_name = getenv('FROM_NAME');
        $from_address = getenv('FROM_ADDRESS');
        $to_address = 'meet@trajinfotech.com';

        $input['from_name'] = $from_name;
        $input['from_address'] = $from_address;
        $input['to'] = $to_address;

        // Candidate details

        $candidate_id = $request->get('candidate_id');
        $candidate_response  = CandidateBasicInfo::find($candidate_id);
        $cname = $candidate_response->full_name;
        $ccity = $candidate_response->city;
        $cmobile = $candidate_response->mobile;
        $cemail = $candidate_response->email;

        // job Details

        $posting_title = $request->get('posting_title');
        $job_details = JobOpen::getJobById($posting_title);

        $input['cname'] = $cname;
        $input['ccity'] = $ccity;
        $input['cmobile'] = $cmobile;
        $input['cemail'] = $cemail;
        $input['city'] = $job_details['city'];
        $input['company_name'] = $job_details['company_name'];
        $input['company_url'] =$job_details['company_url'];
        $input['client_desc'] = $job_details['client_desc'];
        $input['job_designation'] = $job_details['posting_title'];
        $input['job_location'] = $job_details['job_location'];
        $input['job_description'] = $job_details['job_description'];
        $input['interview_date'] = $job_details['interview_date'];
        $input['interview_day'] = '';
        $input['interview_time'] = $job_details['interview_time'];
        $input['interview_location'] = $job_details['interview_location'];
        $input['interview_type'] =$job_details['interview_type'];

        \Mail::send('adminlte::emails.interviewschedule', $input, function ($message) use($input) {
            $message->from($input['from_address'], $input['from_name']);
            $message->to($input['to'])->subject('Interview Schedule for '.$input['company_name'].' position in '. $input['city']);
        });*/

        // sent mail to logged in user about interview details

       /* $from_name = getenv('FROM_NAME');
        $from_address = getenv('FROM_ADDRESS');

        $input['from_name'] = $from_name;
        $input['from_address'] = $from_address;
        $input['to'] = $user_email;

        // Candidate details
        $candidate_response  = CandidateBasicInfo::find($candidate_id);
        $cname = $candidate_response->full_name;

        // job Details
        $job_details = JobOpen::getJobById($posting_title);

        $input['cname'] = $cname;
        $input['city'] = $job_details['city'];
        $input['company_name'] = $job_details['company_name'];
        $input['company_url'] =$job_details['company_url'];
        $input['client_desc'] = $job_details['client_desc'];
        $input['job_designation'] = $job_details['posting_title'];
        $input['job_location'] = $job_details['job_location'];
        $input['job_description'] = $job_details['job_description'];
        $input['interview_date'] = $job_details['interview_date'];
        $input['interview_day'] = '';
        $input['interview_time'] = $job_details['interview_time'];
        $input['interview_location'] = $job_details['interview_location'];
        $input['contact_person'] = $job_details['contact_person'];

        \Mail::send('adminlte::emails.interviewcandidate', $input, function ($message) use($input) {
            $message->from($input['from_address'], $input['from_name']);
            $message->to($input['to'])->subject('Interview Details - '.$input['company_name'].' - '. $input['city']);
        });*/

        return redirect()->route('interview.index')->with('success','Interview Updated Successfully');
    }

    public function show($id){

        $dateClass = new Date();

        $interviewDetails = Interview::join('candidate_basicinfo','candidate_basicinfo.id','=','interview.candidate_id')
            ->join('job_openings','job_openings.id','=','interview.posting_title')
            ->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id')
            ->leftjoin('users','users.id','=','interview.interviewer_id')
            ->select('interview.*', DB::raw('CONCAT(candidate_basicinfo.full_name) AS candidate_name'),
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
        $interview['about'] = $interviewDetails->about;
        $interview['interviewOwner'] = $interviewOwner;

        return view('adminlte::interview.show', $interview);
    }

    public function destroy($id){

        $interviewDelete = Interview::where('id',$id)->delete();

        return redirect()->route('interview.index')->with('success','Interview Deleted Successfully');

    }

    public function getClientInfos(){

        $job_id = $_GET['job_id'];

        // get client info
        $client = ClientBasicinfo::getClientAboutByJobId($job_id);

        echo json_encode($client);exit;

    }

}
