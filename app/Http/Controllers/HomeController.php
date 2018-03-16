<?php

namespace App\Http\Controllers;

use App\Interview;
use App\ToDos;
use App\UsersLog;
use Illuminate\Http\Request;
use App\User;
use App\Date;
use App\ClientBasicinfo;
use App\JobOpen;
use DB;

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

    public function dashboard(){

        $loggedin_userid = \Auth::user()->id;
        $user_role_id = \Auth::user()->roles->first()->id;

        // get todo list
        $from = date('Y-m-d 00:00:00');
        $to = date('Y-m-d 23:59:59');
        $today = date('Y-m-d');
        $toDos = ToDos::where('due_date','>=',$today)->get();

        $user =  \Auth::user();

        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $access_roles_id = array($admin_role_id,$director_role_id);

        //get Job List
        if(in_array($user_role_id,$access_roles_id)){
            $job = DB::table('job_openings')->where('job_status')->count();
        }

        else{
             $job = DB::table('job_openings')->where('job_status')
                                             ->where('hiring_manager_id',$user->id)
                                             ->count();
        }

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
            $interviews = Interview::getAllInterviews(1,$user->id);
        }
        else{
            $interviews = Interview::getAllInterviews(0,$user->id);
        }

        //print_r($interviews);exit;
        $viewVariable = array();
        $viewVariable['toDos'] = $toDos;
        $viewVariable['interviews'] = $interviews;
        $viewVariable['interviewCount'] = sizeof($interviews);
        $viewVariable['jobCount'] = $job;
        $viewVariable['clientCount'] = $client;
       /* $viewVariable['candidatejoinCount'] = $candidatejoin;*/

        return view('dashboard',$viewVariable);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /*$user = \Auth::user();
        //$user_id = \Auth::user()->id;
        // Entry of login
        $users_log= new UsersLog();
        $users_log->user_id = $user->id;
        $users_log->date = gmdate("Y-m-d");
        $users_log->time = gmdate("H:i:s");
        $users_log->type ='login';
        $users_log->created_at = gmdate("Y-m-d H:i:s");
        $users_log->updated_at = gmdate("Y-m-d H:i:s");
        $users_log->save();*/

        /*if(\Auth::user()->hasRole('Administrator')){
            print_r("In");exit;
        } else {
            print_r("out");exit;
        }*/

        $loggedin_userid = \Auth::user()->id;

        $user_role_id = \Auth::user()->roles->first()->id;

        $isAdmin= User::isAdmin($user_role_id);

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

        if($isAdmin){
            $users = User::getUsers();
        }
        else{
            $users = User::getUsers($loggedin_userid);
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

        if($isAdmin){
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
        return view('home',array("list"=>$list,"month_list"=>$month_array,"year_list"=>$year_array,"month"=>$month,"year"=>$year));

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
}
