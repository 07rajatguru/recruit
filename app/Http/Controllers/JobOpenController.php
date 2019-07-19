<?php

namespace App\Http\Controllers;

use App\Date;
use App\Events\NotificationEvent;
use App\JobVisibleUsers;
use App\TeamMates;
use Illuminate\Http\Request;
use App\Industry;
use App\ClientBasicinfo;
use App\User;
use App\JobOpen;
use App\JobOpenDoc;
use App\Interview;
use Carbon\Carbon;
use App\Utils;
use App\CandidateBasicInfo;
use App\JobAssociateCandidates;
use App\JobCandidateJoiningdate;
use App\Lead;
use DB;
use App\CandidateStatus;
use Illuminate\Queue\Jobs\Job;
use Illuminate\Support\Facades\Input;
use Excel;
use App\Events\NotificationMail;
use App\Holidays;
use App\ClientHeirarchy;

class JobOpenController extends Controller
{

    public function salary()
    {
        $job_salary = JobOpen::select('job_openings.id as id','job_openings.salary_from as salary_from','job_openings.salary_to as salary_to')
                                  //->limit(2)
                                  ->get();

        $i=0;
        foreach ($job_salary as $jobsalary) {
            $id[$i] = $jobsalary->id;
            $salary_from[$i] = $jobsalary->salary_from;
            $salary_to[$i] = $jobsalary->salary_to;

            $sid = $id[$i];
            $sfrom = $salary_from[$i];
            $sto = $salary_to[$i];

        $from = $sfrom; 
        $lacs_from = floor($from/100000);
        $lacs_convert = ($lacs_from*100000);
        $remaining = $from - $lacs_convert;
        $thousand_from = floor($remaining/1000);

        $to = $sto; 
        $lacs_to = floor($to/100000);
        $lacs_convert = ($lacs_to*100000);
        $remaining = $to - $lacs_convert;
        $thousand_to = floor($remaining/1000);

        if ($lacs_from>=100) 
            $lacs_from = "100+";
        
        if ($lacs_to>=100) 
            $lacs_to = "100+";

        // lacs dropdown
        $lacs = array();
        $lacs[0] = 'lacs';
        for($i=1;$i<=50;$i++){
            $lacs[$i] = $i;
        }
        for($i=55;$i<100;$i+=5){
            $lacs[$i] = $i;
        }
        $lacs['100+'] = '100+';

        // Thousand dropdown
        $thousand = array(''=>'Thousand');
        for($i=0;$i<100;$i+=5){
            $thousand[$i] = $i;
        }

        // Value for Thousand From if not in array
        if(!in_array($thousand_from,$thousand)){
            $tfrom = $thousand_from%5;
            $thousand_from = $thousand_from - $tfrom;
        }
        else{
            $thousand_from = $thousand_from;
        }

        // Value for Thousand To if not in array
        if(!in_array($thousand_to,$thousand)){
            $tto = $thousand_to%5;
            $thousand_to = $thousand_to - $tto;
        }
        else{
            $thousand_to = $thousand_to;
        }

        // Value for Lacs From if not in array
        if(!in_array($lacs_from,$lacs)){
            $lfrom = $lacs_from%5;
            $lacs_from = $lacs_from - $lfrom;
        }
        else{
            $lacs_from = $lacs_from;
        }

        // Value for Lacs To if not in array
        if(!in_array($lacs_to,$lacs)){
            $lto = $lacs_to%5;
            $lacs_to = $lacs_to - $lto;
        }
        else{
            $lacs_to = $lacs_to;
        }

        
        //echo "id:".$sid."lacs_from:".$lacs_from."lacs_to:".$lacs_to;
        //echo "id:".$sid."lacs_from:".$lacs_from." Thousand_from: ".$thousand_from;
        //echo "id:".$sid."lacs_to:".$lacs_to." thousand_to: ".$thousand_to;

        DB::statement("UPDATE job_openings SET lacs_from = '$lacs_from', thousand_from = '$thousand_from', lacs_to = '$lacs_to', thousand_to = '$thousand_to' where id=$sid");
            $i++;
        }
        

    }

    public function work()
    {
        $job_work = JobOpen::select('job_openings.id as id','job_openings.work_experience_from as work_exp_from','job_openings.work_experience_to as work_exp_to')
                                  ->limit(13)
                                  ->get();


        $i=0;
        foreach ($job_work as $jobwork) {
            $id[$i] = $jobwork->id;
            $work_exp_from[$i] = $jobwork->work_exp_from;
            $work_exp_to[$i] = $jobwork->work_exp_to;

            $wid = $id[$i];
            $wfrom = $work_exp_from[$i];
            $wto = $work_exp_to[$i];
            $i++;
            DB::statement("UPDATE job_openings SET work_exp_from = '$wfrom', work_exp_to = '$wto' where id=$wid");
        }
        //echo $job_work;exit;

    }

    //Script for set open_to_all_date based on created_date
    public function openToAllDate(){

        $job_date = JobOpen::select('job_openings.id as id','job_openings.created_at as added_date')
                                    ->limit(4)
                                    ->get();

        $i = 0;
        foreach ($job_date as $key => $value) {
            $id[$i] = $value->id;
            $add_date[$i] = $value->added_date;

            $job_id = $id[$i];
            $added_date = $add_date[$i];

            //From when Job Open to all Date set
            $date_day = date('l',strtotime($added_date));
            if ($date_day == 'Friday') {
                $open_to_all = date('Y-m-d H:i:s',strtotime("$added_date +3 days"));
            }
            else if ($date_day == 'Saturday') {
                $open_to_all = date('Y-m-d H:i:s',strtotime("$added_date +3 days"));
            }
            else{
                $open_to_all = date('Y-m-d H:i:s',strtotime("$added_date +2 days"));
            }
            DB::statement("UPDATE job_openings SET open_to_all_date = '$open_to_all' where id=$job_id");
            $i++;
            //print_r($open_to_all);exit;
        }
    }

    public function index(Request $request){

        // logged in user with role 'Administrator,Director,Manager can see all the open jobs
        // Rest other users can only see the jobs assigned to them

        $user = \Auth::user();

        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);

        $user_obj = new User();

        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);

        $user_id = $user->id;
        $user_role_id = User::getLoggedinUserRole($user);

        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $manager_role_id = env('MANAGER');
        $superadmin_role_id = env('SUPERADMIN');
        $isStrategy = $user_obj::isStrategyCoordination($role_id);
        $isClient = $user_obj::isClient($role_id);

        // for get client id by email
        $user_email = $user->email;
        $client_id = ClientBasicinfo::getClientIdByEmail($user_email);
        /*$access_roles_id = array($admin_role_id,$director_role_id,$manager_role_id,$superadmin_role_id,$isStrategy);
        if(in_array($user_role_id,$access_roles_id)){
            $count = JobOpen::getAllJobsCount(1,$user_id,'');
        }
        else{
            $count = JobOpen::getAllJobsCount(0,$user_id,'');
        }*/

        // Year Data
        $starting_year = '2017';
        $ending_year = date('Y',strtotime('+1 year'));
        $year_array = array();
        $year_array[0] = "Select Year";
        for ($y=$starting_year; $y < $ending_year ; $y++) {
            $next = $y+1;
            $year_array[$y.'-4, '.$next.'-3'] = 'April-' .$y.' to March-'.$next;
        }

        if (isset($_POST['year']) && $_POST['year'] != '') {
            $year = $_POST['year'];
            if (isset($year) && $year != 0) {
                $year_data = explode(", ", $year); // [result : Array ( [0] => 2019-4 [1] => 2020-3 )] by default
                $year1 = $year_data[0]; // [result : 2019-4]
                $year2 = $year_data[1]; // [result : 2020-3]
                $current_year = date('Y-m-d h:i:s',strtotime("first day of $year1"));
                $next_year = date('Y-m-d h:i:s',strtotime("last day of $year2"));
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

        $access_roles_id = array($admin_role_id,$director_role_id/*,$manager_role_id*/,$superadmin_role_id,$isStrategy);
        if(in_array($user_role_id,$access_roles_id)){
            $count = JobOpen::getAllJobsCount(1,$user_id,NUll,$current_year,$next_year);
            $job_priority_data = JobOpen::getPriorityWiseJobs(1,$user_id,NULL);
        }
        else if ($isClient) {
            $job_response = JobOpen::getAllJobsByCLient($client_id);
            $count = sizeof($job_response);
            $job_priority_data = JobOpen::getPriorityWiseJobsByClient($client_id,NULL);
        }
        else{
            $count = JobOpen::getAllJobsCount(0,$user_id,NULL,$current_year,$next_year);
            $job_priority_data = JobOpen::getPriorityWiseJobs(0,$user_id,NULL);
        }

        $priority_0 = 0;
        $priority_1 = 0;
        $priority_2 = 0;
        $priority_3 = 0;
        $priority_5 = 0;
        $priority_6 = 0;
        $priority_7 = 0;
        $priority_8 = 0;

        foreach ($job_priority_data as $job_priority) 
        {
           if($job_priority['priority'] == 0) 
           {
                $priority_0++;
           }
           else if($job_priority['priority'] == 1) 
           {
                $priority_1++;
           }
            else if($job_priority['priority'] == 2) 
           {
                $priority_2++;
           }
            else if($job_priority['priority'] == 3) 
           {
                $priority_3++;
           }
            else if($job_priority['priority'] == 5) 
           {
                $priority_5++;
           }
            else if($job_priority['priority'] == 6) 
           {
                $priority_6++;
           }
            else if($job_priority['priority'] == 7) 
           {
                $priority_7++;
           }
            else if($job_priority['priority'] == 8) 
           {
                $priority_8++;
           }
        }

        //$count = sizeof($job_response);

        $viewVariable = array();
        //$viewVariable['jobList'] = $job_response;
        $viewVariable['job_priority'] = JobOpen::getJobPriorities();
        $viewVariable['isSuperAdmin'] = $isSuperAdmin;
        $viewVariable['count'] = $count;
        $viewVariable['isClient'] = $isClient;
        $viewVariable['year_array'] = $year_array;
        $viewVariable['year'] = $year;

        return view('adminlte::jobopen.index', $viewVariable,compact('count','priority_0','priority_1','priority_2','priority_3','priority_5','priority_6','priority_7','priority_8'));

    }

    // Job open to all page
    public function OpentoAll(Request $request){

        // logged in user with role 'Administrator,Director,Manager can see all the open jobs
        // Rest other users can only see the jobs assigned to them

        $user = \Auth::user();

        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);

        $user_obj = new User();

        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);

        $user_id = $user->id;
        $user_role_id = User::getLoggedinUserRole($user);

        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $manager_role_id = env('MANAGER');
        $superadmin_role_id = env('SUPERADMIN');
        $isStrategy = $user_obj::isStrategyCoordination($role_id);

        $access_roles_id = array($admin_role_id,$director_role_id/*,$manager_role_id*/,$superadmin_role_id,$isStrategy);
        if(in_array($user_role_id,$access_roles_id)){
            $job_response = JobOpen::getOpenToAllJobs(1,$user_id,0);
        }
        else{
            $job_response = JobOpen::getOpenToAllJobs(0,$user_id,0);
        }

        $count = sizeof($job_response);

        $viewVariable = array();
        $viewVariable['jobList'] = $job_response;
        $viewVariable['job_priority'] = JobOpen::getJobPriorities();
        $viewVariable['isSuperAdmin'] = $isSuperAdmin;
        $viewVariable['count'] = $count;

        return view('adminlte::jobopen.opentoall', $viewVariable,compact('count'));

    }

    // Function for priority wise job page
    public function priorityWise($priority,$year){
        $user = \Auth::user();

        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);

        $user_obj = new User();

        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);

        $user_id = $user->id;
        $user_role_id = User::getLoggedinUserRole($user);

        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $manager_role_id = env('MANAGER');
        $superadmin_role_id = env('SUPERADMIN');
        $isStrategy = $user_obj::isStrategyCoordination($role_id);
        $isClient = $user_obj::isClient($role_id);

        if (isset($year) && $year != 0) {
            $year_data = explode(", ", $year); // [result : Array ( [0] => 2019-4 [1] => 2020-3 )] by default
            $year1 = $year_data[0]; // [result : 2019-4]
            $year2 = $year_data[1]; // [result : 2020-3]
            $current_year = date('Y-m-d h:i:s',strtotime("first day of $year1"));
            $next_year = date('Y-m-d h:i:s',strtotime("last day of $year2"));

            $financial_year = date('F-Y',strtotime("$current_year")) . " to " . date('F-Y',strtotime("$next_year"));
        }
        else {
            $year = NULL;
            $current_year = NULL;
            $next_year = NULL;

            $financial_year = '';
        }

        // for get client id by email
        $user_email = $user->email;
        $client_id = ClientBasicinfo::getClientIdByEmail($user_email);

        $access_roles_id = array($admin_role_id,$director_role_id/*,$manager_role_id*/,$superadmin_role_id,$isStrategy);
        if(in_array($user_role_id,$access_roles_id)){
            $job_response = JobOpen::getPriorityWiseJobs(1,$user_id,$priority,$current_year,$next_year);
            $job_response_data = JobOpen::getPriorityWiseJobs(1,$user_id,NULL,$current_year,$next_year);
        }
        else if ($isClient) {
            $job_response = JobOpen::getPriorityWiseJobsByClient($client_id,$priority);
            $count = sizeof($job_response);
            $job_response_data = JobOpen::getPriorityWiseJobsByClient($client_id,NULL);
        }
        else{
            $job_response = JobOpen::getPriorityWiseJobs(0,$user_id,$priority,$current_year,$next_year);
            $job_response_data = JobOpen::getPriorityWiseJobs(0,$user_id,NULL,$current_year,$next_year);
        }

        $count = sizeof($job_response);

        /*$priority_0 = 0;
        $priority_1 = 0;
        $priority_2 = 0;
        $priority_3 = 0;
        $priority_4 = 0;
        $priority_5 = 0;
        $priority_6 = 0;
        $priority_7 = 0;
        $priority_8 = 0;
        $priority_9 = 0;
        $priority_10 = 0;
        foreach ($job_response_data as $job_priority) 
        {
            if($job_priority['priority'] == 0) {
                $priority_0++;
            }
            else if($job_priority['priority'] == 1) {
                $priority_1++;
            }
            else if($job_priority['priority'] == 2) {
                $priority_2++;
            }
            else if($job_priority['priority'] == 3) {
                $priority_3++;
            }
            else if($job_priority['priority'] == 4) {
                $priority_4++;
            }
            else if($job_priority['priority'] == 5) {
                $priority_5++;
            }
            else if($job_priority['priority'] == 6) {
                $priority_6++;
            }
            else if($job_priority['priority'] == 7) {
                $priority_7++;
            }
            else if($job_priority['priority'] == 8) {
                $priority_8++;
            }
            else if($job_priority['priority'] == 9) {
                $priority_9++;
            }
            else if($job_priority['priority'] == 10) {
                $priority_10++;
            }
        }*/

        $viewVariable = array();
        $viewVariable['jobList'] = $job_response;
        $viewVariable['job_priority'] = JobOpen::getJobPriorities();
        $viewVariable['isSuperAdmin'] = $isSuperAdmin;
        $viewVariable['count'] = $count;
        $viewVariable['priority'] = $priority;
        // $viewVariable['priority_0'] = $priority_0;
        // $viewVariable['priority_1'] = $priority_1;
        // $viewVariable['priority_2'] = $priority_2;
        // $viewVariable['priority_3'] = $priority_3;
        // $viewVariable['priority_4'] = $priority_4;
        // $viewVariable['priority_5'] = $priority_5;
        // $viewVariable['priority_6'] = $priority_6;
        // $viewVariable['priority_7'] = $priority_7;
        // $viewVariable['priority_8'] = $priority_8;
        // $viewVariable['priority_9'] = $priority_9;
        // $viewVariable['priority_10'] = $priority_10;
        $viewVariable['isClient'] = $isClient;
        $viewVariable['financial_year'] = $financial_year;

        return view('adminlte::jobopen.prioritywisejob', $viewVariable);
    }

    public static function getJobOrderColumnName($order){
        $order_column_name = '';
        if (isset($order) && $order >= 0) {
            if ($order == 0) {
                $order_column_name = "job_openings.updated_at";
            }
            if ($order == 2) {
                $order_column_name = "job_openings.priority";
            }
            else if ($order == 3) {
                $order_column_name = "users.name";
            }
            else if ($order == 4) {
                $order_column_name = "client_basicinfo.name";
            }
            else if ($order == 5) {
                $order_column_name = "job_openings.posting_title";
            }
            else if ($order == 6) {
                $order_column_name = "count";
            }
            else if ($order == 7) {
                $order_column_name = "job_openings.city";
            }
            else if ($order == 8) {
                $order_column_name = "job_openings.lacs_from";
            }
            else if($order == 9) {
                $order_column_name = "job_openings.lacs_to";
            }
            else if ($order == 10) {
                $order_column_name = "client_basicinfo.coordinator_name";
            }
            else if ($order == 11) {
                $order_column_name = "job_openings.created_at";
            }
            else if ($order == 12) {
                $order_column_name = "job_openings.no_of_positions";
            }
        }
        return $order_column_name;
    }

    public function getAllJobsDetails(){

        $limit = $_GET['length'];
        $offset = $_GET['start'];
        $draw = $_GET['draw'];
        $search = $_GET['search']['value'];
        $order = $_GET['order'][0]['column'];
        $type = $_GET['order'][0]['dir'];

        if (isset($_GET['year']) && $_GET['year'] != '') {
            $year = $_GET['year'];

            if (isset($year) && $year != 0) {
                $year_data = explode(", ", $year); // [result : Array ( [0] => 2019-4 [1] => 2020-3 )] by default
                $year1 = $year_data[0]; // [result : 2019-4]
                $year2 = $year_data[1]; // [result : 2020-3]
                $current_year = date('Y-m-d h:i:s',strtotime("first day of $year1"));
                $next_year = date('Y-m-d h:i:s',strtotime("last day of $year2"));
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

        $order_column_name = self::getJobOrderColumnName($order);

        $user = \Auth::user();
        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);
        $user_obj = new User();
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);
        $user_id = $user->id;
        $user_role_id = User::getLoggedinUserRole($user);

        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $manager_role_id = env('MANAGER');
        $superadmin_role_id = env('SUPERADMIN');
        $isStrategy = $user_obj::isStrategyCoordination($role_id);
        $isClient = $user_obj::isClient($role_id);

        // for get client id by email
        $user_email = $user->email;
        $client_id = ClientBasicinfo::getClientIdByEmail($user_email);
        //print_r($client_id);exit;

        $job_priority = JobOpen::getJobPriorities();

        $access_roles_id = array($admin_role_id,$director_role_id/*,$manager_role_id*/,$superadmin_role_id,$isStrategy);
        if(in_array($user_role_id,$access_roles_id)){
            $job_response = JobOpen::getAllJobs(1,$user_id,$limit,$offset,$search,$order_column_name,$type,$current_year,$next_year);
            $count = JobOpen::getAllJobsCount(1,$user_id,$search,$current_year,$next_year);
        }
        else if ($isClient) {
            $job_response = JobOpen::getAllJobsByCLient($client_id,$limit,$offset,$search,$order_column_name,$type);
            $count = sizeof($job_response);
        }
        else{
            $job_response = JobOpen::getAllJobs(0,$user_id,$limit,$offset,$search,$order_column_name,$type,$current_year,$next_year);
            $count = JobOpen::getAllJobsCount(0,$user_id,$search,$current_year,$next_year);
        }

        $jobs = array();
        $i = 0;$j = 0;

        $priority_0 = 0;
        $priority_1 = 0;
        $priority_2 = 0;
        $priority_3 = 0;
        $priority_5 = 0;
        $priority_6 = 0;
        $priority_7 = 0;
        $priority_8 = 0;

        foreach ($job_response as $key => $value) {
            $action = '';
            $checkbox = '';

            $action .= '<a title="Show"  class="fa fa-circle" href="'.route('jobopen.show',$value['id']).'" style="margin:3px;"></a>';
            if(isset($value['access']) && $value['access']==1){
                $action .= '<a title="Edit" class="fa fa-edit" href="'.route('jobopen.edit',$value['id']).'" style="margin:3px;"></a>';
        
                $status_view = \View::make('adminlte::partials.jobstatus',['data' => $value, 'name' => 'jobopen', 'display_name'=>'Job Open', 'job_priority' => $job_priority]);
                $status = $status_view->render();
                $action .= $status;
            }
            if ($isSuperAdmin) {
                $delete_view = \View::make('adminlte::partials.jobdelete',['data' => $value, 'name' => 'jobopen', 'display_name'=>'Job']);
                $delete = $delete_view->render();
                $action .= $delete;
            }
            if(isset($value['access']) && $value['access']==1){
                $action .= '<a title="Clone Job"  class="fa fa-clone" href="'.route('jobopen.clone',$value['id']).'"></a>';
                $checkbox .= '<input type=checkbox name=job_ids value='.$value['id'].' class=multiple_jobs id='.$value['id'].'/>';
            }

            $managed_by = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['am_name'].'</a>';
            $company_name = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['display_name'].'</a>';
            // $level_name = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['level_name'].'</a>';
            $posting_title = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['posting_title'].'</a>';
            if ($isClient) {
                $associated_count = '<a title="Show Candidates Details" href="'.route('jobopen.candidates_details_get',$value['id']).'">'.$value['associate_candidate_cnt'].'</a>';
            }
            else{
                $associated_count = '<a title="Show Associated Candidates" href="'.route('jobopen.associated_candidates_get',$value['id']).'">'.$value['associate_candidate_cnt'].'</a>';
            }

            if($value['priority'] == 0)
            {
                $priority_0++;
            }
            else if($value['priority'] == 1)
            {
                $priority_1++;
            }
            else if($value['priority'] == 2)
            {
                $priority_2++;
            }
            else if($value['priority'] == 3)
            {
                $priority_3++;
            }
            else if($value['priority'] == 5)
            {
                $priority_5++;
            }
            else if($value['priority'] == 6)
            {
                $priority_6++;
            }
            else if($value['priority'] == 7)
            {
                $priority_7++;
            }
            else if($value['priority'] == 8)
            {
                $priority_8++;
            }

            $location = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['location'].'</a>';
            $data = array(++$j,$checkbox,$action,$managed_by,$company_name/*,$level_name*/,$posting_title,$associated_count,$location,$value['min_ctc'],$value['max_ctc'],$value['coordinator_name'],$value['created_date'],$value['no_of_positions'],$value['qual'],$value['industry'],$value['desired_candidate'],$value['priority']);
            $jobs[$i] = $data;
            $i++;
        }

        $priority = array();
        $priority['priority_0'] = $priority_0;
        $priority['priority_1'] = $priority_1;
        $priority['priority_2'] = $priority_2;
        $priority['priority_3'] = $priority_3;
        $priority['priority_5'] = $priority_5;
        $priority['priority_6'] = $priority_6;
        $priority['priority_7'] = $priority_7;
        $priority['priority_8'] = $priority_8;

        $json_data = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            'year' => $year,
            "data" => $jobs,
            "priority" => $priority
        );

        echo json_encode($json_data);exit;
    }

    /*public function index(Request $request)
    {
        $dateClass = new Date();

        $client_id = $request->client_id;
        $job_open_id = $request->job_id;
        $posting_title_id = $request->posting_title;
        $job_opening_status_id = $request->job_opening_status;
        $city_id = $request->city;

        $user_id = \Auth::user()->id;
        $client = ClientBasicinfo::getClientArray();

        // logged in user with role 'Administrator,Director,Manager can see all the open jobs
        // Rest other users can only see the jobs assigned to them

        $user = \Auth::user();
        $user_role_id = User::getLoggedinUserRole($user);

        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $manager_role_id = env('MANAGER');

        $access_roles_id = array($admin_role_id,$director_role_id,$manager_role_id);
        if(in_array($user_role_id,$access_roles_id)){
            $job_ids = array();
        }
        else{
            // get logged in users jobs from job_visble_users table
            $logged_in_users_jobs = JobVisibleUsers::where('user_id','=',$user_id)->get();
            $job_ids = array();
            if(isset($logged_in_users_jobs) && sizeof($logged_in_users_jobs)>0) {
                foreach ($logged_in_users_jobs as $logged_in_users_job) {
                    $job_ids[] = $logged_in_users_job->job_id;
                }
            }
        }

        
        $job_open_clients = JobOpen::join('client_basicinfo', 'client_basicinfo.id', '=', 'job_openings.client_id')
            ->select('job_openings.*', 'client_basicinfo.id as client_id', 'client_basicinfo.name as name',
                'client_basicinfo.mail as mail','client_basicinfo.mobile as mobile');

            if(isset($client_id) && $client_id != null){
                $job_open_clients->where('job_openings.client_id',$client_id);
            }
            if(isset($job_open_id) && $job_open_id != null){
                $job_open_clients->where('job_openings.job_id',$job_open_id);
            }
            if(isset($posting_title_id) && $posting_title_id != null){
                $job_open_clients->where('job_openings.posting_title',$posting_title_id);
            }
            if(isset($job_opening_status_id) && $job_opening_status_id != null){
                $job_open_clients->where('job_openings.job_opening_status',$job_opening_status_id);
            }
            if(isset($city_id) && $city_id != null){
                $job_open_clients->where('job_openings.city',$city_id);
            }
            if(isset($job_ids) && sizeof($job_ids)>0){
                $job_open_clients->whereIn('job_openings.id',$job_ids);
            }
            $job_open_clients = $job_open_clients->get();

        $jobList = array();
        if(isset($job_open_clients) && sizeof($job_open_clients)>0){
            foreach ($job_open_clients as $job_open_client) {
                $jobList[$job_open_client->client_id]['client_id'] = $job_open_client->client_id;
                $jobList[$job_open_client->client_id]['client_name'] = $job_open_client->name;
                $jobList[$job_open_client->client_id]['client_mail'] = $job_open_client->mail;
                $jobList[$job_open_client->client_id]['client_mobile'] = $job_open_client->mobile;

                $job_open_details = JobOpen::where('client_id',$job_open_client->client_id)->whereIn('job_openings.id',$job_ids)->get();
                if(isset($job_open_details) && sizeof($job_open_details)>0){
                    $j = 0;
                    foreach ($job_open_details as $job_open_detail) {
                        $jobList[$job_open_client->client_id]['clientJobs'][$j]['id'] = $job_open_detail->id;
                        $jobList[$job_open_client->client_id]['clientJobs'][$j]['job_id'] = $job_open_detail->job_id;
                        $jobList[$job_open_client->client_id]['clientJobs'][$j]['posting_title'] = $job_open_detail->posting_title;
                        $jobList[$job_open_client->client_id]['clientJobs'][$j]['target_date'] = $dateClass->changeYMDtoDMY($job_open_detail->target_date);
                        $jobList[$job_open_client->client_id]['clientJobs'][$j]['job_opening_status'] = $job_open_detail->job_opening_status;
                        $jobList[$job_open_client->client_id]['clientJobs'][$j]['city'] = $job_open_detail->city;

                        $j++;
                    }
                }
            }
        }


        $job_open_id = JobOpen::getJobOpeningId();
        $job_open_posting_title = JobOpen::getPostingTitle();
        $job_open_status = JobOpen::getJobOpenStatus();
        $job_open_city = JobOpen::getCity();

        $viewVariable = array();
        $viewVariable['jobList'] = $jobList;
        $viewVariable['client'] = $client;
        $viewVariable['job_open_id'] = $job_open_id;
        $viewVariable['posting_title'] = $job_open_posting_title;
        $viewVariable['job_open_status'] = array_fill_keys(array(''),'Select Job Open Status')+$job_open_status;
        if(isset($clint_id) && $client_id != null){
            $viewVariable['client_id'] = $client_id;
        }

        return view('adminlte::jobopen.index', $viewVariable);
    }*/

    public function create()
    {

        $user = \Auth::user();
        $user_id = $user->id;

        // get all industry
        $industry_res = Industry::orderBy('name', 'ASC')->get();
        $industry = array();

        if (sizeof($industry_res) > 0) {
            foreach ($industry_res as $r) {
                $industry[$r->id] = $r->name;
            }
        }

        // lacs dropdown
        $lacs = array('' => 'lacs');
        // $lacs[] = 'lacs';
        for($i=1;$i<=50;$i++){
            $lacs[$i] = $i;
        }
        for($i=55;$i<100;$i+=5){
            $lacs[$i] = $i;
        }
        $lacs['100+'] = '100+';

        // Thousand dropdown
        $thousand = array(''=>'Thousand');
        for($i=0;$i<100;$i+=5){
            $thousand[$i] = $i;
        }

        //Work experience from dropdown
        $work_from = array('0'=>'Work Experience From');
        for($i=1;$i<=30;$i++){
            $work_from[$i] = $i;
        }

        //Work experience from dropdown
        $work_to = array('0'=>'Work Experience To');
        for($i=1;$i<=30;$i++){
            $work_to[$i] = $i;
        }


        $user_role_id = User::getLoggedinUserRole($user);
        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $manager_role_id = env('MANAGER');
        $superadmin_role_id = env('SUPERADMIN');

        $access_roles_id = array($admin_role_id,$director_role_id/*,$manager_role_id*/,$superadmin_role_id);
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
                $client[$r->id] = $r->name." - ".$r->coordinator_name." - ".$r->billing_city;
            }
        }

        // get all users
        $user = \Auth::user();
        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);

        $user_obj = new User();
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);
        $user_id = $user->id;

        // For account manager
         
        $users = User::getAllUsers(NULL,'Yes');
        $select_all_users = User::getAllUsers('recruiter');
        //print_r($users);exit;
      //  $team_mates = $user_id;

        // job opening status
        /*$job_open_status = JobOpen::getJobOpenStatus();*/

        // job type
        $job_type = JobOpen::getJobTypes();

        // job priority
        $job_priorities = JobOpen::getJobPriorities();

        $no_of_positions = 1;

        $lacs_from = '';
        $thousand_from = '';
        $lacs_to = '';
        $thousand_to = '';
        $work_exp_from = '';
        $work_exp_to = '';

        // $client_hierarchy_name = ClientHeirarchy::getAllClientHeirarchyName();

        $action = "add";

        $super_admin_user_id = getenv('SUPERADMINUSERID');
        $selected_users = array($user_id,$super_admin_user_id);
        return view('adminlte::jobopen.create', compact('user_id','action', 'industry','no_of_positions', 'client', 'users', 'job_open_status', 'job_type','job_priorities','selected_users','lacs','thousand','lacs_from','thousand_from','lacs_to','thousand_to','work_from','work_to','work_exp_from','work_exp_to','select_all_users'/*,'client_hierarchy_name'*/));

    }

    public function store(Request $request)
    {

        $dateClass = new Date();

        $user_id = \Auth::user()->id;
        $user_name = \Auth::user()->name;
        $user_email = \Auth::user()->email;
        $loggedin_email = \Auth::user()->email;
        $superadminuserid = getenv('SUPERADMINUSERID');

        $input = $request->all();

        $max_id = JobOpen::find(\DB::table('job_openings')->max('id'));
        if (isset($max_id->id) && $max_id->id != '')
            $max_id = $max_id->id;
        else
            $max_id = 0;

        $posting_title = $input['posting_title'];
        $hiring_manager_id = $input['hiring_manager_id'];
        //$target_date = $input['target_date'];
        // $formatted_target_date = Carbon::parse($target_date)->format('Y/m/d');
        //$job_opening_status = $input['job_opening_status'];
        $job_priority = $input['job_priority'];
        $industry_id = $input['industry_id'];
        $client_id = $input['client_id'];
        $no_of_positions = $input['no_of_positions'];
        $date_open = $input['date_opened'];
        $target_date=$input['target_date'];
        //$formatted_date_open = Carbon::parse($date_open)->format('Y/m/d');
        $job_type = $input['job_type'];
        $job_description = $input['job_description'];
        //$work_experience_from = $input['work_experience_from'];
        //$work_experience_to = $input['work_experience_to'];
       // $salary_from = $input['salary_from'];
        //$salary_to = $input['salary_to'];
        $city = $input['city'];
        $state = $input['state'];
        $country = $input['country'];
        $job_show = 0;
        $users = $input['user_ids'];
        //print_r($users);exit;
        $desired_candidate = $input['desired_candidate'];
        $qualifications = $input['qualifications'];

        $lacs_from = $input['lacs_from'];
        $thousand_from = $input['thousand_from'];
        $lacs_to = $input['lacs_to'];
        $thousand_to = $input['thousand_to'];
        $work_exp_from = $input['work_experience_from'];
        $work_exp_to = $input['work_experience_to'];

        //if (isset($work_experience_from) && $work_experience_from == '')
          //  $work_experience_from = 0;
        //if (isset($work_experience_to) && $work_experience_to == '')
          //  $work_experience_to = 0;
       // if (isset($salary_from) && $salary_from == '')
         //   $salary_from = 0;
       // if (isset($salary_to) && $salary_to == '')
         //   $salary_to = 0;
        if (isset($no_of_positions) && $no_of_positions == '')
            $no_of_positions = 0;
        if (isset($qualifications) && $qualifications == '')
            $qualifications = '';
        if (isset($desired_candidate) && $desired_candidate == '')
            $desired_candidate = '';
        if (isset($lacs_from) && $lacs_from == '')
            $lacs_from = 0;
        if (isset($thousand_from) && $thousand_from == '')
            $thousand_from = 0;
        if (isset($lacs_to) && $lacs_to == '')
            $lacs_to = 0;
        if (isset($thousand_to) && $thousand_to == '')
            $thousand_to = 0;
        if (isset($work_exp_from) && $work_exp_from == '')
            $work_exp_from = 0;
        if (isset($work_exp_to) && $work_exp_to == '')
            $work_exp_to = 0;

        //From when Job Open to all Date set
        $date = date('Y-m-d H:i:s');
        $date_day = date('l',strtotime($date));
        if ($date_day == 'Friday') {
            $open_to_all = date('Y-m-d H:i:s',strtotime("$date +3 days"));
        }
        else if ($date_day == 'Saturday') {
            $open_to_all = date('Y-m-d H:i:s',strtotime("$date +3 days"));
        }
        else{
            $open_to_all = date('Y-m-d H:i:s',strtotime("$date +2 days"));
        }
        $change_date = date('Y-m-d',strtotime("$open_to_all"));
        $fixed_date = Holidays::getFixedLeaveDate();
        if (in_array($change_date, $fixed_date)) {
            $open_to_all_day = date('l',strtotime("$open_to_all"));
            if ($open_to_all_day == 'Saturday') {
                $open_to_all = date('Y-m-d H:i:s',strtotime("$open_to_all +2 days"));
            }
            else{
                $open_to_all = date('Y-m-d H:i:s',strtotime("$open_to_all +1 days"));   
            }
        //print_r($open_to_all);exit;
        }
        // $level_id = $input['level_id'];

        $increment_id = $max_id + 1;
        $job_unique_id = "TT-JO-$increment_id";
        $job_open = new JobOpen();
        $job_open->job_id = $job_unique_id;
        $job_open->job_show = $job_show;
        $job_open->posting_title = $posting_title;
        $job_open->hiring_manager_id = $hiring_manager_id;
        //$job_open->target_date = 'NULL';//$dateClass->changeDMYtoYMD($target_date);//'2016-01-01';// $formatted_target_date;
        //$job_open->job_opening_status = $job_opening_status;
        $job_open->industry_id = $industry_id;
        $job_open->client_id = $client_id;
        $job_open->no_of_positions = $no_of_positions;
        $job_open->date_opened = $dateClass->changeDMYtoYMD($date_open);
        $job_open->target_date = $dateClass->changeDMYtoYMD($target_date);
         //'2016-01-01';//$formatted_date_open;
        $job_open->job_type = $job_type;
        $job_open->job_description = $job_description;
        //$job_open->work_experience_from = $work_experience_from;
        //$job_open->work_experience_to = $work_experience_to;
        //$job_open->salary_from = $salary_from;
        //$job_open->salary_to = $salary_to;
        $job_open->city = $city;
        $job_open->state = $state;
        $job_open->country = $country;
        $job_open->priority = $job_priority;
        $job_open->desired_candidate = $desired_candidate;
        $job_open->qualifications = $qualifications;
        $job_open->lacs_from = $lacs_from;
        $job_open->thousand_from = $thousand_from;
        $job_open->lacs_to = $lacs_to;
        $job_open->thousand_to = $thousand_to;
        $job_open->work_exp_from = $work_exp_from;
        $job_open->work_exp_to = $work_exp_to;
        $job_open->open_to_all_date = $open_to_all;
        // $job_open->level_id = $level_id;

//     print_r($job_open);exit;
        $validator = \Validator::make(Input::all(),$job_open::$rules);

        if($validator->fails()){
            return redirect('jobs/create')->withInput(Input::all())->withErrors($validator->errors());
        }

        $job_open->save();
        $job_id = $job_open->id;
        //$job_id = $job_response->id;

        if (isset($job_id) && $job_id > 0) {

            if(isset($users) && sizeof($users)>0){
                foreach ($users as $key=>$value){
                    $job_visible_users = new JobVisibleUsers();
                    $job_visible_users->job_id = $job_id;
                    $job_visible_users->user_id = $value;
                    $job_visible_users->save();
                }
            }

            // Get job_visible_user count and check to all users then update open_to_all field
            $users_id = User::getAllUsers('recruiter');
            $user_count = sizeof($users_id);

            $job_users = sizeof($users);
            if ($job_users == $user_count) {
                \DB::statement("UPDATE job_openings SET open_to_all = '1' where id=$job_id");
            }
            else {
                \DB::statement("UPDATE job_openings SET open_to_all = '0' where id=$job_id");
            }

            $job_summary = $request->file('job_summary');
            $others_doc = $request->file('others_doc');

            if (isset($job_summary) && $job_summary->isValid()) {
                $job_summary_name = $job_summary->getClientOriginalName();
                $filesize = filesize($job_summary);

                $dir_name = "uploads/jobs/" . $job_id . "/";
                $job_summary_key = "uploads/jobs/" . $job_id . "/" . $job_summary_name;

                if (!file_exists($dir_name)) {
                    mkdir("uploads/jobs/$job_id", 0777, true);
                }

                if (!$job_summary->move($dir_name, $job_summary_name)) {
                    return false;
                } else {
                    $job_open_doc = new JobOpenDoc();

                    $job_open_doc->job_id = $job_id;
                    $job_open_doc->category = 'Job Summary';
                    $job_open_doc->name = $job_summary_name;
                    $job_open_doc->file = $job_summary_key;
                    $job_open_doc->uploaded_by = $user_id;
                    $job_open_doc->size = $filesize;
                    $job_open_doc->created_at = time();
                    $job_open_doc->updated_at = time();
                    $job_open_doc->save();
                }

            }

            if (isset($others_doc) && $others_doc->isValid()) {
                $others_doc_name = $others_doc->getClientOriginalName();
                $others_filesize = filesize($others_doc);

                $dir_name = "uploads/jobs/" . $job_id . "/";
                $others_doc_key = "uploads/jobs/" . $job_id . "/" . $others_doc_name;

                if (!file_exists($dir_name)) {
                    mkdir("uploads/jobs/$job_id", 0777, true);
                }

                if (!$others_doc->move($dir_name, $others_doc_name)) {
                    return false;
                } else {
                    $job_open_doc = new JobOpenDoc;

                    $job_open_doc->job_id = $job_id;
                    $job_open_doc->category = 'Others';
                    $job_open_doc->name = $others_doc_name;
                    $job_open_doc->file = $others_doc_key;
                    $job_open_doc->uploaded_by = $user_id;
                    $job_open_doc->size = $others_filesize;
                    $job_open_doc->created_at = time();
                    $job_open_doc->updated_at = time();
                    $job_open_doc->save();
                }

            }

            // TODO:: Notifications : On creating job openings : send notification to selected users that new job openings is added (except user who created jobopening) . default send notificaations to admin user .
            $module_id = $job_id;
            $module = 'Job Openings';
            $message = $user_name . " added new job";
            $link = route('jobopen.show',$job_id);

            $user_arr = array();

            if(isset($users) && sizeof($users)>0)
            {
                $user_emails = array();
                foreach ($users as $key=>$value)
                {
                    if($user_id!=$value)
                    {
                        $module_id = $job_id;
                        $module = 'Job Openings';
                        $message = $user_name . " added new job";
                        $link = route('jobopen.show',$job_id);
                        $user_arr =trim($value);

                        event(new NotificationEvent($module_id, $module, $message, $link, $user_arr));

                        $email = User::getUserEmailById($value);

                        $user_emails[] = $email;
                    }
                }

                // Email Notification : data store in datebase

                        $superadminsecondemail=User::getUserEmailById($superadminuserid);

                        //$cc1 = "adler.rgl@gmail.com";
                        //$cc2 = "tarikapanjwani@gmail.com";

                        $cc_users_array=array($loggedin_email,$superadminsecondemail);

                        $module = "Job Open";
                        $sender_name = $user_id;
                        $to = implode(",",$user_emails);
                        $cc = implode(",",$cc_users_array);

                        $client_name = ClientBasicinfo::getCompanyOfClientByID($client_id);
                        $client_city = ClientBasicinfo::getBillingCityOfClientByID($client_id);
                        
                        $subject = "Job Opening - ". $posting_title . "@" .$client_name . "-" . $client_city;
                        $message = "<tr><th>" . $posting_title . "/" . $job_unique_id . "</th></tr>";
                        $module_id = $job_id;

                        event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
            }

        }

        return redirect()->route('jobopen.index')->with('success', 'Job Opening Created Successfully');
    }

    public function show($id)
    {
        $dateClass = new Date();
        $job_open=array();

        $job_open_detail = \DB::table('job_openings')
            // ->leftjoin('client_heirarchy','client_heirarchy.id','=','job_openings.level_id')
            ->join('client_basicinfo', 'client_basicinfo.id', '=', 'job_openings.client_id')
            ->join('client_address','client_address.client_id','=','client_basicinfo.id')
            ->join('users', 'users.id', '=', 'job_openings.hiring_manager_id')
            ->join('industry', 'industry.id', '=', 'job_openings.industry_id')
            ->select('job_openings.*', 'client_basicinfo.name as client_name','client_basicinfo.coordinator_name as co_nm','client_address.billing_city as bill_city', 'users.name as hiring_manager_name', 'industry.name as industry_name'/*,'client_heirarchy.name as level_name'*/)
            ->where('job_openings.id', '=', $id)
            ->get();

        $job_open['id'] = $id;

        $check_visible_users=\DB::table('job_visible_users')
            ->select('users.id','users.name')
            ->join('users','users.id','=','job_visible_users.user_id')
            ->where('job_visible_users.job_id',$id)
            ->get();
   
        foreach ($job_open_detail as $key => $value) 
        {
            $user = \Auth::user();
            $user_id = $user->id;

            $userRole = $user->roles->pluck('id','id')->toArray();
            $role_id = key($userRole);
            $user_obj = new User();
            $isClient = $user_obj::isClient($role_id);

            // for get client id by email
            $user_email = $user->email;
            $client_id = ClientBasicinfo::getClientIdByEmail($user_email);

            $user_role_id = User::getLoggedinUserRole($user);

            $admin_role_id = env('ADMIN');
            $director_role_id = env('DIRECTOR');
            $manager_role_id = env('MANAGER');
            $superadmin_role_id = env('SUPERADMIN');
            $strategy_role_id = env('STRATEGY');

            $access_roles_id = array($admin_role_id,$director_role_id/*,$manager_role_id*/,$superadmin_role_id,$strategy_role_id);

            /*if(in_array($user_role_id,$access_roles_id))
            {
                $job_open['access'] = '1';
            }
            else
            {
                if($value->hiring_manager_id==$user_id)
                {
                    $job_open['access'] = '1';
                }
                else
                {
                    $cnt = 0;

                    foreach($check_visible_users as $key => $value)
                    {
                        $job_open['users_ids'][$cnt] = $value->id;

                        if($job_open['users_ids'][$cnt]==$user_id)
                        {
                            $job_open['access'] = '1';

                        }
                        else
                        {
                            $job_open['access'] = '0';
                            return view('adminlte::jobopen.403');
                        }
                        $cnt++;
                    }    
                }
            }*/

            $cnt = 0;

            foreach($check_visible_users as $key => $val)
            {
                $job_open['users_ids'][$cnt] = $val->id;
                $cnt++;
            }

            $users_array=$job_open['users_ids'];

            if(in_array($user_role_id,$access_roles_id))
            {
                $job_open['access'] = '1';
            }
            else if($value->hiring_manager_id==$user_id)
            {
                $job_open['access'] = '1';
            }
            else if(in_array($user_id,$users_array))
            {
                $job_open['access'] = '0';
            }
            else if ($isClient && $value->client_id == $client_id) {
                $job_open['access'] = '1';
            }
            else
            {
                $job_open['access'] = '0';
                return view('errors.403');
            }

            if ($value->lacs_from >= '100') 
            {
                $min_ctc = '100+';
            }
            else
            {
                $lacs_from = $value->lacs_from*100000;
                $thousand_from = $value->thousand_from*1000;
                $mictc = $lacs_from+$thousand_from;
                $minctc = $mictc/100000;
                $min_ctc = number_format($minctc,2);
            }

            if ($value->lacs_to >= '100') 
            {
                $max_ctc = '100+';
            }
            else
            {
                $lacs_to = $value->lacs_to*100000;
                $thousand_to = $value->thousand_to*1000;
                $mactc = $lacs_to+$thousand_to;
                $maxctc = $mactc/100000;
                $max_ctc = number_format($maxctc,2);
            }

          //  $salary = $min_ctc.'-'.$max_ctc;
            /*if (isset($value->level_name) && $value->level_name != '') {
                $job_open['posting_title'] = $value->level_name." - ".$value->posting_title;
            }
            else {*/
                $job_open['posting_title'] = $value->posting_title;   
            /*}*/
            $job_open['job_id'] = $value->job_id;
            $job_open['client_name'] = $value->client_name . "-" . $value->bill_city;
            $job_open['client_id'] = $value->client_id;
            //$job_open['job_opening_status'] = $value->job_opening_status;
            $job_open['desired_candidate'] = $value->desired_candidate;
            $job_open['hiring_manager_name'] = $value->hiring_manager_name;
            //$job_open['hiring_manager_id'] = $value->hiring_manager_id;
            $job_open['no_of_positions'] = $value->no_of_positions;
            $job_open['target_date'] = $dateClass->changeYMDtoDMY($value->target_date);
            $job_open['date_opened'] = $dateClass->changeYMDtoDMY($value->date_opened);
            $job_open['job_type'] = $value->job_type;
            $job_open['industry_name'] = $value->industry_name;
            $job_open['description'] = $value->job_description;
            $job_open['work_experience_from'] = $value->work_exp_from;
            $job_open['work_experience_to']= $value->work_exp_to;
            $job_open['min_salary'] = $min_ctc;
            $job_open['max_salary'] = $max_ctc;
            $job_open['country'] = $value->country;
            $job_open['state'] = $value->state;
            $job_open['city'] = $value->city;
            $job_open['education_qualification'] = $value->qualifications;

            // already added posting,massmail and job search options
            $selected_posting = array();
            $selected_mass_mail = array();
            $selected_job_search = array();

            $mo_posting = '';
            $mo_mass_mail='';
            $mo_job_search = '';
            if(isset($value->posting) && $value->posting!='')
            {
                $mo_posting = $value->posting;
                $selected_posting = explode(",",$mo_posting);
            }
            if(isset($value->mass_mail) && $value->mass_mail!='')
            {
                $mo_mass_mail = $value->mass_mail;
                $selected_mass_mail = explode(",",$mo_mass_mail);
            }
            if(isset($value->job_search) && $value->job_search!='')
            {
                $mo_job_search = $value->job_search;
                $selected_job_search = explode(",",$mo_job_search);
            }

        }

        $count = 0;

        foreach ($check_visible_users as $key => $value) 
        {
            $job_open['users'][$count] = $value->name;
            $count++;
        }

        $jobopen_model = new JobOpen();
        $upload_type = $jobopen_model->upload_type;

        $i = 0;
        $job_open['doc'] = array();
        $jobopen_doc = \DB::table('job_openings_doc')
            ->join('users', 'users.id', '=', 'job_openings_doc.uploaded_by')
            ->select('job_openings_doc.*', 'users.name as upload_name')
            ->where('job_id', '=', $id)
            ->get();

        $utils = new Utils();
        foreach ($jobopen_doc as $key => $value) {
            $job_open['doc'][$i]['name'] = $value->name;
            $job_open['doc'][$i]['id'] = $value->id;
            $job_open['doc'][$i]['url'] = "../" . $value->file;//$utils->getUrlAttribute($value->file);
            $job_open['doc'][$i]['category'] = $value->category;
            $job_open['doc'][$i]['uploaded_by'] = $value->upload_name;
            $job_open['doc'][$i]['size'] = $utils->formatSizeUnits($value->size);
            $i++;
            if (array_search($value->category, $upload_type)) {
                unset($upload_type[array_search($value->category, $upload_type)]);
            }
        }
        $upload_type['Others'] = 'Others';

        $posting_status = JobOpen::getJobPostingStatus();
        $job_search = JobOpen::getJobSearchOptions();
        $job_status = JobOpen::getJobStatus();

        return view('adminlte::jobopen.show', array('jobopen' => $job_open, 'upload_type' => $upload_type,'posting_status'=>$posting_status,
                    'job_search'=>$job_search,'selected_posting'=>$selected_posting,'selected_mass_mail'=>$selected_mass_mail,'selected_job_search'=>$selected_job_search,'job_status'=>$job_status, 'strategy_role_id' => $strategy_role_id, 'user_role_id' => $user_role_id, 'isClient' => $isClient));   
    }

    public function edit($id)
    {
        $dateClass = new Date();

        // get all industry
        $industry_res = Industry::orderBy('name', 'ASC')->get();
        $industry = array();

        $industry[0] = '-None-';
        if (sizeof($industry_res) > 0) {
            foreach ($industry_res as $r) {
                $industry[$r->id] = $r->name;
            }
        }

        $lacs = array();
        $lacs[0] = 'lacs';
        for($i=1;$i<=50;$i++){
            $lacs[$i] = $i;
        }
        for($i=55;$i<100;$i+=5){
            $lacs[$i] = $i;
        }
        $lacs['100+'] = '100+';

        // Thousand dropdown
        $thousand = array(''=>'Thousand');
        for($i=0;$i<100;$i+=5){
            $thousand[$i] = $i;
        }

        //Work experience from dropdown
        $work_from = array('0'=>'Work Experience From');
        for($i=1;$i<=30;$i++){
            $work_from[$i] = $i;
        }

        //Work experience from dropdown
        $work_to = array('0'=>'Work Experience From');
        for($i=1;$i<=30;$i++){
            $work_to[$i] = $i;
        }

        $user = \Auth::user();
        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $manager_role_id = env('MANAGER');
        $superadmin_role_id = env('SUPERADMIN');

        $user_role_id = User::getLoggedinUserRole($user);
        $user_id = $user->id;
        $access_roles_id = array($admin_role_id,$director_role_id/*,$manager_role_id*/,$superadmin_role_id);

        if(in_array($user_role_id,$access_roles_id))
        {
            // get all clients
            $client_res = ClientBasicinfo::getLoggedInUserClients(0);
        }
        else
        {
            // get logged in user clients
            $client_res = ClientBasicinfo::getLoggedInUserClients($user_id);
        }

        $client = array();

        if (sizeof($client_res) > 0) {
            foreach ($client_res as $r) {
                $client[$r->id] = $r->name." - ".$r->coordinator_name." - ".$r->billing_city;
            }
        }

        // get all users
        $user = \Auth::user();
        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);

        $user_obj = new User();
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);
        $user_id = $user->id;

        // For account manager
         
        $users = User::getAllUsers(NULL,'Yes');
        $select_all_users = User::getAllUsers('recruiter');
        
        /*$users_res = User::orderBy('name', 'ASC')->get();
        $users = array();

        if (sizeof($users_res) > 0) {
            foreach ($users_res as $r) {
                $users[$r->id] = $r->name;
            }
        }*/

        // job opening status
        $job_open_status = JobOpen::getJobOpenStatus();

        // job type
        $job_type = JobOpen::getJobTypes();

        // job priority
        $job_priorities = JobOpen::getJobPriorities();
        // get Client hierarchy names
        // $client_hierarchy_name = ClientHeirarchy::getAllClientHeirarchyName();

        $job_open = JobOpen::find($id);

    if(in_array($user_role_id,$access_roles_id) || ($job_open->hiring_manager_id==$user_id))
    {

        
        
        $user_id = $job_open->hiring_manager_id;
        $lacs_from = $job_open->lacs_from;
        $thousand_from = $job_open->thousand_from;
        $lacs_to = $job_open->lacs_to;
        $thousand_to = $job_open->thousand_to;
        $work_exp_from = $job_open->work_exp_from;
        $work_exp_to = $job_open->work_exp_to;
        //print_r($job_open);exit;
       // $target_date = '';
        //$dateClass->changeYMDtoDMY($job_open->target_date);
        $date_opened = $dateClass->changeYMDtoDMY($job_open->date_opened);
        $target_date = $dateClass->changeYMDtoDMY($job_open->target_date);
        $job_visible_users = JobVisibleUsers::where('job_id',$id)->get();
       /* $team_mates = array();
        if(isset($job_visible_users) && sizeof($job_visible_users)>0){
            foreach($job_visible_users as $row){
                $team_mates[] = $row->user_id;
            }
        }*/

        $selected_users = array();
        if(isset($job_visible_users) && sizeof($job_visible_users)>0){
            foreach($job_visible_users as $row){
                $selected_users[] = $row->user_id;
            }
        }

    }
    else
    {
        return view('errors.403');
    }

        $action = "edit";

        return view('adminlte::jobopen.edit', compact('user_id','action', 'industry', 'client', 'users', 'job_open_status', 'job_type','job_priorities', 'job_open', 'date_opened', 'target_date','team_mates','selected_users','lacs','thousand','lacs_from','thousand_from','lacs_to','thousand_to','work_from','work_to','work_exp_from','work_exp_to','select_all_users'/*,'client_hierarchy_name'*/));

    }

    public function update(Request $request, $id)
    {

        $dateClass = new Date();

        $user_id = \Auth::user()->id;
        $input = $request->all();

        $max_id = JobOpen::find(\DB::table('job_openings')->max('id'));
        if (isset($max_id->id) && $max_id->id != '')
            $max_id = $max_id->id;
        else
            $max_id = 0;

        $posting_title = $input['posting_title'];
        $hiring_manager_id = $input['hiring_manager_id'];
       // $target_date = $input['target_date'];
        // $formatted_target_date = Carbon::parse($target_date)->format('Y/m/d');
        //$job_opening_status = $input['job_opening_status'];
        $industry_id = $input['industry_id'];
        $client_id = $input['client_id'];
        $no_of_positions = $input['no_of_positions'];
        $date_open = $input['date_opened'];
        $target_date = $input['target_date'];
        //$formatted_date_open = Carbon::parse($date_open)->format('Y/m/d');
        $job_type = $input['job_type'];
        $job_description = $input['job_description'];
        //$work_experience_from = $input['work_experience_from'];
        //$work_experience_to = $input['work_experience_to'];
        //$salary_from = $input['salary_from'];
        //$salary_to = $input['salary_to'];
        $city = $input['city'];
        $state = $input['state'];
        $country = $input['country'];
        $job_priority = $input['job_priority'];
        //print_r($input);exit;
        $users = $input['user_ids'];
        $desired_candidate = $input['desired_candidate'];
        $qualifications = $input['qualifications'];
        $lacs_from = $input['lacs_from'];
        $thousand_from = $input['thousand_from'];
        $lacs_to = $input['lacs_to'];
        $thousand_to = $input['thousand_to'];
        $work_exp_from = $input['work_experience_from'];
        $work_exp_to = $input['work_experience_to'];

       // if (isset($work_experience_from) && $work_experience_from == '')
         //   $work_experience_from = 0;
        //if (isset($work_experience_to) && $work_experience_to == '')
          //  $work_experience_to = 0;
       // if (isset($salary_from) && $salary_from == '')
         //   $salary_from = 0;
        //if (isset($salary_to) && $salary_to == '')
          //  $salary_to = 0;
        if (isset($no_of_positions) && $no_of_positions == '')
            $no_of_positions = 0;
        if (isset($desired_candidate) && $desired_candidate == '')
            $desired_candidate = '';
        if (isset($qualifications) && $qualifications == '')
            $qualifications = '';
        if (isset($lacs_from) && $lacs_from == '')
            $lacs_from = 0;
        if (isset($thousand_from) && $thousand_from == '')
            $thousand_from = 0;
        if (isset($lacs_to) && $lacs_to == '')
            $lacs_to = 0;
        if (isset($thousand_to) && $thousand_to == '')
            $thousand_to = 0;
        if (isset($work_exp_from) && $work_exp_from == '')
            $work_exp_from = 0;
        if (isset($work_exp_to) && $work_exp_to == '')
            $work_exp_to = 0;

        // $level_id = $input['level_id'];

        $increment_id = $max_id + 1;
        $job_unique_id = "TT-JO-$increment_id";
        $job_open = JobOpen::find($id);
        $job_open->job_id = $job_unique_id;
        //$job_open->job_show = $job_show;
        $job_open->posting_title = $posting_title;
        $job_open->hiring_manager_id = $hiring_manager_id;
       // $job_open->target_date = 'NULL';//$dateClass->changeDMYtoYMD($target_date);//'2016-01-01';// $formatted_target_date;
        //$job_open->job_opening_status = $job_opening_status;
        $job_open->industry_id = $industry_id;
        $job_open->client_id = $client_id;
        $job_open->no_of_positions = $no_of_positions;
        $job_open->date_opened = $dateClass->changeDMYtoYMD($date_open);
         //'2016-01-01';//$formatted_date_open;

        $job_open->target_date = $dateClass->changeDMYtoYMD($target_date);
        $job_open->job_type = $job_type;
        $job_open->job_description = $job_description;
        //$job_open->work_experience_from = $work_experience_from;
        //$job_open->work_experience_to = $work_experience_to;
       // $job_open->salary_from = $salary_from;
        //$job_open->salary_to = $salary_to;
        $job_open->city = $city;
        $job_open->state = $state;
        $job_open->country = $country;
        $job_open->priority = $job_priority;
        $job_open->qualifications = $qualifications;
        $job_open->desired_candidate = $desired_candidate;
        $job_open->lacs_from = $lacs_from;
        $job_open->thousand_from = $thousand_from;
        $job_open->lacs_to = $lacs_to;
        $job_open->thousand_to = $thousand_to;
        $job_open->work_exp_from = $work_exp_from;
        $job_open->work_exp_to = $work_exp_to;
        // $job_open->level_id = $level_id;

        $validator = \Validator::make(Input::all(),$job_open::$rules);

        if($validator->fails()){
            return redirect('jobs/'.$id.'/edit')->withInput(Input::all())->withErrors($validator->errors());
        }

        $job_open->save();
        $job_id = $job_open->id;
        JobVisibleUsers::where('job_id',$job_id)->delete();
        if(isset($users) && sizeof($users)>0){
            foreach ($users as $key=>$value){
                $job_visible_users = new JobVisibleUsers();
                $job_visible_users->job_id = $job_id;
                $job_visible_users->user_id = $value;
                $job_visible_users->save();
            }
        }

        // Get job_visible_user count and check to all users then update open_to_all field
        $users_id = User::getAllUsers('recruiter');
        $user_count = sizeof($users_id);

        $job_users = sizeof($users);
        if ($job_users == $user_count) {
            \DB::statement("UPDATE job_openings SET open_to_all = '1' where id=$job_id");
        }
        else {
            \DB::statement("UPDATE job_openings SET open_to_all = '0' where id=$job_id");
        }

        return redirect()->route('jobopen.index')->with('success', 'Job Opening Updated Successfully');
    }

    public function jobClone($id){
        $user = \Auth::user();
        $user_id = $user->id;

        // get all industry
        $industry_res = Industry::orderBy('name', 'ASC')->get();
        $industry = array();

        if (sizeof($industry_res) > 0) {
            foreach ($industry_res as $r) {
                $industry[$r->id] = $r->name;
            }
        }

        // lacs dropdown
        $lacs = array();
        $lacs[0] = 'lacs';
        for($i=1;$i<=50;$i++){
            $lacs[$i] = $i;
        }
        for($i=55;$i<100;$i+=5){
            $lacs[$i] = $i;
        }
        $lacs['100+'] = '100+';

        // Thousand dropdown
        $thousand = array(''=>'Thousand');
        for($i=0;$i<100;$i+=5){
            $thousand[$i] = $i;
        }

        //Work experience from dropdown
        $work_from = array('0'=>'Work Experience From');
        for($i=1;$i<=30;$i++){
            $work_from[$i] = $i;
        }

        //Work experience from dropdown
        $work_to = array('0'=>'Work Experience From');
        for($i=1;$i<=30;$i++){
            $work_to[$i] = $i;
        }

        $user_role_id = User::getLoggedinUserRole($user);
        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $manager_role_id = env('MANAGER');
        $superadmin_role_id = env('SUPERADMIN');

        $access_roles_id = array($admin_role_id,$director_role_id/*,$manager_role_id*/,$superadmin_role_id);
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
                $client[$r->id] = $r->name." - ".$r->coordinator_name." - ".$r->billing_city;
            }
        }
//print_r($client);exit;
        // get all users
        $user = \Auth::user();
        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);

        $user_obj = new User();
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);
        $user_id = $user->id;

        // For account manager
         
        $users = User::getAllUsers(NULL,'Yes');
        $select_all_users = User::getAllUsers('recruiter');
        //print_r($users);exit;
      //  $team_mates = $user_id;

        // job opening status
        /*$job_open_status = JobOpen::getJobOpenStatus();*/

        $job_open = JobOpen::find($id);
        $posting_title = $job_open->posting_title;
        $no_of_positions = $job_open->no_of_positions;
        $user_id = $job_open->hiring_manager_id;
        $lacs_from = $job_open->lacs_from;
        $thousand_from = $job_open->thousand_from;
        $lacs_to = $job_open->lacs_to;
        $thousand_to = $job_open->thousand_to;
        $work_exp_from = $job_open->work_exp_from;
        $work_exp_to = $job_open->work_exp_to;

        $job_visible_users = JobVisibleUsers::where('job_id',$id)->get();   
        $selected_users = array();
        if(isset($job_visible_users) && sizeof($job_visible_users)>0){
            foreach($job_visible_users as $row){
                $selected_users[] = $row->user_id;
            }
        }

        // job type
        $job_type = JobOpen::getJobTypes();

        // job priority
        $job_priorities = JobOpen::getJobPriorities();
        // get Client hierarchy names
        // $client_hierarchy_name = ClientHeirarchy::getAllClientHeirarchyName();

        $action = "clone";

        
        return view('adminlte::jobopen.create', compact('no_of_positions','posting_title','job_open','user_id','action', 'industry', 'client', 'users', 'job_open_status', 'job_type','job_priorities','selected_users','lacs','thousand','lacs_from','thousand_from','lacs_to','thousand_to','work_from','work_to','work_exp_from','work_exp_to','select_all_users'/*,'client_hierarchy_name'*/));

    }

    public function clonestore(Request $request){
        $dateClass = new Date();

        $user_id = \Auth::user()->id;
        $user_name = \Auth::user()->name;
        $user_email = \Auth::user()->email;
        $input = $request->all();

        $max_id = JobOpen::find(\DB::table('job_openings')->max('id'));
        if (isset($max_id->id) && $max_id->id != '')
            $max_id = $max_id->id;
        else
            $max_id = 0;

        $posting_title = $input['posting_title'];
        $hiring_manager_id = $input['hiring_manager_id'];
        //$target_date = $input['target_date'];
        // $formatted_target_date = Carbon::parse($target_date)->format('Y/m/d');
        //$job_opening_status = $input['job_opening_status'];
        $job_priority = $input['job_priority'];
        $industry_id = $input['industry_id'];
        $client_id = $input['client_id'];
        $no_of_positions = $input['no_of_positions'];
        $date_open = $input['date_opened'];
        //$formatted_date_open = Carbon::parse($date_open)->format('Y/m/d');
        $job_type = $input['job_type'];
        $job_description = $input['job_description'];
        //$work_experience_from = $input['work_experience_from'];
        //$work_experience_to = $input['work_experience_to'];
        //$salary_from = $input['salary_from'];
        //$salary_to = $input['salary_to'];
        $city = $input['city'];
        $state = $input['state'];
        $country = $input['country'];
        $job_show = 0;
        $users = $input['user_ids'];
        //print_r($users);exit;
        $desired_candidate = $input['desired_candidate'];
        $qualifications = $input['qualifications'];
        $lacs_from = $input['lacs_from'];
        $thousand_from = $input['thousand_from'];
        $lacs_to = $input['lacs_to'];
        $thousand_to = $input['thousand_to'];
        $work_exp_from = $input['work_experience_from'];
        $work_exp_to = $input['work_experience_to'];

        if (isset($work_experience_from) && $work_experience_from == '')
            $work_experience_from = 0;
        if (isset($work_experience_to) && $work_experience_to == '')
            $work_experience_to = 0;
      //  if (isset($salary_from) && $salary_from == '')
        //    $salary_from = 0;
        //if (isset($salary_to) && $salary_to == '')
        //    $salary_to = 0;
        if (isset($qualifications) && $qualifications == '')
            $qualifications = '';
        if (isset($desired_candidate) && $desired_candidate == '')
            $desired_candidate = '';
        if (isset($lacs_from) && $lacs_from == '')
            $lacs_from = 0;
        if (isset($thousand_from) && $thousand_from == '')
            $thousand_from = 0;
        if (isset($lacs_to) && $lacs_to == '')
            $lacs_to = 0;
        if (isset($thousand_to) && $thousand_to == '')
            $thousand_to = 0;
        if (isset($work_exp_from) && $work_exp_from == '')
            $work_exp_from = 0;
        if (isset($work_exp_to) && $work_exp_to == '')
            $work_exp_to = 0;

        // $level_id = $input['level_id'];

        $increment_id = $max_id + 1;
        $job_unique_id = "TT-JO-$increment_id";
        $job_open = new JobOpen();
        $job_open->job_id = $job_unique_id;
        $job_open->job_show = $job_show;
        $job_open->posting_title = $posting_title;
        $job_open->hiring_manager_id = $hiring_manager_id;
        //$job_open->target_date = 'NULL';//$dateClass->changeDMYtoYMD($target_date);//'2016-01-01';// $formatted_target_date;
        //$job_open->job_opening_status = $job_opening_status;
        $job_open->industry_id = $industry_id;
        $job_open->client_id = $client_id;
        $job_open->no_of_positions = $no_of_positions;
        $job_open->date_opened = $dateClass->changeDMYtoYMD($date_open); //'2016-01-01';//$formatted_date_open;
        $job_open->job_type = $job_type;
        $job_open->job_description = $job_description;
        //$job_open->work_experience_from = $work_experience_from;
        //$job_open->work_experience_to = $work_experience_to;
       // $job_open->salary_from = $salary_from;
       // $job_open->salary_to = $salary_to;
        $job_open->city = $city;
        $job_open->state = $state;
        $job_open->country = $country;
        $job_open->priority = $job_priority;
        $job_open->desired_candidate = $desired_candidate;
        $job_open->qualifications = $qualifications;
        $job_open->lacs_from = $lacs_from;
        $job_open->thousand_from = $thousand_from;
        $job_open->lacs_to = $lacs_to;
        $job_open->thousand_to = $thousand_to;
        $job_open->work_exp_from = $work_exp_from;
        $job_open->work_exp_to = $work_exp_to;
        // $job_open->level_id = $level_id;

//     print_r($job_open);exit;
        $validator = \Validator::make(Input::all(),$job_open::$rules);

        if($validator->fails()){
            return redirect('jobs/create')->withInput(Input::all())->withErrors($validator->errors());
        }

        $job_open->save();
        $job_id = $job_open->id;
        //$job_id = $job_response->id;

        if (isset($job_id) && $job_id > 0) {

            if(isset($users) && sizeof($users)>0){
                foreach ($users as $key=>$value){
                    $job_visible_users = new JobVisibleUsers();
                    $job_visible_users->job_id = $job_id;
                    $job_visible_users->user_id = $value;
                    $job_visible_users->save();
                }
            }

            // Get job_visible_user count and check to all users then update open_to_all field
            $users_id = User::getAllUsers('recruiter');
            $user_count = sizeof($users_id);

            $job_users = sizeof($users);
            if ($job_users == $user_count) {
                \DB::statement("UPDATE job_openings SET open_to_all = '1' where id=$job_id");
            }
            else {
                \DB::statement("UPDATE job_openings SET open_to_all = '0' where id=$job_id");
            }

            $job_summary = $request->file('job_summary');
            $others_doc = $request->file('others_doc');

            if (isset($job_summary) && $job_summary->isValid()) {
                $job_summary_name = $job_summary->getClientOriginalName();
                $filesize = filesize($job_summary);

                $dir_name = "uploads/jobs/" . $job_id . "/";
                $job_summary_key = "uploads/jobs/" . $job_id . "/" . $job_summary_name;

                if (!file_exists($dir_name)) {
                    mkdir("uploads/jobs/$job_id", 0777, true);
                }

                if (!$job_summary->move($dir_name, $job_summary_name)) {
                    return false;
                } else {
                    $job_open_doc = new JobOpenDoc();

                    $job_open_doc->job_id = $job_id;
                    $job_open_doc->category = 'Job Summary';
                    $job_open_doc->name = $job_summary_name;
                    $job_open_doc->file = $job_summary_key;
                    $job_open_doc->uploaded_by = $user_id;
                    $job_open_doc->size = $filesize;
                    $job_open_doc->created_at = time();
                    $job_open_doc->updated_at = time();
                    $job_open_doc->save();
                }
            }

            if (isset($others_doc) && $others_doc->isValid()) {
                $others_doc_name = $others_doc->getClientOriginalName();
                $others_filesize = filesize($others_doc);

                $dir_name = "uploads/jobs/" . $job_id . "/";
                $others_doc_key = "uploads/jobs/" . $job_id . "/" . $others_doc_name;

                if (!file_exists($dir_name)) {
                    mkdir("uploads/jobs/$job_id", 0777, true);
                }

                if (!$others_doc->move($dir_name, $others_doc_name)) {
                    return false;
                } else {
                    $job_open_doc = new JobOpenDoc;

                    $job_open_doc->job_id = $job_id;
                    $job_open_doc->category = 'Others';
                    $job_open_doc->name = $others_doc_name;
                    $job_open_doc->file = $others_doc_key;
                    $job_open_doc->uploaded_by = $user_id;
                    $job_open_doc->size = $others_filesize;
                    $job_open_doc->created_at = time();
                    $job_open_doc->updated_at = time();
                    $job_open_doc->save();
                }
            }

            // TODO:: Notifications : On creating job openings : send notification to selected users that new job openings is added (except user who created jobopening) . default send notificaations to admin user .
            $user_arr = array();
            if(isset($users) && sizeof($users)>0) {
                $user_emails = array();
                foreach ($users as $key=>$value) {
                    if($user_id!=$value) {
                        $module_id = $job_id;
                        $module = 'Job Openings';
                        $message = $user_name . " added new job";
                        $link = route('jobopen.show',$job_id);
                        $user_arr =trim($value);

                        event(new NotificationEvent($module_id, $module, $message, $link, $user_arr));

                        $email = User::getUserEmailById($value);
                        $user_emails[] = $email;
                    }
                }

                // Email Notification : data store in datebase
                $superadminuserid = getenv('SUPERADMINUSERID');
                $superadminsecondemail=User::getUserEmailById($superadminuserid);
                $loggedin_email = \Auth::user()->email;

                $cc_users_array=array($loggedin_email,$superadminsecondemail);

                $module = "Job Open";
                $sender_name = $user_id;
                $to = implode(",",$user_emails);
                $cc = implode(",",$cc_users_array);

                $client_name = ClientBasicinfo::getCompanyOfClientByID($client_id);
                $client_city = ClientBasicinfo::getBillingCityOfClientByID($client_id);
                
                $subject = "Job Opening - ". $posting_title . "@" .$client_name . "-" . $client_city;
                $message = "<tr><th>" . $posting_title . "/" . $job_unique_id . "</th></tr>";
                $module_id = $job_id;

                event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
            }
        }

        return redirect()->route('jobopen.index')->with('success', 'Job Opening Created Successfully');
    }

    public function destroy($id)
    {
        $job_associate_delete = DB::table('job_associate_candidates')->where('job_id', '=', $id)->delete();
        $job_open_doc_delete = DB::table('job_openings_doc')->where('job_id', '=', $id)->delete();
        $job_visible_user_delete = DB::table('job_visible_users')->where('job_id', '=', $id)->delete();
        $job_joining_date_delete = DB::table('job_candidate_joining_date')->where('job_id', '=', $id)->delete();
        $job_open_delete = JobOpen::where('id',$id)->delete();

        return redirect()->route('jobopen.index')->with('success', 'Job Opening Deleted Successfully');
    }

    public function upload(Request $request)
    {

        $upload_type = $request->upload_type;
        $file = $request->file('file');
        $job_id = $request->id;
        $user_id = \Auth::user()->id;

        if (isset($file) && $file->isValid()) {
            $doc_name = $file->getClientOriginalName();
            $doc_filesize = filesize($file);

            $dir_name = "uploads/jobs/" . $job_id . "/";
            $others_doc_key = "uploads/jobs/" . $job_id . "/" . $doc_name;

            if (!file_exists($dir_name)) {
                mkdir("uploads/jobs/$job_id", 0777, true);
            }

            if (!$file->move($dir_name, $doc_name)) {
                return false;
            } else {
                $jobopen_doc = new JobOpenDoc();

                $jobopen_doc->job_id = $job_id;
                $jobopen_doc->category = $upload_type;
                $jobopen_doc->name = $doc_name;
                $jobopen_doc->file = $others_doc_key;
                $jobopen_doc->uploaded_by = $user_id;
                $jobopen_doc->size = $doc_filesize;
                $jobopen_doc->created_at = time();
                $jobopen_doc->updated_at = time();
                $jobopen_doc->save();
            }

        }

        return redirect()->route('jobopen.show', [$job_id])->with('success', 'Attachment uploaded successfully');
    }

    public function attachmentsDestroy($docid)
    {

        $file_name = JobOpenDoc::where('id', $docid)->first();
        $delete_file_name = $file_name->file;

        if (isset($delete_file_name) && $delete_file_name != '') {
            unlink("$delete_file_name");
        }

        $jobopenDocDelete = JobOpenDoc::where('id', $docid)->delete();

        $id = $_POST['id'];
        return redirect()->route('jobopen.show', [$id])->with('success', 'Attachment deleted Successfully');
    }

    public function associateCandidate($id)
    {

        // get job name from id
        $jobopen_response = JobOpen::where('id', $id)->first();
        $posting_title = $jobopen_response->posting_title;

        // get candidate ids already associated with job
        $candidate_response = JobAssociateCandidates::where('job_id', '=', $id)->get();
        $candidates = array();
        foreach ($candidate_response as $key => $value) {
            $candidates[] = $value->candidate_id;
        }

        $candidateDetails = CandidateBasicInfo::leftjoin('candidate_otherinfo', 'candidate_otherinfo.candidate_id', '=', 'candidate_basicinfo.id')
            ->leftjoin('users', 'users.id', '=', 'candidate_otherinfo.owner_id')
            ->select('candidate_basicinfo.id as id', 'candidate_basicinfo.full_name as fname', 'candidate_basicinfo.lname as lname',
                'candidate_basicinfo.email as email', 'users.name as owner')
            ->whereNotIn('candidate_basicinfo.id', $candidates)
            ->orderBy('candidate_basicinfo.id','desc')
            ->get();

        return view('adminlte::jobopen.associate_candidate', array('candidates' => $candidateDetails, 'job_id' => $id, 'posting_title' => $posting_title, 'message' => ''));
    }

    public function postAssociateCandidates()
    {
        $user_id = \Auth::user()->id;
        $job_id = $_POST['jobid'];
        $candidate_ids = $_POST['candidate_ids'];
        $status_id = env('associate_candidate_status', 1);

        $candidate_ids_array = explode(",", $candidate_ids);

        foreach ($candidate_ids_array as $key => $value) {
            $job_associate_candidate = new JobAssociateCandidates();
            $job_associate_candidate->job_id = $job_id;
            $job_associate_candidate->candidate_id = $value;
            $job_associate_candidate->status_id = $status_id;
            $job_associate_candidate->created_at = time();
            $job_associate_candidate->updated_at = time();
            $job_associate_candidate->date = date("Y-m-d h:i:s");
            $job_associate_candidate->associate_by = $user_id;
            $job_associate_candidate->shortlisted = 0;
            $job_associate_candidate->save();
        }

        $jobDetail = JobOpen::find($job_id);

        $hiring_manager_id = $jobDetail->hiring_manager_id;
        $job_show = $jobDetail->job_show;

        /*$authUserTeamId = TeamMates::where('user_id',$hiring_manager_id)->first();

        if($job_show == 0){
            $user_details = TeamMates::select('user_id')
                ->where('team_id',$authUserTeamId->team_id)
                ->where('user_id','!=', $user_id)
                ->get();
        } else {
            $user_details = User::select('id as user_id')
                ->where('id','!=',$user_id)
                ->get();
        }

        $user_arr = array();

        if(isset($user_details) && sizeof($user_details) > 0){
            foreach ($user_details as $user_detail) {
                $user_arr[] = $user_detail->user_id;
            }
        }

        $module_id = $job_id;
        $module = 'Associate Candidates';
        $message = "Associating Candidate";
        $link = route('jobopen.show',$job_id);

        event(new NotificationEvent($module_id, $module, $message, $link, $user_arr));*/

        return redirect()->route('jobopen.associate_candidate_get', [$job_id])->with('success', 'Candidate associated successfully');
    }

    public function associateCandidateCount()
    {
        $job_id = $_POST['jobid'];

        $count = JobAssociateCandidates::where('job_id', '=', $job_id)->count();

        $response['returnvalue'] = "valid";
        $response['count'] = $count;

        echo json_encode($response);
        exit;

    }

    public function associatedCandidates($id)
    {
        $user = \Auth::user();
        $user_id = $user->id;
        $user_role_id = User::getLoggedinUserRole($user);

        $director_role_id = env('DIRECTOR');
        $manager_role_id = env('MANAGER');
        $superadmin_role_id = env('SUPERADMIN');

        // get job name from id
        $jobopen_response = JobOpen::where('id', $id)->first();
        $hiring_manager_id = $jobopen_response->hiring_manager_id;

        $access = false;
        $access_roles_id = array($director_role_id/*,$manager_role_id*/,$superadmin_role_id);
        if(in_array($user_role_id,$access_roles_id)){
            $access= true;
        }
        elseif ($hiring_manager_id == $user->id){
            $access = true;
        }

        $client_id = $jobopen_response->client_id;
        $posting_title = $jobopen_response->posting_title;

        $candidateDetails = JobAssociateCandidates::getAssociatedCandidatesByJobId($id);

        // get candidate status
        $candidateresult = CandidateStatus::orderBy('name','asc')->select('id','name')->get()->toArray();
        foreach ($candidateresult as $key=>$value){
            $candidateStatus[$value['id']] = $value['name'];
        }

        $shortlist_type = JobOpen::getShortlistType();

        $type = Interview::getTypeArray();
        $status = Interview::getInterviewStatus();
        $users = User::getAllUsers();
        return view('adminlte::jobopen.associated_candidate', array('job_id' => $id, 'posting_title' => $posting_title,
            'message' => '','candidates'=>$candidateDetails ,'candidatestatus'=>$candidateStatus,'type'=>$type,
            'status' => $status,'users' => $users,'client_id'=>$client_id, 'shortlist_type'=>$shortlist_type,'access'=>$access,'user_id'=>$user_id));
    }

    public function deAssociateCandidates(){

        $user_id = \Auth::user()->id;
        $job_id = $_POST['jobid'];
        $candidate_id = $_POST['candidate_id'];

        JobAssociateCandidates::where('candidate_id',$candidate_id)->where('job_id',$job_id)->forceDelete();

        $jobDetail = JobOpen::find($job_id);

        $hiring_manager_id = $jobDetail->hiring_manager_id;
        $job_show = $jobDetail->job_show;

        // TODO :: DeAssociating Candidate : send notification to team/all (except user who deassociate Candidate). default send notificaations to admin user .
        /*$authUserTeamId = TeamMates::where('user_id',$hiring_manager_id)->first();

        if($job_show == 0){
            $user_details = TeamMates::select('user_id')
                ->where('team_id',$authUserTeamId->team_id)
                ->where('user_id','!=', $user_id)
                ->get();
        } else {
            $user_details = User::select('id as user_id')
                ->where('id','!=',$user_id)
                ->get();
        }

        $user_arr = array();

        if(isset($user_details) && sizeof($user_details) > 0){
            foreach ($user_details as $user_detail) {
                $user_arr[] = $user_detail->user_id;
            }
        }

        $module_id = $job_id;
        $module = 'DeAssociate Candidates';
        $message = "DeAssociating Candidate";
        $link = route('jobopen.show',$job_id);

        event(new NotificationEvent($module_id, $module, $message, $link, $user_arr));*/

        return redirect()->route('jobopen.associated_candidates_get', [$job_id])->with('success', 'Candidate deassociated successfully');
    }

    public function updateCandidateStatus(){

        $user_id = \Auth::user()->id;
        $job_id = $_POST['jobid'];
        $candidate_id = $_POST['candidate_id'];
        $status_id = $_POST['status_id'];

        DB::statement("UPDATE job_associate_candidates SET status_id = $status_id where candidate_id in ($candidate_id) and job_id = $job_id");
        DB::statement("UPDATE  candidate_otherinfo SET status_id =$status_id where candidate_id = $candidate_id");

        $jobDetail = JobOpen::find($job_id);

        $hiring_manager_id = $jobDetail->hiring_manager_id;
        $job_show = $jobDetail->job_show;

        /*$authUserTeamId = TeamMates::where('user_id',$hiring_manager_id)->first();

        if($job_show == 0){
            $user_details = TeamMates::select('user_id')
                ->where('team_id',$authUserTeamId->team_id)
                ->where('user_id','!=', $user_id)
                ->get();
        } else {
            $user_details = User::select('id as user_id')
                ->where('id','!=',$user_id)
                ->get();
        }

        $user_arr = array();

        if(isset($user_details) && sizeof($user_details) > 0){
            foreach ($user_details as $user_detail) {
                $user_arr[] = $user_detail->user_id;
            }
        }

        $module_id = $job_id;
        $module = 'Change Job Status';
        $message = "Status update";
        $link = route('jobopen.show',$job_id);

        event(new NotificationEvent($module_id, $module, $message, $link, $user_arr));*/

        return redirect()->route('jobopen.associated_candidates_get', [$job_id])->with('success', 'Candidate status update successfully');
    }

    public function scheduleInterview(Request $request){

        $user_id = \Auth::user()->id;
        $dateClass = new Date();

        $data = array();
        //$data['interview_name'] = $request->get('interview_name');
        $data['candidate_id'] = $request->get('candidate_id');
        $data['interviewer_id'] = $request->get('interviewer_id');
        $data['client'] = $request->get('client_id');
        $data['interview_date'] = $dateClass->changeDMYHMStoYMDHMS($request->get('interview_date'));
        $data['location'] = $request->get('location');
        $data['comments'] = '';
        $data['posting_title'] =  $request->get('job_id');
        $data['type'] = $request->get('type');
        $data['about'] = '';
        $data['status'] = $request->get('status');
        $data['interview_owner_id'] = $user_id;
        $data['location'] = $request->get('location');
        $data['skype_id'] = $request->get('skype_id');
        $data['round'] = '1';
        $data['candidate_location'] = $request->get('candidate_location');
        $data['interview_location'] = $request->get('interview_location');

        $job_id = $request->get('job_id');

        $interview = Interview::createInterview($data);

        $validator = \Validator::make(Input::all(),$interview::$rules);

        if($validator->fails()){
            return redirect('interview/create')->withInput(Input::all())->withErrors($validator->errors());
        }

        $interviewStored = $interview->save();

        $interview_id = $interview->id;
        $candidate_id = $request->get('candidate_id');
        $posting_title = $request->get('job_id');

        $candidate_mail = Interview::getCandidateEmail($candidate_id,$posting_title,$interview_id);

        $scheduled_mail = Interview::getScheduleEmail($candidate_id,$posting_title,$interview_id);

        return redirect('jobs/'.$job_id.'/associated_candidates')->with('success','Interview scheduled successfully');

    }

    public function addJoiningDate(){

        $jobid = $_POST['jobid'];
        $candidate_id = $_POST['candidate_id'];
        $dateClass = new Date();

        // First check joining date already added den update date else add joining date
        $id = JobCandidateJoiningdate::checkJoiningDateAdded($jobid,$candidate_id);
        if($id>0){
            $jobCandidateJoiningDate = JobCandidateJoiningdate::find($id);
            $jobCandidateJoiningDate->joining_date = $dateClass->changeDMYtoYMD($_POST['joining_date']);;
            $jobCandidateJoiningDate->save();
        }
        else{
            $jobCandidateJoiningDate = new JobCandidateJoiningdate;

            $jobCandidateJoiningDate->job_id = $jobid;
            $jobCandidateJoiningDate->joining_date = $dateClass->changeDMYtoYMD($_POST['joining_date']);
            $jobCandidateJoiningDate->candidate_id = $candidate_id;
            $jobCandidateJoiningDate->save();
        }

        return redirect('jobs/'.$_POST['jobid'].'/associated_candidates')->with('success','Joining date added successfully');
    }

    public function shortlisted(Request $request,$job_id){
        $input = $request->all();

        $shortlist = $input['shortlist_type'];
        $candidate_id = $input['job_candidate_id'];

       
        //print_r($candidate_short);exit;

        DB::statement("UPDATE job_associate_candidates SET shortlisted = $shortlist where candidate_id = $candidate_id and job_id = $job_id");

         return redirect()->route('jobopen.associated_candidates_get', [$job_id])->with('success','Candidate shortlisted successfully');

    }

    public function undoshortlisted(Request $request,$job_id){
        $input = $request->all();

        $undoshortlist = $input['undoshortlisted'];
        $candidate_id = $input['job_undo_candidate_id'];
        //print_r($candidate_id);exit;

        DB::statement("UPDATE job_associate_candidates SET shortlisted = $undoshortlist where candidate_id = $candidate_id and job_id = $job_id");

         return redirect()->route('jobopen.associated_candidates_get', [$job_id])->with('success','Undo shortlisted Candidate successfully');
           
    }

    public function getOpenJobs(){

        // logged in user with role 'Administrator,Director,Manager can see all the open jobs
        // Rest other users can only see the jobs assigned to them

        $user = \Auth::user();
        $user_role_id = User::getLoggedinUserRole($user);

        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $manager_role_id = env('MANAGER');
        $superadmin_role_id = env('SUPERADMIN');

        $access_roles_id = array($admin_role_id,$director_role_id/*,$manager_role_id*/,$superadmin_role_id);
        if(in_array($user_role_id,$access_roles_id)){

        }

    }

    public function moreOptions(Request $request){

        $posting_status = $request->get('posting_status');
        $mass_mail = $request->get('mass_mail');
        $job_search = $request->get('job_search');
        $job_id = $request->get('job_id');

        $job_open = JobOpen::find($job_id);

        $posting = '';
        if (isset($posting_status) && sizeof($posting_status)>0){
            foreach ($posting_status as $k=>$v) {
                if($posting=='')
                    $posting .= $v;
                else
                    $posting .= ','.$v;
            }
        }

        $mm = '';
        if (isset($mass_mail) && sizeof($mass_mail)>0){
            foreach ($mass_mail as $k=>$v) {
                if($mm=='')
                    $mm .= $v;
                else
                    $mm .= ','.$v;
            }
        }

        $js = '';
        if (isset($job_search) && sizeof($job_search)>0){
            foreach ($job_search as $k=>$v) {
                if($js=='')
                    $js .= $v;
                else
                    $js .= ','.$v;
            }
        }

         $job_open->posting = $posting;
         $job_open->mass_mail = $mm;
         $job_open->job_search = $js;

        $response = $job_open->save();

        if($response){
            return redirect()->route('jobopen.show', [$job_id])->with('success', 'Job Opening additional information added successfully');
        }
        else{
            return redirect()->route('jobopen.show', [$job_id])->with('success', 'Error while updating data');
        }

    }

    public function status(Request $request){

        $priority = $request->get('job_priority');
        $job_id = $request->get('job_id');
        $display_name = $request->get('display_name');
        // print_r($display_name);exit;

        $job_open = JobOpen::find($job_id);

        $job = '';
        if (isset($priority) && sizeof($priority)>0){

                 $job = $priority;
            
        }
        $job_open->priority = $job;
         
        $job_open->save();

        if ($display_name == 'Job Close') {
            return redirect()->route('jobopen.close')->with('success', 'Job Priority Updated successfully');
        }
        else if ($display_name == 'Job Open') {
            return redirect()->route('jobopen.index')->with('success', 'Job Priority Updated successfully');
        }
       
    }

    public function close(Request $request){

        $dateClass = new Date();

        $client_id = $request->client_id;
        $job_open_id = $request->job_id;
        $posting_title_id = $request->posting_title;
       // $job_opening_status_id = $request->job_opening_status;
        $city_id = $request->city;

        // logged in user with role 'Administrator,Director,Manager can see all the open jobs
        // Rest other users can only see the jobs assigned to them

        $user = \Auth::user();
        $user_id = $user->id;
        $user_role_id = User::getLoggedinUserRole($user);

        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);
        $user_obj = new User();
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);
        $isStrategy = $user_obj::isStrategyCoordination($role_id);
        $isClient = $user_obj::isClient($role_id);

        // for get client id by email
        $user_email = $user->email;
        $client_id = ClientBasicinfo::getClientIdByEmail($user_email);

        $admin_role_id = getenv('ADMIN');
        $director_role_id = getenv('DIRECTOR');
        $manager_role_id = getenv('MANAGER');
        $superadmin_role_id = getenv('SUPERADMIN');

        // Year Data
        $starting_year = '2017';
        $ending_year = date('Y',strtotime('+1 year'));
        $year_array = array();
        for ($y=$starting_year; $y < $ending_year ; $y++) {
            $next = $y+1;
            $year_array[$y.'-4, '.$next.'-3'] = 'April-' .$y.' to March-'.$next;
        }

        if (isset($_POST['year']) && $_POST['year'] != '') {
            $year = $_POST['year'];
        }
        else{
            $y = date('Y');
            $m = date('m');
            if ($m > 3) {
                $n = $y + 1;
                $year = $y.'-4, '.$n.'-3';
            }
            else{
                $n = $y-1;
                $year = $n.'-4, '.$y.'-3';
            }
        }

        $year_data = explode(", ", $year); // [result : Array ( [0] => 2019-4 [1] => 2020-3 )] by default
        $year1 = $year_data[0]; // [result : 2019-4]
        $year2 = $year_data[1]; // [result : 2020-3]
        $current_year = date('Y-m-d h:i:s',strtotime("first day of $year1"));
        $next_year = date('Y-m-d h:i:s',strtotime("last day of $year2"));

        $access_roles_id = array($admin_role_id,$director_role_id/*,$manager_role_id*/,$superadmin_role_id,$isStrategy);
        if(in_array($user_role_id,$access_roles_id)){
            $job_response = JobOpen::getClosedJobs(1,$user_id,0,0,0,NULL,'DESC',$current_year,$next_year);
            $job_priority_data = JobOpen::getPriorityWiseJobs(1,$user_id,NULL,$current_year,$next_year);
        }
        else if ($isClient) {
            $job_response = JobOpen::getClosedJobsByClient($client_id);
            $job_priority_data = JobOpen::getPriorityWiseJobs(0,$user_id,NULL,$current_year,$next_year);
        }
        else{
            $job_response = JobOpen::getClosedJobs(0,$user_id,0,0,0,NULL,'DESC',$current_year,$next_year);
            $job_priority_data = JobOpen::getPriorityWiseJobs(0,$user_id,NULL,$current_year,$next_year);
        }

        $count = sizeof($job_response);
        $priority_4 = 0;
        $priority_9 = 0;
        $priority_10 = 0;

        foreach ($job_priority_data as $job_priority) {
           if($job_priority['priority'] == 4) {
                $priority_4++;
           }
           else if($job_priority['priority'] == 9) {
                $priority_9++;
           }
           else if($job_priority['priority'] == 10) {
                $priority_10++;
           }
        }

        $viewVariable = array();
        $viewVariable['jobList'] = $job_response;
        $viewVariable['isSuperAdmin'] = $isSuperAdmin;
        $viewVariable['job_priority'] = JobOpen::getJobPriorities();
        $viewVariable['count'] = $count;
        $viewVariable['priority_4'] = $priority_4;
        $viewVariable['priority_9'] = $priority_9;
        $viewVariable['priority_10'] = $priority_10;
        $viewVariable['year_array'] = $year_array;
        $viewVariable['year'] = $year;


        return view('adminlte::jobopen.close',$viewVariable);   
    }

    public function getAllCloseJobDetails(){

        $limit = $_GET['length'];
        $offset = $_GET['start'];
        $draw = $_GET['draw'];
        $search = $_GET['search']['value'];
        $order = $_GET['order'][0]['column'];
        $type = $_GET['order'][0]['dir'];
        $year = $_GET['year'];

        $year_data = explode(", ", $year); // [result : Array ( [0] => 2019-4 [1] => 2020-3 )] by default
        $year1 = $year_data[0]; // [result : 2019-4]
        $year2 = $year_data[1]; // [result : 2020-3]
        $current_year = date('Y-m-d h:i:s',strtotime("first day of $year1"));
        $next_year = date('Y-m-d h:i:s',strtotime("last day of $year2"));
        
        $order_column_name = self::getJobOrderColumnName($order);

        $user = \Auth::user();
        $user_id = $user->id;
        $user_role_id = User::getLoggedinUserRole($user);

        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);
        $user_obj = new User();
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);
        $isStrategy = $user_obj::isStrategyCoordination($role_id);
        $isClient = $user_obj::isClient($role_id);

        // for get client id by email
        $user_email = $user->email;
        $client_id = ClientBasicinfo::getClientIdByEmail($user_email);

        $admin_role_id = getenv('ADMIN');
        $director_role_id = getenv('DIRECTOR');
        $manager_role_id = getenv('MANAGER');
        $superadmin_role_id = getenv('SUPERADMIN');

        $access_roles_id = array($admin_role_id,$director_role_id/*,$manager_role_id*/,$superadmin_role_id,$isStrategy);
        if(in_array($user_role_id,$access_roles_id)){
            $job_response = JobOpen::getClosedJobs(1,$user_id,$limit,$offset,$search,$order_column_name,$type,$current_year,$next_year);
            $count = JobOpen::getAllClosedJobsCount(1,$user_id,$search,$current_year,$next_year);
            $job_priority_data = JobOpen::getPriorityWiseJobs(1,$user_id,NULL,$current_year,$next_year);
        }
        else if ($isClient) {
            $job_response = JobOpen::getClosedJobsByClient($client_id,$limit,$offset,$search,$order_column_name,$type);
            $count = sizeof($job_response);
            $job_priority_data = JobOpen::getPriorityWiseJobs(0,$user_id,NULL,$current_year,$next_year);
        }
        else{
            $job_response = JobOpen::getClosedJobs(0,$user_id,$limit,$offset,$search,$order_column_name,$type,$current_year,$next_year);
            $count = JobOpen::getAllClosedJobsCount(0,$user_id,$search,$current_year,$next_year);
            $job_priority_data = JobOpen::getPriorityWiseJobs(0,$user_id,NULL,$current_year,$next_year);
        }

        $priority_4 = 0;$priority_9 = 0;$priority_10 = 0;
        foreach ($job_priority_data as $job_priority) {
           if($job_priority['priority'] == 4) {
                $priority_4++;
           }
           else if($job_priority['priority'] == 9) {
                $priority_9++;
           }
           else if($job_priority['priority'] == 10) {
                $priority_10++;
           }
        }

        $job_priority = JobOpen::getJobPriorities();
        $jobs = array();
        $i = 0;$j = 0;
        foreach ($job_response as $key => $value) {
            $action = '';
            $checkbox = '';

            $action .= '<a title="Show"  class="fa fa-circle" href="'.route('jobopen.show',$value['id']).'" style="margin:3px;"></a>';
            if(isset($value['access']) && $value['access']==1){
                $action .= '<a title="Edit" class="fa fa-edit" href="'.route('jobopen.edit',$value['id']).'" style="margin:3px;"></a>';
        
                $status_view = \View::make('adminlte::partials.jobstatus',['data' => $value, 'name' => 'jobopen', 'display_name'=>'Job Close', 'job_priority' => $job_priority]);
                $status = $status_view->render();
                $action .= $status;
            }
            if ($isSuperAdmin) {
                $delete_view = \View::make('adminlte::partials.jobdelete',['data' => $value, 'name' => 'jobopen', 'display_name'=>'Job']);
                $delete = $delete_view->render();
                $action .= $delete;
            }
            if(isset($value['access']) && $value['access']==1){
                $action .= '<a title="Clone Job"  class="fa fa-clone" href="'.route('jobopen.clone',$value['id']).'"></a>';
                /*$checkbox .= '<input type=checkbox name=job_ids value='.$value['id'].' class=multiple_jobs id='.$value['id'].'/>';*/
            }

            $managed_by = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['am_name'].'</a>';
            $company_name = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['display_name'].'</a>';
            $posting_title = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['posting_title'].'</a>';
            if ($isClient) {
                $associated_count = '<a title="Show Candidates Details" href="'.route('jobopen.candidates_details_get',$value['id']).'">'.$value['associate_candidate_cnt'].'</a>';
            }
            else{
                $associated_count = '<a title="Show Associated Candidates" href="'.route('jobopen.associated_candidates_get',$value['id']).'">'.$value['associate_candidate_cnt'].'</a>';
            }
            $location = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['location'].'</a>';
            $data = array(++$j,$action,$job_priority[$value['priority']],$managed_by,$company_name,$posting_title,$associated_count,$location,$value['min_ctc'],$value['max_ctc'],$value['coordinator_name'],$value['created_date'],$value['no_of_positions'],$value['qual'],$value['industry'],$value['desired_candidate'],$value['priority']);
            $jobs[$i] = $data;
            $i++;
        }

        $json_data = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            'priority_onhold' => intval($priority_4),
            'priority_closed_us' => intval($priority_9),
            'priority_closed_client' => intval($priority_10),
            'year' => $year,
            'job_priority' => $job_priority,
            "data" => $jobs
        );

        echo json_encode($json_data);exit;
    }

    public function importExport(){
        return view('adminlte::jobopen.import');
    }

    public function importExcel(Request $request)
    {
        $dateClass = new Date();

        if ($request->hasFile('import_file')) {
            $path = $request->file('import_file')->getRealPath();

            $data = Excel::load($path, function ($reader) {
            })->get();

            $messages = array();

            if (!empty($data) && $data->count()) {

                foreach ($data->toArray() as $key => $value) {

                    if (!empty($value)) {
                        //foreach ($value as $v) {

                            $max_id = JobOpen::find(\DB::table('job_openings')->max('id'));
                            if (isset($max_id->id) && $max_id->id != '')
                                $max_id = $max_id->id;
                            else
                                $max_id = 0;

                            $job_show = 0;
                            $title = $value['title'];
                            $hiring_manager_id = $value['hiring_manager_id'];
                            $job_priority = $value['job_priority'];
                            $industry = $value['industry'];
                            $client_id = $value['client_id'];
                            $no_of_positions = $value['positions'];
                            $status = $value['status'];
                            $date_opened = $value['date_opened'];
                            $salary_min = $value['salary_min'];
                            $salary_max = $value['salary_max'];
                            $country = $value['country'];
                            $visible_user_id = $value['visible_user_id'];
                            $desired_candidate = $value['desired_candidate'];
                            $qualifications = $value['qualifications'];
                            $posting_status = $value['posting_status'];
                            $mass_mail = $value['mass_mail'];

                            $work_experience_from = 0;
                            $work_experience_to = 0;
                            if (isset($salary_min) && $salary_min == '')
                                $salary_min = 0;
                            if (isset($salary_max) && $salary_max == '')
                                $salary_max = 0;
                            if (isset($qualifications) && $qualifications == '')
                                $qualifications = '';
                            if (isset($desired_candidate) && $desired_candidate == '')
                                $desired_candidate = '';

                            $increment_id = $max_id + 1;
                            $job_unique_id = "TT-JO-$increment_id";
                            $job_open = new JobOpen();
                            $job_open->job_id = $job_unique_id;
                            $job_open->job_show = $job_show;
                            $job_open->posting_title = $title;
                            $job_open->hiring_manager_id = $hiring_manager_id;
                            $job_open->job_opening_status = $status;
                            $job_open->industry_id = $industry;
                            $job_open->client_id = $client_id;
                            $job_open->no_of_positions = $no_of_positions;
                            $date_opened = (array)$date_opened;
                            $date_new = $date_opened['date'];
                            $job_open->date_opened = $date_new; //'2016-01-01';//$formatted_date_open;
                            //$job_open->job_type = $job_type;
                            $job_open->work_experience_from = $work_experience_from;
                            $job_open->work_experience_to = $work_experience_to;
                            $job_open->salary_from = $salary_min;
                            $job_open->salary_to = $salary_max;
                            $job_open->country = $country;
                            $job_open->priority = $job_priority;
                            $job_open->desired_candidate = $desired_candidate;
                            $job_open->qualifications = $qualifications;


                            $validator = \Validator::make(Input::all(),$job_open::$rules);
                        print_r($validator->errors());exit;
                            if($validator->fails()){
                                return redirect('jobs/create')->withInput(Input::all())->withErrors($validator->errors());
                            }

                            $job_open->save();
                            $job_id = $job_open->id;

                            if (isset($job_id) && $job_id > 0) {

                                $job_visible_users = new JobVisibleUsers();
                                $job_visible_users->job_id = $job_id;
                                $job_visible_users->user_id = $visible_user_id;
                                $job_visible_users->save();

                                $posting = '';
                                if (isset($posting_status) && sizeof($posting_status)>0){
                                    foreach ($posting_status as $k=>$v) {
                                        if($posting=='')
                                            $posting .= $v;
                                        else
                                            $posting .= ','.$v;
                                    }
                                }

                                $mm = '';
                                if (isset($mass_mail) && sizeof($mass_mail)>0){
                                    foreach ($mass_mail as $k=>$v) {
                                        if($mm=='')
                                            $mm .= $v;
                                        else
                                            $mm .= ','.$v;
                                    }
                                }

                                $job_open->posting = $posting;
                                $job_open->mass_mail = $mm;
                                $job_open->job_search = '';

                                $response = $job_open->save();

                            }
                        //}
                    }
                }
            }

        }
        echo "adfdsf";exit;
    }

    public function getAssociatedcandidates(){

        $job_id = $_GET['job_id'];

        $associated_candidates = JobAssociateCandidates::getAssociatedCandidatesByJobId($job_id);

        $response = array();
        $response['returnvalue'] = 'invalid';
        $response['data'] = array();

        $i = 1;
        if(isset($associated_candidates) && sizeof($associated_candidates)>0){
            $response['returnvalue'] = 'valid';
            $response['data'][0]['id'] = '0';
            $response['data'][0]['value'] = 'Select';
            foreach ($associated_candidates as $k=>$v){
                $response['data'][$i]['id'] = $v->id;
                $response['data'][$i]['value'] = $v->fname;
                $i++;
            }
        }

        echo json_encode($response);exit;
    }

    public function associatedCVS(){

        $user = \Auth::user();
        $user_id = $user->id;

        $month = date("m");
        $year = date("Y");

        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);

        $user_obj = new User();


        $user_id = $user->id;
        $user_role_id = User::getLoggedinUserRole($user);

        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $manager_role_id = env('MANAGER');
        $superadmin_role_id = env('SUPERADMIN');
        $isStrategy = $user_obj::isStrategyCoordination($role_id);

        $access_roles_id = array($admin_role_id,$director_role_id/*,$manager_role_id*/,$superadmin_role_id,$isStrategy);
        if(in_array($user_role_id,$access_roles_id)){
            $response = JobAssociateCandidates::getAssociatedCvsByUseridMonthWise(0,$month,$year);
        }
        else{
            $response = JobAssociateCandidates::getAssociatedCvsByUseridMonthWise($user_id,$month,$year);
        }

        $count = sizeof($response);
        return view ('adminlte::jobopen.associatedcvs',compact('response','count'));
    }

    public function checkJobId(){

        if (isset($_POST['job_ids']) && $_POST['job_ids'] != '') {
            $job_ids = $_POST['job_ids'];
        }

        if (isset($job_ids) && sizeof($job_ids) > 0) {
            $job_priority = JobOpen::getJobPriorities();
        
            $view = \View::make('adminlte::partials.jobpriorityindex',['job_priority' => $job_priority]);
            $html = $view->render();

            $msg['success'] = 'success';
            $msg['mail'] = $html;
        }
        else{
            $msg['err'] = '<b>Please select job</b>';
            $msg['msg'] = "fail";
        }

        return $msg;
    }

    public  function MultipleJobPriority(){

        $job_ids = $_POST['job_ids'];
        $job_priority = $_POST['job_priority'];
        $updated_at = date('Y-m-d H:i:s');
        
        $job_ids_array = explode(",", $job_ids);
        foreach ($job_ids_array as $key => $value) {
            DB::statement("UPDATE job_openings SET priority = '$job_priority', updated_at='$updated_at' where id=$value");
        }

        return redirect()->route('jobopen.index')->with('success', 'Job Priority updated successfully');
    }

    // For check wherther associated candidate selected for mail or not
    public function CheckIds(){

        if (isset($_POST['can_ids']) && $_POST['can_ids'] != '') {
            $can_ids = $_POST['can_ids'];
        }

        if (isset($can_ids) && sizeof($can_ids) > 0) {

            $html = '';
            $html .= '<h3>Are you sure want send mail ?</h3>';

            $msg['success'] = 'success';
            $msg['mail'] = $html;
        }
        else{
            $msg['err'] = '<b>Please select Candidate</b>';
            $msg['msg'] = "fail";
        }

        return $msg;
    }
    // Users get for send mail
    public function UsersforSendMail(){

        if (isset($_POST['job_id']) && $_POST['job_id'] != '') {
            $job_id = $_POST['job_id'];
        }

        $users = JobVisibleUsers::getAllUsersByJobId($job_id);
        //print_r($users);exit;

        $view = \View::make('adminlte::partials.jobvisibleuserforassociatemail',['users' => $users]);
        $html = $view->render();

        $msg['success'] = 'success';
        $msg['mail'] = $html;

        return $msg;
    }

    // For send mail of associated candidate
    public function AssociatedCandidateMail(){

        $can_ids = $_POST['can_ids'];
        $posting_title = $_POST['posting_title'];
        $job_id = $_POST['job_id'];
        $user_ids = $_POST['user_ids'];
        //print_r($user_ids);exit;

        $candidate_id = explode(",", $can_ids);
        $i = 0;
        foreach ($candidate_id as $key => $value) {
            $candidate_detail[$i] = JobAssociateCandidates::getAssociatedCandidatesByJobCandidateId($job_id,$value);
            $i++;
        }
        
        $i = 0;
        foreach ($user_ids as $key => $value) {
            $user_email[$i] = User::getUserEmailById($value);
            $i++;
        }
        //print_r($user_email);exit;
        $to = $user_email;
        $from_name = getenv('FROM_NAME');
        $from_address = getenv('FROM_ADDRESS');
        $app_url = getenv('APP_URL');

        $input['from_name'] = $from_name;
        $input['from_address'] = $from_address;
        $input['to_address'] = $to;
        $input['app_url'] = $app_url;
        $input['candidate'] = $candidate_detail;
        $input['subject'] = 'Shortlisted candidate of job openings ' .$posting_title;

        \Mail::send('adminlte::emails.associatedcandidatemail', $input, function ($message) use($input) {
            $message->from($input['from_address'], $input['from_name']);
            $message->to($input['to_address'])->subject($input['subject']);
        });

        return redirect('/jobs/'.$job_id.'/associated_candidates');
    }

    // Function for associated candidates details by job for clinet login show page 
    public function getCandidateDetailsByJob($id){

        $candidate_details = JobAssociateCandidates::getAssociatedCandidatesDetailsByJobId($id);
        //print_r($candidate_details);exit;
        return view('adminlte::jobopen.candidatedetails',compact('candidate_details'));
    }

}
