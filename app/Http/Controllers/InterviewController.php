<?php

namespace App\Http\Controllers;

use App\CandidateBasicInfo;
use App\CandidateUploadedResume;
use App\ClientBasicinfo;
use App\Date;
use App\Interview;
use App\JobOpen;
use App\User;
use App\Events\NotificationEvent;
use Illuminate\Http\Request;
use Illuminate\Queue\Jobs\Job;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\Notifications;

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

        $source = 'index';

        return view('adminlte::interview.index', array('interViews' => $interViews),compact('count','source'));
    }

    public static function getInterviewOrderColumnName($order){
        $order_column_name = '';
        if (isset($order) && $order >= 0) {
            if ($order == 0) {
                $order_column_name = "interview.id";
            }
            if ($order == 2) {
                $order_column_name = "job_openings.posting_title";
            }
            else if ($order == 3) {
                $order_column_name = "candidate_basicinfo.full_name";
            }
            else if ($order == 4) {
                $order_column_name = "candidate_basicinfo.mobile";
            }
            else if ($order == 5) {
                $order_column_name = "interview.interview_date";
            }
            else if ($order == 6) {
                $order_column_name = "interview.location";
            }
            else if ($order == 7) {
                $order_column_name = "interview.status";
            }
            else if ($order == 8) {
                $order_column_name = "users.name";
            }
        }
        return $order_column_name;
    }

    //function for index using ajax call
    public function getAllInterviewsDetails(){

        $limit = $_GET['length'];
        $offset = $_GET['start'];
        $draw = $_GET['draw'];
        $search = $_GET['search']['value'];
        $order = $_GET['order'][0]['column'];
        $type = $_GET['order'][0]['dir'];

        $order_column_name = self::getInterviewOrderColumnName($order);

        $source = 'index';
        $user = \Auth::user();
        $user_role_id = User::getLoggedinUserRole($user);

        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $superadmin_role_id =  env('SUPERADMIN');
        $access_roles_id = array($admin_role_id,$director_role_id,$superadmin_role_id);
        if(in_array($user_role_id,$access_roles_id)){
            $interViews = Interview::getAllInterviewsByAjax(1,$user->id,$limit,$offset,$search,$order_column_name,$type);
            $count = Interview::getAllInterviewsCountByAjax(1,$user->id,$search);
        }
        else{
            $interViews = Interview::getAllInterviewsByAjax(0,$user->id,$limit,$offset,$search,$order_column_name,$type);
            $count = Interview::getAllInterviewsCountByAjax(0,$user->id,$search);
        }
        //print_r($interViews);exit;
        $interview = array();
        $i = 0;$j = 0;
        foreach ($interViews as $key => $value) {

            $date = date('Y-m-d', strtotime('this week'));
            if(date("Y-m-d") == date("Y-m-d",strtotime($value['interview_date'])))
                $color = "#8FB1D5";
            elseif(date('Y-m-d', strtotime('tomorrow')) == date("Y-m-d",strtotime($value['interview_date'])))
                $color = '#feb80a';
            elseif(date('Y-m-d', strtotime($date)) > date("Y-m-d",strtotime($value['interview_date'])) || date('Y-m-d', strtotime($date.'+6days')) < date("Y-m-d",strtotime($value['interview_date'])))
                $color = '';
            else
                $color = '#C4D79B';

            $action = '';
            $checkbox = '<input type=checkbox name=interview_ids value='.$value['id'].' class=interview_ids id='.$value['id'].'/>';

            $posting_title = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'. $value['client_name'] . '-' . $value['posting_title'] . ', ' . $value['city'] . '</a>';
            $date = '<a style="color:black; text-decoration:none;">'. date('d-m-Y h:i A',strtotime($value['interview_date'])) . '</a>';
            $location = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'. $value['location'] . '</a>';
            $action .= '<a title="Show"  class="fa fa-circle" href="'. route('interview.show',$value['id']) .'" style="margin:3px;"></a>';
            $action .= '<a title="Edit" class="fa fa-edit" href="'.route('interview.edit',array($value['id'],'index')).'" style="margin:3px;"></a>';

            $delete_view = \View::make('adminlte::partials.deleteInterview',['data' => $value, 'name' => 'interview', 'display_name'=>'Interview','source' => $source]);
            $delete = $delete_view->render();
            $action .= $delete;

            $data = array(++$j,$checkbox,$action,$posting_title,$value['candidate_fname'],$value['contact'],$date,$location,$value['status'],$value['candidate_owner'],$color);
            $interview[$i] = $data;
            $i++;
        }

        $json_data = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            "data" => $interview
        );

        echo json_encode($json_data);exit;
    }

    // Today Interview Page
    public function today(){

        $time = 'today';
        $user = \Auth::user();
        $user_role_id = User::getLoggedinUserRole($user);

        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $superadmin_role_id =  env('SUPERADMIN');
        $access_roles_id = array($admin_role_id,$director_role_id,$superadmin_role_id);
        if(in_array($user_role_id,$access_roles_id)){
            $interViews = Interview::getInterviewsByTime(1,$user->id,$time);
        }
        else{
            $interViews = Interview::getInterviewsByTime(0,$user->id,$time);
        }

        $count = sizeof($interViews);

        $source = 'Todays';

        return view('adminlte::interview.today', array('interViews' => $interViews),compact('count','source'));
    }

    // Tomorrow Interview Page
    public function tomorrow(){

        $time = 'tomorrow';
        $user = \Auth::user();
        $user_role_id = User::getLoggedinUserRole($user);

        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $superadmin_role_id =  env('SUPERADMIN');
        $access_roles_id = array($admin_role_id,$director_role_id,$superadmin_role_id);
        if(in_array($user_role_id,$access_roles_id)){
            $interViews = Interview::getInterviewsByTime(1,$user->id,$time);
        }
        else{
            $interViews = Interview::getInterviewsByTime(0,$user->id,$time);
        }

        $count = sizeof($interViews);

        $source = 'Tomorrows';

        return view('adminlte::interview.today', array('interViews' => $interViews),compact('count','source'));
    }

    // This Week Interview Page
    public function thisweek(){

        $time = 'thisweek';
        $user = \Auth::user();
        $user_role_id = User::getLoggedinUserRole($user);

        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $superadmin_role_id =  env('SUPERADMIN');
        $access_roles_id = array($admin_role_id,$director_role_id,$superadmin_role_id);
        if(in_array($user_role_id,$access_roles_id)){
            $interViews = Interview::getInterviewsByTime(1,$user->id,$time);
        }
        else{
            $interViews = Interview::getInterviewsByTime(0,$user->id,$time);
        }

        $count = sizeof($interViews);

        $source = 'This Week';

        return view('adminlte::interview.today', array('interViews' => $interViews),compact('count','source'));
    }

    // Upcoming/Previous Interview Page
    public function UpcomingPrevious(){

        $time = 'upcomingprevious';
        $user = \Auth::user();
        $user_role_id = User::getLoggedinUserRole($user);

        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $superadmin_role_id =  env('SUPERADMIN');
        $access_roles_id = array($admin_role_id,$director_role_id,$superadmin_role_id);
        if(in_array($user_role_id,$access_roles_id)){
            $interViews = Interview::getInterviewsByTime(1,$user->id,$time);
        }
        else{
            $interViews = Interview::getInterviewsByTime(0,$user->id,$time);
        }

        $count = sizeof($interViews);

        $source = 'Upcoming & Previous';

        return view('adminlte::interview.today', array('interViews' => $interViews),compact('count','source'));
    }

    public function todaytomorrow(){

        $user = \Auth::user();
        $user_role_id = User::getLoggedinUserRole($user);

        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $superadmin_role_id =  env('SUPERADMIN');
        $access_roles_id = array($admin_role_id,$director_role_id,$superadmin_role_id);
        if(in_array($user_role_id,$access_roles_id)){
            $todaytomorrow = Interview::getTodayTomorrowsInterviews(1,$user->id);
        }
        else{
            $todaytomorrow = Interview::getTodayTomorrowsInterviews(0,$user->id);
        }

        $count = sizeof($todaytomorrow);

        $source = 'tti';

        return view('adminlte::interview.todaytomorrow',compact('count','todaytomorrow','source'));
    }

    public function attendedinterview($month,$year){

        $user = \Auth::user();
        $user_role_id = User::getLoggedinUserRole($user);

        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $superadmin_role_id =  env('SUPERADMIN');
        $access_roles_id = array($admin_role_id,$director_role_id,$superadmin_role_id);
        if(in_array($user_role_id,$access_roles_id)){
            $attended_interview = Interview::getAttendedInterviews(1,$user->id,$month,$year);
        }
        else{
            $attended_interview = Interview::getAttendedInterviews(0,$user->id,$month,$year);
        }

        $count = sizeof($attended_interview);

        $source = 'ai';

        return view('adminlte::interview.attendedinterview',compact('count','attended_interview','source'));
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

        $access_roles_id = array($admin_role_id,$director_role_id/*,$manager_role_id*/,$superadmin_role_id);
        if(in_array($user_role_id,$access_roles_id)){
            $job_response = JobOpen::getAllJobs(1,$user_id);
        }
        else{
            $job_response = JobOpen::getAllJobs(0,$user_id);
        }

        $jobopen = array();
        $jobopen[''] = 'Select';
        foreach ($job_response as $k=>$v){
            $jobopen[$v['id']] = $v['company_name']." - ".$v['posting_title'].",".$v['location'];
        }


        $viewVariable = array();
        //$viewVariable['candidate'] = CandidateBasicInfo::getCandidateArray();
        $viewVariable['interviewer_id'] = $user_id;
        $viewVariable['hidden_candidate_id'] = 0;
     //   $viewVariable['client'] = $client;
        $viewVariable['postingArray'] = $jobopen;
        //$viewVariable['interviewer'] = User::getInterviewerArray();
        $viewVariable['type'] = Interview::getTypeArray();
        $viewVariable['status'] = Interview::getInterviewStatus();
        $viewVariable['users'] = User::getAllUsers();
        $viewVariable['round'] = Interview::getSelectRound();
        $viewVariable['interview_round'] = '';
        $viewVariable['action'] = 'add';

        return view('adminlte::interview.create', $viewVariable,compact('user_id'));
    }

    public function store(Request $request){

        $user_id = \Auth::user()->id;
        $user_name = \Auth::user()->name;
        $user_email = \Auth::user()->email;
        $dateClass = new Date();

        $data = array();
       // $data['interview_name'] = $request->get('interview_name');
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
        $data['skype_id'] = $request->get('skype_id');
        $data['round'] = $request->get('round');
        $data['candidate_location'] = $request->get('candidate_location');
        $data['interview_location'] = $request->get('interview_location');

        $interview = Interview::createInterview($data);

        $validator = \Validator::make(Input::all(),$interview::$rules);

        if($validator->fails()){
            return redirect('interview/create')->withInput(Input::all())->withErrors($validator->errors());
        }

        $interviewStored = $interview->save();
        $interview_id = $interview->id;

        $interviewDetails = Interview::getInterviewids($interview_id);

        $client_owner_id = $interviewDetails->client_owner_id;
        $candidate_owner_id = $interviewDetails->candidate_owner_id;

        // Notifications : On adding new Interview notify Super Admin, Client Owner & Candidate Owner via notification
        $module_id = $interview_id;
        $module = 'Interview';
        $message = $user_name . " has scheduled interview";
        $link = route('interview.show',$interview_id);

        $super_admin_userid = getenv('SUPERADMINUSERID');
        $user_arr = array();
        $user_arr[] = $super_admin_userid;
        $user_arr[] = $client_owner_id;
        $user_arr[] = $candidate_owner_id;

        event(new NotificationEvent($module_id, $module, $message, $link, $user_arr));

        // Interview Candidate Mail

        $candidate_id = $request->get('candidate_id');
        $posting_title = $request->get('posting_title');

        $candidate_mail = Interview::getCandidateEmail($user_id,$candidate_id,$posting_title,$interview_id);

        // Interview Schedule Mail
        $scheduled_mail = Interview::getScheduleEmail($candidate_id,$posting_title,$interview_id);


        return redirect()->route('interview.index')->with('success','Interview Created Successfully');

    }

    public function edit($id,$source){

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

        $access_roles_id = array($admin_role_id,$director_role_id/*,$manager_role_id*/,$superadmin_role_id);
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
        $interview_round = $interview->select_round;

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
        $viewVariable['round'] = Interview::getSelectRound();
        $viewVariable['users'] = User::getAllUsers();
        $viewVariable['about'] = $interview->about;
        $viewVariable['action'] = 'edit';
        $viewVariable['fromDateTime'] = $dateClass->changeYMDHMStoDMYHMS($interview->interview_date);
        $viewVariable['toDateTime'] = $dateClass->changeYMDHMStoDMYHMS($interview->to);
        $viewVariable['interview_round'] = $interview_round;

        return view('adminlte::interview.edit', $viewVariable,compact('user_id','source'));

    }

    public function update(Request $request, $id, $source){
        $user = \Auth::user();

        $user_id = $user->id;
        $user_email = $user->email;

        $dateClass = new Date();

        //$interview_name = $request->get('interview_name');
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
        $skype_id = $request->get('skype_id');
        $round = $request->get('round');
        $candidate_location = $request->get('candidate_location');
        $interview_location = $request->get('interview_location');

        $source = $request->get('source');

        $interview = Interview::find($id);
        $pre_round = $interview->select_round;
       // if(isset($interview_name))
        //    $interview->interview_name = $interview_name;
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
        if (isset($skype_id) && $skype_id != '') {
            $interview->skype_id = $skype_id;
        }
        if (isset($round) && $round != '') {
            $interview->select_round = $round;
        }
        if (isset($candidate_location) && $candidate_location != '') {
            $interview->candidate_location = $candidate_location;
        }
        if (isset($interview_location) && $interview_location != '') {
            $interview->interview_location = $interview_location;
        }

        $validator = \Validator::make(Input::all(),$interview::$rules);

        if($validator->fails()){
            return redirect('interview/'.$id.'/edit')->withInput(Input::all())->withErrors($validator->errors());
        }

        $interviewUpdated = $interview->save();

        if ($pre_round != $round && $round > $pre_round) {
            //print_r($pre_round.'-'.$round);exit;
            //$candidate_mail = Interview::getCandidateEmail($candidate_id,$posting_title,$id);

            // Interview Schedule Mail
            $scheduled_mail = Interview::getScheduleEmail($candidate_id,$posting_title,$id);
        }

        if ($source == 'index') {
            return redirect()->route('interview.index')->with('success','Interview Updated Successfully');
        }
        else if ($source == 'tti') {
            return redirect()->route('interview.todaytomorrow')->with('success','Interview Updated Successfully');
        }
        else {
            return redirect()->route('interview.attendedinterview')->with('success','Interview Updated Successfully');
        }

    }

    public function show($id){

        $dateClass = new Date();

        $interviewDetails = Interview::join('candidate_basicinfo','candidate_basicinfo.id','=','interview.candidate_id')
            ->join('job_openings','job_openings.id','=','interview.posting_title')
            ->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id')
            ->leftjoin('users','users.id','=','interview.interviewer_id')
            ->select('interview.*', DB::raw('CONCAT(candidate_basicinfo.full_name) AS candidate_name'),
                 'job_openings.posting_title as posting_title','users.name as interviewer_name','client_basicinfo.name as company_name','job_openings.city','candidate_basicinfo.mobile as contact')
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
       // $interview['interview_name'] = $interviewDetails->interview_name;
        $interview['candidate'] = $interviewDetails->candidate_name;
        $interview['contact'] = $interviewDetails->contact;
      //  $interview['client'] = $interviewDetails->client_name;
        $interview['posting_title'] = $interviewDetails->company_name." - ".$interviewDetails->posting_title.",".$interviewDetails->city;
        $interview['interviewer'] = $interviewDetails->interviewer_name;
        $interview['type'] = $interviewDetails->type;
        $interview['interview_date'] = $dateClass->changeYMDHMStoDMYHMS($interviewDetails->interview_date);
        $interview['location'] = $interviewDetails->location;
        $interview['status'] = $interviewDetails->status;
        $interview['comments'] = $interviewDetails->comments;
        $interview['about'] = $interviewDetails->about;
        $interview['interviewOwner'] = $interviewOwner;
        $interview['skype_id'] = $interviewDetails->skype_id;
        $interview['round'] = $interviewDetails->select_round;
        if ($interview['round'] == '1') {
            $interview_round = 'Round 1';
        }
        else if ($interview['round'] == '2') {
            $interview_round = 'Round 2';
        }
        else if ($interview['round'] == '3') {
            $interview_round = 'Final Round';
        }
        else{
            $interview_round = '';
        }
        $interview['interview_round'] = $interview_round;
        $interview['candidate_location'] = $interviewDetails->candidate_location;
        $interview['interview_location'] = $interviewDetails->interview_location;
        
        return view('adminlte::interview.show', $interview);
    }

    public function destroy($id,$source){

        // Delete from notifications table
        Notifications::where('module','=','Interview')
        ->where('module_id','=',$id)
        ->delete();

        $interviewDelete = Interview::where('id',$id)->delete();

        if ($source == 'index') {
            return redirect()->route('interview.index')->with('success','Interview Deleted Successfully');
        }
        else if ($source == 'tti') {
            return redirect()->route('interview.todaytomorrow')->with('success','Interview Deleted Successfully');
        }
        else {
            return redirect()->route('interview.attendedinterview')->with('success','Interview Deleted Successfully');
        }
    }

    public function getClientInfos(){

        $job_id = $_GET['job_id'];

        // get client info
        $client = ClientBasicinfo::getClientAboutByJobId($job_id);

        echo json_encode($client);exit;

    }

    // For check wherther interview selected for multiple mail or not
    public function CheckIdsforMail(){

        if (isset($_POST['interview_ids']) && $_POST['interview_ids'] != '') {
            $interview_ids = $_POST['interview_ids'];
        }

        if (isset($interview_ids) && sizeof($interview_ids) > 0) {
        
            $view = \View::make('adminlte::partials.interviewsubjectfield');
            $html = $view->render();

            $msg['success'] = 'success';
            $msg['mail'] = $html;
        }
        else{
            $msg['err'] = '<b>Please select interview</b>';
            $msg['msg'] = "fail";
        }

        return $msg;
    }

    public function multipleInterviewScheduleMail(){

        $interview_ids = $_POST['inter_ids'];
        $subject = $_POST['subject'];

        $interview_id = Interview::getInterviewIdInASCDate($interview_ids);

        $i = 0;
        foreach ($interview_id as $key => $value) {

            $interview[$i] = Interview::ScheduleMailMultiple($value);
            $to_address_client = array();
            $j = 0;
            foreach ($interview as $key1 => $value1) {
                $to_address_client[$j] = $value1['client_owner_email'];
                $j++;
            }
            /*$to_address_candidate = array();
            $k = 0;
            foreach ($interview as $key => $value) {
                $to_address_candidate[$k] = $value['candidate_owner_email'];
                $k++;
            }*/
            $i++;
        }
        $to_address = array_merge(/*$to_address_candidate,*/$to_address_client);
        $to = implode(' ',$to_address);
        //print_r($to);exit;
        $from_name = getenv('FROM_NAME');
        $from_address = getenv('FROM_ADDRESS');
        $app_url = getenv('APP_URL');

        $input['from_name'] = $from_name;
        $input['from_address'] = $from_address;
        $input['to_address'] = $to_address;
        $input['app_url'] = $app_url;
        $input['interview_details'] = $interview;
        $input['subject'] = $subject;
        // $input['company_name'] = $job_details['company_name'];
        // $input['city'] = $job_details['city'];

        \Mail::send('adminlte::emails.interviewmultipleschedule', $input, function ($message) use($input) {
            $message->from($input['from_address'], $input['from_name']);
            $message->to($input['to_address'])->subject($input['subject']);
        });

        return redirect('/interview');
    }

}
