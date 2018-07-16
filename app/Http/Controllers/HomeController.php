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
use Excel;
use DB;
use Calendar;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function login(Request $request)
    {

            $user = \Auth::user();
            //$user_id = \Auth::user()->id;
            // Entry of login
            $users_log= new UsersLog();
            $users_log->user_id = $user->id;
            $users_log->date = gmdate("Y-m-d");
            $users_log->time = gmdate("H:i:s");
            $users_log->type ='login';
            $users_log->created_at = gmdate("Y-m-d H:i:s");
            $users_log->updated_at = gmdate("Y-m-d H:i:s");
            $users_log->save();

            return redirect()->route('dashboard')->with('success', 'Login Successfully');
    }

    public function logout(Request $request)
    {
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

        return redirect()->route('dashboard')->with('success', 'Logout Successfully');

    }

    public function dashboard(){

        $user_role_id = \Auth::user()->roles->first()->id;

        $user =  \Auth::user();

        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $superadmin_role_id =  env('SUPERADMIN');
        $manager_role_id = env('MANAGER');

        // get assigned to todos
        $assigned_todo_ids = ToDos::getTodoIdsByUserId($user->id);
        $owner_todo_ids = ToDos::getAllTaskOwnertodoIds($user->id);

        $todo_ids = array_merge($assigned_todo_ids,$owner_todo_ids);
        $toDos = array();
        if(isset($todo_ids) && sizeof($todo_ids)>0){
            $toDos = ToDos::getAllTodosdash($todo_ids,7);
        }

        //get Job List
        $access_roles_id = array($admin_role_id,$director_role_id,$manager_role_id,$superadmin_role_id);
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
            $client = DB::table('client_basicinfo')->whereRaw('MONTH(created_at) = ?',[$month])->count();
        }

        else{
             $month = date('m');
             $client = DB::table('client_basicinfo')->whereRaw('MONTH(created_at) = ?',[$month])
                                               ->where('account_manager_id',$user->id)
                                               ->count();   
        }

        if(in_array($user_role_id,$access_roles_id)){
            $interviews = Interview::getDashboardInterviews(1,$user->id);
        }
        else{
            $interviews = Interview::getDashboardInterviews(0,$user->id);
        }

        //get candidate join list on this month
        if(in_array($user_role_id,$access_roles_id)){
            $candidatecount = JobCandidateJoiningdate::getJoiningCandidateByUserIdCount($user->id,1);
        }

        else{
            $candidatecount = JobCandidateJoiningdate::getJoiningCandidateByUserIdCount($user->id,0);
        }

        //get CVs Associated on this month
        if(in_array($user_role_id,$access_roles_id)){
            $month = date('m');
            $associated = DB::table('job_associate_candidates')->whereRaw('MONTH(date) = ?',[$month])->count();
        }
        else{
            $month = date('m');
            $associated = DB::table('job_associate_candidates')->whereRaw('MONTH(date) = ?',[$month])
                                               ->where('associate_by',$user->id)
                                               ->count();
        }

        //get No. of interviews attended this month
        if(in_array($user_role_id,$access_roles_id)){
            $month = date('m');
            $interview_attend = DB::table('interview')->whereRaw('MONTH(interview_date) = ?',[$month])
                                                      ->where('status','=','Attended')
                                                      ->count();
        }
        else{
            $month = date('m');
            $interview_attend = DB::table('interview')->whereRaw('MONTH(interview_date) = ?',[$month])
                                               ->where('interview_owner_id',$user->id)
                                               ->where('status','=','Attended')
                                               ->count();
        }        

        //print_r($interview_attend);exit;

        $viewVariable = array();
        $viewVariable['toDos'] = $toDos;
        $viewVariable['interviews'] = $interviews;
        $viewVariable['interviewCount'] = sizeof($interviews);
        $viewVariable['jobCount'] = $job;
        $viewVariable['clientCount'] = $client;
        $viewVariable['candidatejoinCount'] = $candidatecount;
        $viewVariable['associatedCount'] = $associated;
        $viewVariable['interviewAttendCount'] = $interview_attend;

        return view('dashboard',$viewVariable);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $user =  \Auth::user();

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
        }
        else{
            $response = UsersLog::getUsersAttendance($loggedin_userid,$month,$year);
           /* $response = \DB::select("select users.id ,name ,date ,min(time) as login , max(time) as logout from users_log
                        join users on users.id = users_log.user_id where month(date)= $month and year(date)=$year and users.id = $loggedin_userid group by date ,users.id");*/
        }

        $date = new Date();
        if(sizeof($response)>0){
            foreach ($response as $key => $value) {
                $login_time = $date->converttime($value->login);
                $logout_time = $date->converttime($value->logout);
                $list[$value->name][date("j S",strtotime($value->date))]['login'] = date("h:i A",$login_time);
                $list[$value->name][date("j S",strtotime($value->date))]['logout'] = date("h:i A",$logout_time);

                $total = ($logout_time - $login_time) / 60;

                $list[$value->name][date("j S",strtotime($value->date))]['total'] = date('H:i', mktime(0,$total));
            }
        }

        //print_r($list);exit;
        return view('home',array("list"=>$list,"month_list"=>$month_array,"year_list"=>$year_array,"month"=>$month,"year"=>$year),compact('isSuperAdmin','isAdmin','isAccountant','isDirector'));

        //return view('home');
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

        //print_r($interviews);exit;
        $viewVariable = array();
        $viewVariable['toDos'] = $toDos;
        $viewVariable['interviews'] = $interviews;
        $viewVariable['interviewCount'] = sizeof($interviews);

        return view('home', $viewVariable);
    }

    public function userAttendance(){

        $user =  \Auth::user();
        $loggedin_userid = \Auth::user()->id;
        $month = date("n");
        $year = \date("Y");

        // get logged in user attendance for current month
        $response = UsersLog::getUsersAttendance($loggedin_userid,$month,$year);

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

                $color = '';
                if($total>=540){
                    $color= '#00e500';
                }
                else if ($total>=480 && $total<540){
                    $color= '#CCCC00';
                }
                else{
                    $color= '#963838';
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

        $calendar = Calendar::addEvents($events);
        return view('userattendance', compact('calendar'));
    }

    public function export(){

       // $user_log = UsersLog::all();

        Excel::create('Attendance', function($excel) {

        $excel->sheet('Sheet 1', function($sheet) {

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
            $sheet->fromArray($response, null, 'A1', false, false);

            $headings = array('User Name', 'Date', 'Login', 'Logout', 'Total');

            $sheet->prependRow(1, $headings);
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
}
