<?php

namespace App\Http\Controllers;

use App\Interview;
use App\JobCandidateJoiningdate;
use App\ToDos;
use App\UsersLog;
use Illuminate\Http\Request;
use App\User;
use App\Date;
use App\ClientBasicinfo;
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
            $month_array[$m] = date('M', mktime(0,0,0,$m));
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

        if(isset($_POST['month']) && $_POST['month']!=''){
            $month = $_POST['month'];
        }
        else{
            $month = date("n");
        }

        if(isset($_POST['year']) && $_POST['year']!=''){
            $year = $_POST['year'];
        }
        else{
            $year = date("Y");
        }

        $month_array =array();
        for ($m=1; $m<=12; $m++) {
            $month_array[$m] = date('M', mktime(0,0,0,$m));
        }

        $starting_year = '2016';
        $ending_year = date('Y',strtotime('+3 year'));
        $year_array = array();
        for ($y=$starting_year; $y < $ending_year ; $y++) {
            $year_array[$y] = $y;
        }

        $list = array();
        for($d=1; $d<=31; $d++)
        {
            $time = mktime(12, 0, 0, $month, $d, $year);
            foreach ($users as $key => $value) {
              //  echo date('m', $time);exit;
                if (date('n', $time) == $month)
                    $list[$key][date('j', $time)]['login']='';
                $list[$key][date('j', $time)]['logout']='';
                $list[$key][date('j', $time)]['total']='';
                $list[$key][date('j', $time)]['remarks']='';
            }
        }

        if($display_attendance) {
            $response = UsersLog::getUsersAttendance(0,$month,$year);
            $user_remark = UserRemarks::getUserRemarksByUserid(0);
        }
        else if($display_attendance_by_user) {
            $response = UsersLog::getUsersAttendance($user_id,$month,$year);
            $user_remark = UserRemarks::getUserRemarksByUserid($user_id);
        }

        $date = new Date();
        if(sizeof($response)>0){
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
                        else{

                            if (($v['full_name'] == $combine_name) && ($month == $split_month) && ($year == $split_year)) {
                                $list[$combine_name][$v['converted_date']]['remarks'] = $v['remarks'];
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
        for($d1=1; $d1<=31; $d1++)
        {
            $time1=mktime(12, 0, 0, $month, $d1, $year);
            foreach ($users as $key => $value)
            {
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

        $users_name = User::getAllUsersForRemarks();

        return view('home',array("list"=>$list,"list1"=>$list1,"month_list"=>$month_array,"year_list"=>$year_array,"month"=>$month,"year"=>$year,"user_remark"=>$user_remark),compact('users_name'));
    }

    public function userAttendance() {

        $loggedin_userid =  \Auth::user()->id;

        if(isset($_POST['selected_user_id']) && $_POST['selected_user_id'] != '') {

            $user_id = $_POST['selected_user_id'];

            // get selected user attendance for current month
            $response = UsersLog::getUsersAttendance($user_id,0,0);

            // get selected user remarks
            $user_remarks = UserRemarks::getUserRemarksByUserid($user_id);
        }
        else {

            // get logged in user attendance for current month
            $response = UsersLog::getUsersAttendance($loggedin_userid,0,0);

            // get logged in user remarks
            $user_remarks = UserRemarks::getUserRemarksByUserid($loggedin_userid);
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

        $users_name = User::getAllUsersForRemarks();
        
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
            return redirect('/userattendance');
        }
        if($name == 'HomeAttendance') {
            return redirect('/home');
        }
        if($name == 'User-Attendance') {
            return redirect('/user-attendance');
        }
    }

    public function export() {

        Excel::create('Attendance', function($excel) {

            $excel->sheet('Sheet 1', function($sheet) {

                if(isset($_POST['month']) && $_POST['month']!='') {
                    $month = $_POST['month'];
                }
                else{
                    $month = date("n");
                }
                if(isset($_POST['year']) && $_POST['year']!='') {
                    $year = $_POST['year'];
                }
                else{
                    $year = date("Y");
                }

                $response = UsersLog::getUsersAttendanceList(0,$month,$year);

                /*$list = array();
                $date = new Date();
                if(sizeof($response)>0){
                    foreach ($response as $key => $value) {
                        $data[] = array(
                            $login_time = $date->converttime($value->login),
                            $logout_time = $date->converttime($value->logout),
                            $list[$value->name][date("j S",strtotime($value->date))]['login'] = date("h:i A",$login_time),
                            $list[$value->name][date("j S",strtotime($value->date))]['logout'] = date("h:i A",$logout_time),

                            $total = ($logout_time - $login_time) / 60,

                            $list[$value->name][date("j S",strtotime($value->date))]['total'] = date('H:i', mktime(0,$total)),
                        );
                    }
                }*/
                //print_r($response);exit;
                /*user=DB::table('users_log')->join("users","users.id","=","users_log.user_id")
                                            ->select("users_log.*","users.name as name")
                                            ->orderBy('users_log.id','desc')
                                            ->get();
                    foreach($list as $lists) {
                     $data[] = array(
                        $lists->id,
                        $lists->name,
                        $lists->login,
                        $lists->logout,
                        $lists->total,
                    );
                }*/

                for($d=1; $d<=31; $d++) {

                    $time = mktime(12, 0, 0, $month, $d, $year);
                    $dt = date('j S', $time);
                    $dt_header = array($dt);
                    $sheet->fromArray($dt_header, null, 'B1', false, false);   
                }

                foreach($response as $key=>$value) {

                    $heading1 = array($key);
                    $sheet->prependRow(2, $heading1);

                    $heading2 = array('Login');
                    $sheet->prependRow(3, $heading2);

                    $heading3 = array('Logout');
                    $sheet->prependRow(4, $heading3);

                    $heading4 = array('Total');
                    $sheet->prependRow(5, $heading4);
                }
            });
        })->export('xls');

        return view('home');
    }

    public function calenderevent() {

        $data[] = array(
            'title' => 'login',
            'start' => date('Y-m-d'),
            'end' => date('Y-m-d')
        );
        return json_encode($data);
    }

/*    public function recruitmentDashboard() {

        $user = \Auth::user();
        $user_id =  $user->id;

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

        $month = date('m');
        $year = date('Y');
        $department_id = getenv('RECRUITMENT');

        if($display_all_count) {

            // Client Count
            $client = DB::table('client_basicinfo')
            ->whereRaw('MONTH(created_at) = ?',[$month])
            ->whereRaw('YEAR(created_at) = ?',[$year])
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
        }
        else if($display_userwise_count) {

            // Client Count
            $client = DB::table('client_basicinfo')
            ->whereRaw('MONTH(created_at) = ?',[$month])
            ->whereRaw('YEAR(created_at) = ?',[$year])->where('account_manager_id',$user_id)
            ->where('delete_client','=',0)->where('department_id','=',$department_id)->count();

            // Job Count
            $job = JobOpen::getAllJobsCountByDepartment(0,$user_id,'',$department_id);

            $tanisha_user_id = getenv('TANISHAUSERID');

            if($user_id == $tanisha_user_id) {

                // Cvs Associated this month
                $associate_monthly_response = JobAssociateCandidates::getMonthlyReprtAssociate($tanisha_user_id,$month,$year,$department_id);
                $associate_count = $associate_monthly_response['cvs_cnt'];

                // Cvs Shortlisted this month
                $shortlisted_count = JobAssociateCandidates::getMonthlyReprtShortlisted($tanisha_user_id,$month,$year,$department_id);

                // Interview Attended this month
                $interview_attended_list = Interview::getAttendedInterviews(0,$tanisha_user_id,$month,$year,$department_id);
                $interview_attend = sizeof($interview_attended_list);

                // Candidate Join this month
                $candidatecount = JobCandidateJoiningdate::getJoiningCandidateByUserIdCountByMonthwise($tanisha_user_id,0,$month,$year,$department_id);

                // Interview Count
                $interviews = Interview::getDashboardInterviews(0,$tanisha_user_id,$department_id);
                $interviews_cnt = sizeof($interviews);
            }
            else {

                // Cvs Associated this month
                $associate_monthly_response = JobAssociateCandidates::getMonthlyReprtAssociate($user_id,$month,$year,$department_id);
                $associate_count = $associate_monthly_response['cvs_cnt'];

                // Cvs Shortlisted this month
                $shortlisted_count = JobAssociateCandidates::getMonthlyReprtShortlisted($user_id,$month,$year,$department_id);

                // Interview Attended this month
                $interview_attended_list = Interview::getAttendedInterviews(0,$user_id,$month,$year,$department_id);
                $interview_attend = sizeof($interview_attended_list);

                // Candidate Join this month
                $candidatecount = JobCandidateJoiningdate::getJoiningCandidateByUserIdCountByMonthwise($user_id,0,$month,$year,$department_id);

                // Interview Count
                $interviews = Interview::getDashboardInterviews(0,$user_id,$department_id);
                $interviews_cnt = sizeof($interviews);
            }
        }

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
        $user_id = \Auth::user()->id;
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

        $month = date('m');
        $year = date('Y');
        $department_id = getenv('HRADVISORY');

        if($display_all_count) {

            // Client Count
            $client = DB::table('client_basicinfo')
            ->whereRaw('MONTH(created_at) = ?',[$month])
            ->whereRaw('YEAR(created_at) = ?',[$year])
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
        }
        else if($display_userwise_count) {

            // Client Count
            $client = DB::table('client_basicinfo')
            ->whereRaw('MONTH(created_at) = ?',[$month])
            ->whereRaw('YEAR(created_at) = ?',[$year])->where('account_manager_id',$user_id)
            ->where('delete_client','=',0)->where('department_id','=',$department_id)->count();

            // Job Count
            $job = JobOpen::getAllJobsCountByDepartment(0,$user_id,'',$department_id);

            $tanisha_user_id = getenv('TANISHAUSERID');

            if($user_id == $tanisha_user_id) {

                // Cvs Associated this month
                $associate_monthly_response = JobAssociateCandidates::getMonthlyReprtAssociate($tanisha_user_id,$month,$year,$department_id);
                $associate_count = $associate_monthly_response['cvs_cnt'];

                // Cvs Shortlisted this month
                $shortlisted_count = JobAssociateCandidates::getMonthlyReprtShortlisted($tanisha_user_id,$month,$year,$department_id);

                // Interview Attended this month
                $interview_attended_list = Interview::getAttendedInterviews(0,$tanisha_user_id,$month,$year,$department_id);
                $interview_attend = sizeof($interview_attended_list);

                // Candidate Join this month
                $candidatecount = JobCandidateJoiningdate::getJoiningCandidateByUserIdCountByMonthwise($tanisha_user_id,0,$month,$year,$department_id);

                // Interview Count
                $interviews = Interview::getDashboardInterviews(0,$tanisha_user_id,$department_id);
                $interviews_cnt = sizeof($interviews);
            }
            else {

                // Cvs Associated this month
                $associate_monthly_response = JobAssociateCandidates::getMonthlyReprtAssociate($user_id,$month,$year,$department_id);
                $associate_count = $associate_monthly_response['cvs_cnt'];

                // Cvs Shortlisted this month
                $shortlisted_count = JobAssociateCandidates::getMonthlyReprtShortlisted($user_id,$month,$year,$department_id);

                // Interview Attended this month
                $interview_attended_list = Interview::getAttendedInterviews(0,$user_id,$month,$year,$department_id);
                $interview_attend = sizeof($interview_attended_list);

                // Candidate Join this month
                $candidatecount = JobCandidateJoiningdate::getJoiningCandidateByUserIdCountByMonthwise($user_id,0,$month,$year,$department_id);

                // Interview Count
                $interviews = Interview::getDashboardInterviews(0,$user_id,$department_id);
                $interviews_cnt = sizeof($interviews);
            }
        }

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
        $user_id = \Auth::user()->id;
        $display_jobs = $user->can('display-jobs-open-to-all');

        $department_id = getenv('HRADVISORY');

        if($display_jobs) {
            $job_opened = JobOpen::getOpenToAllJobs(10,$user_id,$department_id);
        }
        else {
            $job_opened = array();
        }
        return json_encode($job_opened);
    }*/

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
        $superadmin_userid = getenv('SUPERADMINUSERID');

        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);

        $user_obj = new User();
        $isClient = $user_obj::isClient($role_id);

        if($isClient) {
            return redirect()->route('jobopen.index');
        }

        $date = date('Y-m-d');
        $month = date('m');
        $year = date('Y');

        if ($user_id == $superadmin_userid) {

            // Get Pending Work Planning Count
            $work_planning = WorkPlanning::getPendingWorkPlanningDetails(0,$month,$year,0);
            $pending_work_planning_count = sizeof($work_planning);

            // Get Applied Leave Count
            $leave_data = UserLeave::getAllLeavedataByUserId(1,0,$month,$year,'');
            $leave_count = sizeof($leave_data);
            
            // Set present days
            $present_days = 0;

            // Get Early go late in count
            $leave_details = UserLeave::getLateInEarlyGoByUserID(0);
            $earlygo_latein_count = sizeof($leave_details);

            // Get Optional Holidays
            $optional_holiday_details = Holidays::getUserHolidaysByType(0,$month,$year,'Optional Leave');
            $optional_holidays_count = sizeof($optional_holiday_details);

            // Get Fixed Holidays
            $fixed_holiday_details = Holidays::getUserHolidaysByType(0,$month,$year,'Fixed Leave');
            $fixed_holidays_count = sizeof($fixed_holiday_details);
        }
        else {

            // Get Pending Work Planning Count
            $work_planning = WorkPlanning::getPendingWorkPlanningDetails($user_id,$month,$year,0);
            $pending_work_planning_count = sizeof($work_planning);

            // Get Applied Leave Count
            $floor_reports_id = User::getAssignedUsers($user_id);
            foreach ($floor_reports_id as $key => $value) {
                $user_ids[] = $key;
            }

            $leave_data = UserLeave::getAllLeavedataByUserId(0,$user_ids,$month,$year,'');
            $leave_count = sizeof($leave_data);

            // Get Present Days Count
            $present_days_res = WorkPlanning::getWorkPlanningDetails($user_id,$month,$year,'');
            $present_days = sizeof($present_days_res);

            // Get Early go late in count
            $leave_details = UserLeave::getLateInEarlyGoByUserID($user_id);
            $earlygo_latein_count = sizeof($leave_details);

            // Get Optional Holidays
            $optional_holiday_details = Holidays::getUserHolidaysByType($user_id,$month,$year,'Optional Leave');
            $optional_holidays_count = sizeof($optional_holiday_details);

            // Get Fixed Holidays
            $fixed_holiday_details = Holidays::getUserHolidaysByType($user_id,$month,$year,'Fixed Leave');
            $fixed_holidays_count = sizeof($fixed_holiday_details);
        }
        
        $viewVariable = array();
        $viewVariable['pending_work_planning_count'] = $pending_work_planning_count;
        $viewVariable['leave_count'] = $leave_count;
        $viewVariable['present_days'] = $present_days;
        $viewVariable['earlygo_latein_count'] = $earlygo_latein_count;
        $viewVariable['optional_holidays_count'] = $optional_holidays_count;
        $viewVariable['fixed_holidays_count'] = $fixed_holidays_count;

        return view('employee-self-service',$viewVariable);
    }

    public function selfAttendance() {

        $user = \Auth::user();
        $user_id = $user->id;

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
            $month_array[$m] = date('M', mktime(0,0,0,$m));
        }

        $starting_year = '2021';
        $ending_year = date('Y',strtotime('+2 year'));
        $year_array = array();
        for ($y=$starting_year; $y < $ending_year ; $y++) {
            $year_array[$y] = $y;
        }

        // Get Users
        $users = User::getOtherUsersNew($user_id,'Self');

        // Get Attendance & Remarks
        $response = WorkPlanning::getUsersAttendanceByWorkPlanning($user_id,$month,$year,'Self');
        $user_remark = UserRemarks::getUserRemarksByUserIDNew($user_id,$month,$year,'Self');
        
        $list = array();
        for($d=1; $d<=31; $d++) {

            $time = mktime(12, 0, 0, $month, $d, $year);
            foreach ($users as $key => $value) {
              
                if (date('n', $time) == $month)
                    $list[$key][date('j', $time)]['attendance']='';
                
                $list[$key][date('j', $time)]['remarks']='';
            }
        }
        

        $date = new Date();
        if(sizeof($response) > 0) {

            foreach ($response as $key => $value) {

                $joining_date = date('d/m/Y', strtotime("$value->joining_date"));
                $combine_name = $value->first_name."-".$value->last_name.",".$value->department_name.",".$value->working_hours.",".$joining_date;

                $list[$combine_name][date("j",strtotime($value->added_date))]['attendance'] = $value->attendance;

                if (isset($user_remark) && sizeof($user_remark)>0) {
                    foreach ($user_remark as $k => $v) {

                        $split_month = date('n',strtotime($v['remark_date']));
                        $split_year = date('Y',strtotime($v['remark_date']));

                        if (($v['full_name'] == $combine_name) && ($v['remark_date'] == $value->added_date) && ($month == $split_month) && ($year == $split_year)) {
                            $list[$combine_name][$v['converted_date']]['remarks'] = $v['remarks'];
                        }
                        else{

                            if (($v['full_name'] == $combine_name) && ($month == $split_month) && ($year == $split_year)) {
                                $list[$combine_name][$v['converted_date']]['remarks'] = $v['remarks'];
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

        $users_name = User::getAllUsersForRemarks();

        $page = 'Self';

        return view('user-attendance',array("list"=>$list,"list1"=>$list1,"month_list"=>$month_array,"year_list"=>$year_array,"month"=>$month,"year"=>$year,"user_remark"=>$user_remark),compact('users_name','sundays','page'));
    }

    public function teamUsersAttendance() {

        $user = \Auth::user();
        $user_id = $user->id;

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
            $month_array[$m] = date('M', mktime(0,0,0,$m));
        }

        $starting_year = '2021';
        $ending_year = date('Y',strtotime('+2 year'));
        $year_array = array();
        for ($y=$starting_year; $y < $ending_year ; $y++) {
            $year_array[$y] = $y;
        }

        // Get Users
        $users = User::getOtherUsersNew($user_id,'');

        // Get Attendance & Remarks
        $response = WorkPlanning::getUsersAttendanceByWorkPlanning($user_id,$month,$year,'');
        $user_remark = UserRemarks::getUserRemarksByUserIDNew($user_id,$month,$year,'');

        $list = array();
        for($d=1; $d<=31; $d++) {

            $time = mktime(12, 0, 0, $month, $d, $year);
            foreach ($users as $key => $value) {
              
                if (date('n', $time) == $month)
                    $list[$key][date('j', $time)]['attendance']='';
                
                $list[$key][date('j', $time)]['remarks']='';
            }
        }

        $date = new Date();
        if(sizeof($response) > 0) {

            foreach ($response as $key => $value) {

                $joining_date = date('d/m/Y', strtotime("$value->joining_date"));
                $combine_name = $value->first_name."-".$value->last_name.",".$value->department_name.",".$value->working_hours.",".$joining_date;

                $list[$combine_name][date("j",strtotime($value->added_date))]['attendance'] = $value->attendance;

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

        $users_name = User::getAllUsersForRemarks();

        $page = 'Team';

        return view('user-attendance',array("list"=>$list,"list1"=>$list1,"month_list"=>$month_array,"year_list"=>$year_array,"month"=>$month,"year"=>$year,"user_remark"=>$user_remark),compact('users_name','sundays','page'));
    }

    public function departmentWiseUsersAttendance($department_id) {

        $user = \Auth::user();
        $user_id = $user->id;

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
            $month_array[$m] = date('M', mktime(0,0,0,$m));
        }

        $starting_year = '2021';
        $ending_year = date('Y',strtotime('+2 year'));
        $year_array = array();
        for ($y=$starting_year; $y < $ending_year ; $y++) {
            $year_array[$y] = $y;
        }

        $all_perm = $user->can('display-recruitment-dashboard');
        $all_perm = $user->can('display-hr-advisory-dashboard');

        // Get Users
        $users = User::getOtherUsersNew(0,'',$department_id);

        // Get Attendance & Remarks

        $response = WorkPlanning::getUsersAttendanceByWorkPlanning($user_id,$month,$year,'',$department_id);
        $user_remark = UserRemarks::getUserRemarksByUserIDNew($user_id,$month,$year,'',$department_id);

        //print_r($user_remark);exit;

        $list = array();
        for($d=1; $d<=31; $d++) {

            $time = mktime(12, 0, 0, $month, $d, $year);
            foreach ($users as $key => $value) {
              
                if (date('n', $time) == $month)
                    $list[$key][date('j', $time)]['attendance']='';
                
                $list[$key][date('j', $time)]['remarks']='';
            }
        }


        $date = new Date();
        if(sizeof($response) > 0) {

            foreach ($response as $key => $value) {

                $joining_date = date('d/m/Y', strtotime("$value->joining_date"));
                $combine_name = $value->first_name."-".$value->last_name.",".$value->department_name.",".$value->working_hours.",".$joining_date;

                $list[$combine_name][date("j",strtotime($value->added_date))]['attendance'] = $value->attendance;

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

        $users_name = User::getAllUsersForRemarks();

        $page = 'Department';

        return view('user-attendance',array("list"=>$list,"list1"=>$list1,"month_list"=>$month_array,"year_list"=>$year_array,"month"=>$month,"year"=>$year,"user_remark"=>$user_remark),compact('users_name','sundays','page','department_id'));
    }
}