<?php

namespace App\Http\Controllers;

use App\Interview;
use App\JobCandidateJoiningdate;
use App\ToDos;
use App\UsersLog;
use Illuminate\Http\Request;
use App\User;
use App\Date;
use App\JobOpen;
use App\JobAssociateCandidates;
use Excel;
use DB;
use Calendar;
use App\UserRemarks;
use App\UserBenchMark;
use App\WorkPlanning;
use App\UserLeave;
use App\Holidays;
use App\LateInEarlyGo;
use App\WorkFromHome;
use App\EmailsNotifications;
use App\Lead;
use App\Bills;
use App\ClientBasicinfo;
use App\LeaveDoc;
use App\CandidateBasicInfo;
use App\BillsDoc;
use App\Role;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use App\ClientTimeline;
use App\UsersEmailPwd;
use App\CandidateUploadedResume;
use App\EmailTemplate;
use App\Contactsphere;
use App\TicketsDiscussion;
use App\TicketsDiscussionDoc;
use App\TicketDiscussionPost;
use App\TicketsDiscussionPostDoc;
use App\WorkPlanningList;
use App\WorkPlanningPost;
use App\SpecifyHolidays;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {

        $this->middleware('auth');
    }

    public function login(Request $request) {

        $user = \Auth::user();

        // Entry of login
        $users_log = new UsersLog();
        $users_log->user_id = $user->id;
        $users_log->date = gmdate("Y-m-d");
        $users_log->time = gmdate("H:i:s");
        $users_log->type ='login';
        $users_log->created_at = gmdate("Y-m-d H:i:s");
        $users_log->updated_at = gmdate("Y-m-d H:i:s");
        $users_log->save();

        return redirect()->route('dashboard')->with('success', 'Login Successfully.');
    }

    public function logout(Request $request) {

        $user_id = \Auth::user()->id;

        // Entry of login
        $users_log = new UsersLog();
        $users_log->user_id = $user_id;
        $users_log->date = gmdate("Y-m-d");
        $users_log->time = gmdate("H:i:s");
        $users_log->type ='logout';
        $users_log->created_at = gmdate("Y-m-d H:i:s");
        $users_log->updated_at = gmdate("Y-m-d H:i:s");
        $users_log->save();

        return redirect()->route('dashboard')->with('success', 'Logout Successfully.');
    }

    public function dashboard() {

        $user = \Auth::user();
        $user_id =  $user->id;
        $allclient = getenv('ALLCLIENTVISIBLEUSERID');
        $strtegy = getenv('STRATEGYUSERID');
        $superadmin = getenv('SUPERADMINUSERID');
        $jasmine = getenv('JASMINEUSERID');

        $recruitment = getenv('RECRUITMENT');

        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);

        $user_obj = new User();
        $isClient = $user_obj::isClient($role_id);

        if($isClient) {
            return redirect()->route('jobopen.index');
        }

        $all_perm = $user->can('display-productivity-report-of-all-users');

        if($all_perm) {

            $users = User::getAllUsersForBenchmarkModal($recruitment);

            if(isset($users) && sizeof($users) > 0) {

                $users_array = array();
                $i=0;

                foreach ($users as $key => $value) {
                    $user_benchmark = UserBenchMark::getBenchMarkByUserID($key);

                    if(isset($user_benchmark) && sizeof($user_benchmark) > 0) {
                    }
                    else {
                        $users_array[$i] = $value;
                    }
                    $i++;
                }   

                if(isset($users_array) && sizeof($users_array) > 0) {

                    $users_name_string = implode(", ", $users_array);
                    $msg = 'Please Add User Benchmark of Users : ' . $users_name_string;
                }
                else {
                    $msg = '';
                }
            }
        }
        else {

            if($user_id == $allclient || $user_id == $strtegy || $user_id == $jasmine) {
                $msg = '';
            }
            else {
                $user_details = User::getAllDetailsByUserID($user_id);

                if($user_details->type == 'recruiter') {

                    $user_benchmark = UserBenchMark::getBenchMarkByUserID($user_id);

                    if(isset($user_benchmark) && sizeof($user_benchmark) > 0) {
                        $msg = '';
                    }
                    else {
                        $msg = "Please Contact to HR for add your benchmark.";
                    }
                }
                else {
                    $msg = '';
                }
            }
        }

        $display_all_count = $user->can('display-all-count');
        $display_userwise_count = $user->can('display-userwise-count');

        // get assigned to todos
        $assigned_todo_ids = ToDos::getTodoIdsByUserId($user_id);
        $owner_todo_ids = ToDos::getAllTaskOwnertodoIds($user_id);

        $todo_ids = array_merge($assigned_todo_ids,$owner_todo_ids);
        $toDos = array();

        if(isset($todo_ids) && sizeof($todo_ids)>0) {
            $toDos = ToDos::getAllTodosdash($todo_ids,7);
        }

        $date = date('Y-m-d');
        $month = date('m');
        $year = date('Y');

        if($display_all_count) {

            // Client Count
            $client = DB::table('client_basicinfo')
            ->whereRaw('MONTH(created_at) = ?',[$month])->whereRaw('YEAR(created_at) = ?',[$year])
            ->where('delete_client','=',0)->count();

            // Job Count
            $job = JobOpen::getAllJobsCount(1,$user_id,0,NULL,NULL,0);

            // Cvs Associated this month
            $associate_monthly_response = JobAssociateCandidates::getMonthlyReprtAssociate(0,$month,$year,0);
            $associate_count = $associate_monthly_response['cvs_cnt'];

            // Cvs Shortlisted this month
            $shortlisted_count = JobAssociateCandidates::getMonthlyReprtShortlisted(0,$month,$year,0);

            // Interview Attended this month
            $interview_attended_list = Interview::getAttendedInterviews(1,$user_id,$month,$year,0);
            $interview_attend = sizeof($interview_attended_list);

            // Candidate Join this month
            $candidatecount = JobCandidateJoiningdate::getJoiningCandidateByUserIdCountByMonthwise($user_id,1,$month,$year,0);

            // Interview Count
            $interviews = Interview::getDashboardInterviews(1,$user_id,0);
            $interviews_cnt = sizeof($interviews);
        }
        else if($display_userwise_count) {

            // Client Count
            $client = DB::table('client_basicinfo')
            ->whereRaw('MONTH(created_at) = ?',[$month])->whereRaw('YEAR(created_at) = ?',[$year])
            ->where('account_manager_id',$user_id)->where('delete_client','=',0)->count();

            // Job Count
            $job = JobOpen::getAllJobsCount(0,$user_id,0,NULL,NULL,0);

            $tanisha_user_id = getenv('TANISHAUSERID');

            if($user_id == $tanisha_user_id) {

                // Cvs Associated this month
                $associate_monthly_response = JobAssociateCandidates::getMonthlyReprtAssociate($tanisha_user_id,$month,$year,0);
                $associate_count = $associate_monthly_response['cvs_cnt'];

                // Cvs Shortlisted this month
                $shortlisted_count = JobAssociateCandidates::getMonthlyReprtShortlisted($tanisha_user_id,$month,$year,0);

                // Interview Attended this month
                $interview_attended_list = Interview::getAttendedInterviews(0,$tanisha_user_id,$month,$year,0);
                $interview_attend = sizeof($interview_attended_list);

                // Candidate Join this month
                $candidatecount = JobCandidateJoiningdate::getJoiningCandidateByUserIdCountByMonthwise($tanisha_user_id,0,$month,$year,0);

                // Interview Count
                $interviews = Interview::getDashboardInterviews(0,$tanisha_user_id,0);
                $interviews_cnt = sizeof($interviews);
            }
            else {

                // Cvs Associated this month
                $associate_monthly_response = JobAssociateCandidates::getMonthlyReprtAssociate($user_id,$month,$year,0);
                $associate_count = $associate_monthly_response['cvs_cnt'];

                // Cvs Shortlisted this month
                $shortlisted_count = JobAssociateCandidates::getMonthlyReprtShortlisted($user_id,$month,$year,0);

                // Interview Attended this month
                $interview_attended_list = Interview::getAttendedInterviews(0,$user_id,$month,$year,0);
                $interview_attend = sizeof($interview_attended_list);

                // Candidate Join this month
                $candidatecount = JobCandidateJoiningdate::getJoiningCandidateByUserIdCountByMonthwise($user_id,0,$month,$year,0);

                // Interview Count
                $interviews = Interview::getDashboardInterviews(0,$user_id,0);
                $interviews_cnt = sizeof($interviews);
            }
        }

        // Get User logs from log table

        $date = date('Y-m-d');
        $user_log_count = UsersLog::getUserLogsByIdDate($user_id,$date);

        if($user_log_count == 1) {

            // Get Birthday Dates Array of users

            $today_birthday = User::getAllUsersBirthDateString();

            if(isset($today_birthday) && $today_birthday != '') {

                $birthday_date_string = "Let's wish " . $today_birthday . " a very Happy Birthday today!";
            }
            else {

                $birthday_date_string = '';
            }

            // Get Work Anniversary Dates Array of users

            $today_work_ani = User::getAllUsersWorkAnniversaryDateString();

            if(isset($today_work_ani) && $today_work_ani != '') {

                $work_ani_date_string = "Today is " . $today_work_ani . " Work Anniversary!";
            }
            else {

                $work_ani_date_string = '';
            }
        }
        else {

            $birthday_date_string = '';
            $work_ani_date_string = '';
        }

        // Display Users Listing With Role Name & Profile Photo

        $dashboard_users = User::getDashboardUsers();

        $viewVariable = array();
        $viewVariable['toDos'] = $toDos;
        $viewVariable['interviews'] = $interviews;
        $viewVariable['interviewCount'] = $interviews_cnt;
        $viewVariable['jobCount'] = $job;
        $viewVariable['clientCount'] = $client;
        $viewVariable['candidatejoinCount'] = $candidatecount;
        $viewVariable['associatedCount'] = $associate_count;
        $viewVariable['interviewAttendCount'] = $interview_attend;
        $viewVariable['shortlisted_count'] = $shortlisted_count;
        $viewVariable['date'] = $date;
        $viewVariable['month'] = $month;
        $viewVariable['year'] = $year;
        $viewVariable['msg'] = $msg;
        $viewVariable['superadmin'] = $superadmin;
        $viewVariable['user_id'] = $user_id;
        $viewVariable['birthday_date_string'] = $birthday_date_string;
        $viewVariable['work_ani_date_string'] = $work_ani_date_string;
        $viewVariable['dashboard_users'] = $dashboard_users;
        $viewVariable['total_dashboard_users'] = sizeof($dashboard_users);
        $viewVariable['department_id'] = 0;

        return view('dashboard',$viewVariable);
    }

    public function dashboardMonthwise() {

        $user = \Auth::user();
        $user_id = $user->id;
        $display_monthwise = $user->can('display-month-wise-dashboard');
        $display_all_count = $user->can('display-all-count');
        $display_userwise_count = $user->can('display-userwise-count');
        $manager_user_id = getenv('MANAGERUSERID');
        
        if(isset($_POST['month']) && $_POST['month']!='') {
            $month = $_POST['month'];
        }
        else {
            $month = date("n");
        }
        if(isset($_POST['year']) && $_POST['year']!='') {
            $year = $_POST['year'];
        }
        else {
            $year = date("Y");
        }

        $month_array = array();
        for ($m=1; $m<=12; $m++) {
            $month_array[$m] = date('M', mktime(0,0,0,$m,1,$year));
        }

        // Year Data
        $starting_year = '2017';
        $ending_year = date('Y',strtotime('+2 year'));
        $year_array = array();

        for ($y = $starting_year; $y < $ending_year ; $y++) { 
            $year_array[$y] = $y;
        }

        if($display_monthwise) {

            if($display_all_count || $user_id == $manager_user_id) {

                // Client Count
                $clientCount = DB::table('client_basicinfo')
                ->whereRaw('MONTH(created_at) = ?',[$month])
                ->whereRaw('YEAR(created_at) = ?',[$year])
                ->where('delete_client','=',0)->count();

                // Job Count
                $jobCount = JobOpen::getAllJobsCount(1,$user_id,0,NULL,NULL,0);

                // Cvs Associated this month
                $associate_monthly_response = JobAssociateCandidates::getMonthlyReprtAssociate(0,$month,$year,0);
                $associatedCount = $associate_monthly_response['cvs_cnt'];

                // Cvs Shortlisted this month
                $shortlisted_count = JobAssociateCandidates::getMonthlyReprtShortlisted(0,$month,$year,0);

                // Interview Attended this month
                $interview_attended_list = Interview::getAttendedInterviews(1,$user_id,$month,$year,0);
                $interviewAttendCount = sizeof($interview_attended_list);

                // Candidate Join this month
                $candidatejoinCount = JobCandidateJoiningdate::getJoiningCandidateByUserIdCountByMonthwise($user_id,1,$month,$year,0);
            }
            else if($display_userwise_count) {

                // Client Count
                $clientCount = DB::table('client_basicinfo')
                ->whereRaw('MONTH(created_at) = ?',[$month])
                ->whereRaw('YEAR(created_at) = ?',[$year])
                ->where('account_manager_id',$user_id)->where('delete_client','=',0)->count();

                // Job Count
                $jobCount = JobOpen::getAllJobsCount(0,$user_id,0,NULL,NULL,0);

                // Cvs Associated this month
                $associate_monthly_response = JobAssociateCandidates::getMonthlyReprtAssociate($user_id,$month,$year,0);
                $associatedCount = $associate_monthly_response['cvs_cnt'];

                // Cvs Shortlisted this month
                $shortlisted_count = JobAssociateCandidates::getMonthlyReprtShortlisted($user_id,$month,$year,0);

                // Interview Attended this month
                $interview_attended_list = Interview::getAttendedInterviews(0,$user_id,$month,$year,0);
                $interviewAttendCount = sizeof($interview_attended_list);

                // Candidate Join this month
                $candidatejoinCount = JobCandidateJoiningdate::getJoiningCandidateByUserIdCountByMonthwise($user_id,0,$month,$year,0);
            }

            $viewVariable = array();
            $viewVariable['month'] = $month;
            $viewVariable['month_list'] = $month_array;
            $viewVariable['year'] = $year;
            $viewVariable['year_list'] = $year_array;
            $viewVariable['clientCount'] = $clientCount;
            $viewVariable['jobCount'] = $jobCount;
            $viewVariable['associatedCount'] = $associatedCount;
            $viewVariable['interviewAttendCount'] = $interviewAttendCount;
            $viewVariable['candidatejoinCount'] = $candidatejoinCount;
            $viewVariable['shortlisted_count'] = $shortlisted_count;

            return view('dashboardmonthwise',$viewVariable);
        }
        else {
            return view('errors.403');
        }
    }

    public function openToAllJob() {

        $user = \Auth::user();
        $user_id = $user->id;
        $display_jobs = $user->can('display-jobs-open-to-all');
        $department_id = 0;

        if($display_jobs) {
            $job_opened = JobOpen::getOpenToAllJobs(10,$user_id,$department_id);
        }
        else {
            $job_opened = array();
        }
        return json_encode($job_opened);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    public function index() {

        $user = \Auth::user();
        $user_id = $user->id;
        $display_attendance = $user->can('display-attendance-of-all-users-in-admin-panel');
        $display_attendance_by_user = $user->can('display-attendance-by-loggedin-user-in-admin-panel');

        if($display_attendance) {
            $users = User::getOtherUsers();
        }
        else if($display_attendance_by_user) {
            $users = User::getOtherUsers($user_id);
        }

        if(isset($_POST['month']) && $_POST['month']!='') {
            $month = $_POST['month'];
        }
        else {
            $month = date("n");
        }

        if(isset($_POST['year']) && $_POST['year']!='') {
            $year = $_POST['year'];
        }
        else {
            $year = date("Y");
        }

        $month_array =array();
        for ($m=1; $m<=12; $m++) {
            $month_array[$m] = date('M', mktime(0,0,0,$m,1,$year));
        }

        $starting_year = '2016';
        $ending_year = date('Y',strtotime('+3 year'));
        $year_array = array();
        for ($y=$starting_year; $y < $ending_year ; $y++) {
            $year_array[$y] = $y;
        }

        $list = array();
        for($d=1; $d<=31; $d++) {

            $time = mktime(12, 0, 0, $month, $d, $year);

            foreach ($users as $key => $value) {
              
                if (date('n', $time) == $month)
                    $list[$key][date('j', $time)]['login'] = '';

                $list[$key][date('j', $time)]['logout'] = '';
                $list[$key][date('j', $time)]['total'] = '';
                $list[$key][date('j', $time)]['remarks'] = '';
            }
        }

        if($display_attendance) {

            $response = UsersLog::getUsersAttendance(0,$month,$year);
            $user_remark = UserRemarks::getUserRemarksByUserid(0,$month,$year);
        }
        else if($display_attendance_by_user) {

            $response = UsersLog::getUsersAttendance($user_id,$month,$year);
            $user_remark = UserRemarks::getUserRemarksByUserid($user_id,$month,$year);
        }

        $date = new Date();
        if(sizeof($response)>0) {
            foreach ($response as $key => $value) {

                $login_time = $date->converttime($value->login);
                $logout_time = $date->converttime($value->logout);

                $combine_name = $value->first_name."-".$value->last_name;

                $list[$combine_name][date("j",strtotime($value->date))]['login'] = date("h:i A",$login_time);
                $list[$combine_name][date("j",strtotime($value->date))]['logout'] = date("h:i A",$logout_time);

                $total = ($logout_time - $login_time) / 60;

                $list[$combine_name][date("j",strtotime($value->date))]['total'] = date('H:i', mktime(0,$total));

                if (isset($user_remark) && sizeof($user_remark)>0) {

                    foreach ($user_remark as $k => $v) {

                        $split_month = date('n',strtotime($v['remark_date']));
                        $split_year = date('Y',strtotime($v['remark_date']));

                        if (($v['full_name'] == $combine_name) && ($v['remark_date'] == $value->date) && ($month == $split_month) && ($year == $split_year)) {

                            $list[$combine_name][$v['converted_date']]['remarks'] = $v['remarks'];
                        }
                        else {

                            if (($v['full_name'] == $combine_name) && ($month == $split_month) && ($year == $split_year)) {

                                $list[$combine_name][$v['converted_date']]['remarks'] = $v['remarks'];
                            }
                            else {

                                $list[$v['full_name']][$v['converted_date']]['remarks'] = $v['remarks'];
                            }
                        }
                    }
                }
            }
        }
        else{

            if (isset($user_remark) && sizeof($user_remark)>0) {
                foreach ($user_remark as $k => $v) {
                    $split_month = date('n',strtotime($v['remark_date']));
                    $split_year = date('Y',strtotime($v['remark_date']));
                    if (($month == $split_month) && ($year == $split_year)) {
                        $list[$v['full_name']][$v['converted_date']]['remarks'] = $v['remarks'];
                    }
                }
            }
        }

        // New List1
        $list1 = array();
        for($d1=1; $d1<=31; $d1++) {

            $time1=mktime(12, 0, 0, $month, $d1, $year);
            foreach ($users as $key => $value) {

                if (date('n', $time1)==$month)
                    $list1[$key][date('j S', $time1)]='';
            }
        }

        if(sizeof($list)>0) {

            foreach ($list as $key => $value) {

                if(sizeof($value)>0) {

                    $i=0;
                    foreach ($value as $key1 => $value1) {

                        if (isset($user_remark) && sizeof($user_remark)>0) {

                            foreach ($user_remark as $u_k1 => $u_v1) {

                                $split_month = date('n',strtotime($u_v1['remark_date']));
                                $split_year = date('Y',strtotime($u_v1['remark_date']));

                                if (($u_v1['full_name'] == $key) && ($u_v1['converted_date'] == $key1) && ($month == $split_month) && ($year == $split_year)) {
                                    
                                    $list1[$u_v1['full_name']][$u_v1['converted_date']][$u_v1['remark_date']][$i] = $u_v1['remarks'];
                                }
                                $i++;
                            }
                        }
                    }
                }
            }
        }

        $users_name = User::getAllUsersForRemarks(0,0);

        return view('home',array("list"=>$list,"list1"=>$list1,"month_list"=>$month_array,"year_list"=>$year_array,"month"=>$month,"year"=>$year,"user_remark"=>$user_remark),compact('users_name'));
    }

    public function userAttendance() {

        $loggedin_userid =  \Auth::user()->id;

        if(isset($_POST['selected_user_id']) && $_POST['selected_user_id'] != '') {

            $user_id = $_POST['selected_user_id'];

            // get selected user attendance for current month
            $response = UsersLog::getUsersAttendance($user_id,0,0);

            // get selected user remarks
            $user_remarks = UserRemarks::getUserRemarksByUserid($user_id,0,0);
        }
        else {

            // get logged in user attendance for current month
            $response = UsersLog::getUsersAttendance($loggedin_userid,0,0);

            // get logged in user remarks
            $user_remarks = UserRemarks::getUserRemarksByUserid($loggedin_userid,0,0);
        }

        $date = new Date();

        if($response->count()>0) {

            foreach ($response as $k=>$v) {
                $title = '';

                $login_time = $date->converttime($v->login);
                $logout_time = $date->converttime($v->logout);

                $total = ($logout_time - $login_time) / 60;

                $title = "Login : ". date('h:i A',$login_time);
                $title .= "\n Logout : ". date('h:i A',$logout_time);
                $title .= "\n Total : ".date('H:i', mktime(0,$total));

                // light yellow : FFFACD - between 8-9 hours
                // blue : B0E0E6 : more than or euqal to 9 hours
                // red : F08080 : less than 8 hours
                $color = '';
                if($total>=540){
                    $color= '#B0E0E6';
                }
                else if ($total>=480 && $total<540){
                    $color= '#FFFACD';
                }
                else{
                    $color= '#F08080';
                }
                // "Login:9:30 PM \n Logout:6:30 PM \n Total : 9 "
                $events[] = Calendar::event(
                    $title,
                    true,
                    $v->date,
                    $v->date,
                    null,
                    [
                        'color' => $color,
                    ]
                );
            }
        }

        foreach ($user_remarks as $k=>$v) {
            $title = '';

            $title .= $v['remarks'];
            $color = '#5cb85c';
            // Remarks
            $events[] = Calendar::event(
                $title,
                true,
                $v['remark_date'],
                $v['remark_date'],
                null,
                [
                    'color' => $color,
                ]
            );
        }

        $calendar = Calendar::addEvents($events);

        $users_name = User::getAllUsersForRemarks(0,0);
        
        return view('userattendance', compact('calendar','users_name'));
    }

    // Save User remarks in calendar
    public function storeUserRemarks(Request $request) {

        $name = $_POST['name'];

        $user = \Auth::user();
        $dateClass = new Date();

        if (isset($_POST['user_id']) && $_POST['user_id'] > 0) {
            $user_id = $_POST['user_id'];
        }
        else {
            $user_id = $user->id;
        }

        $date = $request->get('date');
        $remarks = $request->get('remarks');

        $user_remark = new UserRemarks();
        $user_remark->user_id = $user_id;
        $user_remark->date = $dateClass->changeDMYtoYMD($date);
        $user_remark->remarks = $remarks;
        $user_remark->save();

        if($name == 'UserAttendance') {
            return redirect('/userattendance')->with('success', 'Remarks Added Successfully.');
        }
        if($name == 'HomeAttendance') {
            return redirect('/home')->with('success', 'Remarks Added Successfully.');
        }
        else {
            return redirect('/users-attendance/'.$name)->with('success', 'Remarks Added Successfully.');
        }
    }

    public function calenderevent() {

        $data[] = array(
            'title' => 'login',
            'start' => date('Y-m-d'),
            'end' => date('Y-m-d')
        );
        return json_encode($data);
    }

    public function recruitmentDashboard() {

        $user = \Auth::user();
        $user_id =  $user->id;

        // get assigned to todos
        $assigned_todo_ids = ToDos::getTodoIdsByUserId($user_id);
        $owner_todo_ids = ToDos::getAllTaskOwnertodoIds($user_id);

        $todo_ids = array_merge($assigned_todo_ids,$owner_todo_ids);
        $toDos = array();

        if(isset($todo_ids) && sizeof($todo_ids)>0) {
            $toDos = ToDos::getAllTodosdash($todo_ids,7);
        }

        $month = date('m');
        $year = date('Y');
        $department_id = getenv('RECRUITMENT');

        // Client Count
        $client = DB::table('client_basicinfo')
        ->whereRaw('MONTH(created_at) = ?',[$month])->whereRaw('YEAR(created_at) = ?',[$year])
        ->where('delete_client','=',0)->where('department_id','=',$department_id)->count();

        // Job Count
        $job = JobOpen::getAllJobsCountByDepartment(1,$user_id,'',$department_id);

        // Cvs Associated this month
        $associate_monthly_response = JobAssociateCandidates::getMonthlyReprtAssociate(0,$month,$year,$department_id);
        $associate_count = $associate_monthly_response['cvs_cnt'];

        // Cvs Shortlisted this month
        $shortlisted_count = JobAssociateCandidates::getMonthlyReprtShortlisted(0,$month,$year,$department_id);

        // Interview Attended this month
        $interview_attended_list = Interview::getAttendedInterviews(1,$user_id,$month,$year,$department_id);
        $interview_attend = sizeof($interview_attended_list);

        // Candidate Join this month
        $candidatecount = JobCandidateJoiningdate::getJoiningCandidateByUserIdCountByMonthwise($user_id,1,$month,$year,$department_id);

        // Interview Count
        $interviews = Interview::getDashboardInterviews(1,$user_id,$department_id);
        $interviews_cnt = sizeof($interviews);

        $viewVariable = array();
        $viewVariable['toDos'] = $toDos;
        $viewVariable['interviews'] = $interviews;
        $viewVariable['interviewCount'] = $interviews_cnt;
        $viewVariable['jobCount'] = $job;
        $viewVariable['clientCount'] = $client;
        $viewVariable['candidatejoinCount'] = $candidatecount;
        $viewVariable['associatedCount'] = $associate_count;
        $viewVariable['interviewAttendCount'] = $interview_attend;
        $viewVariable['shortlisted_count'] = $shortlisted_count;
        $viewVariable['month'] = $month;
        $viewVariable['year'] = $year;
        $viewVariable['department_id'] = $department_id;

        return view('recruitment-dashboard',$viewVariable);
    }

    public function recruitmentOpentoAllJob() {

        $user = \Auth::user();
        $user_id = $user->id;
        $display_jobs = $user->can('display-jobs-open-to-all');

        $department_id = getenv('RECRUITMENT');

        if($display_jobs) {
            $job_opened = JobOpen::getOpenToAllJobs(10,$user_id,$department_id);
        }
        else {
            $job_opened = array();
        }
        return json_encode($job_opened);
    }

    public function hrAdvisoryDashboard() {

        $user = \Auth::user();
        $user_id =  $user->id;

        // get assigned to todos
        $assigned_todo_ids = ToDos::getTodoIdsByUserId($user_id);
        $owner_todo_ids = ToDos::getAllTaskOwnertodoIds($user_id);

        $todo_ids = array_merge($assigned_todo_ids,$owner_todo_ids);
        $toDos = array();

        if(isset($todo_ids) && sizeof($todo_ids)>0) {
            $toDos = ToDos::getAllTodosdash($todo_ids,7);
        }

        $month = date('m');
        $year = date('Y');
        $department_id = getenv('HRADVISORY');

        // Client Count
        $client = DB::table('client_basicinfo')
        ->whereRaw('MONTH(created_at) = ?',[$month])->whereRaw('YEAR(created_at) = ?',[$year])
        ->where('delete_client','=',0)->where('department_id','=',$department_id)->count();

        // Job Count
        $job = JobOpen::getAllJobsCountByDepartment(1,$user_id,'',$department_id);

        // Cvs Associated this month
        $associate_monthly_response = JobAssociateCandidates::getMonthlyReprtAssociate(0,$month,$year,$department_id);
        $associate_count = $associate_monthly_response['cvs_cnt'];

        // Cvs Shortlisted this month
        $shortlisted_count = JobAssociateCandidates::getMonthlyReprtShortlisted(0,$month,$year,$department_id);

        // Interview Attended this month
        $interview_attended_list = Interview::getAttendedInterviews(1,$user_id,$month,$year,$department_id);
            $interview_attend = sizeof($interview_attended_list);

        // Candidate Join this month
        $candidatecount = JobCandidateJoiningdate::getJoiningCandidateByUserIdCountByMonthwise($user_id,1,$month,$year,$department_id);

        // Interview Count
        $interviews = Interview::getDashboardInterviews(1,$user_id,$department_id);
        $interviews_cnt = sizeof($interviews);
        
        $viewVariable = array();
        $viewVariable['toDos'] = $toDos;
        $viewVariable['interviews'] = $interviews;
        $viewVariable['interviewCount'] = $interviews_cnt;
        $viewVariable['jobCount'] = $job;
        $viewVariable['clientCount'] = $client;
        $viewVariable['candidatejoinCount'] = $candidatecount;
        $viewVariable['associatedCount'] = $associate_count;
        $viewVariable['interviewAttendCount'] = $interview_attend;
        $viewVariable['shortlisted_count'] = $shortlisted_count;
        $viewVariable['month'] = $month;
        $viewVariable['year'] = $year;
        $viewVariable['department_id'] = $department_id;

        return view('hr-advisory-dashboard',$viewVariable);
    }

    public function hrAdvisoryOpentoAllJob() {

        $user = \Auth::user();
        $user_id = $user->id;
        $display_jobs = $user->can('display-jobs-open-to-all');

        $department_id = getenv('HRADVISORY');

        if($display_jobs) {
            $job_opened = JobOpen::getOpenToAllJobs(10,$user_id,$department_id);
        }
        else {
            $job_opened = array();
        }
        return json_encode($job_opened);
    }

    // ESS Module Functions

    public function employeeSelfService() {

        $user = \Auth::user();
        $user_id =  $user->id;

        if(isset($_POST['month']) && $_POST['month']!='') {
            $month = $_POST['month'];
        }
        else {
            $month = date("n");
        }

        if(isset($_POST['year']) && $_POST['year']!='') {
            $year = $_POST['year'];
        }
        else {
            $year = date("Y");
        }

        $month_array =array();
        for ($m=1; $m<=12; $m++) {
            $month_array[$m] = date('M', mktime(0,0,0,$m,1,$year));
        }

        $starting_year = '2022';
        $ending_year = date('Y',strtotime('+2 year'));
        $year_array = array();
        for ($y=$starting_year; $y < $ending_year ; $y++) {
            $year_array[$y] = $y;
        }

        // Get Pending Work Planning Count
        $work_planning = WorkPlanning::getPendingWorkPlanningDetails($user_id,$month,$year);
        $pending_work_planning_count = sizeof($work_planning);

        // Get Applied Leave Count
        $user_ids[] = $user_id;
        $leave_data = UserLeave::getAllLeavedataByUserId(0,$user_ids,$month,$year,'');
        $leave_count = sizeof($leave_data);

        // Get Present Days Count
        $present_days_res = WorkPlanning::getWorkPlanningDetails($user_id,$month,$year,'','');
        $present_days = sizeof($present_days_res);

        // Get Early go late in count
        $latein_earlygo_details = LateInEarlyGo::getLateInEarlyGoDetailsByUserId(0,$user_ids,$month,$year,'');
        $earlygo_latein_count = sizeof($latein_earlygo_details);

        // Get work from home request count
        $wfh_data = WorkFromHome::getAllWorkFromHomeRequestsByUserId(0,$user_ids,$month,$year,'');
        $work_from_count = sizeof($wfh_data);

        // Get Holidays of User
        $holiday_details = Holidays::getUserHolidays($user_id,$month,$year);
        $holidays_count = sizeof($holiday_details);

        // Get Work Anniversary dates of Current Month
        $work_anniversary_dates = User::getUsersWorkAnniversaryDatesByMonth($month);

        // Get Birthday dates of Current Month
        $birthday_dates = User::getUserBirthDatesByMonth($month);

        // Get Holiday of Current Year
        $holidays = Holidays::getFinancialYearHolidaysList();

        // Get Assigners users
        $assigned_users = User::getAssignedUsers($user_id);

        if(isset($assigned_users) && sizeof($assigned_users) > 0) {
            foreach ($assigned_users as $key => $value) {
                $user_ids_array[] = $key;
            }
        }
        else {
            $user_ids_array = array();
        }

        if (in_array($user_id, $user_ids_array)) {
            unset($user_ids_array[array_search($user_id,$user_ids_array)]);
        }

        // Get List of applied late in early go requests of my team
        $latein_earlygo_data = LateInEarlyGo::getLateInEarlyGoDetailsByUserId(0,$user_ids_array,$month,$year,'');

        // Get List of applied leaves of my team
        $leave_data = UserLeave::getAllLeavedataByUserId(0,$user_ids_array,$month,$year,'');

        // Get List of applied work from home requests of my team
        $wfh_data = WorkFromHome::getAllWorkFromHomeRequestsByUserId(0,$user_ids_array,$month,$year,'');

        $viewVariable = array();
        $viewVariable['pending_work_planning_count'] = $pending_work_planning_count;
        $viewVariable['leave_count'] = $leave_count;
        $viewVariable['present_days'] = $present_days;
        $viewVariable['earlygo_latein_count'] = $earlygo_latein_count;
        $viewVariable['work_from_count'] = $work_from_count;
        $viewVariable['holidays_count'] = $holidays_count;

        $viewVariable['work_anniversary_dates'] = $work_anniversary_dates;
        $viewVariable['birthday_dates'] = $birthday_dates;
        $viewVariable['holidays'] = $holidays;

        $viewVariable['latein_earlygo_data'] = $latein_earlygo_data;
        $viewVariable['leave_data'] = $leave_data;
        $viewVariable['wfh_data'] = $wfh_data;

        $viewVariable['month_array'] = $month_array;
        $viewVariable['year_array'] = $year_array;
        $viewVariable['month'] = $month;
        $viewVariable['year'] = $year;

        return view('employee-self-service',$viewVariable);
    }

    // HR ESS
    public function hrEmployeeSelfService() {

        $user = \Auth::user();
        $user_id =  $user->id;
        $superadmin_userid = getenv('SUPERADMINUSERID');

        if(isset($_POST['month']) && $_POST['month']!='') {
            $month = $_POST['month'];
        }
        else {
            $month = date("n");
        }

        if(isset($_POST['year']) && $_POST['year']!='') {
            $year = $_POST['year'];
        }
        else {
            $year = date("Y");
        }

        $month_array =array();
        for ($m=1; $m<=12; $m++) {
            $month_array[$m] = date('M', mktime(0,0,0,$m,1,$year));
        }

        $starting_year = '2022';
        $ending_year = date('Y',strtotime('+2 year'));
        $year_array = array();
        for ($y=$starting_year; $y < $ending_year ; $y++) {
            $year_array[$y] = $y;
        }

        // Set All Array Empty
        $team_leave_details = array();
        $all_leave_details = array();

        $team_latein_earlygo_details = array();
        $all_latein_earlygo_details = array();

        $team_wfh_details = array();
        $all_wfh_details = array();

        $leave_data = array();
        $latein_earlygo_data = array();
        $wfh_data = array();

        $leave_count = 0;
        $earlygo_latein_count = 0;
        $work_from_count = 0;

        // Get List of applied leaves
        $leave_data = UserLeave::getAllLeavedataByUserId(1,0,$month,$year,'',5);

        // Get List of applied late in early go requests
        $latein_earlygo_data = LateInEarlyGo::getLateInEarlyGoDetailsByUserId(1,0,$month,$year,'',5);

        // Get List of applied work from home requests
        $wfh_data = WorkFromHome::getAllWorkFromHomeRequestsByUserId(1,0,$month,$year,'',5);

        if($user_id == $superadmin_userid) {

            // Get Leave Details By Team & Other users
            if(isset($leave_data) && sizeof($leave_data) > 0) {

                $i = 0;
                foreach ($leave_data as $key => $value) {

                    $report_to_id = User::getReportsToById($value['user_id']);

                    if($report_to_id == $superadmin_userid) {
                        $team_leave_details[$i] = $value;
                    }
                    else {
                        $all_leave_details[$i] = $value;
                    }

                    $i++;
                }
            }

            // Get Late In Early Go Details By Team & All users
            if(isset($latein_earlygo_data) && sizeof($latein_earlygo_data) > 0) {

                $i = 0;
                foreach ($latein_earlygo_data as $key => $value) {

                    $report_to_id = User::getReportsToById($value['user_id']);

                    if($report_to_id == $superadmin_userid) {
                        $team_latein_earlygo_details[$i] = $value;
                    }
                    else {
                        $all_latein_earlygo_details[$i] = $value;
                    }
                    $i++;
                }
            }
            
            // Get Work From Home Requests Details By Team & All users
            if(isset($wfh_data) && sizeof($wfh_data) > 0) {

                $i = 0;

                foreach ($wfh_data as $key => $value) {

                    $report_to_id = User::getReportsToById($value['user_id']);

                    if($report_to_id == $superadmin_userid) {
                        $team_wfh_details[$i] = $value;
                    }
                    else {
                        $all_wfh_details[$i] = $value;
                    }
                    $i++;
                }
            }

            $leave_data = array();
            $latein_earlygo_data = array();
            $wfh_data = array();
        }

        // Get Pending Work Planning Count
        $work_planning = WorkPlanning::getPendingWorkPlanningDetails(0,$month,$year);
        $pending_work_planning_count = sizeof($work_planning);

        // Get All Leave Count
        $leaves_all_data = UserLeave::getAllLeavedataByUserId(1,0,$month,$year,'');
        $leave_count = sizeof($leaves_all_data);

        // Get All Latein Early Go Count
        $earlygo_latein_all_data = LateInEarlyGo::getLateInEarlyGoDetailsByUserId(1,0,$month,$year,'');
        $earlygo_latein_count = sizeof($earlygo_latein_all_data);

        // Get All Work From Home Count
        $work_from_all_data = WorkFromHome::getAllWorkFromHomeRequestsByUserId(1,0,$month,$year,'');
        $work_from_count = sizeof($work_from_all_data);

        // Get Holidays count of current month
        $holiday_details = Holidays::getUserHolidays(0,$month,$year);
        $holidays_count = sizeof($holiday_details);

        // Get Work Anniversary dates of Current Month
        $work_anniversary_dates = User::getUsersWorkAnniversaryDatesByMonth($month);

        // Get Birthday dates of Current Month
        $birthday_dates = User::getUserBirthDatesByMonth($month);

        // Get Holiday of Current Year
        $holidays = Holidays::getFinancialYearHolidaysList();

        $viewVariable = array();
        $viewVariable['pending_work_planning_count'] = $pending_work_planning_count;
        $viewVariable['leave_count'] = $leave_count;
        $viewVariable['earlygo_latein_count'] = $earlygo_latein_count;
        $viewVariable['work_from_count'] = $work_from_count;
        $viewVariable['holidays_count'] = $holidays_count;
        
        $viewVariable['work_anniversary_dates'] = $work_anniversary_dates;
        $viewVariable['birthday_dates'] = $birthday_dates;
        $viewVariable['holidays'] = $holidays;

        $viewVariable['latein_earlygo_data'] = $latein_earlygo_data;
        $viewVariable['leave_data'] = $leave_data;
        $viewVariable['wfh_data'] = $wfh_data;

        $viewVariable['month_array'] = $month_array;
        $viewVariable['year_array'] = $year_array;
        $viewVariable['month'] = $month;
        $viewVariable['year'] = $year;

        $viewVariable['team_leave_details'] = $team_leave_details;
        $viewVariable['all_leave_details'] = $all_leave_details;
        $viewVariable['team_latein_earlygo_details'] = $team_latein_earlygo_details;
        $viewVariable['all_latein_earlygo_details'] = $all_latein_earlygo_details;
        $viewVariable['team_wfh_details'] = $team_wfh_details;
        $viewVariable['all_wfh_details'] = $all_wfh_details;

        return view('hr-employee-self-service',$viewVariable);
    }

    public function usersAttendance($department_nm,$month,$year) {

        $user = \Auth::user();
        $user_id = $user->id;

        if(isset($month) && $month !='') {
        }
        else {
            $month = date("n");
        }

        if(isset($year) && $year !='') {
        }
        else {
            $year = date("Y");
        }

        // Get All Sundays dates in selected month
        $date = "$year-$month-01";
        $first_day = date('N',strtotime($date));
        $first_day = 7 - $first_day + 1;
        $last_day =  date('t',strtotime($date));
        $sundays = array();

        for($i = $first_day; $i <= $last_day; $i = $i+7 ) {
            $sundays[] = $i;
        }

        $month_array =array();
        for ($m=1; $m<=12; $m++) {
            $month_array[$m] = date('M', mktime(0,0,0,$m,1,$year));
        }

        $starting_year = '2022';
        $ending_year = date('Y',strtotime('+2 year'));
        $year_array = array();
        for ($y=$starting_year; $y < $ending_year ; $y++) {
            $year_array[$y] = $y;
        }

        // Check type name wise permissions

        if($department_nm == 'self') {

            $selected_attendance_type = 'self';

            $all_perm = $user->can('display-attendance-by-loggedin-user-in-admin-panel');
            $dept_perm = '';

            // Get Previous data from joining date

            if($month <= 9) {

                $month = "0".$month;
            }
            $check_date = $year."-".$month."-31";

            // Get Users
            $user_details = User::getProfileInfo($user_id);

            if($user_details->joining_date <= $check_date) {

                $joining_date = date('d/m/Y', strtotime("$user_details->joining_date"));
                $full_name = $user_details->first_name."-".$user_details->last_name.",".$user_details->department_name.",".$user_details->employment_type.",".$user_details->working_hours.",".$joining_date;
                $users = array($full_name => "");
            }
            else {
                $users = array();
            }

            // Get Attendance & Remarks
            $response = WorkPlanning::getWorkPlanningByUserID($user_id,$month,$year);
            $user_remark = UserRemarks::getUserRemarksDetailsByUserID($user_id,$month,$year);

            // Set User names array for add remarks using modal popup
            $users_name = array();
        }
        else if($department_nm == 'team') {

            $selected_attendance_type = 'team';

            $all_perm = $user->can('display-attendance-by-loggedin-user-in-admin-panel');
            $dept_perm = '';

            // Get Users
            $users = User::getOtherUsersNew($user_id,'',$month,$year);

            // Get Attendance & Remarks
            $response = WorkPlanning::getUsersAttendanceByWorkPlanning($user_id,$month,$year,'');
            $user_remark = UserRemarks::getUserRemarksByUserIDNew($user_id,$month,$year,'');

            // Set User names array for add remarks using modal popup
            $users_name = User::getAllUsersForRemarks($user_id,0);
        }
        else if($department_nm == 'adler') {

            $selected_attendance_type = 'adler';

            $all_perm = $user->can('display-attendance-of-all-users-in-admin-panel');
            $dept_perm = '';

            $department_id = 0;

            // Get Users
            $users = User::getOtherUsersNew('','',$month,$year);

            // Get Attendance & Remarks
            $response = WorkPlanning::getUsersAttendanceByWorkPlanning(0,$month,$year,0);
            $user_remark = UserRemarks::getUserRemarksByUserIDNew(0,$month,$year,0);

            // Set User names array for add remarks using modal popup
            $users_name = User::getAllUsersForRemarks(0,$department_id);
        }
        else if($department_nm == 'recruitment') {

            $selected_attendance_type = 'recruitment';

            $all_perm = $user->can('display-attendance-of-all-users-in-admin-panel');
            $dept_perm = $user->can('display-recruitment-dashboard');

            $department_id = getenv('RECRUITMENT');

            // Get Users
            $users = User::getOtherUsersNew('',$department_id,$month,$year);

            // Get Attendance & Remarks
            $response = WorkPlanning::getUsersAttendanceByWorkPlanning($user_id,$month,$year,$department_id);
            $user_remark = UserRemarks::getUserRemarksByUserIDNew($user_id,$month,$year,$department_id);

            // Set User names array for add remarks using modal popup
            $users_name = User::getAllUsersForRemarks(0,$department_id);
        }
        else if($department_nm == 'hr-advisory') {

            $selected_attendance_type = 'hr-advisory';

            $all_perm = $user->can('display-attendance-of-all-users-in-admin-panel');
            $dept_perm = $user->can('display-hr-advisory-dashboard');

            $department_id = getenv('HRADVISORY');

            // Get Users
            $users = User::getOtherUsersNew('',$department_id,$month,$year);

            // Get Attendance & Remarks
            $response = WorkPlanning::getUsersAttendanceByWorkPlanning($user_id,$month,$year,$department_id);
            $user_remark = UserRemarks::getUserRemarksByUserIDNew($user_id,$month,$year,$department_id);

            // Set User names array for add remarks using modal popup
            $users_name = User::getAllUsersForRemarks(0,$department_id);
        }
        else if($department_nm == 'operations') {

            $selected_attendance_type = 'operations';

            $all_perm = $user->can('display-attendance-of-all-users-in-admin-panel');
            $dept_perm = '';

            $department_id = getenv('OPERATIONS');

            // Get Users
            $users = User::getOtherUsersNew('',$department_id,$month,$year);

            // Get Attendance & Remarks
            $response = WorkPlanning::getUsersAttendanceByWorkPlanning($user_id,$month,$year,$department_id);
            $user_remark = UserRemarks::getUserRemarksByUserIDNew($user_id,$month,$year,$department_id);

            // Set User names array for add remarks using modal popup
            $users_name = User::getAllUsersForRemarks(0,$department_id);
        }

        if($all_perm || $dept_perm) {

            $list = array();
            for($d=1; $d<=31; $d++) {

                $time = mktime(12, 0, 0, $month, $d, $year);
                foreach ($users as $key => $value) {
                  
                    if (date('n', $time) == $month)
                        $list[$key][date('j', $time)]['attendance']='';
                    
                    $list[$key][date('j', $time)]['remarks']='';
                    $list[$key][date('j', $time)]['fixed_holiday']='';
                    $list[$key][date('j', $time)]['optional_holiday']='';
                    $list[$key][date('j', $time)]['privilege_leave']='';
                    $list[$key][date('j', $time)]['sick_leave']='';
                    $list[$key][date('j', $time)]['unapproved_leave']='';
                }
            }

            $date = new Date();
            if(sizeof($response) > 0) {

                foreach ($response as $key => $value) {

                    $get_dt = date("j",strtotime($value->added_date));

                    $joining_date = date('d/m/Y', strtotime("$value->joining_date"));
                    $combine_name = $value->first_name."-".$value->last_name.",".$value->department_name.",".$value->employment_type.",".$value->working_hours.",".$joining_date;

                    // Get User id from both name 
                    $user_name = $value->first_name."-".$value->last_name;
                    $u_id = User::getUserIdByBothName($user_name);

                    $approved_wfh_data = WorkFromHome::getWorkFromHomeRequestByDate($value->added_date,$u_id,1);

                    $rejected_wfh_data = WorkFromHome::getWorkFromHomeRequestByDate($value->added_date,$u_id,2);

                    if($value->status == 2 && $value->attendance == "A") {

                        $list[$combine_name][$get_dt]['attendance'] = 'FR';
                    }
                    else if($value->attendance == "A") {

                        $list[$combine_name][$get_dt]['attendance'] = 'A';
                    }
                    else if(in_array($get_dt, $sundays)) {

                        $list[$combine_name][$get_dt]['attendance'] = 'H';
                    }
                    else if($value->status == NULL && $value->loggedin_time == NULL) {

                        $list[$combine_name][$get_dt]['attendance'] = '';
                    }
                    else if($value->status == 0) {

                        $list[$combine_name][$get_dt]['attendance'] = 'WPP';
                    }
                    else if($value->status == 1 && $value->attendance == "HD" && isset($approved_wfh_data) && sizeof($approved_wfh_data) > 0) {

                        $list[$combine_name][$get_dt]['attendance'] = 'WFHHD';
                    }
                    else if($value->status == 1 && $value->attendance == "F" && isset($approved_wfh_data) && sizeof($approved_wfh_data) > 0) {

                        $list[$combine_name][$get_dt]['attendance'] = 'WFHP';
                    }
                    else if(isset($rejected_wfh_data) && sizeof($rejected_wfh_data) > 0) {

                        $list[$combine_name][$get_dt]['attendance'] = 'WFHR';
                    }
                    else if($value->status == 1 && $value->attendance == "HD") {

                        $list[$combine_name][$get_dt]['attendance'] = 'HD';
                    }
                    else if($value->status == 1 && $value->attendance == "F") {

                        $list[$combine_name][$get_dt]['attendance'] = 'P';
                    }
                    else if($value->status == 2 && $value->attendance == "HD") {

                        $list[$combine_name][$get_dt]['attendance'] = 'HDR';
                    }
                    

                    // Set holiday dates
                    $fixed_holidays = Holidays::getHolidaysByUserID($u_id,$month,$year,'Fixed Leave');

                    if (isset($fixed_holidays) && sizeof($fixed_holidays)>0) {

                        foreach ($fixed_holidays as $f_h_k => $f_h_v) {

                            $list[$combine_name][$f_h_v]['fixed_holiday'] = 'Y';
                        }
                    }

                    $optional_holidays = Holidays::getHolidaysByUserID($u_id,$month,$year,'Optional Leave');

                    if (isset($optional_holidays) && sizeof($optional_holidays)>0) {

                        foreach ($optional_holidays as $o_h_k => $o_h_v) {

                            $list[$combine_name][$o_h_v]['optional_holiday'] = 'Y';
                        }
                    }

                    // Set Leave dates
                    $pl_leave_data = UserLeave::getUserLeavesById($u_id,$month,$year,'Privilege Leave',1);
                    $sl_leave_data = UserLeave::getUserLeavesById($u_id,$month,$year,'Sick Leave',1);
                    $ul_leave_data = UserLeave::getUserLeavesById($u_id,$month,$year,'',2);

                    if (isset($pl_leave_data) && sizeof($pl_leave_data)>0) {

                        foreach ($pl_leave_data as $pl_k => $pl_v) {

                            for($pl_i=$pl_v['from_date']; $pl_i <= $pl_v['to_date']; $pl_i++) {
                                $list[$combine_name][$pl_i]['privilege_leave'] = 'Y';
                            }
                        }
                    }

                    if (isset($sl_leave_data) && sizeof($sl_leave_data)>0) {

                        foreach ($sl_leave_data as $sl_k => $sl_v) {

                            for($sl_i=$sl_v['from_date']; $sl_i <= $sl_v['to_date']; $sl_i++) {
                                $list[$combine_name][$sl_i]['sick_leave'] = 'Y';
                            }
                        }
                    }

                    if (isset($ul_leave_data) && sizeof($ul_leave_data)>0) {

                        foreach ($ul_leave_data as $ul_k => $ul_v) {

                            for($ul_i=$ul_v['from_date']; $ul_i <= $ul_v['to_date']; $ul_i++) { 
                                $list[$combine_name][$ul_i]['unapproved_leave'] = 'Y';
                            }
                        }
                    }

                    if (isset($user_remark) && sizeof($user_remark)>0) {

                        foreach ($user_remark as $k => $v) {

                            $split_month = date('n',strtotime($v['remark_date']));
                            $split_year = date('Y',strtotime($v['remark_date']));

                            if (($v['full_name'] == $combine_name) && ($v['remark_date'] == $value->added_date) && ($month == $split_month) && ($year == $split_year)) {
                                $list[$combine_name][$v['converted_date']]['remarks'] = $v['remarks'];
                            }
                            else {

                                if (($v['full_name'] == $combine_name) && ($month == $split_month) && ($year == $split_year)) {

                                    $list[$combine_name][$v['converted_date']]['remarks'] = $v['remarks'];
                                }
                                else {

                                    $list[$v['full_name']][$v['converted_date']]['remarks'] = $v['remarks'];
                                }
                            }
                        }
                    }
                }
            }
            else {

                if (isset($user_remark) && sizeof($user_remark)>0) {

                    foreach ($user_remark as $k => $v) {

                        $split_month = date('n',strtotime($v['remark_date']));
                        $split_year = date('Y',strtotime($v['remark_date']));
                        if (($month == $split_month) && ($year == $split_year)) {
                            $list[$v['full_name']][$v['converted_date']]['remarks'] = $v['remarks'];
                        }
                    }
                }
            }

            //print_r($list);exit;

            // New List1
            $list1 = array();
            for($d1=1; $d1<=31; $d1++) {

                $time1 = mktime(12, 0, 0, $month, $d1, $year);
                foreach ($users as $key => $value) {

                    if (date('n', $time1) == $month) {
                        $list1[$key][date('j S', $time1)]='';
                    }
                }
            }

            if(sizeof($list)>0) {

                foreach ($list as $key => $value) {

                    if(sizeof($value)>0) {

                        $i=0;
                        foreach ($value as $key1 => $value1) {

                            $split_unm = explode(",",$key);

                            // Get User id from both name
                            $u_id = User::getUserIdByBothName($split_unm[0]);

                            // Set holiday dates
                            $fixed_holidays = Holidays::getHolidaysByUserID($u_id,$month,$year,'Fixed Leave');

                            if (isset($fixed_holidays) && sizeof($fixed_holidays)>0) {

                                foreach ($fixed_holidays as $f_h_k => $f_h_v) {

                                    $list[$key][$f_h_v]['fixed_holiday'] = 'Y';
                                }
                            }

                            $optional_holidays = Holidays::getHolidaysByUserID($u_id,$month,$year,'Optional Leave');

                            if (isset($optional_holidays) && sizeof($optional_holidays)>0) {

                                foreach ($optional_holidays as $o_h_k => $o_h_v) {

                                    $list[$key][$o_h_v]['optional_holiday'] = 'Y';
                                }
                            }

                            // Set Leave dates
                            $pl_leave_data = UserLeave::getUserLeavesById($u_id,$month,$year,'Privilege Leave',1);
                            $sl_leave_data = UserLeave::getUserLeavesById($u_id,$month,$year,'Sick Leave',1);
                            $ul_leave_data = UserLeave::getUserLeavesById($u_id,$month,$year,'',2);

                            if (isset($pl_leave_data) && sizeof($pl_leave_data)>0) {

                                foreach ($pl_leave_data as $pl_k => $pl_v) {

                                    for($pl_i=$pl_v['from_date']; $pl_i <= $pl_v['to_date']; $pl_i++) {
                                        $list[$key][$pl_i]['privilege_leave'] = 'Y';
                                    }
                                }
                            }

                            if (isset($sl_leave_data) && sizeof($sl_leave_data)>0) {

                                foreach ($sl_leave_data as $sl_k => $sl_v) {

                                    for($sl_i=$sl_v['from_date']; $sl_i <= $sl_v['to_date']; $sl_i++) {
                                        $list[$key][$sl_i]['sick_leave'] = 'Y';
                                    }
                                }
                            }

                            if (isset($ul_leave_data) && sizeof($ul_leave_data)>0) {

                                foreach ($ul_leave_data as $ul_k => $ul_v) {

                                    for($ul_i=$ul_v['from_date']; $ul_i <= $ul_v['to_date']; $ul_i++) { 
                                        $list[$key][$ul_i]['unapproved_leave'] = 'Y';
                                    }
                                }
                            }

                            if (isset($user_remark) && sizeof($user_remark)>0) {
                                foreach ($user_remark as $u_k1 => $u_v1) {

                                    $split_month = date('n',strtotime($u_v1['remark_date']));
                                    $split_year = date('Y',strtotime($u_v1['remark_date']));

                                    if (($u_v1['full_name'] == $key) && ($u_v1['converted_date'] == $key1) && ($month == $split_month) && ($year == $split_year)) {
                                        
                                        $list1[$u_v1['full_name']][$u_v1['converted_date']][$u_v1['remark_date']][$i] = $u_v1['remarks'];
                                    }
                                    $i++;
                                }
                            }
                        }
                    }
                }
            }
        }
        else {

            return view('errors.403');
        }

        // Get Attendance Type
        $attendance_type = User::getAttendanceType();

        // Get Employment Type
        $employment_type = User::getEmploymentType();

        $new_list = array();
        foreach ($employment_type as $key => $value) {
                
            foreach ($list as $key1 => $value1) {

                if(strpos($key1, $value) !== false) {
                    $new_list[$key][$key1] = $value1;
                }
            }
        }

        return view('user-attendance',array("list"=>$list,"new_list"=>$new_list,"list1"=>$list1,"month_list"=>$month_array,"year_list"=>$year_array,"month"=>$month,"year"=>$year,"user_remark"=>$user_remark,"attendance_type" => $attendance_type,"selected_attendance_type" => $selected_attendance_type),compact('users_name','department_nm'));
    }

    public function exportAttendance() {

        $user = \Auth::user();
        $user_id = $user->id;
        $all_perm = $user->can('display-attendance-of-all-users-in-admin-panel');

        if($all_perm) {

            $attendance_type = $_POST['attendance_type'];
            $month = $_POST['month'];
            $year = $_POST['year'];
            
            $month_name = date("F", mktime(0, 0, 0, $month, 10,$year));
            $sheet_name = 'Attendance-'.$month_name."-".$year;

            // Get All Sundays dates in selected month
            $date = "$year-$month-01";
            $first_day = date('N',strtotime($date));
            $first_day = 7 - $first_day + 1;
            $last_day =  date('t',strtotime($date));
            $sundays = array();

            for($i = $first_day; $i <= $last_day; $i = $i+7) {
                $sundays[] = $i;
            }

            if($attendance_type == 'self') {

                if($month <= 9) {
                    $month = "0".$month;
                }
                $check_date = $year."-".$month."-31";

                // Get Users
                $user_details = User::getProfileInfo($user_id);

                if($user_details->joining_date <= $check_date) {

                    $joining_date = date('d/m/Y', strtotime("$user_details->joining_date"));
                    $full_name = $user_details->first_name."-".$user_details->last_name.",".$user_details->department_name.",".$user_details->employment_type.",".$user_details->working_hours.",".$joining_date;
                    $users = array($full_name => "");
                }
                else {
                    $users = array();
                }

                // Get Attendance & Remarks
                $response = WorkPlanning::getWorkPlanningByUserID($user_id,$month,$year);
                $user_remark = UserRemarks::getUserRemarksDetailsByUserID($user_id,$month,$year);
            }
            else if($attendance_type == 'team') {

                // Get Users
                $users = User::getOtherUsersNew($user_id,'',$month,$year);

                // Get Attendance & Remarks
                $response = WorkPlanning::getUsersAttendanceByWorkPlanning($user_id,$month,$year,'');
                $user_remark = UserRemarks::getUserRemarksByUserIDNew($user_id,$month,$year,'');
            }
            else if($attendance_type == 'adler') {

                // Get Users
                $users = User::getOtherUsersNew('','',$month,$year);

                // Get Attendance & Remarks
                $response = WorkPlanning::getUsersAttendanceByWorkPlanning(0,$month,$year,0);
                $user_remark = UserRemarks::getUserRemarksByUserIDNew(0,$month,$year,0);

            }
            else if($attendance_type == 'recruitment') {

                $department_id = getenv('RECRUITMENT');

                // Get Users
                $users = User::getOtherUsersNew('',$department_id,$month,$year);

                // Get Attendance & Remarks
                $response = WorkPlanning::getUsersAttendanceByWorkPlanning($user_id,$month,$year,$department_id);
                $user_remark = UserRemarks::getUserRemarksByUserIDNew($user_id,$month,$year,$department_id);

            }
            else if($attendance_type == 'hr-advisory') {

                $department_id = getenv('HRADVISORY');

                // Get Users
                $users = User::getOtherUsersNew('',$department_id,$month,$year);

                // Get Attendance & Remarks
                $response = WorkPlanning::getUsersAttendanceByWorkPlanning($user_id,$month,$year,$department_id);
                $user_remark = UserRemarks::getUserRemarksByUserIDNew($user_id,$month,$year,$department_id);

            }
            else if($attendance_type == 'operations') {

                $department_id = getenv('OPERATIONS');

                // Get Users
                $users = User::getOtherUsersNew('',$department_id,$month,$year);

                // Get Attendance & Remarks
                $response = WorkPlanning::getUsersAttendanceByWorkPlanning($user_id,$month,$year,$department_id);
                $user_remark = UserRemarks::getUserRemarksByUserIDNew($user_id,$month,$year,$department_id);
            }

            // Set new array from data array

            $list = array();
            for($d=1; $d<=31; $d++) {

                $time = mktime(12, 0, 0, $month, $d, $year);
                foreach ($users as $key => $value) {
                  
                    if (date('n', $time) == $month)
                        $list[$key][date('j', $time)]['attendance']='';
                    
                    $list[$key][date('j', $time)]['remarks']='';
                    $list[$key][date('j', $time)]['fixed_holiday']='';
                    $list[$key][date('j', $time)]['optional_holiday']='';
                    $list[$key][date('j', $time)]['privilege_leave']='';
                    $list[$key][date('j', $time)]['sick_leave']='';
                    $list[$key][date('j', $time)]['unapproved_leave']='';
                }
            }

            $date = new Date();
            if(sizeof($response) > 0) {

                foreach ($response as $key => $value) {

                    $get_dt = date("j",strtotime($value->added_date));

                    $joining_date = date('d/m/Y', strtotime("$value->joining_date"));
                    $combine_name = $value->first_name."-".$value->last_name.",".$value->department_name.",".$value->employment_type.",".$value->working_hours.",".$joining_date;

                    // Get User id from both name
                    $user_name = $value->first_name."-".$value->last_name;
                    $u_id = User::getUserIdByBothName($user_name);

                    $approved_wfh_data = WorkFromHome::getWorkFromHomeRequestByDate($value->added_date,$u_id,1);

                    $rejected_wfh_data = WorkFromHome::getWorkFromHomeRequestByDate($value->added_date,$u_id,2);

                    if($value->status == 2 && $value->attendance == "A") {

                        $list[$combine_name][$get_dt]['attendance'] = 'FR';
                    }
                    else if($value->attendance == "A") {

                        $list[$combine_name][$get_dt]['attendance'] = 'A';
                    }
                    else if(in_array($get_dt, $sundays)) {

                        $list[$combine_name][$get_dt]['attendance'] = 'H';
                    }
                    else if($value->status == NULL && $value->loggedin_time == NULL) {

                        $list[$combine_name][$get_dt]['attendance'] = '';
                    }
                    else if($value->status == 0) {

                        $list[$combine_name][$get_dt]['attendance'] = 'WPP';
                    }
                    else if($value->status == 1 && $value->attendance == "HD" && isset($approved_wfh_data) && sizeof($approved_wfh_data) > 0) {

                        $list[$combine_name][$get_dt]['attendance'] = 'WFHHD';
                    }
                    else if($value->status == 1 && $value->attendance == "F" && isset($approved_wfh_data) && sizeof($approved_wfh_data) > 0) {

                        $list[$combine_name][$get_dt]['attendance'] = 'WFHP';
                    }
                    else if(isset($rejected_wfh_data) && sizeof($rejected_wfh_data) > 0) {

                        $list[$combine_name][$get_dt]['attendance'] = 'WFHR';
                    }
                    else if($value->status == 1 && $value->attendance == "HD") {

                        $list[$combine_name][$get_dt]['attendance'] = 'HD';
                    }
                    else if($value->status == 1 && $value->attendance == "F") {

                        $list[$combine_name][$get_dt]['attendance'] = 'P';
                    }
                    else if($value->status == 2 && $value->attendance == "HD") {

                        $list[$combine_name][$get_dt]['attendance'] = 'HDR';
                    }

                    // Set holiday dates
                    $fixed_holidays = Holidays::getHolidaysByUserID($u_id,$month,$year,'Fixed Leave');

                    if (isset($fixed_holidays) && sizeof($fixed_holidays)>0) {

                        foreach ($fixed_holidays as $f_h_k => $f_h_v) {

                            $list[$combine_name][$f_h_v]['fixed_holiday'] = 'Y';
                        }
                    }

                    $optional_holidays = Holidays::getHolidaysByUserID($u_id,$month,$year,'Optional Leave');

                    if (isset($optional_holidays) && sizeof($optional_holidays)>0) {

                        foreach ($optional_holidays as $o_h_k => $o_h_v) {

                            $list[$combine_name][$o_h_v]['optional_holiday'] = 'Y';
                        }
                    }

                    // Set Leave dates
                    $pl_leave_data = UserLeave::getUserLeavesById($u_id,$month,$year,'Privilege Leave',1);
                    $sl_leave_data = UserLeave::getUserLeavesById($u_id,$month,$year,'Sick Leave',1);
                    $ul_leave_data = UserLeave::getUserLeavesById($u_id,$month,$year,'',2);

                    if (isset($pl_leave_data) && sizeof($pl_leave_data)>0) {

                        foreach ($pl_leave_data as $pl_k => $pl_v) {

                            for($pl_i=$pl_v['from_date']; $pl_i <= $pl_v['to_date']; $pl_i++) {

                                $list[$combine_name][$pl_i]['privilege_leave'] = 'Y';
                            }
                        }
                    }

                    if (isset($sl_leave_data) && sizeof($sl_leave_data)>0) {

                        foreach ($sl_leave_data as $sl_k => $sl_v) {

                            for($sl_i=$sl_v['from_date']; $sl_i <= $sl_v['to_date']; $sl_i++) {
                                
                                $list[$combine_name][$sl_i]['sick_leave'] = 'Y';
                            }
                        }
                    }

                    if (isset($ul_leave_data) && sizeof($ul_leave_data)>0) {

                        foreach ($ul_leave_data as $ul_k => $ul_v) {

                            for($ul_i=$ul_v['from_date']; $ul_i <= $ul_v['to_date']; $ul_i++) {
                                
                                $list[$combine_name][$ul_i]['unapproved_leave'] = 'Y';
                            }
                        }
                    }

                    if (isset($user_remark) && sizeof($user_remark) > 0) {

                        foreach ($user_remark as $k => $v) {

                            $split_month = date('n',strtotime($v['remark_date']));
                            $split_year = date('Y',strtotime($v['remark_date']));

                            if (($v['full_name'] == $combine_name) && ($v['remark_date'] == $value->added_date) && ($month == $split_month) && ($year == $split_year)) {
                                $list[$combine_name][$v['converted_date']]['remarks'] = $v['remarks'];
                            }
                            else {

                                if (($v['full_name'] == $combine_name) && ($month == $split_month) && ($year == $split_year)) {

                                    $list[$combine_name][$v['converted_date']]['remarks'] = $v['remarks'];
                                }
                                else {

                                    $list[$v['full_name']][$v['converted_date']]['remarks'] = $v['remarks'];
                                }
                            }
                        }
                    }
                }
            }
            else {

                if (isset($user_remark) && sizeof($user_remark)>0) {

                    foreach ($user_remark as $k => $v) {

                        $split_month = date('n',strtotime($v['remark_date']));
                        $split_year = date('Y',strtotime($v['remark_date']));
                        if (($month == $split_month) && ($year == $split_year)) {
                            $list[$v['full_name']][$v['converted_date']]['remarks'] = $v['remarks'];
                        }
                    }
                }
            }

            if(isset($list) && sizeof($list) > 0) {

                // Get Employment Type
                $employment_type = User::getEmploymentType();

                $new_list = array();
                foreach ($employment_type as $key => $value) {
                        
                    foreach ($list as $key1 => $value1) {

                        if(strpos($key1, $value) !== false) {
                            $new_list[$key][$key1] = $value1;
                        }
                    }
                }

                Excel::create($sheet_name,function($excel) use ($list,$new_list,$year,$month) {

                    $excel->sheet('sheet 1',function($sheet) use ($list,$new_list,$year,$month) {

                        $sheet->loadView('attendance-sheet', array('list' => $list,'new_list' => $new_list,'year' => $year,'month' => $month));
                    });
                })->export('xlsx');
            }
        }
    }

    public function myAttendance() {

        $user_id =  \Auth::user()->id;

        // Get Attendance By Users Work Planning Sheet
        $response = WorkPlanning::getAttendanceByWorkPlanning($user_id);

        // Set Array
        $list = array();
        $month = date('m');
        $year = date('Y');

        for($d=1; $d<=31; $d++) {

            $time = mktime(12, 0, 0, $month, $d, $year);
                  
            if (date('n', $time) == $month) {
                $list[$user_id][date('j', $time)]['attendance']='';
            }

            $list[$user_id][date('j', $time)]['holiday']='';
            $list[$user_id][date('j', $time)]['privilege_leave']='';
            $list[$user_id][date('j', $time)]['sick_leave']='';
            $list[$user_id][date('j', $time)]['unapproved_leave']='';
        }
        
        if(isset($response) && sizeof($response) > 0) {

            foreach ($response as $key => $value) {

                $get_dt = date("j",strtotime($value->added_date));

                $approved_wfh_data = WorkFromHome::getWorkFromHomeRequestByDate($value->added_date,$user_id,1);

                $rejected_wfh_data = WorkFromHome::getWorkFromHomeRequestByDate($value->added_date,$user_id,2);

                if($value->status == 2 && $value->attendance == "A") {

                    $list[$user_id][$get_dt]['attendance'] = 'FR';
                }
                else if($value->attendance == "A") {

                    $list[$user_id][$get_dt]['attendance'] = 'A';
                }
                else if($value->status == NULL && $value->loggedin_time == NULL) {

                    $list[$user_id][$get_dt]['attendance'] = '';
                }
                else if($value->status == 0) {

                    $list[$user_id][$get_dt]['attendance'] = 'WPP';
                }
                else if($value->status == 1 && $value->attendance == "HD" && isset($approved_wfh_data) && sizeof($approved_wfh_data) > 0) {

                    $list[$user_id][$get_dt]['attendance'] = 'WFHHD';
                }
                else if($value->status == 1 && $value->attendance == "F" && isset($approved_wfh_data) && sizeof($approved_wfh_data) > 0) {

                    $list[$user_id][$get_dt]['attendance'] = 'WFHP';
                }
                else if(isset($rejected_wfh_data) && sizeof($rejected_wfh_data) > 0) {

                    $list[$user_id][$get_dt]['attendance'] = 'WFHR';
                }
                else if($value->status == 1 && $value->attendance == "HD") {

                    $list[$user_id][$get_dt]['attendance'] = 'HD';
                }
                else if($value->status == 1 && $value->attendance == "F") {

                    $list[$user_id][$get_dt]['attendance'] = 'P';
                }
                else if($value->status == 2 && $value->attendance == "HD") {

                    $list[$user_id][$get_dt]['attendance'] = 'HDR';
                }

                // Set holiday dates
                $fixed_holidays = Holidays::getHolidaysByUserID($u_id,$month,$year,'Fixed Leave');

                if (isset($fixed_holidays) && sizeof($fixed_holidays)>0) {

                    foreach ($fixed_holidays as $f_h_k => $f_h_v) {

                        $list[$user_id][$f_h_v]['fixed_holiday'] = 'Y';
                    }
                }

                $optional_holidays = Holidays::getHolidaysByUserID($u_id,$month,$year,'Optional Leave');

                if (isset($optional_holidays) && sizeof($optional_holidays)>0) {

                    foreach ($optional_holidays as $o_h_k => $o_h_v) {

                        $list[$user_id][$o_h_v]['optional_holiday'] = 'Y';
                    }
                }

                // Set Leave dates
                $pl_leave_data = UserLeave::getUserLeavesById($user_id,$month,$year,'Privilege Leave',1);
                $sl_leave_data = UserLeave::getUserLeavesById($user_id,$month,$year,'Sick Leave',1);
                $ul_leave_data = UserLeave::getUserLeavesById($user_id,$month,$year,'',2);

                if (isset($pl_leave_data) && sizeof($pl_leave_data)>0) {

                    foreach ($pl_leave_data as $pl_k => $pl_v) {

                        for($pl_i=$pl_v['from_date']; $pl_i <= $pl_v['to_date']; $pl_i++) {
                            $list[$user_id][$pl_i]['privilege_leave'] = 'Y';
                        }
                    }
                }

                if (isset($sl_leave_data) && sizeof($sl_leave_data)>0) {

                    foreach ($sl_leave_data as $sl_k => $sl_v) {

                        for($sl_i=$sl_v['from_date']; $sl_i <= $sl_v['to_date']; $sl_i++) {
                            $list[$user_id][$sl_i]['sick_leave'] = 'Y';
                        }
                    }
                }

                if (isset($ul_leave_data) && sizeof($ul_leave_data)>0) {

                    foreach ($ul_leave_data as $ul_k => $ul_v) {

                        for($ul_i=$ul_v['from_date']; $ul_i <= $ul_v['to_date']; $ul_i++) { 
                            $list[$user_id][$ul_i]['unapproved_leave'] = 'Y';
                        }
                    }
                }
            }
        }

        // Get All Sundays dates in selected month
        $date = "$year-$month-01";
        $first_day = date('N',strtotime($date));
        $first_day = 7 - $first_day + 1;
        $last_day =  date('t',strtotime($date));
        $sundays = array();

        for($i = $first_day; $i <= $last_day; $i = $i+7 ) {
            $sundays[] = $i;
        }

        if(isset($list) && sizeof($list) > 0) {

            foreach ($list as $k=>$v) {

                foreach($v as $key1=>$value1) {

                    $attendance = '';
                    $color = '';

                    if($key1 < 10) {

                        $key1 = "0$key1";
                    }
                    $added_date = "$year-$month-$key1";

                    // Set Attendance Variable
                    if(isset($value1['holiday']) && $value1['holiday'] == 'Y') {
                        
                        $attendance = 'PH';
                        $color = '#76933C';
                    }
                    else if(isset($value1['privilege_leave']) && $value1['privilege_leave'] == 'Y') {
                        
                        $attendance = 'PL';
                        $color = '#8db3e2';
                    }
                    else if(isset($value1['sick_leave']) && $value1['sick_leave'] == 'Y') {
                        
                        $attendance = 'SL';
                        $color = '#7030a0';
                    }
                    else if(isset($value1['unapproved_leave']) && $value1['unapproved_leave'] == 'Y') {
                        
                        $attendance = 'UL';
                        $color = '#fac090';
                    }
                    else if(in_array($key1, $sundays)) {
                        
                        $attendance = 'H';
                        $color = '#ffc000';
                    }
                    else if(isset($value1['attendance']) && $value1['attendance'] == 'F') {

                        $attendance = 'F';
                        $color = '#d8d8d8';
                    }
                    else if(isset($value1['attendance']) && $value1['attendance'] == 'WPP') {

                        $attendance = '';
                        $color = '#8db3e2';
                    }
                    else if(isset($value1['attendance']) && $value1['attendance'] == 'A') {

                        $attendance = 'A';
                        $color = '#ff0000';
                    }
                    else if(isset($value1['attendance']) && $value1['attendance'] == 'HD') {

                        $attendance = 'HD';
                        $color = '#d99594';
                    }
                    else {

                        if(isset($value1['attendance'])) {
                            $attendance = $value1['attendance'];
                        }
                        else {
                            $attendance = '';
                        }
                        $color = 'white';
                    }
                
                    $events[] = Calendar::event(
                        $attendance,
                        true,
                        $added_date,
                        $added_date,
                        null,
                        [
                            'color' => $color,
                        ]
                    );
                }
            }
        }
        else {

            $events = array();
        }

        $calendar = Calendar::addEvents($events);

        $users_name = User::getAllUsersForRemarks(0,0);
        
        return view('my-attendance', compact('calendar','users_name'));
    }

    public function sendEmailByURL(Request $request) {

        $mail_res = \DB::table('emails_notification')
        ->select('emails_notification.*', 'emails_notification.id as id')
        ->where('status','=',0)->orderBy('emails_notification.id','ASC')->limit(1)->get();

        $mail = array();
        $i = 0;

        foreach ($mail_res as $key => $value) {

            $email_notification_id = $value->id;
            $mail[$i]['id'] = $value->id;
            $mail[$i]['module'] = $value->module;
            $mail[$i]['to'] = $value->to;
            $mail[$i]['cc'] = $value->cc;
            $mail[$i]['subject'] = $value->subject;
            $mail[$i]['message'] = $value->message;
            $mail[$i]['status'] = $value->status;
            $mail[$i]['module_id'] = $value->module_id;
            $mail[$i]['sender_name'] = $value->sender_name;
            $sent_date = date('Y-m-d');

            $status = 2;

            \DB::statement("UPDATE `emails_notification` SET `sent_date` = '$sent_date', `status`=$status where `id` = $email_notification_id");
            $i++;
        }

        $from_name = getenv('FROM_NAME');
        $from_address = getenv('FROM_ADDRESS');
        $app_url = getenv('APP_URL');

        $input['from_name'] = $from_name;
        $input['from_address'] = $from_address;

        $input['mail'] = $mail;

        $status = 1;

        foreach ($mail as $key => $value) {

            $input['to'] = $value['to'];
            $input['cc'] = $value['cc'];
            $input['subject'] = $value['subject'];
            $input['message'] = $value['message'];
            $input['app_url'] = $app_url;
            $module_id = $value['module_id'];
            $sender_id = $value['sender_name'];
            $input['module'] = $value['module'];

            if ($value['module'] == 'Job Open' || $value['module'] == 'Job Open to All') {

                $to_array = array();
                $to_array = explode(",",$input['to']);

                $cc_array = array();
                $cc_array = explode(",",$input['cc']);

                $input['to_array'] = $to_array;
                $input['cc_array'] = array_unique($cc_array);

                $id = $value['id'];

                $job = EmailsNotifications::getShowJobs($id);

                $input['job'] = $job;

                \Mail::send('adminlte::emails.emailNotification', $input, function ($job) use($input) {
                    $job->from($input['from_address'], $input['from_name']);
                    $job->to($input['to_array'])->cc($input['cc_array'])->subject($input['subject']);
                });  

                \DB::statement("UPDATE `emails_notification` SET `status`='$status' where `id` = '$email_notification_id'");
            } 

            else if ($value['module'] == 'Todos') {

                // get todos subject and description

                $cc_array = array();
                $cc_array = explode(",",$input['cc']);

                $input['cc_array'] = $cc_array;

                $todos = ToDos::find($module_id);

                $input['todo_subject'] = $todos->subject;
                $input['description'] = $todos->description;

                $user_name = User::getUserNameByEmail($input['to']);

                $input['uname'] = $user_name;

                $input['todo_id'] = $module_id;

                \Mail::send('adminlte::emails.todomail', $input, function ($message) use ($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to'])->cc($input['cc_array'])->subject($input['subject']);
                });              
               
                \DB::statement("UPDATE `emails_notification` SET `status`='$status' where `id` = '$email_notification_id'");
            }

            else if ($value['module'] == 'Leave') {

                $cc_array = array();
                $cc_array = explode(",",$input['cc']);
                $input['cc_array'] = $cc_array;

                // Get Sender name details
                $user_details = User::getAllDetailsByUserID($value['sender_name']);
                $input['from_name'] = $user_details->first_name . " " . $user_details->last_name;
                $input['owner_email'] = $user_details->email;

                $user_info = User::getProfileInfo($value['sender_name']);
                $input['signature'] = $user_info['signature'];

                $user_email_details = UsersEmailPwd::getUserEmailDetails($value['sender_name']);

                $input['from_address'] = trim($user_email_details->email);

                $leave = UserLeave::find($module_id);

                $leave_doc = LeaveDoc::getLeaveDocById($module_id);

                if (isset($leave_doc) && sizeof($leave_doc) > 0) {

                    $input['attachment'] = array();$j = 0;

                    foreach ($leave_doc as $key => $value) {
                        $input['attachment'][$j] = 'public/'.$value['fileName'];
                        $j++;
                    }
                }

                $input['leave_message'] = $leave->message;
                $input['leave_id'] = $module_id;

                if(strpos($input['from_address'], '@gmail.com') !== false) {

                    config([
                        'mail.driver' => trim('mail'),
                        'mail.host' => trim('smtp.gmail.com'),
                        'mail.port' => trim('587'),
                        'mail.username' => trim($user_email_details->email),
                        'mail.password' => trim($user_email_details->password),
                        'mail.encryption' => trim('tls'),
                    ]);
                }
                else {

                    config([
                        'mail.driver' => trim('smtp'),
                        'mail.host' => trim('smtp.zoho.com'),
                        'mail.port' => trim('465'),
                        'mail.username' => trim($user_email_details->email),
                        'mail.password' => trim($user_email_details->password),
                        'mail.encryption' => trim('ssl'),
                    ]);
                }

                \Mail::send('adminlte::emails.leavemail', $input, function ($message) use ($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to'])->cc($input['cc_array'])->bcc($input['owner_email'])->subject($input['subject']);

                    if (isset($input['attachment']) && sizeof($input['attachment']) > 0) {
                        
                        foreach ($input['attachment'] as $key => $value) {
                            $message->attach($value);
                        }
                    }
                });

                \DB::statement("UPDATE `emails_notification` SET `status`='$status' where `id` = '$email_notification_id'"); 
            }

            else if ($value['module'] == 'Daily Report') {

                $to_array = explode(",",$input['to']);
                $cc_array = explode(",",$input['cc']);

                $associate_response = JobAssociateCandidates::getDailyReportAssociate($sender_id,NULL);
                $associate_daily = $associate_response['associate_data'];
                $associate_count = $associate_response['cvs_cnt'];
                   
                // Get Leads with count

                $leads = Lead::getDailyReportLeads($sender_id,NULL);
                $leads_daily = $leads['leads_data'];
               
                $leads_count = Lead::getDailyReportLeadCount($sender_id,NULL);

                $interview_daily = Interview::getDailyReportInterview($sender_id,NULL);
                   
                $user_details = User::getAllDetailsByUserID($sender_id);

                $input['value'] = $user_details->name;

                $input['user_details'] = $user_details;
                $input['associate_daily'] = $associate_daily;
                $input['associate_count'] = $associate_count;

                $input['leads_daily'] = $leads_daily;
                $input['leads_count'] = $leads_count;
                $input['interview_daily'] = $interview_daily;

                $input['to_array'] = array_unique($to_array);
                $input['cc_array'] = array_unique($cc_array);

                \Mail::send('adminlte::emails.dailyReport', $input, function ($message) use ($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to_array'])->cc($input['cc_array'])->subject('Daily Activity Report - ' . $input['value'] . ' - ' . date("d-m-Y"));
                });

                \DB::statement("UPDATE `emails_notification` SET `status`='$status' where `id` = '$email_notification_id'");
            }
            else if ($value['module'] == 'Weekly Report') {

                $to_array = explode(",",$input['to']);
                $cc_array = explode(",",$input['cc']);

                $associate_weekly_response = JobAssociateCandidates::getWeeklyReportAssociate($sender_id,NULL,NULL);

                $associate_weekly = $associate_weekly_response['associate_data'];
                $associate_count = $associate_weekly_response['cvs_cnt'];

                $interview_weekly_response = Interview::getWeeklyReportInterview($sender_id,NULL,NULL);
                $interview_weekly = $interview_weekly_response['interview_data'];
                $interview_count = $interview_weekly_response['interview_cnt'];

                // Get Leads with count
                $leads = Lead::getWeeklyReportLeads($sender_id,NULL,NULL);
                $leads_weekly = $leads['leads_data'];
                $leads_count = Lead::getWeeklyReportLeadCount($sender_id,NULL,NULL);

                $user_details = User::getAllDetailsByUserID($sender_id);

                $input['value'] = $user_details->name;
                $input['user_details'] = $user_details;
                $input['associate_weekly'] = $associate_weekly;
                $input['associate_count'] = $associate_count;
                $input['interview_weekly'] = $interview_weekly;
                $input['interview_count'] = $interview_count;
                $input['leads_weekly'] = $leads_weekly;
                $input['leads_count'] = $leads_count;
                $input['to_array'] = array_unique($to_array);
                $input['cc_array'] = array_unique($cc_array);

                \Mail::send('adminlte::emails.WeeklyReport', $input, function ($message) use($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to_array'])->cc($input['cc_array'])->subject('Weekly Activity Report -'.$input['value']);
                });

                \DB::statement("UPDATE `emails_notification` SET `status`='$status' where `id` = '$email_notification_id'");
            }

            else if ($value['module'] == 'Lead' || $value['module'] == 'Cancel Lead') {
                
                $cc_array = array();
                $cc_array = explode(",",$input['cc']);

                $input['cc_array'] = $cc_array;

                $lead_details = Lead::getLeadDetailsById($value['module_id']);
                
                $input['lead_details'] = $lead_details;

                \Mail::send('adminlte::emails.leadaddemail', $input, function ($message) use($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to'])->cc($input['cc_array'])->subject($input['subject']);
                });

                \DB::statement("UPDATE `emails_notification` SET `status`='$status' where `id` = '$email_notification_id'");
            }

            else if ($value['module'] == 'Forecasting' || $value['module'] == 'Recovery' || $value['module'] == 'Forecasting Update' || $value['module'] == 'Recovery Update') {
                
                $cc_array = array();
                $cc_array = explode(",",$input['cc']);

                $input['cc_array'] = $cc_array;

                $id = array($value['module_id']);

                $bills_details = Bills::getBillsByIds($id);
                
                $input['bills_details'] = $bills_details;

                $bill_docs = BillsDoc::getBillDocs($value['module_id']);

                $input['bill_docs'] = $bill_docs;

                \Mail::send('adminlte::emails.billsemail', $input, function ($message) use($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to'])->cc($input['cc_array'])->subject($input['subject']);

                    if (isset($input['bill_docs']) && sizeof($input['bill_docs']) > 0) {

                        foreach ($input['bill_docs'] as $key => $value) {

                            if(isset($value['file']) && $value['file'] != '') {
                                $message->attach($value['file']);
                            }
                        }
                    }
                });

                \DB::statement("UPDATE `emails_notification` SET `status`='$status' where `id` = '$email_notification_id'");
            }

            else if ($value['module'] == 'Cancel Forecasting' || $value['module'] == 'Cancel Recovery' || $value['module'] == 'Relive Forecasting' || $value['module'] == 'Relive Recovery') {
                
                $cc_array = array();
                $cc_array = explode(",",$input['cc']);

                $input['cc_array'] = $cc_array;

                $id = array($value['module_id']);

                $bills_details = Bills::getBillsByIds($id);

                $input['bills_details'] = $bills_details;

                \Mail::send('adminlte::emails.billsemail', $input, function ($message) use($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to'])->cc($input['cc_array'])->subject($input['subject']);
                });

                \DB::statement("UPDATE `emails_notification` SET `status`='$status' where `id` = '$email_notification_id'");
            }

            else if ($value['module'] == 'Training Material') {

                $to_array = explode(",",$input['to']);
                $cc_array = explode(",",$input['cc']);

                $input['module_id'] = $value['module_id'];
                $input['to_array'] = $to_array;
                $input['cc_array'] = $cc_array;

                \Mail::send('adminlte::emails.training', $input, function ($message) use($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to_array'])->cc($input['cc_array'])->subject($input['subject']);
                });

                \DB::statement("UPDATE `emails_notification` SET `status`='$status' where `id` = '$email_notification_id'");
            }

            else if ($value['module'] == 'Process Manual') {

                $to_array = explode(",",$input['to']);
                $cc_array = explode(",",$input['cc']);

                $input['module_id'] = $value['module_id'];
                $input['to_array'] = $to_array;
                $input['cc_array'] = $cc_array;

                \Mail::send('adminlte::emails.processmanual', $input, function ($message) use($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to_array'])->cc($input['cc_array'])->subject($input['subject']);
                });

                \DB::statement("UPDATE `emails_notification` SET `status`='$status' where `id` = '$email_notification_id'");
            }

            else if ($value['module'] == 'Client' || $value['module'] == 'Forbid Client' || $value['module'] == 'Client Account Manager' || $value['module'] == 'Client Delete') {

                $to_array = explode(",",$input['to']);
                $cc_array = explode(",",$input['cc']);

                $client = ClientBasicinfo::getClientDetailsById($module_id);

                $input['module_id'] = $value['module_id'];
                $input['to_array'] = $to_array;
                $input['cc_array'] = $cc_array;
                $input['client'] = $client;

                \Mail::send('adminlte::emails.clientaddmail', $input, function ($message) use($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to_array'])->cc($input['cc_array'])->subject($input['subject']);
                });

                \DB::statement("UPDATE `emails_notification` SET `status`='$status' where `id` = '$email_notification_id'");
            }

            else if ($value['module'] == 'List of Clients transferred') {

                $to_array = explode(",",$input['to']);
                $cc_array = explode(",",$input['cc']);

                $client_ids_array = explode(",",$module_id);
                $client_info = array();
                $i=0;

                foreach($client_ids_array as $key => $value) {

                    $client = ClientBasicinfo::getClientDetailsById($value);
                    $client_history = ClientTimeline::getTimelineDetailsByClientId($value);

                    if(isset($client_history[1]['user_id']) && $client_history[1]['user_id'] >= '0') {

                        $client_info[$i]['transferred_from'] = $client_history[1]['user_name'];
                    }
                    else {

                        $client_info[$i]['transferred_from'] = $client_history[0]['user_name'];
                    }
                    
                    if($client['am_name'] == '') {
                        $client_info[$i]['transferred_to'] = 'Yet to Assign';
                    }
                    else {
                        $client_info[$i]['transferred_to'] = $client['am_name'];
                    }
                    
                    $client_info[$i]['name'] = $client['name'];
                    $client_info[$i]['coordinator_name'] = $client['coordinator_name'];
                    $client_info[$i]['billing_city'] = $client['billing_city'];
                    
                    $i++;
                }

                $input['client_info'] = $client_info;
                $input['to_array'] = $to_array;
                $input['cc_array'] = $cc_array;

                \Mail::send('adminlte::emails.clientmultipleaccountmanager', $input, function ($message) use($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to_array'])->cc($input['cc_array'])->subject($input['subject']);
                });

                \DB::statement("UPDATE `emails_notification` SET `status`='$status' where `id` = '$email_notification_id'");
            }

            // Mail for Leave reply approved/unapproved
            else if ($value['module'] == 'Leave Reply') {

                $cc_array = array();
                $cc_array = explode(",",$input['cc']); 

                $input['cc_array'] = array_unique($cc_array);

                // Get Sender name details

                $user_details = User::getAllDetailsByUserID($value['sender_name']);
                $input['from_name'] = $user_details->first_name . " " . $user_details->last_name;

                $user_info = User::getProfileInfo($value['sender_name']);
                $input['signature'] = $user_info['signature'];

                $user_email_details = UsersEmailPwd::getUserEmailDetails($value['sender_name']);

                $input['from_address'] = trim($user_email_details->email);

                $leave = UserLeave::find($module_id);
                $input['leave_message'] = $leave->reply_message;
                $input['remarks'] = $leave->remarks;
                $input['days'] = $leave->days;
                $input['status'] = $leave->status;
                $input['type_of_leave'] = $leave->type_of_leave;
                $input['from_date'] = date('d-m-Y',strtotime($leave->from_date));
                $input['to_date'] = date('d-m-Y',strtotime($leave->to_date));

                $user_name = User::getUserNameByEmail($input['to']);
                $input['user_name'] = $user_name;

                if(strpos($input['from_address'], '@gmail.com') !== false) {

                    config([
                        'mail.driver' => trim('mail'),
                        'mail.host' => trim('smtp.gmail.com'),
                        'mail.port' => trim('587'),
                        'mail.username' => trim($user_email_details->email),
                        'mail.password' => trim($user_email_details->password),
                        'mail.encryption' => trim('tls'),
                    ]);
                }
                else {

                    config([
                        'mail.driver' => trim('smtp'),
                        'mail.host' => trim('smtp.zoho.com'),
                        'mail.port' => trim('465'),
                        'mail.username' => trim($user_email_details->email),
                        'mail.password' => trim($user_email_details->password),
                        'mail.encryption' => trim('ssl'),
                    ]);
                }

                \Mail::send('adminlte::emails.leavereply', $input, function ($message) use ($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to'])->cc($input['cc_array'])->subject($input['subject']);
                });

                \DB::statement("UPDATE `emails_notification` SET `status`='$status' where `id` = '$email_notification_id'"); 
            }

            // Mail for Joining Confirmation of recovery
            else if ($value['module'] == 'Joining Confirmation') {

                $cc_array = array();
                $cc_array = explode(",",$input['cc']);

                $input['cc_array'] = array_unique($cc_array);

                $join_mail = Bills::getJoinConfirmationMail($module_id);

                $input['join_mail'] = $join_mail;

                \Mail::send('adminlte::emails.joinconfirmationmail', $input, function ($message) use ($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to'])->cc($input['cc_array'])->subject($input['subject']);
                });

                \DB::statement("UPDATE `emails_notification` SET `status`='$status' where `id` = '$email_notification_id'");
            }

            // Mail for Invoice gererate of recovery
            else if ($value['module'] == 'Invoice Generate') {

                $cc_array = array();
                $cc_array = explode(",",$input['cc']);

                $input['cc_array'] = array_unique($cc_array);

                $join_mail = Bills::getJoinConfirmationMail($module_id);

                $input['join_mail'] = $join_mail;

                $bill_invoice = BillsDoc::getBillInvoice($module_id,'Invoice');

                $input['xls_attachment'] = public_path() . "/" . $bill_invoice['file'];

                /*$pdf_url = str_replace(".xls", ".pdf", $bill_invoice['file']);
                $input['pdf_attachment'] = public_path() . "/" . $pdf_url;*/
                
                \Mail::send('adminlte::emails.invoicegenerate', $input, function ($message) use ($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to'])->cc($input['cc_array'])->subject($input['subject']);
                    $message->attach($input['xls_attachment']);
                    //$message->attach($input['pdf_attachment']);
                });

                \DB::statement("UPDATE `emails_notification` SET `status`='$status' where `id` = '$email_notification_id'");
            }

            // Mail for Passive Client Listing
            else if ($value['module'] == 'Passive Client List') {

                $to_array = explode(",",$input['to']);
                //$cc_array = explode(",",$input['cc']);

                //$user_id = $value['module_id'];

                $client_res = ClientBasicinfo::getPassiveClients();
                $clients_count = sizeof($client_res);
                
                $input['client_res'] = $client_res;
                $input['clients_count'] = $clients_count;
                $input['to_array'] = array_unique($to_array);
                //$input['cc_array'] = array_unique($cc_array);

                \Mail::send('adminlte::emails.PassiveClients', $input, function ($message) use($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to_array'])->subject($input['subject']);
                });

                \DB::statement("UPDATE `emails_notification` SET `status`='$status' where `id` = '$email_notification_id'");
            }

            else if ($value['module'] == 'Client Bulk Email') {

                $to_array = explode(",",$input['to']);
                $cc_array = explode(",",$input['cc']);

                $input['to_array'] = $to_array;
                $input['cc_array'] = $cc_array;
              
                $input['module_id'] = $value['module_id'];
                $input['bulk_message'] = $value['message'];

                $client = ClientBasicinfo::getClientDetailsById($module_id);

                $input['owner_email'] = $client['am_email'];

                $user_details = User::getAllDetailsByUserID($value['sender_name']);

                $input['from_name'] = $user_details->first_name . " " . $user_details->last_name;

                $user_email_details = UsersEmailPwd::getUserEmailDetails($value['sender_name']);

                $input['from_address'] = trim($user_email_details->email);

                if(strpos($input['from_address'], '@gmail.com') !== false) {

                    config([
                        'mail.driver' => trim('mail'),
                        'mail.host' => trim('smtp.gmail.com'),
                        'mail.port' => trim('587'),
                        'mail.username' => trim($user_email_details->email),
                        'mail.password' => trim($user_email_details->password),
                        'mail.encryption' => trim('tls'),
                    ]);
                }
                else {

                    config([
                        'mail.driver' => trim('smtp'),
                        'mail.host' => trim('smtp.zoho.com'),
                        'mail.port' => trim('465'),
                        'mail.username' => trim($user_email_details->email),
                        'mail.password' => trim($user_email_details->password),
                        'mail.encryption' => trim('ssl'),
                    ]);
                }

                \Mail::send('adminlte::emails.clientbulkmail', $input, function ($message) use($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to_array'])->cc($input['cc_array'])->bcc($input['owner_email'])->replyTo($input['owner_email'], $input['from_name'])->subject($input['subject']);
                });

                \DB::statement("UPDATE `emails_notification` SET `status`='$status' where `id` = '$email_notification_id'");
            }

            else if ($value['module'] == 'Applicant Candidate') {

                $to_array = explode(",",$input['to']);
                $cc_array = explode(",",$input['cc']);

                $input['to_array'] = $to_array;
                $input['cc_array'] = $cc_array;
              
                $input['module_id'] = $value['module_id'];
                $candidate_details = CandidateBasicInfo::getCandidateDetailsById($input['module_id']);

                $input['candidate_details'] = $candidate_details;
                $input['resume'] = public_path() . $candidate_details['org_resume_path'];

                \Mail::send('adminlte::emails.applicantcandidatemail', $input, function ($message) use($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to_array'])->subject($input['subject']);
                    $message->attach($input['resume']);
                });

                \DB::statement("UPDATE `emails_notification` SET `status`='$status' where `id` = '$email_notification_id'");
            }

            // Mail for Expected Passive Client Listing in next week
            else if ($value['module'] == 'Expected Passive Client') {

                $to_array = explode(",",$input['to']);
                $cc_array = explode(",",$input['cc']);
                $client_ids = $value['module_id'];
                
                $client_res = ClientBasicinfo::getExpectedPassiveClients($client_ids);

                $clients_count = sizeof($client_res);
                
                $input['client_res'] = $client_res;
                $input['clients_count'] = $clients_count;
                $input['to_array'] = array_unique($to_array);
                $input['cc_array'] = array_unique($cc_array);

                \Mail::send('adminlte::emails.PassiveClients', $input, function ($message) use($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to_array'])->cc($input['cc_array'])->subject($input['subject']);
                });

                \DB::statement("UPDATE `emails_notification` SET `status`='$status' where `id` = '$email_notification_id'");
            }

            else if ($value['module'] == 'Monthly Report') {

                $recruitment = getenv('RECRUITMENT');
                $hr_advisory = getenv('HRADVISORY');
                $type_array = array($recruitment,$hr_advisory);

                $to_array = explode(",",$input['to']);
                $cc_array = explode(",",$input['cc']);

                $superAdminUserID = getenv('SUPERADMINUSERID');
                $managerUserID = getenv('MANAGERUSERID');

                $access_roles_id = array($superAdminUserID,$managerUserID);

                if(in_array($value['sender_name'],$access_roles_id)) {
                    
                    $users_array = User::getAllUsersExpectSuperAdmin($type_array);

                    $users = array();

                    if(isset($users_array) && sizeof($users_array) > 0) {

                        foreach ($users_array as $k1 => $v1) {
                                       
                            $user_details = User::getAllDetailsByUserID($k1);

                            if($user_details->type == '2') {
                                if($user_details->hr_adv_recruitemnt == 'Yes') {
                                    $users[$k1] = $v1;
                                }
                            }
                            else {
                                $users[$k1] = $v1;
                            }    
                        }
                    }
                }
                else {
                    $users = User::getAssignedUsers($value['sender_name']);
                }

                $response = array();

                // set 0 value for all users
                foreach ($users as $k => $v) {

                    $response[$k]['cvs'] = 0;
                    $response[$k]['interviews'] = 0;
                    $response[$k]['lead_count'] = 0;
                    $response[$k]['leads_data'] = 0;
                    $response[$k]['uname'] = $users[$k];
                }

                $month = date('m',strtotime('last month'));

                if($month == 12) {
                    $year = date('Y',strtotime('last year'));
                }
                else {
                    $year = date('Y');
                }

                $associate_response = JobAssociateCandidates::getUserWiseAssociatedCVS($users,$month,$year);

                foreach ($associate_response as $k => $v) {
                    $response[$k]['cvs'] = $v;
                }

                $interview_count = Interview::getUserWiseMonthlyReportInterview($users,$month,$year);

                if(sizeof($interview_count) > 0) {
                    foreach ($interview_count as $k => $v) {
                        $response[$k]['interviews'] = $v;
                    }
                }

                $lead_count = Lead::getUserWiseMonthlyReportLeadCount($users,$month,$year);

                if(isset($lead_count) && sizeof($lead_count) > 0) {
                    foreach ($lead_count as $k => $v) {
                        $response[$k]['lead_count'] = $v;
                    }
                }

                $leads_details = Lead::getUserWiseMonthlyReportLeads($users,$month,$year);

                if(isset($leads_details) && sizeof($leads_details) > 0) {
                    foreach ($leads_details as $k => $v) {
                        $response[$k]['leads_data'] = $v;
                    }
                }

                if(isset($leads_details) && sizeof($leads_details) > 0)
                    $total_leads = sizeof($leads_details);
                else
                    $total_leads = '';
                   
                $user_details = User::getAllDetailsByUserID($sender_id);

                $input['value'] = $user_details->name;
                $input['user_details'] = $user_details;
                $input['to_array'] = array_unique($to_array);
                $input['cc_array'] = array_unique($cc_array);

                $input['response'] = $response;
                $input['total_leads'] = $total_leads;

                \Mail::send('adminlte::emails.userwiseMonthlyReport', $input, function ($message) use ($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to_array'])->cc($input['cc_array'])->subject('Monthly Activity Report - ' . $input['value'] . ' - ' . date("F",strtotime("last month"))." ".date("Y"));
                });

                \DB::statement("UPDATE `emails_notification` SET `status`='$status' where `id` = '$email_notification_id'");
            }

            else if ($value['module'] == 'Productivity Report') {

                $to_array = explode(",",$input['to']);
                $cc_array = explode(",",$input['cc']);

                // Get user Bench Mark from master
                $user_bench_mark = UserBenchMark::getBenchMarkByUserID($sender_id);

                $year = date('Y');
                $month = date('m');
                $lastDayOfWeek = '7';

                // Get Weeks
                $weeks = Date::getWeeksInMonth($year, $month, $lastDayOfWeek);

                // Set new weeks
                $new_weeks = array();

                if(isset($weeks) && sizeof($weeks) == 6) {

                    // Week1
                    $new_weeks[0]['from_date'] = $weeks[0]['from_date'];
                    $new_weeks[0]['to_date'] = $weeks[1]['to_date'];

                    // Week2
                    $new_weeks[1]['from_date'] = $weeks[2]['from_date'];
                    $new_weeks[1]['to_date'] = $weeks[2]['to_date'];

                    // Week3
                    $new_weeks[2]['from_date'] = $weeks[3]['from_date'];
                    $new_weeks[2]['to_date'] = $weeks[3]['to_date'];

                    // Week4
                    $new_weeks[3]['from_date'] = $weeks[4]['from_date'];
                    $new_weeks[3]['to_date'] = $weeks[5]['to_date'];
                }
                else if(isset($weeks) && sizeof($weeks) == 5) {

                    $date1 = $weeks[0]['from_date'];
                    $date2 = $weeks[0]['to_date'];

                    $diff = (strtotime($date2) - strtotime($date1))/24/3600;
                    
                    if($diff > 2) {

                        // Week1
                        $new_weeks[0]['from_date'] = $weeks[0]['from_date'];
                        $new_weeks[0]['to_date'] = $weeks[0]['to_date'];

                        // Week2
                        $new_weeks[1]['from_date'] = $weeks[1]['from_date'];
                        $new_weeks[1]['to_date'] = $weeks[1]['to_date'];

                        // Week3
                        $new_weeks[2]['from_date'] = $weeks[2]['from_date'];
                        $new_weeks[2]['to_date'] = $weeks[2]['to_date'];

                        // Week4
                        $last_date1 = $weeks[4]['from_date'];
                        $last_date2 = $weeks[4]['to_date'];

                        $last_diff = (strtotime($last_date2) - strtotime($last_date1))/24/3600;

                        if($last_diff > 1) {

                            $new_weeks[3]['from_date'] = $weeks[3]['from_date'];
                            $new_weeks[3]['to_date'] = $weeks[3]['to_date'];

                            $new_weeks[4]['from_date'] = $weeks[4]['from_date'];
                            $new_weeks[4]['to_date'] = $weeks[4]['to_date'];
                        }
                        else {

                            $new_weeks[3]['from_date'] = $weeks[3]['from_date'];
                            $new_weeks[3]['to_date'] = $weeks[4]['to_date'];
                        }
                    }
                    else {

                        // Week1
                        $new_weeks[0]['from_date'] = $weeks[0]['from_date'];
                        $new_weeks[0]['to_date'] = $weeks[1]['to_date'];

                        // Week2
                        $new_weeks[1]['from_date'] = $weeks[2]['from_date'];
                        $new_weeks[1]['to_date'] = $weeks[2]['to_date'];

                        // Week3
                        $new_weeks[2]['from_date'] = $weeks[3]['from_date'];
                        $new_weeks[2]['to_date'] = $weeks[3]['to_date'];

                        // Week4
                        $new_weeks[3]['from_date'] = $weeks[4]['from_date'];
                        $new_weeks[3]['to_date'] = $weeks[4]['to_date'];
                    }
                }
                else {

                    // Set all Weeks
                    $new_weeks = $weeks;
                }

                // Get no of weeks in month & get from date & to date
                $i=1;
                $frm_to_date_array = array();

                if(isset($new_weeks) && $new_weeks != '') {

                    foreach ($new_weeks as $key => $value) {

                        $no_of_weeks = $i;

                        $frm_to_date_array[$i]['from_date'] = $value['from_date'];
                        $frm_to_date_array[$i]['to_date'] = $value['to_date'];

                        // Get no of cv's associated count in this week
                        $frm_to_date_array[$i]['ass_cnt'] = JobAssociateCandidates::getProductivityReportCVCount($sender_id,$frm_to_date_array[$i]['from_date'],$frm_to_date_array[$i]['to_date']);

                        // Get no of shortlisted candidate count in this week
                        $frm_to_date_array[$i]['shortlisted_cnt'] = JobAssociateCandidates::getProductivityReportShortlistedCount($sender_id,$frm_to_date_array[$i]['from_date'],$frm_to_date_array[$i]['to_date']);

                        // Get no of interview of candidates count in this week
                        $frm_to_date_array[$i]['interview_cnt'] = Interview::getProductivityReportInterviewCount($sender_id,$frm_to_date_array[$i]['from_date'],$frm_to_date_array[$i]['to_date']);

                        // Get no of selected candidate count in this week
                        $frm_to_date_array[$i]['selected_cnt'] = JobAssociateCandidates::getProductivityReportSelectedCount($sender_id,$frm_to_date_array[$i]['from_date'],$frm_to_date_array[$i]['to_date']);

                        // Get no of offer acceptance count in this week
                        $frm_to_date_array[$i]['offer_acceptance_ratio'] = Bills::getProductivityReportOfferAcceptanceRatio($sender_id,$frm_to_date_array[$i]['from_date'],$frm_to_date_array[$i]['to_date']);

                        // Get no of joining count in this week
                        $frm_to_date_array[$i]['joining_ratio'] = Bills::getProductivityReportJoiningRatio($sender_id,$frm_to_date_array[$i]['from_date'],$frm_to_date_array[$i]['to_date']);

                        // Get no of after joining success count in this week
                        $frm_to_date_array[$i]['joining_success_ratio'] = Bills::getProductivityReportJoiningSuccessRatio($sender_id,$frm_to_date_array[$i]['from_date'],$frm_to_date_array[$i]['to_date']);

                        $i++;
                    }
                }

                if(isset($user_bench_mark) && sizeof($user_bench_mark) > 0) {
                    
                    $user_bench_mark['no_of_resumes_monthly'] = $user_bench_mark['no_of_resumes'];
                    $user_bench_mark['no_of_resumes_weekly'] = number_format($user_bench_mark['no_of_resumes'] / $no_of_weeks);

                    $user_bench_mark['shortlist_ratio_monthly'] = number_format($user_bench_mark['no_of_resumes'] * $user_bench_mark['shortlist_ratio']/100);
                    $user_bench_mark['shortlist_ratio_weekly'] = number_format($user_bench_mark['shortlist_ratio_monthly'] / $no_of_weeks);

                    $user_bench_mark['interview_ratio_monthly'] = number_format($user_bench_mark['shortlist_ratio_monthly'] * $user_bench_mark['interview_ratio'] / 100);
                    $user_bench_mark['interview_ratio_weekly'] = number_format($user_bench_mark['interview_ratio_monthly'] / $no_of_weeks);

                    $user_bench_mark['selection_ratio_monthly'] = number_format($user_bench_mark['interview_ratio_monthly'] * $user_bench_mark['selection_ratio'] / 100);
                    $user_bench_mark['selection_ratio_weekly'] = number_format($user_bench_mark['selection_ratio_monthly'] / $no_of_weeks);

                    $user_bench_mark['offer_acceptance_ratio_monthly'] = number_format($user_bench_mark['selection_ratio_monthly'] * $user_bench_mark['offer_acceptance_ratio'] / 100);
                    $user_bench_mark['offer_acceptance_ratio_weekly'] = number_format($user_bench_mark['offer_acceptance_ratio_monthly'] / $no_of_weeks);

                    $user_bench_mark['joining_ratio_monthly'] = number_format($user_bench_mark['offer_acceptance_ratio_monthly'] * $user_bench_mark['joining_ratio'] / 100);
                    $user_bench_mark['joining_ratio_weekly'] = number_format($user_bench_mark['joining_ratio_monthly'] / $no_of_weeks);

                    $user_bench_mark['after_joining_success_ratio_monthly'] = number_format($user_bench_mark['joining_ratio_monthly'] * $user_bench_mark['after_joining_success_ratio'] / 100);
                    $user_bench_mark['after_joining_success_ratio_weekly'] = number_format($user_bench_mark['after_joining_success_ratio_monthly'] / $no_of_weeks);
                }

                // Set last column Monthly Achivment value

                if(isset($frm_to_date_array) && $frm_to_date_array != '') {

                    $no_of_resumes_monthly = '';
                    $shortlist_ratio_monthly = '';
                    $interview_ratio_monthly = '';
                    $selection_ratio_monthly = '';
                    $offer_acceptance_ratio_monthly = '';
                    $joining_ratio_monthly = '';
                    $after_joining_success_ratio_monthly = '';

                    foreach ($frm_to_date_array as $key => $value) {

                        if($no_of_resumes_monthly == '') {

                            $no_of_resumes_monthly = $value['ass_cnt'];
                        }
                        else {

                            $no_of_resumes_monthly = $no_of_resumes_monthly + $value['ass_cnt'];
                        }

                        if($shortlist_ratio_monthly == '') {

                            $shortlist_ratio_monthly = $value['shortlisted_cnt'];
                        }
                        else {

                            $shortlist_ratio_monthly = $shortlist_ratio_monthly + $value['shortlisted_cnt'];
                        }

                        if($interview_ratio_monthly == '') {

                            $interview_ratio_monthly = $value['interview_cnt'];
                        }
                        else {

                            $interview_ratio_monthly = $interview_ratio_monthly + $value['interview_cnt'];
                        }

                        if($selection_ratio_monthly == '') {

                            $selection_ratio_monthly = $value['selected_cnt'];
                        }
                        else {

                            $selection_ratio_monthly =  $selection_ratio_monthly + $value['selected_cnt'];
                        }

                        if($offer_acceptance_ratio_monthly == '') {

                            $offer_acceptance_ratio_monthly = $value['offer_acceptance_ratio'];
                        }
                        else {

                            $offer_acceptance_ratio_monthly = $offer_acceptance_ratio_monthly + $value['offer_acceptance_ratio'];
                        }

                        if($joining_ratio_monthly == '') {

                            $joining_ratio_monthly = $value['joining_ratio'];
                        }
                        else {

                            $joining_ratio_monthly = $joining_ratio_monthly + $value['joining_ratio'];
                        }

                        if($after_joining_success_ratio_monthly == '') {

                            $after_joining_success_ratio_monthly = $value['joining_success_ratio'];
                        }
                        else {

                            $after_joining_success_ratio_monthly = $after_joining_success_ratio_monthly + $value['joining_success_ratio'];
                        }
                    }
                }

                // Get user name
                $user_details = User::getAllDetailsByUserID($sender_id);

                $input['user_bench_mark'] = $user_bench_mark;
                $input['no_of_weeks'] = $no_of_weeks;
                $input['frm_to_date_array'] = $frm_to_date_array;
                $input['to_array'] = array_unique($to_array);
                $input['cc_array'] = array_unique($cc_array);
                $input['user_name'] = $user_details->name;

                // Set last column Monthly Achivment value

                if(isset($no_of_resumes_monthly) && $no_of_resumes_monthly > 0) {
                    $input['no_of_resumes_monthly'] = $no_of_resumes_monthly;
                }
                else {
                    $input['no_of_resumes_monthly'] = '';
                }

                if(isset($shortlist_ratio_monthly) && $shortlist_ratio_monthly > 0) {
                    $input['shortlist_ratio_monthly'] = $shortlist_ratio_monthly;
                }
                else {
                    $input['shortlist_ratio_monthly'] = '';
                }

                if(isset($interview_ratio_monthly) && $interview_ratio_monthly > 0) {
                    $input['interview_ratio_monthly'] = $interview_ratio_monthly;
                }
                else {
                    $input['interview_ratio_monthly'] = '';
                }

                if(isset($selection_ratio_monthly) && $selection_ratio_monthly > 0) {
                    $input['selection_ratio_monthly'] = $selection_ratio_monthly;
                }
                else {
                    $input['selection_ratio_monthly'] = '';
                }

                if(isset($offer_acceptance_ratio_monthly) && $offer_acceptance_ratio_monthly > 0) {
                    $input['offer_acceptance_ratio_monthly'] = $offer_acceptance_ratio_monthly;
                }
                else {
                    $input['offer_acceptance_ratio_monthly'] = '';
                }

                if(isset($joining_ratio_monthly) && $joining_ratio_monthly > 0) {
                    $input['joining_ratio_monthly'] = $joining_ratio_monthly;
                }
                else {
                    $input['joining_ratio_monthly'] = '';
                }
                
                if(isset($after_joining_success_ratio_monthly) && $after_joining_success_ratio_monthly > 0) {
                    $input['after_joining_success_ratio_monthly'] = $after_joining_success_ratio_monthly;
                }
                else {
                    $input['after_joining_success_ratio_monthly'] = '';
                }
                
                \Mail::send('adminlte::emails.ProductivityReport', $input, function ($message) use($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to_array'])->cc($input['cc_array'])->subject('Productivity Report -'.$input['user_name']);
                });

                \DB::statement("UPDATE `emails_notification` SET `status`='$status' where `id` = '$email_notification_id'");
            }

            else if ($value['module'] == 'New Candidate AutoScript Mail') {

                $candidate_details = CandidateBasicInfo::getCandidateDetailsById($module_id);

                $input['candidate_name'] = $candidate_details['full_name'];
                $input['owner_email'] = $candidate_details['owner_email'];

                $input['from_name'] = $candidate_details['owner_first_name'] . " " . $candidate_details['owner_last_name'];

                if($input['owner_email'] == 'careers@adlertalent.com') {

                    \Mail::send('adminlte::emails.candidateAutoScriptMail', $input, function ($message) use($input) {

                        $message->from($input['from_address'], $input['from_name']);
                        $message->to($input['to'])->replyTo($input['owner_email'], $input['from_name'])->subject($input['subject']);
                    });
                }
                else {

                    \Mail::send('adminlte::emails.candidateAutoScriptMail', $input, function ($message) use($input) {

                        $message->from($input['from_address'], $input['from_name']);
                        $message->to($input['to'])->bcc($input['owner_email'])->replyTo($input['owner_email'], $input['from_name'])->subject($input['subject']);
                    });
                }

                \DB::statement("UPDATE `emails_notification` SET `status`='$status' where `id` = '$email_notification_id'");

                \DB::statement("UPDATE `candidate_basicinfo` SET `autoscript_status` = '1' where id = '$module_id';");
            }

            else if ($value['module'] == 'Client 2nd Line Account Manager') {

                $to_array = explode(",",$input['to']);
                $cc_array = explode(",",$input['cc']);

                $client = ClientBasicinfo::getClientDetailsById($module_id);

                $input['module_id'] = $value['module_id'];
                $input['to_array'] = $to_array;
                $input['cc_array'] = $cc_array;
                $input['client'] = $client;

                \Mail::send('adminlte::emails.clientsecondlineamemail', $input, function ($message) use($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to_array'])->cc($input['cc_array'])->subject($input['subject']);
                });

                \DB::statement("UPDATE `emails_notification` SET `status`='$status' where `id` = '$email_notification_id'");
            }

            else if ($value['module'] == '2nd Line of Multiple Clients') {

                $to_array = explode(",",$input['to']);
                $cc_array = explode(",",$input['cc']);

                $client_ids_array = explode(",",$module_id);
                $client_info = array();
                $i=0;

                foreach($client_ids_array as $key => $value) {

                    $client = ClientBasicinfo::getClientDetailsById($value);
                    
                    $client_info[$i]['am_name'] = $client['am_name'];
                    $client_info[$i]['name'] = $client['name'];
                    $client_info[$i]['coordinator_name'] = $client['coordinator_name'];
                    $client_info[$i]['second_line_am_name'] = $client['second_line_am_name'];
                    
                    $i++;
                }

                $input['second_line_am_name'] = $client['second_line_am_name'];
                $input['client_info'] = $client_info;
                $input['to_array'] = $to_array;
                $input['cc_array'] = $cc_array;

                \Mail::send('adminlte::emails.multipleclientsecondlineamemail', $input, function ($message) use($input) {
                    
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to_array'])->cc($input['cc_array'])->subject($input['subject']);
                });

                \DB::statement("UPDATE `emails_notification` SET `status`='$status' where `id` = '$email_notification_id'");
            }

            else if ($value['module'] == 'Lead Bulk Email') {

                $to_array = explode(",",$input['to']);
                $cc_array = explode(",",$input['cc']);

                $input['to_array'] = $to_array;
                $input['cc_array'] = $cc_array;
              
                $input['module_id'] = $value['module_id'];
                $input['bulk_message'] = $value['message'];

                $input['from_email'] = User::getUserEmailById($value['sender_name']);

                $user_details = User::getAllDetailsByUserID($value['sender_name']);

                $input['from_name'] = $user_details->first_name . " " . $user_details->last_name;

                $user_email_details = UsersEmailPwd::getUserEmailDetails($value['sender_name']);

                $input['from_address'] = trim($user_email_details->email);

                if(strpos($input['from_address'], '@gmail.com') !== false) {

                    config([

                        'mail.driver' => trim('mail'),
                        'mail.host' => trim('smtp.gmail.com'),
                        'mail.port' => trim('587'),
                        'mail.username' => trim($user_email_details->email),
                        'mail.password' => trim($user_email_details->password),
                        'mail.encryption' => trim('tls'),
                    ]);
                }
                else {

                    config([
                        'mail.driver' => trim('smtp'),
                        'mail.host' => trim('smtp.zoho.com'),
                        'mail.port' => trim('465'),
                        'mail.username' => trim($user_email_details->email),
                        'mail.password' => trim($user_email_details->password),
                        'mail.encryption' => trim('ssl'),
                    ]);
                }

                \Mail::send('adminlte::emails.clientbulkmail', $input, function ($message) use($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to_array'])->cc($input['cc_array'])->bcc($input['from_email'])->replyTo($input['from_email'], $input['from_name'])->subject($input['subject']);
                });

                \DB::statement("UPDATE `emails_notification` SET `status`='$status' where `id` = '$email_notification_id'");
            }

            else if ($value['module'] == "Today's Interviews") {

                $from_date = date("Y-m-d 00:00:00");
                $to_date = date("Y-m-d 23:59:59");

                $type_array = array();
                $file_path_array = array();
                $j=0;

                $interviews = Interview::getAllInterviewsByReminders($value['sender_name'],$from_date,$to_date);

                if(isset($interviews) && sizeof($interviews) > 0) {

                    foreach ($interviews as $key1 => $value1) {

                        if(isset($value1) && $value1 != '') {

                            $type_array[$j] = $value1['interview_type'];

                            // Candidate Attachments
                            $attachments = CandidateUploadedResume::getCandidateAttachment($value1['candidate_id']);

                            if(isset($attachments) && sizeof($attachments) > 0) {

                                $file_path_all = array();
                                $i=0;

                                foreach ($attachments as $attach_key => $attach_val) {

                                    if (isset($attach_val) && $attach_val != '') {

                                        $file_path = public_path() . "/" . $attach_val->file;
                                    }
                                    else {
                                        $file_path = '';
                                    }
                                    
                                    $file_path_all[$i] = $file_path;
                                    $i++;
                                }
                            }
                            else {

                                $file_path_all = array();
                            }

                            $file_path_array[$j] = $file_path_all;

                            $j++;
                        }
                    }
                }

                $to_array = explode(",",$input['to']);
                $input['to_array'] = $to_array;
                $input['type_string'] = implode(",", $type_array);
                $input['file_path'] = $file_path_array;
                $input['interview_details'] = $interviews;

                \Mail::send('adminlte::emails.interviewmultipleschedule', $input, function ($message) use($input) {

                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to_array'])->subject($input['subject']);

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

                \DB::statement("UPDATE `emails_notification` SET `status`='$status' where `id` = '$email_notification_id'");
            }

            else if ($value['module'] == "Yesterday's Interviews") {

                $from_date = date('Y-m-d 00:00:00',strtotime("-1 days"));
                $to_date = date("Y-m-d 23:59:59", strtotime("-1 days"));

                $type_array = array();
                $file_path_array = array();
                $j=0;

                $interviews = Interview::getAllInterviewsByReminders($value['sender_name'],$from_date,$to_date);

                if(isset($interviews) && sizeof($interviews) > 0) {

                    foreach ($interviews as $key1 => $value1) {

                        if(isset($value1) && $value1 != '') {

                            $type_array[$j] = $value1['interview_type'];

                            // Candidate Attachments
                            $attachments = CandidateUploadedResume::getCandidateAttachment($value1['candidate_id']);

                            if(isset($attachments) && sizeof($attachments) > 0) {

                                $file_path_all = array();
                                $i=0;

                                foreach ($attachments as $attach_key => $attach_val) {

                                    if (isset($attach_val) && $attach_val != '') {

                                        $file_path = public_path() . "/" . $attach_val->file;
                                    }
                                    else {
                                        $file_path = '';
                                    }
                                    
                                    $file_path_all[$i] = $file_path;
                                    $i++;
                                }
                            }
                            else {

                                $file_path_all = array();
                            }

                            $file_path_array[$j] = $file_path_all;

                            $j++;
                        }
                    }
                }

                $to_array = explode(",",$input['to']);
                $input['to_array'] = $to_array;
                $input['type_string'] = implode(",", $type_array);
                $input['file_path'] = $file_path_array;
                $input['interview_details'] = $interviews;
                $input['yesterday_date'] = date('Y-m-d',strtotime("-1 days"));

                \Mail::send('adminlte::emails.interviewmultipleschedule', $input, function ($message) use($input) {

                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to_array'])->subject($input['subject']);

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

                \DB::statement("UPDATE `emails_notification` SET `status`='$status' where `id` = '$email_notification_id'");
            }

            else if ($value['module'] == "Interview Reminder") {

                $interview_ids_array = explode(",",$module_id);

                if(isset($interview_ids_array) && sizeof($interview_ids_array) > 0) {

                    $type_array = array();
                    $file_path_array = array();
                    $j=0;

                    $interviews = array();

                    foreach ($interview_ids_array as $k1 => $v1) {

                        if(isset($v1) && $v1 != '') {

                            $get_interview_by_id = Interview::getInterviewById($v1);

                            $type_array[$j] = $get_interview_by_id->interview_type;

                            // Candidate Attachments
                            $attachments = CandidateUploadedResume::getCandidateAttachment($get_interview_by_id->candidate_id);

                            if(isset($attachments) && sizeof($attachments) > 0) {

                                $file_path_all = array();
                                $i=0;

                                foreach ($attachments as $attach_key => $attach_val) {

                                    if (isset($attach_val) && $attach_val != '') {

                                        $file_path = public_path() . "/" . $attach_val->file;
                                    }
                                    else {
                                        $file_path = '';
                                    }
                                    
                                    $file_path_all[$i] = $file_path;
                                    $i++;
                                }
                            }
                            else {

                                $file_path_all = array();
                            }

                            $file_path_array[$j] = $file_path_all;
                            $interviews[$j] = $get_interview_by_id;
                        }
                        $j++;
                    }
                }

                if(isset($interviews) && sizeof($interviews) > 0) {

                    $interview_details = array();
                    $i=0;

                    foreach ($interviews as $k2 => $v2) {

                        $interview_details[$i]['id'] = $v2['id'];
                        $interview_details[$i]['client_name'] = $v2['client_name'];
                        $interview_details[$i]['job_designation'] = $v2['posting_title'];

                        if($v2['remote_working'] == '1') {

                            $interview_details[$i]['job_location'] = "Remote";
                        }
                        else {

                            $interview_details[$i]['job_location'] = $v2['job_city'];
                        }

                        $interview_details[$i]['interview_type'] = $v2['interview_type'];
                        $interview_details[$i]['interview_location'] = $v2['interview_location'];
                        
                        $interview_details[$i]['cname'] = $v2['full_name'];
                        $interview_details[$i]['cemail'] = $v2['candidate_email'];
                        $interview_details[$i]['cmobile'] = $v2['candidate_mobile'];
                        $interview_details[$i]['candidate_location'] = $v2['candidate_location'];
                        $interview_details[$i]['skype_id'] = $v2['skype_id'];

                        $datearray = explode(' ', $v2['interview_date']);
                        $interview_date = $datearray[0];
                        $interview_time = $datearray[1];
                        $interview_details[$i]['interview_date'] = $interview_date;
                        $interview_details[$i]['interview_time'] = $interview_time;

                        $i++;   
                    }
                }

                if(isset($interview_details) && sizeof($interview_details) > 0) {
                    
                    $to_array = explode(",",$input['to']);
                    $input['to_array'] = $to_array;
                    $input['type_string'] = implode(",", $type_array);
                    $input['file_path'] = $file_path_array;
                    $input['interview_details'] = $interview_details;

                    \Mail::send('adminlte::emails.interviewmultipleschedule', $input, function ($message) use($input) {

                        $message->from($input['from_address'], $input['from_name']);
                        $message->to($input['to_array'])->subject($input['subject']);

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

                    \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
                }
            }

            else if ($value['module'] == 'Update User Informations') {

                $to_array = explode(",",$input['to']);

                // Get users for popup of add information
                $users_array = User::getBefore7daysUsersDetails();

                if(isset($users_array) && sizeof($users_array) > 0) {

                    $input['users_array'] = $users_array;
                    $input['to_array'] = $to_array;

                     \Mail::send('adminlte::emails.adduserotherinformationsemail', $input, function ($message) use($input) {
                    
                        $message->from($input['from_address'], $input['from_name']);
                        $message->to($input['to_array'])->subject($input['subject']);
                    });

                    \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
                }
            }

            else if ($value['module'] == 'Email Template') {

                $to_array = explode(",",$input['to']);
                $cc_array = explode(",",$input['cc']);

                // Get users for popup of add information
                $email_template_details = EmailTemplate::getEmailTemplateDetailsById($value['module_id']);

                if(isset($email_template_details) && sizeof($email_template_details) > 0) {

                    $input['email_template_details'] = $email_template_details;
                    $input['to_array'] = $to_array;
                    $input['cc_array'] = $cc_array;

                     \Mail::send('adminlte::emails.newemailtemplatemail', $input, function ($message) use($input) {
                    
                        $message->from($input['from_address'], $input['from_name']);
                        $message->to($input['to_array'])->cc($input['cc_array'])->subject($input['subject']);
                    });

                    \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
                }
            }

            else if ($value['module'] == 'New User') {

                $to_array = explode(",",$input['to']);
                $cc_array = explode(",",$input['cc']);

                // Get users for popup of add information
                $users_details = User::getProfileInfo($value['module_id']);

                if(isset($users_details) && $users_details != '') {

                    $input['users_details'] = $users_details;
                    $input['to_array'] = $to_array;
                    $input['cc_array'] = $cc_array;

                     \Mail::send('adminlte::emails.useraddemail', $input, function ($message) use($input) {
                    
                        $message->from($input['from_address'], $input['from_name']);
                        $message->to($input['to_array'])->cc($input['cc_array'])->subject($input['subject']);
                    });

                    \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
                }
            }

            else if ($value['module'] == 'Contactsphere' || $value['module'] == 'Hold Contact' || $value['module'] == 'Relive Hold Contact' || $value['module'] == 'Forbid Contact' || $value['module'] == 'Relive Forbid Contact') {

                $to_array = explode(",",$input['to']);
                $cc_array = explode(",",$input['cc']);

                // Get users for popup of add information
                $contact_details = Contactsphere::getContactDetailsById($value['module_id']);

                if(isset($contact_details) && $contact_details != '') {

                    $input['contact_details'] = $contact_details;
                    $input['to_array'] = $to_array;
                    $input['cc_array'] = $cc_array;

                     \Mail::send('adminlte::emails.contactspheremail', $input, function ($message) use($input) {
                    
                        $message->from($input['from_address'], $input['from_name']);
                        $message->to($input['to_array'])->cc($input['cc_array'])->subject($input['subject']);
                    });

                    \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
                }
            }

            else if ($value['module'] == 'Contact Bulk Email') {

                $to_array = explode(",",$input['to']);
                $cc_array = explode(",",$input['cc']);

                $input['to_array'] = $to_array;
                $input['cc_array'] = $cc_array;
              
                $input['module_id'] = $value['module_id'];
                $input['bulk_message'] = $value['message'];

                $input['from_email'] = User::getUserEmailById($value['sender_name']);

                $user_details = User::getAllDetailsByUserID($value['sender_name']);

                $input['from_name'] = $user_details->first_name . " " . $user_details->last_name;

                $user_email_details = UsersEmailPwd::getUserEmailDetails($value['sender_name']);

                $input['from_address'] = trim($user_email_details->email);

                if(strpos($input['from_address'], '@gmail.com') !== false) {

                    config([

                        'mail.driver' => trim('mail'),
                        'mail.host' => trim('smtp.gmail.com'),
                        'mail.port' => trim('587'),
                        'mail.username' => trim($user_email_details->email),
                        'mail.password' => trim($user_email_details->password),
                        'mail.encryption' => trim('tls'),
                    ]);
                }
                else {

                    config([
                        'mail.driver' => trim('smtp'),
                        'mail.host' => trim('smtp.zoho.com'),
                        'mail.port' => trim('465'),
                        'mail.username' => trim($user_email_details->email),
                        'mail.password' => trim($user_email_details->password),
                        'mail.encryption' => trim('ssl'),
                    ]);
                }

                \Mail::send('adminlte::emails.clientbulkmail', $input, function ($message) use($input) {

                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to_array'])->cc($input['cc_array'])->bcc($input['from_email'])->replyTo($input['from_email'], $input['from_name'])->subject($input['subject']);
                });

                \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
            }

            else if ($value['module'] == 'Candidate Information Form') {

                $to_array = explode(",",$input['to']);

                $split_module_id = explode("-",$value['module_id']);

                $candidate_id = $split_module_id[0];
                $job_id = $split_module_id[1];

                // Get users for popup of add information
                $candidate_job_details = CandidateBasicInfo::getCandidateJobDetailsById($candidate_id,$job_id);

                $user_email_details = UsersEmailPwd::getUserEmailDetails($candidate_job_details['owner_id']);

                $input['from_address'] = trim($user_email_details->email);

                if(strpos($input['from_address'], '@gmail.com') !== false) {

                    config([

                        'mail.driver' => trim('mail'),
                        'mail.host' => trim('smtp.gmail.com'),
                        'mail.port' => trim('587'),
                        'mail.username' => trim($user_email_details->email),
                        'mail.password' => trim($user_email_details->password),
                        'mail.encryption' => trim('tls'),
                    ]);
                }
                else {

                    config([
                        'mail.driver' => trim('smtp'),
                        'mail.host' => trim('smtp.zoho.com'),
                        'mail.port' => trim('465'),
                        'mail.username' => trim($user_email_details->email),
                        'mail.password' => trim($user_email_details->password),
                        'mail.encryption' => trim('ssl'),
                    ]);
                }

                if(isset($candidate_job_details) && $candidate_job_details != '') {

                    // Get candidate owner signature
                    $owner_id = $candidate_job_details['owner_id'];
                    $owner_info = User::getProfileInfo($owner_id);
                    $input['owner_signature'] = $owner_info['signature'];

                    $input['from_name'] = $candidate_job_details['owner_first_name'] . " " . $candidate_job_details['owner_last_name'];
                    
                    $input['candidate_job_details'] = $candidate_job_details;
                    $input['to_array'] = $to_array;
                    $input['bcc_email'] = $candidate_job_details['owner_email'];
                    $input['attachment'] = public_path() . "/" . 'uploads/Candidate_Information_Form_Adler.docx';

                     \Mail::send('adminlte::emails.candidateinformationform', $input, function ($message) use($input) {
                    
                        $message->from($input['from_address'], $input['from_name']);
                        $message->to($input['to_array'])->bcc($input['bcc_email'])->replyTo($input['bcc_email'], $input['from_name'])->subject($input['subject']);

                        if (isset($input['attachment']) && $input['attachment'] != '') {
                            $message->attach($input['attachment']);
                        }
                    });

                    \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
                }
            }

            else if ($value['module'] == 'Add User Salary Information') {

                $to_array = explode(",",$input['to']);
                $cc_array = explode(",",$input['cc']);

                // Get users for popup of add information
                $users_array = User::getBefore7daysUserSalaryDetails();

                if(isset($users_array) && sizeof($users_array) > 0) {

                    $input['users_array'] = $users_array;
                    $input['to_array'] = $to_array;
                    $input['cc_array'] = $cc_array;

                     \Mail::send('adminlte::emails.addusersalaryinformationsemail', $input, function ($message) use($input) {
                    
                        $message->from($input['from_address'], $input['from_name']);
                        $message->to($input['to_array'])->cc($input['cc_array'])->subject($input['subject']);
                    });

                    \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
                }
            }

            else if ($value['module'] == 'Applicant Candidates Report') {

                $to_array = explode(",",$input['to']);

                $jobs = JobOpen::getJobsByMB($value['sender_name']);

                if(isset($jobs) && sizeof($jobs) > 0) {

                    foreach ($jobs as $k1 => $v1) {

                        if(isset($v1['applicant_candidates']) && sizeof($v1['applicant_candidates']) > 0) {

                            $input['applicant_candidates'] = $v1['applicant_candidates'];
                            $input['to_array'] = $to_array;

                            \Mail::send('adminlte::emails.applicantcandidatesreport', $input, function ($message) use($input) {

                                $message->from($input['from_address'], $input['from_name']);
                                $message->to($input['to_array'])->subject($input['subject']);
                            });

                            \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
                        }
                    }
                }
            }

            else if ($value['module'] == 'Update User') {

                // Get users for popup of add information
                $users_details = User::getProfileInfo($value['module_id']);

                if(isset($users_details) && $users_details != '') {

                    \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
                }
            }

            else if ($value['module'] == 'Ticket Discussion') {

                $to_array = explode(",",$input['to']);
                $cc_array = explode(",",$input['cc']);

                // Get users for popup of add information
                $ticket_res = TicketsDiscussion::getTicketDetailsById($value['module_id']);
                $ticket_res_doc = TicketsDiscussionDoc::getTicketDocsById($value['module_id']);

                if (isset($ticket_res_doc) && $ticket_res_doc != '') {

                    $file_path_array = array();
                    $j=0;

                    foreach ($ticket_res_doc as $k => $v) {

                        $file_path = public_path() . "/" . $v['fileName'];
                        $file_path_array[$j] = $file_path;
                        $j++;
                    }
                }

                if(isset($ticket_res) && sizeof($ticket_res) > 0) {

                    $input['ticket_res'] = $ticket_res;
                    $input['to_array'] = $to_array;
                    $input['cc_array'] = $cc_array;

                    if (isset($file_path_array) && sizeof($file_path_array) > 0) {

                        $input['file_path'] = $file_path_array;
                    }

                    \Mail::send('adminlte::emails.ticketdiscussionemail', $input, function ($message) use($input) {
                    
                        $message->from($input['from_address'], $input['from_name']);
                        $message->to($input['to_array'])->cc($input['cc_array'])->subject($input['subject']);

                        if (isset($input['file_path']) && sizeof($input['file_path']) > 0) {

                            foreach ($input['file_path'] as $key => $value) {

                                if(isset($value) && $value != '') {
                                    $message->attach($value);
                                }
                            }
                        }
                    });

                    \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
                }
            }

            else if ($value['module'] == 'Ticket Discussion Comment') {

                $to_array = explode(",",$input['to']);
                $cc_array = explode(",",$input['cc']);

                // Get users for popup of add information
                $post_res = TicketDiscussionPost::getTicketPostDetailsById($value['module_id']);
                $ticket_post_res_doc = TicketsDiscussionPostDoc::getTicketPostDocsById($value['module_id']);

                if (isset($ticket_post_res_doc) && $ticket_post_res_doc != '') {

                    $file_path_array = array();
                    $j=0;

                    foreach ($ticket_post_res_doc as $k => $v) {

                        $file_path = public_path() . "/" . $v['fileName'];
                        $file_path_array[$j] = $file_path;
                        $j++;
                    }
                }

                // Get Ticket Details from Post ID
                $ticket_res = TicketsDiscussion::getTicketDetailsById($post_res['tickets_discussion_id']);

                if(isset($post_res) && sizeof($post_res) > 0) {

                    $input['ticket_res'] = $ticket_res;
                    $input['post_res'] = $post_res;
                    $input['to_array'] = $to_array;
                    $input['cc_array'] = $cc_array;

                    if (isset($file_path_array) && sizeof($file_path_array) > 0) {

                        $input['file_path'] = $file_path_array;
                    }

                    \Mail::send('adminlte::emails.ticketdiscussionpostemail', $input, function ($message) use($input) {
                    
                        $message->from($input['from_address'], $input['from_name']);
                        $message->to($input['to_array'])->cc($input['cc_array'])->subject($input['subject']);

                        if (isset($input['file_path']) && sizeof($input['file_path']) > 0) {

                            foreach ($input['file_path'] as $key => $value) {

                                if(isset($value) && $value != '') {
                                    $message->attach($value);
                                }
                            }
                        }
                    });

                    \DB::statement("UPDATE emails_notification SET `status`='$status' where `id` = '$email_notification_id'");
                }
            }

            else if ($value['module'] == 'Work Planning') {

                $cc_array = explode(",",$input['cc']);
                $input['cc_array'] = $cc_array;
              
                $input['module_id'] = $value['module_id'];

                $user_details = User::getAllDetailsByUserID($value['sender_name']);
                $input['from_name'] = $user_details->first_name . " " . $user_details->last_name;
                $input['owner_email'] = $user_details->email;

                $user_info = User::getProfileInfo($value['sender_name']);
                $input['signature'] = $user_info['signature'];

                $user_email_details = UsersEmailPwd::getUserEmailDetails($value['sender_name']);
                $input['from_address'] = trim($user_email_details->email);

                if(strpos($input['from_address'], '@gmail.com') !== false) {

                    config([

                        'mail.driver' => trim('mail'),
                        'mail.host' => trim('smtp.gmail.com'),
                        'mail.port' => trim('587'),
                        'mail.username' => trim($user_email_details->email),
                        'mail.password' => trim($user_email_details->password),
                        'mail.encryption' => trim('tls'),
                    ]);
                }
                else {

                    config([
                        'mail.driver' => trim('smtp'),
                        'mail.host' => trim('smtp.zoho.com'),
                        'mail.port' => trim('465'),
                        'mail.username' => trim($user_email_details->email),
                        'mail.password' => trim($user_email_details->password),
                        'mail.encryption' => trim('ssl'),
                    ]);
                }

                $work_planning = WorkPlanning::getWorkPlanningDetailsById($value['module_id']);
                $work_planning_list = WorkPlanningList::getWorkPlanningList($value['module_id']);
                $work_planning_post = WorkPlanningPost::getWorkPlanningPostList($value['module_id']);

                $today_date = $work_planning['added_date'];
                $report_delay = $work_planning['report_delay'];
                $report_delay_content = $work_planning['report_delay_content'];
                $link = $work_planning['link'];
                $total_projected_time = $work_planning['total_projected_time'];
                $total_actual_time = $work_planning['total_actual_time'];

                $input['today_date'] = $today_date;
                $input['report_delay'] = $report_delay;
                $input['report_delay_content'] = $report_delay_content;
                $input['link'] = $link;
                $input['total_projected_time'] = $total_projected_time;
                $input['total_actual_time'] = $total_actual_time;
                $input['work_planning_list'] = $work_planning_list;
                $input['work_planning_post'] = $work_planning_post;

                \Mail::send('adminlte::emails.workplanningmail', $input, function ($message) use($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to'])->cc($input['cc_array'])->bcc($input['owner_email'])->subject($input['subject']);
                });

                \DB::statement("UPDATE `emails_notification` SET `status`='$status' where `id` = '$email_notification_id'");
            }

            else if ($value['module'] == 'Work Planning Remarks') {

                $cc_array = explode(",",$input['cc']);
                $input['cc_array'] = $cc_array;
              
                $input['module_id'] = $value['module_id'];

                $user_details = User::getAllDetailsByUserID($value['sender_name']);
                $input['from_name'] = $user_details->first_name . " " . $user_details->last_name;
                $input['owner_email'] = $user_details->email;

                $user_info = User::getProfileInfo($value['sender_name']);
                $input['signature'] = $user_info['signature'];

                $user_email_details = UsersEmailPwd::getUserEmailDetails($value['sender_name']);
                $input['from_address'] = trim($user_email_details->email);

                if(strpos($input['from_address'], '@gmail.com') !== false) {

                    config([

                        'mail.driver' => trim('mail'),
                        'mail.host' => trim('smtp.gmail.com'),
                        'mail.port' => trim('587'),
                        'mail.username' => trim($user_email_details->email),
                        'mail.password' => trim($user_email_details->password),
                        'mail.encryption' => trim('tls'),
                    ]);
                }
                else {

                    config([
                        'mail.driver' => trim('smtp'),
                        'mail.host' => trim('smtp.zoho.com'),
                        'mail.port' => trim('465'),
                        'mail.username' => trim($user_email_details->email),
                        'mail.password' => trim($user_email_details->password),
                        'mail.encryption' => trim('ssl'),
                    ]);
                }

                // Get Work Planning Details
                $work_planning = WorkPlanning::getWorkPlanningDetailsById($value['module_id']);

                // Get Task List
                $work_planning_list = WorkPlanningList::getWorkPlanningList($value['module_id']);

                // Get Remarks List
                $work_planning_post = WorkPlanningPost::getWorkPlanningPostList($value['module_id']);

                $today_date = $work_planning['added_date'];
                $link = $work_planning['link'];
                $total_projected_time = $work_planning['total_projected_time'];
                $total_actual_time = $work_planning['total_actual_time'];

                $input['today_date'] = $today_date;
                $input['link'] = $link;
                $input['total_projected_time'] = $total_projected_time;
                $input['total_actual_time'] = $total_actual_time;
                $input['work_planning_list'] = $work_planning_list;
                $input['work_planning_post'] = $work_planning_post;

                \Mail::send('adminlte::emails.workplanningmail', $input, function ($message) use($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to'])->cc($input['cc_array'])->subject($input['subject']);
                });

                \DB::statement("UPDATE `emails_notification` SET `status`='$status' where `id` = '$email_notification_id'");
            }

            else if ($value['module'] == "Hiring Report") {

                $job_ids_array = explode(",",$value['module_id']);

                $from_date = date('Y-m-d',strtotime("monday this week"));
                $to_date = date('Y-m-d',strtotime("$from_date +6days"));

                if(isset($job_ids_array) && sizeof($job_ids_array) > 0) {

                    $j=0;
                    $list_array = array();

                    foreach ($job_ids_array as $k1 => $v1) {

                        if(isset($v1) && $v1 != '') {

                            $associate_candidates = JobAssociateCandidates::getAssociatedCandidatesByWeek($v1,$from_date,$to_date);

                            $list_array[$j]['associate_candidates'] = $associate_candidates;

                            $shortlisted_candidates = JobAssociateCandidates::getShortlistedCandidatesByWeek($v1,$from_date,$to_date);

                            $list_array[$j]['shortlisted_candidates'] = $shortlisted_candidates;

                            $attended_interviews = Interview::getAttendedInterviewsByWeek($v1,$from_date,$to_date);

                            $list_array[$j]['attended_interviews'] = $attended_interviews;

                            $job_details = JobOpen::getJobById($v1);
                            $list_array[$j]['posting_title'] = $job_details['posting_title'] . " - " . $job_details['city'];

                            $client_info = ClientBasicinfo::getClientInfoByJobId($v1);
                            $client_name = $client_info['coordinator_prefix'] . " " .  $client_info['coordinator_name'];

                            $j++;
                        }
                    }
                }

                if(isset($list_array) && sizeof($list_array) > 0) {
                    
                    $to_array = explode(",",$input['to']);
                    $input['to_array'] = $to_array;

                    $input['list_array'] = $list_array;
                    $input['client_name'] = $client_name;

                    $owner_details = User::getAllDetailsByUserID($value['sender_name']);
                    $input['client_owner'] = $owner_details['name'];

                    \Mail::send('adminlte::emails.clientautogeneratereportemail', $input, function ($message) use($input) {

                        $message->from($input['from_address'], $input['from_name']);
                        $message->to($input['to_array'])->subject($input['subject']);
                    });

                    \DB::statement("UPDATE `emails_notification` SET `status`='$status' where `id` = '$email_notification_id'");
                }
            }

            else if ($value['module'] == 'Exist Candidate AutoScript Mail') {

                $candidate_details = CandidateBasicInfo::getCandidateDetailsById($module_id);

                $input['candidate_name'] = $candidate_details['full_name'];
                $input['owner_email'] = $candidate_details['owner_email'];

                if($input['owner_email'] == 'careers@adlertalent.com') {

                    \Mail::send('adminlte::emails.candidateAutoScriptMail', $input, function ($message) use($input) {

                        $message->from($input['from_address'], $input['from_name']);
                        $message->to($input['to'])->replyTo($input['owner_email'], $input['from_name'])->subject($input['subject']);
                    });
                }
                else {

                    $input['from_name'] = $candidate_details['owner_first_name'] . " " . $candidate_details['owner_last_name'];

                    \Mail::send('adminlte::emails.candidateAutoScriptMail', $input, function ($message) use($input) {

                        $message->from($input['from_address'], $input['from_name']);
                        $message->to($input['to'])->bcc($input['owner_email'])->replyTo($input['owner_email'], $input['from_name'])->subject($input['subject']);
                    });
                }

                \DB::statement("UPDATE `emails_notification` SET `status`='$status' where `id` = '$email_notification_id'");

                \DB::statement("UPDATE `candidate_basicinfo` SET `autoscript_status` = '1' where id = '$module_id';");
            }

            else if ($value['module'] == 'Work Planning Rejection') {

                $cc_array = explode(",",$input['cc']);
                $input['cc_array'] = $cc_array;
              
                $input['module_id'] = $value['module_id'];

                $work_planning = WorkPlanning::getWorkPlanningDetailsById($value['module_id']);

                $user_name = $work_planning['added_by'];
                $added_date = $work_planning['added_date'];

                $input['user_name'] = $user_name;
                $input['added_date'] = date('d/m/Y',strtotime($added_date));

                \Mail::send('adminlte::emails.rejectionworkplanningmail', $input, function ($message) use($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to'])->cc($input['cc_array'])->subject($input['subject']);
                });

                \DB::statement("UPDATE `emails_notification` SET `status`='$status' where `id` = '$email_notification_id'");
            }

            else if ($value['module'] == 'Work Planning Delay' || $value['module'] == 'Work Planning Status Delay') {

                $cc_array = explode(",",$input['cc']);
                $input['cc_array'] = $cc_array;
              
                $input['module_id'] = $value['module_id'];

                $work_planning = WorkPlanning::getWorkPlanningDetailsById($value['module_id']);

                $user_name = $work_planning['added_by'];
                $added_date = $work_planning['added_date'];
                $loggedin_time = $work_planning['loggedin_time'];
                $loggedout_time = $work_planning['loggedout_time'];
                $work_planning_time = $work_planning['work_planning_time'];

                $input['user_name'] = $user_name;
                $input['added_date'] = date('d/m/Y',strtotime($added_date));
                $input['loggedin_time'] = $loggedin_time;
                $input['loggedout_time'] = $loggedout_time;
                $input['work_planning_time'] = $work_planning_time;

                \Mail::send('adminlte::emails.workplanningdelayemail', $input, function ($message) use($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to'])->cc($input['cc_array'])->subject($input['subject']);
                });

                \DB::statement("UPDATE `emails_notification` SET `status`='$status' where `id` = '$email_notification_id'");
            }

            else if ($value['module'] == 'List of Holidays') {

                $cc_array = explode(",",$input['cc']);

                // Get users for popup of add information
                $users_details = User::getAllDetailsByUserID($value['module_id']);
                $user_name = $users_details->first_name." ".$users_details->last_name;

                if(isset($users_details) && $users_details != '') {

                    $input['user_name'] = $user_name;
                    $input['cc_array'] = $cc_array;
                    $input['attachment'] = public_path() . "/" . 'uploads/Adler_List_of_Holidays.pdf';
                    $input['module_id'] = $value['module_id'];

                     \Mail::send('adminlte::emails.listofholidaysemail', $input, function ($message) use($input) {
                    
                        $message->from($input['from_address'], $input['from_name']);
                        $message->to($input['to'])->cc($input['cc_array'])->subject($input['subject']);

                        if (isset($input['attachment']) && $input['attachment'] != '') {
                            $message->attach($input['attachment']);
                        }
                    });

                    \DB::statement("UPDATE `emails_notification` SET `status`='$status' where `id` = '$email_notification_id'");
                }
            }

            else if ($value['module'] == 'Work Planning Status Reminder') {

                //$cc_array = explode(",",$input['cc']);

                $work_planning = WorkPlanning::getWorkPlanningDetailsById($value['module_id']);

                // Get user name & work planning date
                $user_name = $work_planning['added_by'];
                $previous_date = $work_planning['added_date'];

                if(isset($work_planning) && $work_planning != '') {

                    $input['module_id'] = $value['module_id'];
                    //$input['cc_array'] = $cc_array;
                    $input['user_name'] = $user_name;
                    $input['previous_date'] = $previous_date;

                     \Mail::send('adminlte::emails.workplanningstatusreminder', $input, function ($message) use($input) {
                    
                        $message->from($input['from_address'], $input['from_name']);
                        /*$message->to($input['to'])->cc($input['cc_array'])->subject($input['subject']);*/
                        $message->to($input['to'])->subject($input['subject']);
                    });

                    \DB::statement("UPDATE `emails_notification` SET `status`='$status' where `id` = '$email_notification_id'");
                }
            }

            else if ($value['module'] == 'Late In Early Go') {

                $cc_array = array();
                $cc_array = explode(",",$input['cc']);
                $input['cc_array'] = $cc_array;

                // Get Sender name details
                $user_details = User::getAllDetailsByUserID($value['sender_name']);
                $input['from_name'] = $user_details->first_name . " " . $user_details->last_name;
                $input['owner_email'] = $user_details->email;

                $user_info = User::getProfileInfo($value['sender_name']);
                $input['signature'] = $user_info['signature'];

                $user_email_details = UsersEmailPwd::getUserEmailDetails($value['sender_name']);

                $input['from_address'] = trim($user_email_details->email);

                $leave = LateInEarlyGo::find($module_id);
                $input['leave_message'] = $leave->message;

                $input['leave_id'] = $module_id;

                if(strpos($input['from_address'], '@gmail.com') !== false) {

                    config([
                        'mail.driver' => trim('mail'),
                        'mail.host' => trim('smtp.gmail.com'),
                        'mail.port' => trim('587'),
                        'mail.username' => trim($user_email_details->email),
                        'mail.password' => trim($user_email_details->password),
                        'mail.encryption' => trim('tls'),
                    ]);
                }
                else {

                    config([
                        'mail.driver' => trim('smtp'),
                        'mail.host' => trim('smtp.zoho.com'),
                        'mail.port' => trim('465'),
                        'mail.username' => trim($user_email_details->email),
                        'mail.password' => trim($user_email_details->password),
                        'mail.encryption' => trim('ssl'),
                    ]);
                }

                \Mail::send('adminlte::emails.leavemail', $input, function ($message) use ($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to'])->cc($input['cc_array'])->bcc($input['owner_email'])->subject($input['subject']);

                    if (isset($input['attachment']) && sizeof($input['attachment']) > 0) {
                        
                        foreach ($input['attachment'] as $key => $value) {
                            $message->attach($value);
                        }
                    }
                });

                \DB::statement("UPDATE `emails_notification` SET `status`='$status' where `id` = '$email_notification_id'"); 
            }

            // Mail for late in early go reply approved/unapproved
            else if ($value['module'] == 'Late In Early Go Reply') {

                $cc_array = array();
                $cc_array = explode(",",$input['cc']); 

                $input['cc_array'] = array_unique($cc_array);

                // Get Sender name details
                $user_details = User::getAllDetailsByUserID($value['sender_name']);
                $input['from_name'] = $user_details->first_name . " " . $user_details->last_name;

                $user_info = User::getProfileInfo($value['sender_name']);
                $input['signature'] = $user_info['signature'];

                $user_email_details = UsersEmailPwd::getUserEmailDetails($value['sender_name']);

                $input['from_address'] = trim($user_email_details->email);

                $late_in_early_go = LateInEarlyGo::find($module_id);
                $input['leave_message'] = $late_in_early_go->reply_message;
                $input['remarks'] = $late_in_early_go->remarks;
                $input['date'] = date('d-m-Y',strtotime($late_in_early_go->date));

                $user_name = User::getUserNameByEmail($input['to']);
                $input['user_name'] = $user_name;

                if(strpos($input['from_address'], '@gmail.com') !== false) {

                    config([
                        'mail.driver' => trim('mail'),
                        'mail.host' => trim('smtp.gmail.com'),
                        'mail.port' => trim('587'),
                        'mail.username' => trim($user_email_details->email),
                        'mail.password' => trim($user_email_details->password),
                        'mail.encryption' => trim('tls'),
                    ]);
                }
                else {

                    config([
                        'mail.driver' => trim('smtp'),
                        'mail.host' => trim('smtp.zoho.com'),
                        'mail.port' => trim('465'),
                        'mail.username' => trim($user_email_details->email),
                        'mail.password' => trim($user_email_details->password),
                        'mail.encryption' => trim('ssl'),
                    ]);
                }

                \Mail::send('adminlte::emails.leavereply', $input, function ($message) use ($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to'])->cc($input['cc_array'])->subject($input['subject']);
                });

                \DB::statement("UPDATE `emails_notification` SET `status`='$status' where `id` = '$email_notification_id'"); 
            }

            // Mail for selected optional holidays
            else if ($value['module'] == 'Optional Holidays') {

                $cc_array = array();
                $cc_array = explode(",",$input['cc']);
                $input['cc_array'] = array_unique($cc_array);

                // Split holidays id & specific holiday
                $module_ids_array = explode("-", $module_id);

                // Split holidays id
                $holidays_ids_array = explode(",", $module_ids_array[0]);

                $selected_holidays = array();

                if(isset($holidays_ids_array) && sizeof($holidays_ids_array) > 0) {

                    foreach ($holidays_ids_array as $key => $value) {

                        $holidays = Holidays::find($value);
                        $title = $holidays->title;
                        $from_date = date("d-m-Y",strtotime($holidays->from_date));
                        $day = date("l",strtotime($from_date));
                        $display_string = '';

                        if($title != "Any other Religious Holiday for respective community - Please specify") {

                            $display_string = $title . " (" . $from_date . " - " . $day . ")";
                            array_push($selected_holidays,$display_string);
                        }
                    }
                }

                if(isset($module_ids_array[1]) && $module_ids_array[1] != '') {

                    $specific_holiday = SpecifyHolidays::find($module_ids_array[1]);
                    $title1 = $specific_holiday->title;
                    $from_date1 = date("d-m-Y",strtotime($specific_holiday->date));
                    $day1 = date("l",strtotime($from_date1));

                    $display_string = $title1 . " (" . $from_date1 . " - " . $day1 . ")";
                    array_push($selected_holidays,$display_string);
                }

                $input['selected_holidays'] = $selected_holidays;

                // Get Username
                $user_name = User::getUserNameByEmail($input['to']);
                $input['user_name'] = $user_name;

                \Mail::send('adminlte::emails.selectedoptionalholidaysemail', $input, function ($message) use ($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to'])->cc($input['cc_array'])->subject($input['subject']);
                });

                \DB::statement("UPDATE `emails_notification` SET `status`='$status' where `id` = '$email_notification_id'"); 
            }

            else if ($value['module'] == 'Work From Home Request') {

                $cc_array = array();
                $cc_array = explode(",",$input['cc']);
                $input['cc_array'] = $cc_array;

                // Get Sender name details
                $user_details = User::getAllDetailsByUserID($value['sender_name']);
                $input['from_name'] = $user_details->first_name . " " . $user_details->last_name;
                $input['owner_email'] = $user_details->email;

                $user_info = User::getProfileInfo($value['sender_name']);
                $input['signature'] = $user_info['signature'];

                $user_email_details = UsersEmailPwd::getUserEmailDetails($value['sender_name']);

                $input['from_address'] = trim($user_email_details->email);

                $work_from_home_res = WorkFromHome::find($module_id);
                $input['work_from_home_res'] = $work_from_home_res;

                if(strpos($input['from_address'], '@gmail.com') !== false) {

                    config([
                        'mail.driver' => trim('mail'),
                        'mail.host' => trim('smtp.gmail.com'),
                        'mail.port' => trim('587'),
                        'mail.username' => trim($user_email_details->email),
                        'mail.password' => trim($user_email_details->password),
                        'mail.encryption' => trim('tls'),
                    ]);
                }
                else {

                    config([
                        'mail.driver' => trim('smtp'),
                        'mail.host' => trim('smtp.zoho.com'),
                        'mail.port' => trim('465'),
                        'mail.username' => trim($user_email_details->email),
                        'mail.password' => trim($user_email_details->password),
                        'mail.encryption' => trim('ssl'),
                    ]);
                }

                \Mail::send('adminlte::emails.workfromhomerequestemail', $input, function ($message) use ($input) {
                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to'])->cc($input['cc_array'])->bcc($input['owner_email'])->subject($input['subject']);
                });

                \DB::statement("UPDATE `emails_notification` SET `status`='$status' where `id` = '$email_notification_id'"); 
            }

            // Mail for Work From Home Request reply Approve / Reject
            else if ($value['module'] == 'Work From Home Request Reply') {

                $cc_array = array();
                $cc_array = explode(",",$input['cc']); 

                // Get Work From Home Request
                $work_from_home_res = WorkFromHome::getWorkFromHomeRequestDetailsById($module_id);
                
                $user_name = $work_from_home_res['added_by'];
                $from_date = $work_from_home_res['from_date'];
                $to_date = $work_from_home_res['to_date'];
                $status = $work_from_home_res['status'];

                if(isset($work_from_home_res) && $work_from_home_res != '') {

                    $input['cc_array'] = array_unique($cc_array);
                    $input['user_name'] = $user_name;
                    $input['from_date'] = $from_date;
                    $input['to_date'] = $to_date;
                    $input['status'] = $status;

                    \Mail::send('adminlte::emails.workfromhomerequestreplyemail', $input, function ($message) use ($input) {
                        $message->from($input['from_address'], $input['from_name']);
                        $message->to($input['to'])->cc($input['cc_array'])->subject($input['subject']);
                    });

                    \DB::statement("UPDATE `emails_notification` SET `status`='$status' where `id` = '$email_notification_id'");
                }
            }

            else if ($value['module'] == "Pending Work Planning Reminder") {

                $from_date = date('Y-m-d',strtotime("-4 days"));
                $to_date = date("Y-m-d");

                $assigned_users = User::getAllUsersForRemarks($value['sender_name'],0);

                if(isset($assigned_users) && sizeof($assigned_users) > 0) {

                    $user_ids = array();
                    foreach ($assigned_users as $key1 => $value1) {
                            
                        if($key1 != '') {
                            $user_ids[] = $key1;
                        }
                    }
                }

                $user_name = User::getUserNameByEmail($input['to']);
                $input['user_name'] = $user_name;

                $work_planning_res = WorkPlanning::getPendingWorkPlanningsByDate($user_ids,$from_date,$to_date);

                $input['work_planning_res'] = $work_planning_res;

                \Mail::send('adminlte::emails.pendingworkplanningreminder', $input, function ($message) use($input) {

                    $message->from($input['from_address'], $input['from_name']);
                    $message->to($input['to'])->subject($input['subject']);
                });

                \DB::statement("UPDATE `emails_notification` SET `status`='$status' where `id` = '$email_notification_id'");
            }

            else if ($value['module'] == 'Leave Application Reminder') {

                $cc_array = explode(",",$input['cc']);

                //Split module id
                $split_module_id = explode("-",$value['module_id']);

                $leave_id = $split_module_id[0];
                $user_id = $split_module_id[1];

                // Get Leave details
                $leave_details = UserLeave::getLeaveDetails($leave_id);
                $rm_name_string = User::getUserNameByEmail($input['to']);
                $rm_array = explode(" ", $rm_name_string);
                $rm_name = $rm_array[0];

                $user_name = $leave_details['uname'];
                $from_date = $leave_details['from_date'];
                $to_date = $leave_details['to_date'];
                $owner_email = User::getUserEmailById($user_id);

                if(isset($leave_details) && $leave_details != '') {

                    $input['module'] = $value['module'];
                    $input['cc_array'] = $cc_array;
                    $input['rm_name'] = $rm_name;
                    $input['user_name'] = $user_name;
                    $input['from_date'] = $from_date;
                    $input['to_date'] = $to_date;
                    $input['owner_email'] = $owner_email;

                     \Mail::send('adminlte::emails.leaveapplicationreminder', $input, function ($message) use($input) {
                    
                        $message->from($input['from_address'], $input['from_name']);
                        $message->to($input['to'])->cc($input['cc_array'])->bcc($input['owner_email'])->subject($input['subject']);
                    });

                    \DB::statement("UPDATE `emails_notification` SET `status`='$status' where `id` = '$email_notification_id'");
                }
            }

            else if ($value['module'] == 'Optional Holiday Reminder') {

                $cc_array = explode(",",$input['cc']);

                //Split module id
                $split_module_id = explode("-",$value['module_id']);

                $holiday_id = $split_module_id[0];
                $user_id = $split_module_id[1];

                //Get User info
                $user_info = User::getProfileInfo($user_id);

                // Get Holidays details
                $holidays = Holidays::find($holiday_id);
                $from_date = $holidays->from_date;
                $holiday_name = $holidays->title;

                $rm_name_string = User::getUserNameByEmail($input['to']);
                $rm_array = explode(" ", $rm_name_string);
                $rm_name = $rm_array[0];
                
                $user_name = $user_info->first_name . " " . $user_info->last_name;
                $owner_email = $user_info->email;

                if(isset($holidays) && $holidays != '') {

                    $input['module'] = $value['module'];
                    $input['cc_array'] = $cc_array;
                    $input['rm_name'] = $rm_name;
                    $input['user_name'] = $user_name;
                    $input['owner_email'] = $owner_email;
                    $input['from_date'] = $from_date;
                    $input['holiday_name'] = $holiday_name;

                     \Mail::send('adminlte::emails.leaveapplicationreminder', $input, function ($message) use($input) {
                    
                        $message->from($input['from_address'], $input['from_name']);
                        $message->to($input['to'])->cc($input['cc_array'])->bcc($input['owner_email'])->subject($input['subject']);
                    });

                    \DB::statement("UPDATE `emails_notification` SET `status`='$status' where `id` = '$email_notification_id'");
                }
            }
        }

        return redirect('/dashboard')->with('success', 'Successfully.');
    }
}