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
use App\JobAssociateCandidates;
use App\RoleUser;

class InterviewController extends Controller
{
    public function index() {

        $user = \Auth::user();
        $all_perm = $user->can('display-interviews');
        $userwise_perm = $user->can('display-interviews-by-loggedin-user');

        // Year Data
        $starting_year = '2017';
        $ending_year = date('Y',strtotime('+2 year'));
        $year_array = array();

        for ($y = $starting_year; $y < $ending_year ; $y++) {
            $next = $y+1;
            $year_array[$y.'-4, '.$next.'-3'] = 'April-' .$y.' to March-'.$next;
        }

        if (isset($_POST['year']) && $_POST['year'] != '') {
            $year = $_POST['year'];
        }
        else {

            $y = date('Y');
            $m = date('m');
            if ($m > 3) {
                $n = $y + 1;
                $year = $y.'-4, '.$n.'-3';
            }
            else {
                $n = $y-1;
                $year = $n.'-4, '.$y.'-3';
            }
        }

        $year_data = explode(", ", $year);
        $year1 = $year_data[0];
        $year2 = $year_data[1];
        $current_year = date('Y-m-d',strtotime("first day of $year1"));
        $next_year = date('Y-m-d',strtotime("last day of $year2"));


        if($all_perm) {
            $count = Interview::getAllInterviewsCountByAjax(1,$user->id,'',$current_year,$next_year);
        }
        else if($userwise_perm) {
            $count = Interview::getAllInterviewsCountByAjax(0,$user->id,'',$current_year,$next_year);
        }

        $source = 'index';

        $interview_status = Interview::getEditInterviewStatus();

        return view('adminlte::interview.index', compact('count','source','year_array','year','interview_status'));
    }

    public function getInterviewOrderColumnName($order) {

        $order_column_name = '';

        if (isset($order) && $order >= 0) {
            if ($order == 0) {
                $order_column_name = "interview.id";
            }
            if ($order == 3) {
                $order_column_name = "job_openings.posting_title";
            }
            else if ($order == 4) {
                $order_column_name = "candidate_basicinfo.full_name";
            }
            else if ($order == 5) {
                $order_column_name = "candidate_basicinfo.mobile";
            }
            else if ($order == 6) {
                $order_column_name = "candidate_basicinfo.email";
            }
            else if ($order == 7) {
                $order_column_name = "interview.interview_date";
            }
            else if ($order == 8) {
                $order_column_name = "users.name";
            }
            else if ($order == 9) {
                $order_column_name = "interview.status";
            }
            else if ($order == 10) {
                $order_column_name = "interview.location";
            }
        }
        return $order_column_name;
    }

    //function for index using ajax call
    public function getAllInterviewsDetails() {

        $limit = $_GET['length'];
        $offset = $_GET['start'];
        $draw = $_GET['draw'];
        $search = $_GET['search']['value'];
        $order = $_GET['order'][0]['column'];
        $type = $_GET['order'][0]['dir'];

        // Year Data
        $starting_year = '2017';
        $ending_year = date('Y',strtotime('+2 year'));
        $year_array = array();
        $year_array[0] = "Select Year";

        for ($y = $starting_year; $y < $ending_year ; $y++) {
            $next = $y+1;
            $year_array[$y.'-4, '.$next.'-3'] = 'April-' .$y.' to March-'.$next;
        }

        if (isset($_GET['year']) && $_GET['year'] != '') {
            
            $year = $_GET['year'];

            if (isset($year) && $year != 0) {

                $year_data = explode(", ", $year);
                $year1 = $year_data[0];
                $year2 = $year_data[1];
                $current_year = date('Y-m-d',strtotime("first day of $year1"));
                $next_year = date('Y-m-d',strtotime("last day of $year2"));
            }
            else {
                $year = NULL;
                $current_year = NULL;
                $next_year = NULL;    
            }
        }
        else{
            $year = NULL;
            $current_year = NULL;
            $next_year = NULL;
        }

        $order_column_name = self::getInterviewOrderColumnName($order);

        $source = 'index';

        $user = \Auth::user();
        $all_perm = $user->can('display-interviews');
        $userwise_perm = $user->can('display-interviews-by-loggedin-user');
        $send_consolidated_schedule = $user->can('send-consolidated-schedule');
        $delete_perm = $user->can('interview-delete');

        if($all_perm) {
            $interViews = Interview::getAllInterviewsByAjax(1,$user->id,$limit,$offset,$search,$order_column_name,$type,$current_year,$next_year);
            $count = Interview::getAllInterviewsCountByAjax(1,$user->id,$search,$current_year,$next_year);
        }
        else if($userwise_perm) {
            $interViews = Interview::getAllInterviewsByAjax(0,$user->id,$limit,$offset,$search,$order_column_name,$type,$current_year,$next_year);
            $count = Interview::getAllInterviewsCountByAjax(0,$user->id,$search,$current_year,$next_year);
        }
        
        $interview = array();
        $i = 0;$j = 0;
        $interview_status = Interview::getEditInterviewStatus();

        foreach ($interViews as $key => $value) {

            $date = date('Y-m-d', strtotime('this week'));

            if(date("Y-m-d") == date("Y-m-d",strtotime($value['interview_date']))) {

                $color = "#8FB1D5";
            }
            elseif(date('Y-m-d', strtotime('tomorrow')) == date("Y-m-d",strtotime($value['interview_date']))) {

                $color = '#feb80a';
            }
            elseif(date('Y-m-d', strtotime($date)) > date("Y-m-d",strtotime($value['interview_date'])) || date('Y-m-d', strtotime($date.'+6days')) < date("Y-m-d",strtotime($value['interview_date']))) {

                $color = '#F08080';
            }
            else {
                $color = '#C4D79B';
            }

            $action = '';

            if($send_consolidated_schedule) {
                $checkbox = '<input type=checkbox name=interview_ids value='.$value['id'].' class=interview_ids id='.$value['id'].'/>';
            }
            else {
                $checkbox = '';
            }

            $posting_title = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'. $value['client_name'] . '-' . $value['posting_title'] . ', ' . $value['city'] . '</a>';
            $date = '<a style="color:black; text-decoration:none;">'. date('d-m-Y h:i A',strtotime($value['interview_date'])) . '</a>';

            $contact = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'. $value['contact'] . '</a>';
            $candidate_email = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'. $value['candidate_email'] . '</a>';
            
            $location = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'. $value['location'] . '</a>';
            $action .= '<a title="Show"  class="fa fa-circle" href="'. route('interview.show',$value['id']) .'" style="margin:3px;"></a>';
            $action .= '<a title="Edit" class="fa fa-edit" href="'.route('interview.edit',array($value['id'],'index')).'" style="margin:3px;"></a>';

            $status_view = \View::make('adminlte::partials.interviewtatus',['data' => $value, 'name' => 'interview','interview_status' => $interview_status,'year' => $year]);
            $status = $status_view->render();
            $action .= $status;

            if ($delete_perm) {
                $delete_view = \View::make('adminlte::partials.deleteInterview',['data' => $value, 'name' => 'interview', 'display_name'=>'Interview','source' => $source]);
                $delete = $delete_view->render();
                $action .= $delete;
            }

            $data = array(++$j,$checkbox,$action,$posting_title,$value['candidate_fname'],$contact,$candidate_email,$date,$value['candidate_owner'],$value['status'],$location,$color);
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
    public function today() {

        $time = 'today';
        $user = \Auth::user();
        
        $all_perm = $user->can('display-interviews');
        $userwise_perm = $user->can('display-interviews-by-loggedin-user');

        if($all_perm) {
            $count = Interview::getInterviewsCountByType(1,$user->id,$time,'');
        }
        else if($userwise_perm) {
            $count = Interview::getInterviewsCountByType(0,$user->id,$time,'');
        }

        $source = 'Todays';
        $interview_status = Interview::getEditInterviewStatus();

        return view('adminlte::interview.today',compact('count','source','interview_status'));
    }

    // Tomorrow Interview Page
    public function tomorrow() {

        $time = 'tomorrow';
        $user = \Auth::user();
        
        $all_perm = $user->can('display-interviews');
        $userwise_perm = $user->can('display-interviews-by-loggedin-user');
        
        if($all_perm) {
            $count = Interview::getInterviewsCountByType(1,$user->id,$time,'');
        }
        else if($userwise_perm) {
            $count = Interview::getInterviewsCountByType(0,$user->id,$time,'');
        }

        $source = 'Tomorrows';
        $interview_status = Interview::getEditInterviewStatus();

        return view('adminlte::interview.today',compact('count','source','interview_status'));
    }

    // This Week Interview Page
    public function thisweek() {

        $time = 'thisweek';
        $user = \Auth::user();
        
        $all_perm = $user->can('display-interviews');
        $userwise_perm = $user->can('display-interviews-by-loggedin-user');

        if($all_perm) {
            $count = Interview::getInterviewsCountByType(1,$user->id,$time,'');
        }
        else if($userwise_perm) {
            $count = Interview::getInterviewsCountByType(0,$user->id,$time,'');
        }

        $source = 'This Week';
        $interview_status = Interview::getEditInterviewStatus();

        return view('adminlte::interview.today',compact('count','source','interview_status'));
    }

    // Upcoming/Previous Interview Page
    public function UpcomingPrevious() {

        $time = 'upcomingprevious';
        $user = \Auth::user();
        
        $all_perm = $user->can('display-interviews');
        $userwise_perm = $user->can('display-interviews-by-loggedin-user');

        if($all_perm) {
            $count = Interview::getInterviewsCountByType(1,$user->id,$time,'');
        }
        else if($userwise_perm) {
            $count = Interview::getInterviewsCountByType(0,$user->id,$time,'');
        }

        $source = 'Upcoming & Previous';
        $interview_status = Interview::getEditInterviewStatus();

        return view('adminlte::interview.today',compact('count','source','interview_status'));
    }

    //function for index using ajax call
    public function getAllInterviewsDetailsByType() {

        $limit = $_GET['length'];
        $offset = $_GET['start'];
        $draw = $_GET['draw'];
        $search = $_GET['search']['value'];
        $order = $_GET['order'][0]['column'];
        $type = $_GET['order'][0]['dir'];
        $source = $_GET['source'];

        $order_column_name = self::getInterviewOrderColumnName($order);

        $user = \Auth::user();
        $all_perm = $user->can('display-interviews');
        $userwise_perm = $user->can('display-interviews-by-loggedin-user');
        $send_consolidated_schedule = $user->can('send-consolidated-schedule');
        $delete_perm = $user->can('interview-delete');

        if($source == 'Todays') {

            if($all_perm) {

                $interViews = Interview::getInterviewsByType(1,$user->id,'today',$limit,$offset,$search,$order_column_name,$type);
                $count = Interview::getInterviewsCountByType(1,$user->id,'today',$search);
            }
            else if($userwise_perm) {
                $interViews = Interview::getInterviewsByType(0,$user->id,'today',$limit,$offset,$search,$order_column_name,$type);
                $count = Interview::getInterviewsCountByType(0,$user->id,'today',$search);
            }
        }

        if($source == 'Tomorrows') {

            if($all_perm) {

                $interViews = Interview::getInterviewsByType(1,$user->id,'tomorrow',$limit,$offset,$search,$order_column_name,$type);
                $count = Interview::getInterviewsCountByType(1,$user->id,'tomorrow',$search);
            }
            else if($userwise_perm) {
                $interViews = Interview::getInterviewsByType(0,$user->id,'tomorrow',$limit,$offset,$search,$order_column_name,$type);
                $count = Interview::getInterviewsCountByType(0,$user->id,'tomorrow',$search);
            }
        }

        if($source == 'This Week') {

            if($all_perm) {

                $interViews = Interview::getInterviewsByType(1,$user->id,'thisweek',$limit,$offset,$search,$order_column_name,$type);
                $count = Interview::getInterviewsCountByType(1,$user->id,'thisweek',$search);
            }
            else if($userwise_perm) {
                $interViews = Interview::getInterviewsByType(0,$user->id,'thisweek',$limit,$offset,$search,$order_column_name,$type);
                $count = Interview::getInterviewsCountByType(0,$user->id,'thisweek',$search);
            }
        }

        if($source == 'Upcoming & Previous') {

            if($all_perm) {

                $interViews = Interview::getInterviewsByType(1,$user->id,'upcomingprevious',$limit,$offset,$search,$order_column_name,$type);
                $count = Interview::getInterviewsCountByType(1,$user->id,'upcomingprevious',$search);
            }
            else if($userwise_perm) {
                $interViews = Interview::getInterviewsByType(0,$user->id,'upcomingprevious',$limit,$offset,$search,$order_column_name,$type);
                $count = Interview::getInterviewsCountByType(0,$user->id,'upcomingprevious',$search);
            }
        }

        $interview = array();
        $i = 0;$j = 0;
        $interview_status = Interview::getEditInterviewStatus();

        foreach ($interViews as $key => $value) {

            $date = date('Y-m-d', strtotime('this week'));

            if(date("Y-m-d") == date("Y-m-d",strtotime($value['interview_date']))) {

                $color = "#8FB1D5";
            }
            elseif(date('Y-m-d', strtotime('tomorrow')) == date("Y-m-d",strtotime($value['interview_date']))) {

                $color = '#feb80a';
            }
            elseif(date('Y-m-d', strtotime($date)) > date("Y-m-d",strtotime($value['interview_date'])) || date('Y-m-d', strtotime($date.'+6days')) < date("Y-m-d",strtotime($value['interview_date']))) {

                $color = '#F08080';
            }
            else{

                $color = '#C4D79B';
            }

            $action = '';

            if($send_consolidated_schedule) {
                $checkbox = '<input type=checkbox name=interview_ids value='.$value['id'].' class=interview_ids id='.$value['id'].'/>';
            }
            else {
                $checkbox = '';
            }

            $posting_title = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'. $value['client_name'] . '-' . $value['posting_title'] . ', ' . $value['city'] . '</a>';

            $contact = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'. $value['contact'] . '</a>';

            $candidate_email = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'. $value['candidate_email'] . '</a>';

            $date = '<a style="color:black; text-decoration:none;">'. date('d-m-Y h:i A',strtotime($value['interview_date'])) . '</a>';

            $location = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'. $value['location'] . '</a>';

            $action .= '<a title="Show"  class="fa fa-circle" href="'. route('interview.show',$value['id']) .'" style="margin:3px;"></a>';
            $action .= '<a title="Edit" class="fa fa-edit" href="'.route('interview.edit',array($value['id'],'index')).'" style="margin:3px;"></a>';

            $status_view = \View::make('adminlte::partials.interviewtatus',['data' => $value, 'name' => 'interview', 'display_name'=>'Interview','interview_status' => $interview_status,'source' => $source]);
            $status = $status_view->render();
            $action .= $status;

            if ($delete_perm) {
                $delete_view = \View::make('adminlte::partials.deleteInterview',['data' => $value, 'name' => 'interview', 'display_name'=>'Interview','source' => $source]);
                $delete = $delete_view->render();
                $action .= $delete;
            }

            $data = array(++$j,$checkbox,$action,$posting_title,$value['candidate_fname'],$contact,$candidate_email,$date,$value['candidate_owner'],$value['status'],$location,$color);
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

    public function todaytomorrow($department_id) {

        $user = \Auth::user();
        
        $all_perm = $user->can('display-interviews');
        $userwise_perm = $user->can('display-interviews-by-loggedin-user');

        if($all_perm) {
            $todaytomorrow = Interview::getTodayTomorrowsInterviews(1,$user->id,$department_id);
        }
        else if($userwise_perm) {
            $todaytomorrow = Interview::getTodayTomorrowsInterviews(0,$user->id,$department_id);
        }

        $today_count = 0;
        $tomorrow_count = 0;

        if(isset($todaytomorrow) && sizeof ($todaytomorrow) > 0) {

            foreach ($todaytomorrow as $key => $value) {

                if(date("Y-m-d") == date("Y-m-d",strtotime($value['interview_date']))) {

                    $today_count++;
                }
                elseif(date('Y-m-d', strtotime('tomorrow')) == date("Y-m-d",strtotime($value['interview_date']))) {

                    $tomorrow_count++;
                }
            }
        }

        $count = sizeof($todaytomorrow);
        $source = 'tti';

        return view('adminlte::interview.todaytomorrow',compact('count','todaytomorrow','source','today_count','tomorrow_count'));
    }

    public function attendedinterview($month,$year,$department_id) {

        $user = \Auth::user();
        
        $all_perm = $user->can('display-interviews');
        $userwise_perm = $user->can('display-interviews-by-loggedin-user');

        if($department_id == 0) {

            if($all_perm) {
                $attended_interview = Interview::getAttendedInterviews(1,$user->id,$month,$year,$department_id);
            }
            else if($userwise_perm) {
                $attended_interview = Interview::getAttendedInterviews(0,$user->id,$month,$year,$department_id);
            }
        }
        else {
            $attended_interview = Interview::getAttendedInterviews(1,$user->id,$month,$year,$department_id);
        }

        $count = sizeof($attended_interview);
        $source = 'ai';

        return view('adminlte::interview.attendedinterview',compact('count','attended_interview','source'));
    }

    public function attendedinterviewBySelectedMonth($month,$year) {

        $user = \Auth::user();

        $attended_interview = Interview::getAttendedInterviews(1,$user->id,$month,$year,0);

        $count = sizeof($attended_interview);
        $source = 'ai';

        return view('adminlte::interview.attendedinterview',compact('count','attended_interview','source'));
    }

    public function create() {

        $user = \Auth::user();
        $user_id = $user->id;

        $all_jobs_perm = $user->can('display-jobs');
        $user_jobs_perm = $user->can('display-jobs-by-loggedin-user');

        if($all_jobs_perm){
            $job_response = JobOpen::getAllJobs(1,$user_id);
        }
        else if($user_jobs_perm) {
            $job_response = JobOpen::getAllJobs(0,$user_id);
        }

        $jobopen = array();
        $jobopen[''] = 'Select';

        foreach ($job_response as $k=>$v) {
            $jobopen[$v['id']] = $v['company_name']." - ".$v['posting_title'].", ".$v['city'];
        }

        $viewVariable = array();
        $viewVariable['interviewer_id'] = $user_id;
        $viewVariable['hidden_candidate_id'] = 0;
        $viewVariable['postingArray'] = $jobopen;
        $viewVariable['type'] = Interview::getTypeArray();
        $viewVariable['status'] = Interview::getCreateInterviewStatus();

        // Set users dropdown

        // Get HR Role id from env
        $hr_role_id = getenv('HR');

        // Get logged in user role id
        $get_role_id = RoleUser::getRoleIdByUserId($user_id);

        if($hr_role_id == $get_role_id) {

            $recruitment = getenv('RECRUITMENT');
            $hr_advisory = getenv('HRADVISORY');
            $operations = getenv('OPERATIONS');
            $management = getenv('MANAGEMENT');
            $type_array = array($recruitment,$hr_advisory,$operations,$management);
        }
        else {

            $recruitment = getenv('RECRUITMENT');
            $hr_advisory = getenv('HRADVISORY');
            $management = getenv('MANAGEMENT');
            $type_array = array($recruitment,$hr_advisory,$management);
        }

        $users_array = User::getAllUsers($type_array);
        $users = array();

        if(isset($users_array) && sizeof($users_array) > 0) {

            foreach ($users_array as $key => $value) {
               
               $user_details = User::getAllDetailsByUserID($key);

               if($user_details->type == '2') {
                    if($user_details->hr_adv_recruitemnt == 'Yes') {
                        $users[$key] = $value;
                    }
               }
               else {
                    $users[$key] = $value;
               }    
            }
        }

        $viewVariable['users'] = $users;
        $viewVariable['round'] = Interview::getSelectRound();
        $viewVariable['interview_round'] = '';
        $viewVariable['action'] = 'add';

        return view('adminlte::interview.create', $viewVariable,compact('user_id'));
    }

    public function store(Request $request) {

        $user_id = \Auth::user()->id;
        $user_name = \Auth::user()->name;
        $dateClass = new Date();
        $data = array();
       
        $data['candidate_id'] = $request->get('candidate_id');
        $data['interviewer_id'] = $request->get('interviewer_id');
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

        // Add in associated candidate table

        $response = JobAssociateCandidates::where('candidate_id',$candidate_id)->where('job_id',$posting_title)->first();

        if(isset($response) && $response != '') {

            $round = $data['round'];
            $today_date = date('Y-m-d');

            if($round == '1') {

                DB::statement("UPDATE job_associate_candidates SET status_id = '1',shortlisted = '1',shortlisted_date = '$today_date' where candidate_id = $candidate_id and job_id = $posting_title");
            }
            if($round == '2') {

                DB::statement("UPDATE job_associate_candidates SET status_id = '2',shortlisted = '2',shortlisted_date = '$today_date' where candidate_id = $candidate_id and job_id = $posting_title");
            }
            if($round == '3') {

                DB::statement("UPDATE job_associate_candidates SET status_id = '3',shortlisted = '3',shortlisted_date = '$today_date' where candidate_id = $candidate_id and job_id = $posting_title");
            }
        }

        return redirect()->route('interview.index')->with('success','Interview Scheduled Successfully.');
    }

    public function edit($id,$source) {

        $user = \Auth::user();
        $user_id = $user->id;

        $all_jobs_perm = $user->can('display-jobs');
        $user_jobs_perm = $user->can('display-jobs-by-loggedin-user');

        if($all_jobs_perm) {
            $job_response = JobOpen::getAllJobs(1,$user_id);
        }
        else if($user_jobs_perm) {
            $job_response = JobOpen::getAllJobs(0,$user_id);
        }

        $jobopen = array();
        $jobopen[0] = 'Select';

        foreach ($job_response as $k=>$v) {
           $jobopen[$v['id']] = $v['company_name']." - ".$v['posting_title'].", ".$v['city'];
        }

        $dateClass = new Date();
        $interview = Interview::find($id);
        $interview_round = $interview->select_round;

        $viewVariable = array();
        $viewVariable['interview'] = $interview;
        $viewVariable['interviewer_id'] = $interview->interviewer_id;
        $viewVariable['hidden_candidate_id'] = $interview->candidate_id;
        $viewVariable['candidate'] = CandidateBasicInfo::getCandidateArray();
        $viewVariable['postingArray'] = $jobopen;
        $viewVariable['interviewer'] = User::getInterviewerArray();
        $viewVariable['type'] = Interview::getTypeArray();
        $viewVariable['status'] = Interview::getEditInterviewStatus();
        $viewVariable['round'] = Interview::getSelectRound();

        // Set users dropdown
        
        // Get HR Role id from env
        $hr_role_id = getenv('HR');

        // Get logged in user role id
        $get_role_id = RoleUser::getRoleIdByUserId($user_id);

        if($hr_role_id == $get_role_id) {

            $recruitment = getenv('RECRUITMENT');
            $hr_advisory = getenv('HRADVISORY');
            $operations = getenv('OPERATIONS');
            $management = getenv('MANAGEMENT');
            $type_array = array($recruitment,$hr_advisory,$operations,$management);
        }
        else {

            $recruitment = getenv('RECRUITMENT');
            $hr_advisory = getenv('HRADVISORY');
            $management = getenv('MANAGEMENT');
            $type_array = array($recruitment,$hr_advisory,$management);
        }

        $users_array = User::getAllUsers($type_array);
        $users = array();

        if(isset($users_array) && sizeof($users_array) > 0) {

            foreach ($users_array as $key => $value) {
               
               $user_details = User::getAllDetailsByUserID($key);

               if($user_details->type == '2') {
                    if($user_details->hr_adv_recruitemnt == 'Yes') {
                        $users[$key] = $value;
                    }
               }
               else {
                    $users[$key] = $value;
               }    
            }
        }

        $viewVariable['users'] = $users;
        $viewVariable['about'] = $interview->about;
        $viewVariable['action'] = 'edit';
        $viewVariable['fromDateTime'] = $dateClass->changeYMDHMStoDMYHMS($interview->interview_date);
        $viewVariable['toDateTime'] = $dateClass->changeYMDHMStoDMYHMS($interview->to);
        $viewVariable['interview_round'] = $interview_round;

        return view('adminlte::interview.edit', $viewVariable,compact('user_id','source'));
    }

    public function update(Request $request, $id, $source) {

        $user = \Auth::user();
        $user_id = $user->id;
        $dateClass = new Date();

        $candidate_id = $request->get('candidate_id');
        $interviewer = $request->get('interviewer_id');
        $interview_date = $dateClass->changeDMYHMStoYMDHMS($request->get('interview_date'));
        $location = $request->get('location');
        $comments = $request->get('comments');
        $posting_title = $request->get('posting_title');
        $type = $request->get('type');
        $status = $request->get('status');
        $about = $request->get('about');
        //$interview_owner_id = $user_id;
        $skype_id = $request->get('skype_id');
        $round = $request->get('round');
        $candidate_location = $request->get('candidate_location');
        $interview_location = $request->get('interview_location');

        $source = $request->get('source');

        $interview = Interview::find($id);
        $pre_round = $interview->select_round;
      
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

        //$interview->interview_owner_id = $interview_owner_id;
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

        if($validator->fails()) {
            return redirect('interview/'.$id.'/edit')->withInput(Input::all())->withErrors($validator->errors());
        }

        $interviewUpdated = $interview->save();

        if ($pre_round != $round && $round > $pre_round) {
            // Interview Schedule Mail
            $scheduled_mail = Interview::getScheduleEmail($candidate_id,$posting_title,$id);

            if($round == '2') {

                // Add in associated candidate table

                $response = JobAssociateCandidates::where('candidate_id',$candidate_id)->where('job_id',$posting_title)->first();

                if(isset($response) && $response != '') {

                    $today_date = date('Y-m-d');

                    DB::statement("UPDATE job_associate_candidates SET status_id = '2',shortlisted = '2',shortlisted_date = '$today_date' where candidate_id = $candidate_id and job_id = $posting_title");
                }
            }

            if($round == '3') {

                // Add in associated candidate table

                $response = JobAssociateCandidates::where('candidate_id',$candidate_id)->where('job_id',$posting_title)->first();

                if(isset($response) && $response != '') {

                    $today_date = date('Y-m-d');

                    DB::statement("UPDATE job_associate_candidates SET status_id = '3',shortlisted = '3',shortlisted_date = '$today_date' where candidate_id = $candidate_id and job_id = $posting_title");
                }
            }
        }
        return redirect()->route('interview.index')->with('success','Interview Details Updated Successfully.');
    }

    public function show($id) {

        $dateClass = new Date();

        $interviewDetails = Interview::join('candidate_basicinfo','candidate_basicinfo.id','=','interview.candidate_id')
        ->join('job_openings','job_openings.id','=','interview.posting_title')
        ->join('client_basicinfo','client_basicinfo.id','=','job_openings.client_id')
        ->leftjoin('users','users.id','=','interview.interviewer_id')
        ->select('interview.*', DB::raw('CONCAT(candidate_basicinfo.full_name) AS candidate_name'),
        'job_openings.posting_title as posting_title','users.name as interviewer_name','client_basicinfo.name as company_name','job_openings.city','candidate_basicinfo.mobile as contact','job_openings.remote_working as remote_working')->where('interview.id','=',$id)->first();

        $interviewer_id = $interviewDetails->interviewer_id;

        if(isset($interviewer_id)) {
            $interviewOwnerDetails = User::find($interviewer_id);
            $interviewOwner = $interviewOwnerDetails->name;
        }
        else {
            $interviewOwner = null;
        }
        
        $interview = array();
        $interview['id'] = $id;
        $interview['candidate'] = $interviewDetails->candidate_name;
        $interview['contact'] = $interviewDetails->contact;
        $interview['posting_title'] = $interviewDetails->company_name." - ".$interviewDetails->posting_title.",".$interviewDetails->city;

        if($interviewDetails->remote_working == '1') {

            $interview['posting_title'] = $interviewDetails->company_name." - ".$interviewDetails->posting_title.","."Remote";
        }
        else {

            $interview['posting_title'] = $interviewDetails->company_name." - ".$interviewDetails->posting_title.",".$interviewDetails->city;
        }

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
        else {
            $interview_round = '';
        }

        $interview['interview_round'] = $interview_round;
        $interview['candidate_location'] = $interviewDetails->candidate_location;
        $interview['interview_location'] = $interviewDetails->interview_location;
        
        return view('adminlte::interview.show', $interview);
    }

    public function destroy($id,$source) {

        // Delete from notifications table
        Notifications::where('module','=','Interview')->where('module_id','=',$id)->delete();

        Interview::where('id',$id)->delete();

        if ($source == 'index') {
            return redirect()->route('interview.index')->with('success','Interview Deleted Successfully.');
        }
        else if ($source == 'tti') {
            return redirect()->route('interview.todaytomorrow')->with('success','Interview Deleted Successfully.');
        }
        else if($source == 'Upcoming & Previous') {

            return redirect()->route('interview.upcomingprevious')->with('success','Interview Deleted Successfully.');
        }
        else if($source == 'This Week') {

            return redirect()->route('interview.thisweek')->with('success','Interview Deleted Successfully.');
        }
        else if($source == 'Tomorrows') {

            return redirect()->route('interview.tomorrow')->with('success','Interview Deleted Successfully.');
        }
        else if($source == 'Todays') {

            return redirect()->route('interview.today')->with('success','Interview Deleted Successfully.');
        }
        else {

            return redirect()->route('interview.index')->with('success','Interview Deleted Successfully.');
        }
    }

    public function getClientInfos() {

        $job_id = $_GET['job_id'];

        // get client info
        $client = ClientBasicinfo::getClientAboutByJobId($job_id);

        echo json_encode($client);exit;
    }

    // For check wherther interview selected for multiple mail or not
    public function CheckIdsforMail() {

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
            $msg['err'] = '<b>Please Select Interview</b>';
            $msg['msg'] = "fail";
        }
        return $msg;
    }

    public function multipleInterviewScheduleMail() {

        $interview_ids = $_POST['inter_ids'];
        $subject = $_POST['subject'];

        if(isset($_POST['source']) && $_POST['source'] != '') {
            $source = $_POST['source'];
        }
        else {
            $source = '';
        }

        $interview_id = Interview::getInterviewIdInASCDate($interview_ids);

        $i = 0;
        foreach ($interview_id as $key => $value) {

            $interview[$i] = Interview::ScheduleMailMultiple($value);
            $to_address_client = array();
            $to_address_secondline_client = array();
            $type_array = array();
            $file_path = array();
            $j = 0;

            foreach ($interview as $key1 => $value1) {

                if(isset($value1['client_owner_email']) && $value1['client_owner_email'] != '') {
                    $to_address_client[$j] = $value1['client_owner_email'];
                }

                if(isset($value1['secondline_client_owner_email']) && $value1['secondline_client_owner_email'] != '') {
                    $to_address_secondline_client[$j] = $value1['secondline_client_owner_email'];
                }

                $type_array[$j] = $value1['interview_type'];
                $file_path[$j] = $value1['file_path'];
                $j++;
            }
            $i++;
        }

        $to_address = array_merge($to_address_client,$to_address_secondline_client);
        $to_address = array_unique($to_address);

        $to = implode(' ',$to_address);
        $from_name = getenv('FROM_NAME');
        $from_address = getenv('FROM_ADDRESS');
        $app_url = getenv('APP_URL');

        $input['from_name'] = $from_name;
        $input['from_address'] = $from_address;
        $input['to_address'] = $to_address;
        $input['app_url'] = $app_url;
        $input['interview_details'] = $interview;
        $input['subject'] = $subject;
        $input['type_string'] = implode(",", $type_array);
        $input['file_path'] = $file_path;

        \Mail::send('adminlte::emails.interviewmultipleschedule', $input, function ($message) use($input) {
            $message->from($input['from_address'], $input['from_name']);
            $message->to($input['to_address'])->subject($input['subject']);

            if (isset($input['file_path']) && sizeof($input['file_path']) > 0) {

                foreach ($input['file_path'] as $key => $value) {

                    if(isset($value) && $value != '') {
                        
                        foreach ($value as $k1 => $v1) {
                            $message->attach($v1);
                        }
                    }
                }
            }
        });

        if($source == 'Todays') {
            return redirect('/interview/today')->with('success','Interview Email Sent Successfully.');
        }
        else if($source == 'Tomorrows') {
            return redirect('/interview/tomorrow')->with('success','Interview Email Sent Successfully.');
        }
        else if($source == 'This Week') {
            return redirect('/interview/thisweek')->with('success','Interview Email Sent Successfully.');
        }
        else if($source == 'Upcoming & Previous') {
            return redirect('/interview/upcomingprevious')->with('success','Interview Email Sent Successfully.');
        }
        else {
            return redirect('/interview')->with('success','Interview Email Sent Successfully.');
        }
    }

    public  function multipleInterviewStatus() {

        $inter_ids = $_POST['multi_inter_ids'];

        $status = $_POST['status'];
        $updated_at = date('Y-m-d H:i:s');
        
        $inter_ids_array = explode(",", $inter_ids);

        // Get Source
        if(isset($_POST['source']) && $_POST['source'] != '') {
            $source = $_POST['source'];
        }
        else {
            $source = '';
        }

        // Get Year
        if(isset($_POST['multi_inter_year']) && $_POST['multi_inter_year'] != '') {
            $year = $_POST['multi_inter_year'];
        }
        else {
            $year = '';
        }

        foreach ($inter_ids_array as $key => $value) {

            DB::statement("UPDATE interview SET status = '$status', updated_at='$updated_at' where id=$value");
        }

        if(isset($year) && $year != '') {

           return redirect('/interview')->with('success', 'Interview Status Updated Successfully.')
           ->with('selected_year',$year);
        }
        else {

            if($source == 'Todays') {
                return redirect('/interview/today')->with('success','Interview Status Updated Successfully.');
            }
            else if($source == 'Tomorrows') {
                return redirect('/interview/tomorrow')->with('success','Interview Status Updated Successfully.');
            }
            else if($source == 'This Week') {
                return redirect('/interview/thisweek')->with('success','Interview Status Updated Successfully.');
            }
            else if($source == 'Upcoming & Previous') {
                return redirect('/interview/upcomingprevious')->with('success','Interview Status Updated Successfully.');
            }
            else {
                return redirect('/interview')->with('success','Interview Status Updated Successfully.');
            }
        }
    }

    public function status(Request $request) {

        $status = $request->get('status');
        $interview_id = $request->get('interview_id');

        // Get Selected Year
        $year = $request->get('year');
        $source = $request->get('source');

        $interview = Interview::find($interview_id);

        if (isset($status) && $status != '') {
            $interview->status = $status;
            $interview->save();
        }

        if(isset($year) && $year != '') {

           return redirect('/interview')->with('success', 'Interview Status Updated Successfully.')
           ->with('selected_year',$year);
        }
        else {

            if($source == 'Todays') {
                return redirect('/interview/today')->with('success','Interview Status Updated Successfully.');
            }
            else if($source == 'Tomorrows') {
                return redirect('/interview/tomorrow')->with('success','Interview Status Updated Successfully.');
            }
            else if($source == 'This Week') {
                return redirect('/interview/thisweek')->with('success','Interview Status Updated Successfully.');
            }
            else if($source == 'Upcoming & Previous') {
                return redirect('/interview/upcomingprevious')->with('success','Interview Status Updated Successfully.');
            }
            else {
                return redirect('/interview')->with('success','Interview Status Updated Successfully.');
            }
        }
    }
}