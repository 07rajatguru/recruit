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
        $users_log= new UsersLog();
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
        $users_log= new UsersLog();
        $users_log->user_id = $user_id;
        $users_log->date = gmdate("Y-m-d");
        $users_log->time = gmdate("H:i:s");
        $users_log->type ='logout';
        $users_log->created_at = gmdate("Y-m-d H:i:s");
        $users_log->updated_at = gmdate("Y-m-d H:i:s");
        $users_log->save();

        return redirect()->route('dashboard')->with('success', 'Logout Successfully.');
    }

    /*public function dashboard(){

        $user_role_id = \Auth::user()->roles->first()->id;

        $user =  \Auth::user();

        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $superadmin_role_id =  env('SUPERADMIN');
        $manager_role_id = env('MANAGER');
        $strategy_role_id = env('STRATEGY');

        // get assigned to todos
        $assigned_todo_ids = ToDos::getTodoIdsByUserId($user->id);
        $owner_todo_ids = ToDos::getAllTaskOwnertodoIds($user->id);

        $todo_ids = array_merge($assigned_todo_ids,$owner_todo_ids);
        $toDos = array();
        if(isset($todo_ids) && sizeof($todo_ids)>0){
            $toDos = ToDos::getAllTodosdash($todo_ids,7);
        }

        //get Job List
        $access_roles_id = array($admin_role_id,$director_role_id,$superadmin_role_id,$strategy_role_id);
        if(in_array($user_role_id,$access_roles_id)){
            $job_response = JobOpen::getAllJobs(1,$user->id);
        }
        else{
            $job_response = JobOpen::getAllJobs(0,$user->id);
        }

        $job = sizeof($job_response);

        //get Client List
        if(in_array($user_role_id,$access_roles_id)){
            $month = date('m');
            $year = date('Y');
            $client = DB::table('client_basicinfo')->whereRaw('MONTH(created_at) = ?',[$month])
                                                    ->whereRaw('YEAR(created_at) = ?',[$year])
                                                    ->count();
        }

        else{
             $month = date('m');
             $year = date('Y');
             $client = DB::table('client_basicinfo')->whereRaw('MONTH(created_at) = ?',[$month])
                                                ->whereRaw('YEAR(created_at) = ?',[$year])
                                                ->where('account_manager_id',$user->id)
                                                ->count();   
        }

        if(in_array($user_role_id,$access_roles_id)){
            $interviews = Interview::getDashboardInterviews(1,$user->id);
        }
        else{
            $interviews = Interview::getDashboardInterviews(0,$user->id);
        }

        if(in_array($user_role_id,$access_roles_id)){
            $interviews_cnt = Interview::getTodayTomorrowsInterviews(1,$user->id);
        }
        else{
            $interviews_cnt = Interview::getTodayTomorrowsInterviews(0,$user->id);
        }

        //get candidate join list on this month
        if(in_array($user_role_id,$access_roles_id)){
            $candidatecount = JobCandidateJoiningdate::getJoiningCandidateByUserIdCount($user->id,1);
        }

        else{
            $candidatecount = JobCandidateJoiningdate::getJoiningCandidateByUserIdCount($user->id,0);
        }

        //get CVs Associated on this month
        $month = date('m');
        $year = date('Y');
        if(in_array($user_role_id,$access_roles_id)){
            $associate_monthly_response = JobAssociateCandidates::getMonthlyReprtAssociate(0,$month,$year);
            $associate_count = $associate_monthly_response['cvs_cnt'];
        }
        else{
            $associate_monthly_response = JobAssociateCandidates::getMonthlyReprtAssociate($user->id,$month,$year);
            $associate_count = $associate_monthly_response['cvs_cnt'];
        }

        //get No. of interviews attended this month
        if(in_array($user_role_id,$access_roles_id)){
            $month = date('m');
            $year = date('Y');
            $interview_attend = DB::table('interview')->whereRaw('MONTH(interview_date) = ?',[$month])
                                                      ->whereRaw('YEAR(interview_date) = ?',[$year])
                                                      ->where('status','=','Attended')
                                                      ->count();
        }
        else{
            $month = date('m');
            $year = date('Y');
            $interview_attend = DB::table('interview')->whereRaw('MONTH(interview_date) = ?',[$month])
                                               ->whereRaw('YEAR(interview_date) = ?',[$year])
                                               ->where('interview_owner_id',$user->id)
                                               ->where('status','=','Attended')
                                               ->count();
        }
        //print_r($interview_attend);exit;

        $date = date('Y-m-d');

        $viewVariable = array();
        $viewVariable['toDos'] = $toDos;
        $viewVariable['interviews'] = $interviews;
        $viewVariable['interviewCount'] = sizeof($interviews_cnt);
        $viewVariable['jobCount'] = $job;
        $viewVariable['clientCount'] = $client;
        $viewVariable['candidatejoinCount'] = $candidatecount;
        $viewVariable['associatedCount'] = $associate_count;
        $viewVariable['interviewAttendCount'] = $interview_attend;
        $viewVariable['date'] = $date;
        $viewVariable['month'] = $month;
        $viewVariable['year'] = $year;

        return view('dashboard',$viewVariable);
    }*/

    public function dashboard() {

        $user = \Auth::user();
        $user_id =  \Auth::user()->id;
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
            ->whereRaw('MONTH(created_at) = ?',[$month])
            ->whereRaw('YEAR(created_at) = ?',[$year])->count();

            // Job Count
            $job_response = JobOpen::getAllJobs(1,$user_id);
            $job = sizeof($job_response);

            // Cvs Associated this month
            $associate_monthly_response = JobAssociateCandidates::getMonthlyReprtAssociate(0,$month,$year);
            $associate_count = $associate_monthly_response['cvs_cnt'];

            // Cvs Shortlisted this month
            $shortlisted_count = JobAssociateCandidates::getMonthlyReprtShortlisted(0,$month,$year);

            // Interview Attended this month
            $interview_attended_list = Interview::getAttendedInterviews(1,$user_id,$month,$year);
            $interview_attend = sizeof($interview_attended_list);

            // Candidate Join this month
            $candidatecount = JobCandidateJoiningdate::getJoiningCandidateByUserIdCountByMonthwise($user_id,1,$month,$year);

            // Interview Count
            $interviews = Interview::getDashboardInterviews(1,$user_id);
            //$interviews_cnt = Interview::getTodayTomorrowsInterviews(1,$user_id);
            $interviews_cnt = sizeof($interviews);
        }
        else if($display_userwise_count) {

            // Client Count
            $client = DB::table('client_basicinfo')
            ->whereRaw('MONTH(created_at) = ?',[$month])
            ->whereRaw('YEAR(created_at) = ?',[$year])->where('account_manager_id',$user_id)->count();

            // Job Count
            $job_response = JobOpen::getAllJobs(0,$user_id);
            $job = sizeof($job_response);

            // Cvs Associated this month
            $associate_monthly_response = JobAssociateCandidates::getMonthlyReprtAssociate($user_id,$month,$year);
            $associate_count = $associate_monthly_response['cvs_cnt'];

            // Cvs Associated this month
            $associate_monthly_response = JobAssociateCandidates::getMonthlyReprtAssociate($user_id,$month,$year);
            $associate_count = $associate_monthly_response['cvs_cnt'];

            // Cvs Shortlisted this month
            $shortlisted_count = JobAssociateCandidates::getMonthlyReprtShortlisted($user_id,$month,$year);

            // Interview Attended this month
            $interview_attended_list = Interview::getAttendedInterviews(0,$user_id,$month,$year);
            $interview_attend = sizeof($interview_attended_list);

            // Candidate Join this month
            $candidatecount = JobCandidateJoiningdate::getJoiningCandidateByUserIdCountByMonthwise($user_id,0,$month,$year);

            // Interview Count
            $interviews = Interview::getDashboardInterviews(0,$user_id);
            //$interviews_cnt = Interview::getTodayTomorrowsInterviews(0,$user_id);
            $interviews_cnt = sizeof($interviews);
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
        $viewVariable['date'] = $date;
        $viewVariable['month'] = $month;
        $viewVariable['year'] = $year;

        return view('dashboard',$viewVariable);
    }

    public function dashboardMonthwise() {

        $user = \Auth::user();
        $user_id =  \Auth::user()->id;
        $display_monthwise = $user->can('display-month-wise-dashboard');
        $display_all_count = $user->can('display-all-count');
        $display_userwise_count = $user->can('display-userwise-count');
        
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

        $year_array = array();
        $year_array[2017] = 2017;
        $year_array[2018] = 2018;
        $year_array[2019] = 2019;
        $year_array[2020] = 2020;
        $year_array[2021] = 2021;

        if($display_monthwise) {

            if($display_all_count) {

                // Client Count
                $clientCount = DB::table('client_basicinfo')
                ->whereRaw('MONTH(created_at) = ?',[$month])
                ->whereRaw('YEAR(created_at) = ?',[$year])->count();

                // Job Count
                $job_response = JobOpen::getAllJobs(1,$user_id);
                $jobCount = sizeof($job_response);

                // Cvs Associated this month
                $associate_monthly_response = JobAssociateCandidates::getMonthlyReprtAssociate(0,$month,$year);
                $associatedCount = $associate_monthly_response['cvs_cnt'];

                // Cvs Shortlisted this month
                $shortlisted_count = JobAssociateCandidates::getMonthlyReprtShortlisted(0,$month,$year);

                // Interview Attended this month
                $interview_attended_list = Interview::getAttendedInterviews(1,$user_id,$month,$year);
                $interviewAttendCount = sizeof($interview_attended_list);

                // Candidate Join this month
                $candidatejoinCount = JobCandidateJoiningdate::getJoiningCandidateByUserIdCountByMonthwise($user_id,1,$month,$year);
            }
            else if($display_userwise_count) {

                // Client Count
                $clientCount = DB::table('client_basicinfo')
                ->whereRaw('MONTH(created_at) = ?',[$month])->whereRaw('YEAR(created_at) = ?',[$year])
                ->where('account_manager_id',$user_id)->count();

                // Job Count
                $job_response = JobOpen::getAllJobs(0,$user_id);
                $jobCount = sizeof($job_response);

                // Cvs Associated this month
                $associate_monthly_response = JobAssociateCandidates::getMonthlyReprtAssociate($user_id,$month,$year);
                $associatedCount = $associate_monthly_response['cvs_cnt'];

                // Cvs Shortlisted this month
                $shortlisted_count = JobAssociateCandidates::getMonthlyReprtShortlisted($user_id,$month,$year);

                // Interview Attended this month
                $interview_attended_list = Interview::getAttendedInterviews(0,$user_id,$month,$year);
                $interviewAttendCount = sizeof($interview_attended_list);

                // Candidate Join this month
                $candidatejoinCount = JobCandidateJoiningdate::getJoiningCandidateByUserIdCountByMonthwise($user_id,0,$month,$year);
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

        /*$superadmin_role_id =  env('SUPERADMINUSERID');
        if ($user_id != $superadmin_role_id) {
            
        }
        else{
            return view('dashboardmonthwise',$viewVariable);
        }*/
    }

    /*public function OpentoAllJob(){

        $user_id =  \Auth::user()->id;

        $user_role_id = \Auth::user()->roles->first()->id;
        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $superadmin_role_id =  env('SUPERADMIN');
        $manager_role_id = env('MANAGER');
        $strategy_role_id = env('STRATEGY');

        $access_roles_id = array($admin_role_id,$director_role_id,$superadmin_role_id,$strategy_role_id);
        if(in_array($user_role_id,$access_roles_id)){
            $job_opened = JobOpen::getOpenToAllJobs(1,$user_id,10);
        }
        else {
            $job_opened = JobOpen::getOpenToAllJobs(0,$user_id,10);
        }

        return json_encode($job_opened);
    }*/

    public function OpentoAllJob(){

        $user = \Auth::user();
        $user_id = \Auth::user()->id;
        $display_jobs = $user->can('display-jobs-open-to-all');

        if($display_jobs) {
            $job_opened = JobOpen::getOpenToAllJobs(10,$user_id);
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
/*    public function index()
    {
        $user =  \Auth::user();

        // get role of logged in user
        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);

        $user_obj = new User();
        $isAdmin = $user_obj::isAdmin($role_id);
        $isAccountant = $user_obj::isAccountant($role_id);
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);
        $isOperationsExecutive = $user_obj::isOperationsExecutive($role_id);

        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $superadmin_role_id =  env('SUPERADMIN');
        $acct_role_id = env('ACCOUNTANT');
        $developer_role_id = env('DEVELOPER');
        $operations_excutive_role_id = env('OPERATIONSEXECUTIVE');

        $loggedin_userid = \Auth::user()->id;

        $user_role_id = \Auth::user()->roles->first()->id;

        //$isAdmin= User::isAdmin($user_role_id);

        $list=array();

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

        $access_roles_id = array($admin_role_id,$director_role_id,$acct_role_id,$superadmin_role_id,$developer_role_id,$operations_excutive_role_id);
        if(in_array($user_role_id,$access_roles_id)){
            $users = User::getOtherUsers();
        }
        else{
            $users = User::getOtherUsers($loggedin_userid);
        }

        $list = array();
        for($d=1; $d<=31; $d++)
        {
            $time=mktime(12, 0, 0, $month, $d, $year);
            foreach ($users as $key => $value) {
              //  echo date('m', $time);exit;
                if (date('n', $time)==$month)
                    $list[$key][date('j', $time)]['login']='';
                $list[$key][date('j', $time)]['logout']='';
                $list[$key][date('j', $time)]['total']='';
                $list[$key][date('j', $time)]['remarks']='';
            }
        }

        if(in_array($user_role_id,$access_roles_id)){
            $response = UsersLog::getUsersAttendance(0,$month,$year);
            $user_remark = UserRemarks::getUserRemarksByUserid(0);
        }
        else{
            $response = UsersLog::getUsersAttendance($loggedin_userid,$month,$year);
            $user_remark = UserRemarks::getUserRemarksByUserid($loggedin_userid);
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

        $users_name = User::getAllUsersForRemarks(['Recruiter','admin']);

        return view('home',array("list"=>$list,"list1"=>$list1,"month_list"=>$month_array,"year_list"=>$year_array,"month"=>$month,"year"=>$year,"user_remark"=>$user_remark),compact('isSuperAdmin','isAdmin','isAccountant','isDirector','users_name','isOperationsExecutive'));

        $from = date('Y-m-d 00:00:00');
        $to = date('Y-m-d 23:59:59');
        $today = date('Y-m-d');
        $toDos = ToDos::where('due_date','>=',$today)->get();

        $interviews = Interview::leftjoin('client_basicinfo','client_basicinfo.id','=','interview.client_id')
            ->leftjoin('candidate_basicinfo','candidate_basicinfo.id','=','interview.candidate_id')
            ->leftjoin('job_openings','job_openings.id','=','interview.posting_title')
            ->select('interview.id as id', 'interview.interview_name as interview_name','interview.interview_date',
                'interview.client_id as clint_id', 'client_basicinfo.name as client_name',
                'interview.candidate_id as candidate_id', 'candidate_basicinfo.fname as candidate_fname',
                'candidate_basicinfo.lname as candidate_lname', 'interview.posting_title as posting_title_id',
                'job_openings.posting_title as posting_title')
            ->where('interview_date','>=',$from)
            ->where('interview_date','<=',$to)
            ->get();

        $viewVariable = array();
        $viewVariable['toDos'] = $toDos;
        $viewVariable['interviews'] = $interviews;
        $viewVariable['interviewCount'] = sizeof($interviews);

        return view('home', $viewVariable);
    }*/

    public function index() {

        $user = \Auth::user();
        $user_id = \Auth::user()->id;
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

        $users_name = User::getAllUsersForRemarks(['Recruiter','admin']);

        return view('home',array("list"=>$list,"list1"=>$list1,"month_list"=>$month_array,"year_list"=>$year_array,"month"=>$month,"year"=>$year,"user_remark"=>$user_remark),compact('users_name'));
    }

    /*public function userAttendance(){

        $user =  \Auth::user();
        $loggedin_userid = \Auth::user()->id;
        $month = date("n");
        $year = \date("Y");

        // get role of logged in user
        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);

        $user_obj = new User();
        $isAccountant = $user_obj::isAccountant($role_id);
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);
        $isOperationsExecutive = $user_obj::isOperationsExecutive($role_id);

        if(isset($_POST['selected_user_id']) && $_POST['selected_user_id'] != '')
        {
            $user_id = $_POST['selected_user_id'];
             // get logged in user attendance for current month
            $response = UsersLog::getUsersAttendance($user_id,0,0);

            // get logged in user remarks
            $user_remarks = UserRemarks::getUserRemarksByUserid($user_id);
        }
        else
        {
            // get logged in user attendance for current month
            $response = UsersLog::getUsersAttendance($loggedin_userid,0,0);

            // get logged in user remarks
            $user_remarks = UserRemarks::getUserRemarksByUserid($loggedin_userid);
        }

        $date = new Date();

        if($response->count()>0){
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

        $users_name = User::getAllUsersForRemarks(['Recruiter','admin']);
        
        return view('userattendance', compact('calendar','isSuperAdmin','isAccountant','users_name','isOperationsExecutive'));
    }*/

    public function userAttendance(){

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

        if($response->count()>0){
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

        $users_name = User::getAllUsersForRemarks(['Recruiter','admin']);
        
        return view('userattendance', compact('calendar','users_name'));
    }

    // Save User remarks in calendar
    public function storeUserRemarks(Request $request){

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
    }

    public function export(){

        // $user_log = UsersLog::all();

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
               
                /*print_r($response);exit;*/

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

                   // $sheet->prependRow(1,$dt_header);
                    $sheet->fromArray($dt_header, null, 'B1', false, false);   
                }

                //$sheet->fromArray($response, null, 'A2', false, false);

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

    public function calenderevent(){

        /*$user =  \Auth::user();

        // get role of logged in user
        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);

        $user_obj = new User();
        $isAdmin = $user_obj::isAdmin($role_id);
        $isAccountant = $user_obj::isAccountant($role_id);
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);

        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $superadmin_role_id =  env('SUPERADMIN');
        $acct_role_id = env('ACCOUNTANT');

        $loggedin_userid = \Auth::user()->id;

        $user_role_id = \Auth::user()->roles->first()->id;
        
        $list=array();

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

        $year_array = array();
        $year_array[2016] = 2016;
        $year_array[2017] = 2017;
        $year_array[2018] = 2018;

        $access_roles_id = array($admin_role_id,$director_role_id,$acct_role_id,$superadmin_role_id);
        if(in_array($user_role_id,$access_roles_id)){
            $users = User::getOtherUsers();
        }
        else{
            $users = User::getOtherUsers($loggedin_userid);
        }

        $list = array();
        for($d=1; $d<=31; $d++)
        {
            $time=mktime(12, 0, 0, $month, $d, $year);
            foreach ($users as $key => $value) {
              //  echo date('m', $time);exit;
                if (date('n', $time)==$month)
                    $list[$key][date('j S', $time)]['login']='';
                $list[$key][date('j S', $time)]['logout']='';
                $list[$key][date('j S', $time)]['total']='';
            }
        }

        if(in_array($user_role_id,$access_roles_id)){
            $response = UsersLog::getUsersAttendance(0,$month,$year);
            /*$response = \DB::select("select users.id ,name ,date ,min(time) as login , max(time) as logout from users_log
                        join users on users.id = users_log.user_id where month(date)= $month and year(date)=$year group by date,users.id");*/
       /* }
        else{
            $response = UsersLog::getUsersAttendance($loggedin_userid,$month,$year);
           /* $response = \DB::select("select users.id ,name ,date ,min(time) as login , max(time) as logout from users_log
                        join users on users.id = users_log.user_id where month(date)= $month and year(date)=$year and users.id = $loggedin_userid group by date ,users.id");*/
       // }

        /*$date = new Date();
        if(sizeof($response)>0){
            foreach ($response as $key => $value) {
                $login_time = $date->converttime($value->login);
                $logout_time = $date->converttime($value->logout);
                $list[$value->name][date("j S",strtotime($value->date))]['login'] = date("h:i A",$login_time);
                $list[$value->name][date("j S",strtotime($value->date))]['logout'] = date("h:i A",$logout_time);

                $total = ($logout_time - $login_time) / 60;

                $list[$value->name][date("j S",strtotime($value->date))]['total'] = date('H:i', mktime(0,$total));
            }
        }*/

        $data[] = array(
            'title' => 'login',
            'start' => date('Y-m-d'),
            'end' => date('Y-m-d')
        ); 
        
        return json_encode($data);
    }

    /*//Url for test mail with from name & from address
    public function testMail(){

        $user_id = \Auth::User()->id;

        $user_name = "Saloni Lalwani";
        $user_email = User::getUserEmailById($user_id);
        //print_r($user_name);exit;

        $input['to'] = 'meet@trajinfotech.com';
        $input['cc'] = 'saloni@trajinfotech.com';
        $input['from_address'] = $user_email;
        $input['from_name'] = $user_name;

        \Mail::send('adminlte::emails.sample', $input, function ($message) use ($input) {
            $message->from($input['from_address'], $input['from_name']);
            $message->to($input['to'])->cc($input['cc'])->subject('Test Mail');
        });

    }*/
}
