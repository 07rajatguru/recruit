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
            $month_array[$m] = date('M', mktime(0,0,0,$m));
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
            $pending_work_planning_count = 0;

            // Get Applied Leave Count
            $leave_count = 0;
            
            // Set present days
            $present_days = 0;

            // Get Early go late in count
            $earlygo_latein_count = 0;
        }
        else {

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
            $leave_details = LateInEarlyGo::getLateInEarlyGoByUserID($user_id);
            $earlygo_latein_count = sizeof($leave_details);
        }

        // Get Optional Holidays of User
        $optional_holiday_details = Holidays::getUserHolidaysByType($user_id,$month,$year,'Optional Leave');
        $optional_holidays_count = sizeof($optional_holiday_details);

        // Get Fixed Holidays of User
        $fixed_holiday_details = Holidays::getUserHolidaysByType($user_id,$month,$year,'Fixed Leave');
        $fixed_holidays_count = sizeof($fixed_holiday_details);

        // Get Work Anniversary dates of Current Month
        $work_anniversary_dates = User::getUsersWorkAnniversaryDatesByMonth($month);

        // Get Birthday dates of Current Month
        $birthday_dates = User::getUserBirthDatesByMonth($month);

        // Get Holiday of Current Year
        $holidays = Holidays::getUserHolidaysByType(0,'',$year,'');

        // Get List of applied leaves of team

        $floor_reports_id = User::getAssignedUsers($user_id);

        if(isset($floor_reports_id) && sizeof($floor_reports_id) > 0) {

            foreach ($floor_reports_id as $key => $value) {

                $user_ids_array[] = $key;
            }
        }
        else {
            $user_ids_array = array();
        }

        if (in_array($user_id, $user_ids_array)) {
            unset($user_ids_array[array_search($user_id,$user_ids_array)]);
        }

        $leave_data = UserLeave::getAllLeavedataByUserId(0,$user_ids_array,$month,$year,'');

        $viewVariable = array();
        $viewVariable['pending_work_planning_count'] = $pending_work_planning_count;
        $viewVariable['leave_count'] = $leave_count;
        $viewVariable['present_days'] = $present_days;
        $viewVariable['earlygo_latein_count'] = $earlygo_latein_count;
        $viewVariable['optional_holidays_count'] = $optional_holidays_count;
        $viewVariable['fixed_holidays_count'] = $fixed_holidays_count;
        $viewVariable['work_anniversary_dates'] = $work_anniversary_dates;
        $viewVariable['birthday_dates'] = $birthday_dates;
        $viewVariable['holidays'] = $holidays;
        $viewVariable['leave_data'] = $leave_data;

        return view('employee-self-service',$viewVariable);
    }

    // HR ESS

    public function hrEmployeeSelfService() {

        $user = \Auth::user();
        $user_id =  $user->id;

        $date = date('Y-m-d');
        $month = date('m');
        $year = date('Y');

        // Get Pending Work Planning Count
        $work_planning = WorkPlanning::getPendingWorkPlanningDetails(0,$month,$year);
        $pending_work_planning_count = sizeof($work_planning);

        // Get Applied Leave Count
        $leave_data = UserLeave::getAllLeavedataByUserId(1,0,$month,$year,'');
        $leave_count = sizeof($leave_data);
            
        // Set present days
        $present_days = 0;

        // Get Early go late in count
        $leave_details = LateInEarlyGo::getLateInEarlyGoByUserID(0);
        $earlygo_latein_count = sizeof($leave_details);

        // Get Optional Holidays count of current month
        $optional_holiday_details = Holidays::getUserHolidaysByType(0,$month,$year,'Optional Leave');
        $optional_holidays_count = sizeof($optional_holiday_details);

        // Get Fixed Holidays count of current month
        $fixed_holiday_details = Holidays::getUserHolidaysByType(0,$month,$year,'Fixed Leave');
            $fixed_holidays_count = sizeof($fixed_holiday_details);

        // Get Work Anniversary dates of Current Month
        $work_anniversary_dates = User::getUsersWorkAnniversaryDatesByMonth($month);

        // Get Birthday dates of Current Month
        $birthday_dates = User::getUserBirthDatesByMonth($month);

        // Get Holiday of Current Year
        $holidays = Holidays::getUserHolidaysByType(0,'',$year,'');

        // Get List of applied leaves of team

        $users = User::getAllUsers();

        if(isset($users) && sizeof($users) > 0) {
            foreach ($users as $key => $value) {
                $user_ids_array[] = $key;
            }
        }
        else {
            $user_ids_array = array();
        }

        $leave_data = UserLeave::getAllLeavedataByUserId(0,$user_ids_array,$month,$year,'');

        $viewVariable = array();
        $viewVariable['pending_work_planning_count'] = $pending_work_planning_count;
        $viewVariable['leave_count'] = $leave_count;
        $viewVariable['present_days'] = $present_days;
        $viewVariable['earlygo_latein_count'] = $earlygo_latein_count;
        $viewVariable['optional_holidays_count'] = $optional_holidays_count;
        $viewVariable['fixed_holidays_count'] = $fixed_holidays_count;
        $viewVariable['work_anniversary_dates'] = $work_anniversary_dates;
        $viewVariable['birthday_dates'] = $birthday_dates;
        $viewVariable['holidays'] = $holidays;
        $viewVariable['leave_data'] = $leave_data;

        return view('hr-employee-self-service',$viewVariable);
    }

    public function usersAttendance($department_nm) {

        $user = \Auth::user();
        $user_id = $user->id;

        // Get Attendance Type
        $attendance_type = User::getAttendanceType();

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
                $full_name = $user_details->first_name."-".$user_details->last_name.",".$user_details->department_name.",".$user_details->working_hours.",".$joining_date;
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
                    $list[$key][date('j', $time)]['holiday']='';
                    $list[$key][date('j', $time)]['privilege_leave']='';
                    $list[$key][date('j', $time)]['sick_leave']='';
                    $list[$key][date('j', $time)]['unapproved_leave']='';
                }
            }

            $date = new Date();
            if(sizeof($response) > 0) {

                foreach ($response as $key => $value) {

                    $joining_date = date('d/m/Y', strtotime("$value->joining_date"));
                    $combine_name = $value->first_name."-".$value->last_name.",".$value->department_name.",".$value->working_hours.",".$joining_date;

                    if($value->status > 0) {

                        $list[$combine_name][date("j",strtotime($value->added_date))]['attendance'] = $value->attendance;
                    }
                    else {

                        $list[$combine_name][date("j",strtotime($value->added_date))]['attendance'] = 'WPP';
                    }

                    // Get User id from both name 
                    $user_name = $value->first_name."-".$value->last_name;
                    $u_id = User::getUserIdByBothName($user_name);

                    // Set holiday dates
                    $user_holidays = Holidays::getHolidaysByUserID($u_id,$month,$year);

                    if (isset($user_holidays) && sizeof($user_holidays)>0) {

                        foreach ($user_holidays as $h_k => $h_v) {

                            $list[$combine_name][$h_v]['holiday'] = 'Y';
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
                            $user_holidays = Holidays::getHolidaysByUserID($u_id,$month,$year);

                            if (isset($user_holidays) && sizeof($user_holidays)>0) {

                                foreach ($user_holidays as $h_k => $h_v) {

                                    $list[$key][$h_v]['holiday'] = 'Y';
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

        return view('user-attendance',array("list"=>$list,"list1"=>$list1,"month_list"=>$month_array,"year_list"=>$year_array,"month"=>$month,"year"=>$year,"user_remark"=>$user_remark,"attendance_type" => $attendance_type,"selected_attendance_type" => $selected_attendance_type),compact('users_name','sundays','department_nm'));
    }

    public function exportAttendance() {

        $user = \Auth::user();
        $user_id = $user->id;
        $all_perm = $user->can('display-attendance-of-all-users-in-admin-panel');

        if($all_perm) {

            $attendance_type = $_POST['attendance_type'];
            $month = $_POST['month'];
            $year = $_POST['year'];
            
            $month_name = date("F", mktime(0, 0, 0, $month, 10));
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
                    $full_name = $user_details->first_name."-".$user_details->last_name.",".$user_details->department_name.",".$user_details->working_hours.",".$joining_date;
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
                    $list[$key][date('j', $time)]['holiday']='';
                    $list[$key][date('j', $time)]['privilege_leave']='';
                    $list[$key][date('j', $time)]['sick_leave']='';
                    $list[$key][date('j', $time)]['unapproved_leave']='';
                }
            }

            $date = new Date();
            if(sizeof($response) > 0) {

                foreach ($response as $key => $value) {

                    $joining_date = date('d/m/Y', strtotime("$value->joining_date"));
                    $combine_name = $value->first_name."-".$value->last_name.",".$value->department_name.",".$value->working_hours.",".$joining_date;

                    if($value->status > 0) {

                        $list[$combine_name][date("j",strtotime($value->added_date))]['attendance'] = $value->attendance;
                    }
                    else {

                        $list[$combine_name][date("j",strtotime($value->added_date))]['attendance'] = 'WPP';
                    }

                    // Get User id from both name
                    $user_name = $value->first_name."-".$value->last_name;
                    $u_id = User::getUserIdByBothName($user_name);

                    // Set holiday dates
                    $user_holidays = Holidays::getHolidaysByUserID($u_id,$month,$year);

                    if (isset($user_holidays) && sizeof($user_holidays)>0) {

                        foreach ($user_holidays as $h_k => $h_v) {
                            $list[$combine_name][$h_v]['holiday'] = 'Y';
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

            // Set new List1
            $list1 = array();
            for($d1=1; $d1<=31; $d1++) {

                $time1 = mktime(12, 0, 0, $month, $d1, $year);
                foreach ($users as $key => $value) {

                    if (date('n', $time1) == $month) {
                        $list1[$key][date('j S', $time1)]='';
                    }
                }
            }

            // If list has values
            if(sizeof($list)>0) {

                foreach ($list as $key => $value) {

                    if(sizeof($value)>0) {

                        $i=0;
                        foreach ($value as $key1 => $value1) {

                            $split_unm = explode(",",$key);

                            // Get User id from both name
                            $u_id = User::getUserIdByBothName($split_unm[0]);

                            // Set holiday dates
                            $user_holidays = Holidays::getHolidaysByUserID($u_id,$month,$year);

                            if (isset($user_holidays) && sizeof($user_holidays)>0) {

                                foreach ($user_holidays as $h_k => $h_v) {
                                    $list[$key][$h_v]['holiday'] = 'Y';
                                }
                            }

                            // Set Leave dates
                            $pl_leave_data = UserLeave::getUserLeavesById($u_id,$month,$year,'Privilege Leave',1);
                            $sl_leave_data = UserLeave::getUserLeavesById($u_id,$month,$year,'Sick Leave',1);
                            $ul_leave_data = UserLeave::getUserLeavesById($u_id,$month,$year,'',2);

                            if (isset($pl_leave_data) && sizeof($pl_leave_data)>0) {

                                foreach ($pl_leave_data as $pl_k => $pl_v) {

                                    for ($pl_i=$pl_v['from_date']; $pl_i <= $pl_v['to_date']; $pl_i++) {

                                        $list[$key][$pl_i]['privilege_leave'] = 'Y';
                                    }
                                }
                            }

                            if (isset($sl_leave_data) && sizeof($sl_leave_data)>0) {

                                foreach ($sl_leave_data as $sl_k => $sl_v) {

                                    for ($sl_i=$sl_v['from_date']; $sl_i <= $sl_v['to_date']; $sl_i++) { 
                                        
                                        $list[$key][$sl_i]['sick_leave'] = 'Y';
                                    }
                                }
                            }

                            if (isset($ul_leave_data) && sizeof($ul_leave_data)>0) {

                                foreach ($ul_leave_data as $ul_k => $ul_v) {

                                    for ($ul_i=$ul_v['from_date']; $ul_i <= $ul_v['to_date']; $ul_i++) { 
                                        
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

            if(isset($list) && sizeof($list) > 0) {

                Excel::create($sheet_name,function($excel) use ($list,$list1,$sundays,$year,$month) {

                    $excel->sheet('sheet 1',function($sheet) use ($list,$list1,$sundays,$year,$month) {

                        $sheet->loadView('attendance-sheet', array('list' => $list,'list1' => $list1,'sundays' => $sundays,'year' => $year,'month' => $month));
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

                if($value->status > 0) {

                    $list[$user_id][date("j",strtotime($value->added_date))]['attendance'] = $value->attendance;
                }
                else {

                    $list[$user_id][date("j",strtotime($value->added_date))]['attendance'] = 'WPP';
                }

                // Set holiday dates
                $user_holidays = Holidays::getHolidaysByUserID($user_id,$month,$year);

                if (isset($user_holidays) && sizeof($user_holidays)>0) {

                    foreach ($user_holidays as $h_k => $h_v) {

                        $list[$user_id][$h_v]['holiday'] = 'Y';
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
}