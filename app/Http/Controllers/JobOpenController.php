<?php

namespace App\Http\Controllers;

use App\Date;
use App\Events\NotificationEvent;
use App\JobVisibleUsers;
//use App\TeamMates;
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
use App\Notifications;
use Illuminate\Support\Facades\File;
use App\CandidateOtherInfo;

class JobOpenController extends Controller
{
    public function salary() {

        $job_salary = JobOpen::select('job_openings.id as id','job_openings.salary_from as salary_from','job_openings.salary_to as salary_to')->get();

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
            $lacs[''] = 'lacs';
            for($i=0;$i<=50;$i++){
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

            DB::statement("UPDATE job_openings SET lacs_from = '$lacs_from', thousand_from = '$thousand_from', lacs_to = '$lacs_to', thousand_to = '$thousand_to' where id=$sid");
            $i++;
        }
    }

    public function work() {

        $job_work = JobOpen::select('job_openings.id as id','job_openings.work_experience_from as work_exp_from','job_openings.work_experience_to as work_exp_to')->limit(13)->get();

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
    }

    //Script for set open_to_all_date based on created_date
    public function openToAllDate() {

        $job_date = JobOpen::select('job_openings.id as id','job_openings.created_at as added_date')
        ->limit(4)->get();

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
        }
    }

    public function index(Request $request) {

        /*$access_roles_id = array($admin_role_id,$director_role_id,$manager_role_id,$superadmin_role_id,$isStrategy);
        if(in_array($user_role_id,$access_roles_id)){
            $count = JobOpen::getAllJobsCount(1,$user_id,'');
        }
        else{
            $count = JobOpen::getAllJobsCount(0,$user_id,'');
        }*/

        // Year Data
        /*$starting_year = '2017';
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
        }*/

        // logged in user with role 'Administrator,Director,Manager can see all the open jobs
        // Rest other users can only see the jobs assigned to them

        $user = \Auth::user();
        $user_id = $user->id;
        $all_jobs_perm = $user->can('display-jobs');
        $user_jobs_perm = $user->can('display-jobs-by-loggedin-user');

        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);

        // for get client id by email
        $user_obj = new User();
        $isClient = $user_obj::isClient($role_id);
        $user_email = $user->email;
        $client_id = ClientBasicinfo::getClientIdByEmail($user_email);

        // for get year selection
        $starting_year = '2017';
        $ending_year = date('Y',strtotime('+1 year'));
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

        // Get Client Heirarchy
        if (isset($_POST['client_heirarchy']) && $_POST['client_heirarchy'] != '') {
            $client_heirarchy = $_POST['client_heirarchy'];
        }
        else {
            $client_heirarchy = '0';
        }

        if($all_jobs_perm) {

            //$count = JobOpen::getAllJobsCount(1,$user_id,NUll,$current_year,$next_year,$client_heirarchy);
            //$job_priority_data = JobOpen::getPriorityWiseJobs(1,$user_id,NULL,$current_year,$next_year,$client_heirarchy);

            $count = JobOpen::getAllJobsCount(1,$user_id,NUll,'','',$client_heirarchy);
            $job_priority_data = JobOpen::getPriorityWiseJobs(1,$user_id,NULL,'','');
        }
        else if ($isClient) {

            $job_response = JobOpen::getAllJobsByCLient($client_id,0,0,0,NULL,'',$client_heirarchy);
            $count = sizeof($job_response);
            $job_priority_data = JobOpen::getPriorityWiseJobsByClient($client_id,NULL,'','');
        }
        else if ($user_jobs_perm) {

            //$count = JobOpen::getAllJobsCount(0,$user_id,NULL,$current_year,$next_year,$client_heirarchy);
            //$job_priority_data = JobOpen::getPriorityWiseJobs(0,$user_id,NULL,$current_year,$next_year,$client_heirarchy);

            $count = JobOpen::getAllJobsCount(0,$user_id,NULL,'','',$client_heirarchy);
            $job_priority_data = JobOpen::getPriorityWiseJobs(0,$user_id,NULL,'','');
        }

        $priority_0 = 0;
        $priority_1 = 0;
        $priority_2 = 0;
        $priority_3 = 0;
        $priority_5 = 0;
        $priority_6 = 0;
        $priority_7 = 0;
        $priority_8 = 0;

        foreach ($job_priority_data as $job_priority) {

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
        }

        // Get All Client Heirarchy
        //$client_heirarchy_name = ClientHeirarchy::getAllClientHeirarchyName();
        $client_hierarchy_name = JobOpen::getAllHierarchyName();
        
        $viewVariable = array();
        $viewVariable['job_priority'] = JobOpen::getJobPriorities();
        $viewVariable['count'] = $count;
        $viewVariable['isClient'] = $isClient;
        $viewVariable['year_array'] = $year_array;
        $viewVariable['year'] = $year;
        $viewVariable['client_hierarchy_name'] = $client_hierarchy_name;
        $viewVariable['priority_0'] = $priority_0;
        $viewVariable['priority_1'] = $priority_1;
        $viewVariable['priority_2'] = $priority_2;
        $viewVariable['priority_3'] = $priority_3;
        $viewVariable['priority_5'] = $priority_5;
        $viewVariable['priority_6'] = $priority_6;
        $viewVariable['priority_7'] = $priority_7;
        $viewVariable['priority_8'] = $priority_8;

        return view('adminlte::jobopen.index', $viewVariable);
    }

    // Job open to all page
    public function OpentoAll(Request $request) {

        // logged in user with role 'Administrator,Director,Manager can see all the open jobs
        // Rest other users can only see the jobs assigned to them

        $user = \Auth::user();
        $user_id = \Auth::user()->id;
        $display_jobs = $user->can('display-jobs-open-to-all');
       
        if($display_jobs) {
            $job_response = JobOpen::getOpenToAllJobs(0,$user_id);
        }

        $count = sizeof($job_response);

        $viewVariable = array();
        $viewVariable['jobList'] = $job_response;
        $viewVariable['job_priority'] = JobOpen::getJobPriorities();
        $viewVariable['count'] = $count;

        return view('adminlte::jobopen.opentoall', $viewVariable);
    }

    // Function for priority wise job page
    /*public function priorityWise($priority,$year){*/

    public function priorityWise($priority) {

        $user = \Auth::user();
        $user_id = $user->id;

        $all_jobs_perm = $user->can('display-jobs');
        $user_jobs_perm = $user->can('display-jobs-by-loggedin-user');

        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);

        $user_obj = new User();
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

        if($all_jobs_perm) {

            $job_response = JobOpen::getPriorityWiseJobs(1,$user_id,$priority,$current_year,$next_year);
            //$job_response_data = JobOpen::getPriorityWiseJobs(1,$user_id,NULL,$current_year,$next_year);
        }
        else if($isClient) {

            $job_response = JobOpen::getPriorityWiseJobsByClient($client_id,$priority,$current_year,$next_year);
            //$job_response_data = JobOpen::getPriorityWiseJobsByClient($client_id,NULL);
        }
        else if($user_jobs_perm) {

            $job_response = JobOpen::getPriorityWiseJobs(0,$user_id,$priority,$current_year,$next_year);
            //$job_response_data = JobOpen::getPriorityWiseJobs(0,$user_id,NULL,$current_year,$next_year);
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

        foreach ($job_response_data as $job_priority) {

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
        $viewVariable['count'] = $count;
        $viewVariable['priority'] = $priority;
        $viewVariable['isClient'] = $isClient;
        $viewVariable['financial_year'] = $financial_year;
        $viewVariable['year'] = $year;

        /*$viewVariable['priority_0'] = $priority_0;
        $viewVariable['priority_1'] = $priority_1;
        $viewVariable['priority_2'] = $priority_2;
        $viewVariable['priority_3'] = $priority_3;
        $viewVariable['priority_4'] = $priority_4;
        $viewVariable['priority_5'] = $priority_5;
        $viewVariable['priority_6'] = $priority_6;
        $viewVariable['priority_7'] = $priority_7;
        $viewVariable['priority_8'] = $priority_8;
        $viewVariable['priority_9'] = $priority_9;
        $viewVariable['priority_10'] = $priority_10;*/

        return view('adminlte::jobopen.prioritywisejob', $viewVariable);
    }

    public function priorityWiseClosedJobs($priority,$year) {

        $user = \Auth::user();
        $user_id = $user->id;

        $all_jobs_perm = $user->can('display-closed-jobs');
        $user_jobs_perm = $user->can('display-closed-jobs-by-loggedin-user');

        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);

        // for get client id by email
        $user_obj = new User();
        $isClient = $user_obj::isClient($role_id);
        $user_email = $user->email;
        $client_id = ClientBasicinfo::getClientIdByEmail($user_email);

        if (isset($year) && $year != 0) {

            $year_data = explode(", ", $year);
            $year1 = $year_data[0];
            $year2 = $year_data[1];
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

        if($all_jobs_perm) {

            $job_response = JobOpen::getPriorityWiseJobs(1,$user_id,$priority,$current_year,$next_year);
            //$job_response_data = JobOpen::getPriorityWiseJobs(1,$user_id,NULL,$current_year,$next_year);
        }
        else if ($isClient) {

            $job_response = JobOpen::getPriorityWiseJobsByClient($client_id,$priority,$current_year,$next_year);
            //$job_response_data = JobOpen::getPriorityWiseJobsByClient($client_id,NULL);
        }
        else if ($user_jobs_perm) {

            $job_response = JobOpen::getPriorityWiseJobs(0,$user_id,$priority,$current_year,$next_year);
            //$job_response_data = JobOpen::getPriorityWiseJobs(0,$user_id,NULL,$current_year,$next_year);
        }

        $count = sizeof($job_response);

        $viewVariable = array();
        $viewVariable['jobList'] = $job_response;
        $viewVariable['job_priority'] = JobOpen::getJobPriorities();
        $viewVariable['count'] = $count;
        $viewVariable['priority'] = $priority;
        $viewVariable['isClient'] = $isClient;
        $viewVariable['financial_year'] = $financial_year;
        $viewVariable['year'] = $year;

        return view('adminlte::jobopen.prioritywisejob', $viewVariable);
    }

    public static function getJobOrderColumnName($order) {

        $order_column_name = '';

        if (isset($order) && $order >= 0) {

            if ($order == 0) {
                $order_column_name = "job_openings.id";
            }
            else if ($order == 3) {
                $order_column_name = "users.name";
            }
            else if ($order == 4) {
                $order_column_name = "client_basicinfo.display_name";
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
                $order_column_name = "job_openings.created_at";
            }
            else if ($order == 11) {
                $order_column_name = "job_openings.updated_at";
            }
            else if ($order == 12) {
                $order_column_name = "job_openings.no_of_positions";
            }
            else if ($order == 13) {
                $order_column_name = "client_basicinfo.coordinator_name";
            }
        }
        return $order_column_name;
    }

    public function getAllJobsDetails() {

        $limit = $_GET['length'];
        $offset = $_GET['start'];
        $draw = $_GET['draw'];
        $search = $_GET['search']['value'];
        $order = $_GET['order'][0]['column'];
        $type = $_GET['order'][0]['dir'];

        // Get Client Heirarchy

        $client_heirarchy = $_GET['client_heirarchy'];

        if (isset($_GET['year']) && $_GET['year'] != '') {

            $year = $_GET['year'];

            if (isset($year) && $year != 0) {
                $year_data = explode(", ", $year);
                $year1 = $year_data[0];
                $year2 = $year_data[1];
                $current_year = date('Y-m-d h:i:s',strtotime("first day of $year1"));
                $next_year = date('Y-m-d h:i:s',strtotime("last day of $year2"));
            }
            else {
                $year = NULL;
                $current_year = NULL;
                $next_year = NULL;    
            }
        }
        else {
            $year = NULL;
            $current_year = NULL;
            $next_year = NULL;
        }

        $order_column_name = self::getJobOrderColumnName($order);

        $user = \Auth::user();
        $user_id = $user->id;

        $all_jobs_perm = $user->can('display-jobs');
        $user_jobs_perm = $user->can('display-jobs-by-loggedin-user');
        $change_priority_perm = $user->can('change-job-priority');
        $change_multiple_priority_perm = $user->can('update-multiple-jobs-priority');
        $clone_perm = $user->can('clone-job');
        $delete_perm = $user->can('job-delete');

        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);
        $user_obj = new User();
        $isClient = $user_obj::isClient($role_id);

        // for get client id by email
        $user_email = $user->email;
        $client_id = ClientBasicinfo::getClientIdByEmail($user_email);

        $job_priority = JobOpen::getJobPriorities();

        if($all_jobs_perm) {
            
            $job_response = JobOpen::getAllJobs(1,$user_id,$limit,$offset,$search,$order_column_name,$type,$current_year,$next_year,$client_heirarchy);
            $count = JobOpen::getAllJobsCount(1,$user_id,$search,$current_year,$next_year,$client_heirarchy);
            $job_priority_data = JobOpen::getPriorityWiseJobs(1,$user_id,NULL,$current_year,$next_year,$client_heirarchy);
        }
        else if ($isClient) {

            $job_response = JobOpen::getAllJobsByCLient($client_id,$limit,$offset,$search,$order_column_name,$type,$client_heirarchy);
            $count = sizeof($job_response);
            $job_priority_data = JobOpen::getPriorityWiseJobsByClient($client_id,NULL,$current_year,$next_year,$client_heirarchy);
        }
        else if ($user_jobs_perm) {

            $job_response = JobOpen::getAllJobs(0,$user_id,$limit,$offset,$search,$order_column_name,$type,$current_year,$next_year,$client_heirarchy);
            $count = JobOpen::getAllJobsCount(0,$user_id,$search,$current_year,$next_year,$client_heirarchy);

            $job_priority_data = JobOpen::getPriorityWiseJobs(0,$user_id,NULL,$current_year,$next_year,$client_heirarchy);
        }

        $jobs = array();
        $i = 0;$j = 0;

        foreach ($job_response as $key => $value) {

            $action = '';
            $checkbox = '';

            $action .= '<a title="Show"  class="fa fa-circle" href="'.route('jobopen.show',$value['id']).'" style="margin:3px;"></a>';

            if(isset($value['access']) && $value['access'] == 1) {

                $action .= '<a title="Edit" class="fa fa-edit" href="'.route('jobopen.edit',$value['id']).'" style="margin:3px;"></a>';
        
                /*$status_view = \View::make('adminlte::partials.jobstatus',['data' => $value, 'name' => 'jobopen', 'display_name'=>'Job Open', 'job_priority' => $job_priority,'year' => $year]);*/

                if($change_priority_perm) {

                    $status_view = \View::make('adminlte::partials.jobstatus',['data' => $value, 'name' => 'jobopen', 'display_name'=>'Job Open','job_priority' => $job_priority]);
                    $status = $status_view->render();
                    $action .= $status;
                }
            }

            if ($delete_perm) {

                /*$delete_view = \View::make('adminlte::partials.jobdelete',['data' => $value, 'name' => 'jobopen', 'display_name'=>'Job','year' => $year,'title' => 'Job Open']);
                $delete = $delete_view->render();
                $action .= $delete;*/

                $delete_view = \View::make('adminlte::partials.jobdelete',['data' => $value, 'name' => 'jobopen', 'display_name'=>'Job','title' => 'Job Open']);
                $delete = $delete_view->render();
                $action .= $delete;
            }

            if(isset($value['access']) && $value['access'] == 1) {

                if($clone_perm) {
                    $action .= '<a title="Clone Job"  class="fa fa-clone" href="'.route('jobopen.clone',$value['id']).'"></a>';
                }

                if($change_multiple_priority_perm) {
                    $checkbox .= '<input type=checkbox name=job_ids value='.$value['id'].' class=multiple_jobs id='.$value['id'].'/>';
                }
                else {
                    $checkbox .= '';
                }
            }

            $managed_by = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['am_name'].'</a>';

            $company_name = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['display_name'].'</a>';

            $posting_title = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['posting_title'].'</a>';

            $qual = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['qual'].'</a>';

            if ($isClient) {

                $associated_count = '<a title="Show Candidates Details" href="'.route('jobopen.candidates_details_get',$value['id']).'">'.$value['associate_candidate_cnt'].'</a>';
            }
            else {
                $associated_count = '<a title="Show Associated Candidates" href="'.route('jobopen.associated_candidates_get',$value['id']).'">'.$value['associate_candidate_cnt'].'</a>';
            }

            $data = array(++$j,$checkbox,$action,$managed_by,$company_name,$posting_title,$associated_count,$value['city'],$value['min_ctc'],$value['max_ctc'],$value['created_date'],$value['updated_date'],$value['no_of_positions'],$qual,$value['coordinator_name'],$value['industry'],$value['desired_candidate'],$value['priority']);
            $jobs[$i] = $data;
            $i++;
        }

        $priority_0 = 0; $priority_1 = 0; $priority_2 = 0; $priority_3 = 0;
        $priority_5 = 0; $priority_6 = 0; $priority_7 = 0; $priority_8 = 0;

        foreach ($job_priority_data as $value) {
           if($value['priority'] == 0) {
                $priority_0++;
           }
           else if($value['priority'] == 1) {
                $priority_1++;
           }
            else if($value['priority'] == 2) {
                $priority_2++;
           }
            else if($value['priority'] == 3) {
                $priority_3++;
           }
            else if($value['priority'] == 5) {
                $priority_5++;
           }
            else if($value['priority'] == 6) {
                $priority_6++;
           }
            else if($value['priority'] == 7) {
                $priority_7++;
           }
            else if($value['priority'] == 8) {
                $priority_8++;
           }
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
            "data" => $jobs,
            "priority" => $priority,
            "job_priority" => $job_priority,
            //'year' => $year,
        );

        echo json_encode($json_data);exit;
    }

    public function create() {

        $user = \Auth::user();
        $user_id = $user->id;
        $loggedin_user_id = $user->id;

        $all_client_perm = $user->can('display-client');
        $userwise_client_perm = $user->can('display-account-manager-wise-client');

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
        for($i=0;$i<=50;$i++) {
            $lacs[$i] = $i;
        }
        for($i=55;$i<100;$i+=5) {
            $lacs[$i] = $i;
        }
        $lacs['100+'] = '100+';

        // Thousand dropdown
        $thousand = array(''=>'Thousand');
        for($i=0;$i<100;$i+=5) {
            $thousand[$i] = $i;
        }

        //Work experience from dropdown
        $work_from = array('0'=>'Work Experience From');
        for($i=0;$i<=30;$i++) {
            $work_from[$i] = $i;
        }

        //Work experience to dropdown
        $work_to = array('0'=>'Work Experience To');
        for($i=0;$i<=30;$i++) {
            $work_to[$i] = $i;
        }

        $client = array();

        if($all_client_perm) {

            // get all clients
            $client_res = ClientBasicinfo::getLoggedInUserClients(0);
        }
        else if($userwise_client_perm) {
            
            // get logged in user clients
            $client_res = ClientBasicinfo::getLoggedInUserClients($user_id);
        }

        if (sizeof($client_res) > 0) {
            foreach ($client_res as $r) {
                $client[$r->id] = $r->name." - ".$r->coordinator_name." - ".$r->billing_city;
            }
        }

        // For account manager
        $users = User::getAllUsers(NULL,'Yes');
        $users[0] = 'Yet to Assign';
        
        $select_all_users = User::getAllUsers(NULL,'Yes');

        // job type
        $job_type = JobOpen::getJobTypes();

        // job priority
        //$job_priorities = JobOpen::getJobPriorities();
        $job_priorities = JobOpen::getNewJobPriorities();

        $no_of_positions = 1;
        $lacs_from = '';
        $thousand_from = '';
        $lacs_to = '';
        $thousand_to = '';
        $work_exp_from = '';
        $work_exp_to = '';

        //$client_hierarchy_name = ClientHeirarchy::getAllClientHeirarchyName();
        $client_hierarchy_name = JobOpen::getAllHierarchyName();

        $action = "add";

        $super_admin_user_id = getenv('SUPERADMINUSERID');
        $selected_users = array($user_id,$super_admin_user_id);

        // For job open or not after 48 hours

        $strategy_user_id = getenv('STRATEGYUSERID');
        $bhagyashree_user_id = getenv('BHAGYASHREEUSERID');
        $arjun_user_id = getenv('ARJUNUSERID');
        $tanisha_user_id = getenv('TANISHAUSERID');

        $job_open_checkbox = '0';
        $adler_career_checkbox = '0';
        $adler_job_disclosed_checkbox = '1';

        return view('adminlte::jobopen.create', compact('user_id','action', 'industry','no_of_positions', 'client', 'users', 'job_type','job_priorities','selected_users','lacs','thousand','lacs_from','thousand_from','lacs_to','thousand_to','work_from','work_to','work_exp_from','work_exp_to','select_all_users','client_hierarchy_name','super_admin_user_id','loggedin_user_id','strategy_user_id','bhagyashree_user_id','arjun_user_id','tanisha_user_id','job_open_checkbox','adler_career_checkbox','adler_job_disclosed_checkbox'));
    }

    public function store(Request $request) {

        $dateClass = new Date();

        $user_id = \Auth::user()->id;
        $user_name = \Auth::user()->name;
        $loggedin_email = \Auth::user()->email;

        $input = $request->all();

        $max_id = JobOpen::find(\DB::table('job_openings')->max('id'));
        if (isset($max_id->id) && $max_id->id != '')
            $max_id = $max_id->id;
        else
            $max_id = 0;

        $posting_title = $input['posting_title'];
        $hiring_manager_id = $input['hiring_manager_id'];
        $job_priority = $input['job_priority'];
        $industry_id = $input['industry_id'];
        $client_id = $input['client_id'];
        $no_of_positions = $input['no_of_positions'];
        $date_open = $input['date_opened'];
        $target_date = $input['target_date'];
        $job_type = $input['job_type'];
        $job_description = $input['job_description'];
        $city = $input['city'];
        $state = $input['state'];
        $country = $input['country'];
        $job_show = 0;
        $users = $input['user_ids'];
        $desired_candidate = $input['desired_candidate'];
        $qualifications = $input['qualifications'];

        $lacs_from = $input['lacs_from'];
        $lacs_to = $input['lacs_to'];
        $thousand_from = $input['thousand_from'];
        $thousand_to = $input['thousand_to'];
        $work_exp_from = $input['work_experience_from'];
        $work_exp_to = $input['work_experience_to'];

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
        else {
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
        }

        $level_id = $input['level_id'];

        $increment_id = $max_id + 1;
        $job_unique_id = "TT-JO-$increment_id";

        // For not open job afer 48 hours
        if(isset($input['job_open_checkbox']) && $input['job_open_checkbox'] != '') {
            $job_open_checkbox = '1';
        }
        else {
            $job_open_checkbox = '0';
        }

        // For display job in career page of adler website
        if(isset($input['adler_career_checkbox']) && $input['adler_career_checkbox'] != '') {
            $adler_career_checkbox = '1';
        }
        else {
            $adler_career_checkbox = '0';
        }

        // For display job salary in career page of adler website
        if(isset($input['adler_job_disclosed_checkbox']) && $input['adler_job_disclosed_checkbox'] != '') {
            $adler_job_disclosed_checkbox = '1';
        }
        else {
            $adler_job_disclosed_checkbox = '0';
        }

        $job_open = new JobOpen();
        $job_open->job_id = $job_unique_id;
        $job_open->job_show = $job_show;
        $job_open->posting_title = $posting_title;
        $job_open->hiring_manager_id = $hiring_manager_id;
        $job_open->industry_id = $industry_id;
        $job_open->client_id = $client_id;
        $job_open->no_of_positions = $no_of_positions;
        $job_open->date_opened = $dateClass->changeDMYtoYMD($date_open);
        $job_open->target_date = $dateClass->changeDMYtoYMD($target_date);
        $job_open->job_type = $job_type;
        $job_open->job_description = $job_description;
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
        $job_open->level_id = $level_id;
        $job_open->job_open_checkbox = $job_open_checkbox;
        $job_open->adler_career_checkbox = $adler_career_checkbox;
        $job_open->adler_job_disclosed_checkbox = $adler_job_disclosed_checkbox;

        $validator = \Validator::make(Input::all(),$job_open::$rules);

        if($validator->fails()){
            return redirect('jobs/create')->withInput(Input::all())->withErrors($validator->errors());
        }

        $job_open->save();

        $job_id = $job_open->id;

        if (isset($job_id) && $job_id > 0) {

            if(isset($users) && sizeof($users) > 0) {

                foreach ($users as $key => $value) {

                    $job_visible_users = new JobVisibleUsers();
                    $job_visible_users->job_id = $job_id;
                    $job_visible_users->user_id = $value;
                    $job_visible_users->save();
                }
            }

            \DB::statement("UPDATE job_openings SET open_to_all = '0' where id=$job_id");

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
                }
                else {

                    $job_open_doc = new JobOpenDoc();
                    $job_open_doc->job_id = $job_id;
                    $job_open_doc->category = 'Job Description';
                    $job_open_doc->name = $job_summary_name;
                    $job_open_doc->file = $job_summary_key;
                    $job_open_doc->uploaded_by = $user_id;
                    $job_open_doc->size = $filesize;
                    $job_open_doc->created_at = time();
                    $job_open_doc->updated_at = time();
                    $job_open_doc->save();
                }
            }

            if (isset($others_doc) && $others_doc != '') {

                foreach ($others_doc as $k => $v) {

                    if (isset($v) && $v->isValid()) {

                        $others_doc_name = $v->getClientOriginalName();
                        $others_filesize = filesize($v);

                        $dir_name = "uploads/jobs/".$job_id."/";
                        $others_doc_key = "uploads/jobs/".$job_id."/".$others_doc_name;

                        if (!file_exists($dir_name)) {
                            mkdir("uploads/jobs/$job_id", 0777,true);
                        }

                        if(!$v->move($dir_name, $others_doc_name)) {
                            return false;
                        }
                        else {

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
                } 
            }

            // For candidate tracker
            $candidate_tracker = $request->file('candidate_tracker');

            if (isset($candidate_tracker) && $candidate_tracker->isValid()) {
                $candidate_tracker_name = $candidate_tracker->getClientOriginalName();
                $filesize = filesize($candidate_tracker);

                $dir_name = "uploads/jobs/" . $job_id . "/";
                $candidate_tracker_key = $dir_name . $candidate_tracker_name;

                if (!file_exists($dir_name)) {
                    mkdir("uploads/jobs/$job_id", 0777, true);
                }

                if (!$candidate_tracker->move($dir_name, $candidate_tracker_name)) {
                    return false;
                }
                else {

                    $job_open_doc = new JobOpenDoc();
                    $job_open_doc->job_id = $job_id;
                    $job_open_doc->category = 'Candidate Tracker';
                    $job_open_doc->name = $candidate_tracker_name;
                    $job_open_doc->file = $candidate_tracker_key;
                    $job_open_doc->uploaded_by = $user_id;
                    $job_open_doc->size = $filesize;
                    $job_open_doc->created_at = time();
                    $job_open_doc->updated_at = time();
                    $job_open_doc->save();
                }
            }

            // Notifications : On creating job openings : send notification to selected users that new job openings is added (except user who created jobopening),default send notificaations to admin user.

            if(isset($users) && sizeof($users) > 0) {

                $user_emails = array();
                $user_arr = array();

                foreach ($users as $key => $value) {

                    if($user_id != $value) {

                        $module_id = $job_id;
                        $module = 'Job Openings';
                        $message = $user_name . " added new job";
                        $link = route('jobopen.show',$job_id);
                        $user_arr = trim($value);

                        event(new NotificationEvent($module_id, $module, $message, $link, $user_arr));

                        $email = User::getUserEmailById($value);

                        $user_emails[] = $email;
                    }
                }

                // Email Notification : data store in datebase
                $superadminuserid = getenv('SUPERADMINUSERID');
                $superadminsecondemail = User::getUserEmailById($superadminuserid);

                $cc_users_array = array($loggedin_email,$superadminsecondemail);

                $module = "Job Open";
                $sender_name = $user_id;
                $to = implode(",",$user_emails);
                $cc = implode(",",$cc_users_array);

                $client_name = ClientBasicinfo::getCompanyOfClientByID($client_id);
                $client_city = ClientBasicinfo::getBillingCityOfClientByID($client_id);
                //$level_nm = ClientHeirarchy::getClientHeirarchyNameById($level_id);
                        
                $subject = "Job Opening - " . $posting_title . "@" .$client_name . " - " . $client_city;
                $message = "<tr><th>" . $posting_title . "/" . $job_unique_id . "</th></tr>";
                $module_id = $job_id;

                event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
            }
        }
        return redirect()->route('jobopen.index')->with('success', 'Job Opening Created Successfully');
    }

    public function show($id) {

        $user = \Auth::user();
        $user_id = $user->id;

        $all_jobs_perm = $user->can('display-jobs');

        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);
        $user_obj = new User();
        $isClient = $user_obj::isClient($role_id);

        // for get client id by email
        $user_email = $user->email;
        $client_id = ClientBasicinfo::getClientIdByEmail($user_email);

        $dateClass = new Date();
        $job_open = array();

        $job_open_detail = \DB::table('job_openings')
        /*->leftjoin('client_heirarchy','client_heirarchy.id','=','job_openings.level_id')*/
        ->join('client_basicinfo', 'client_basicinfo.id', '=', 'job_openings.client_id')
        ->join('client_address','client_address.client_id','=','client_basicinfo.id')
        ->leftjoin('users', 'users.id', '=', 'job_openings.hiring_manager_id')
        ->join('industry', 'industry.id', '=', 'job_openings.industry_id')
        ->select('job_openings.*', 'client_basicinfo.name as client_name','client_basicinfo.coordinator_name as co_nm','client_address.billing_city as bill_city', 'users.name as hiring_manager_name', 'industry.name as industry_name'/*,'client_heirarchy.name as level_name'*/)->where('job_openings.id', '=', $id)->first();

        $job_open['id'] = $id;

        $check_visible_users = \DB::table('job_visible_users')
            ->select('users.id','users.name')->join('users','users.id','=','job_visible_users.user_id')
            ->where('job_visible_users.job_id',$id)->get();
   
        if(isset($job_open_detail) && $job_open_detail != '') {

            $cnt = 0;
            foreach($check_visible_users as $key => $val) {
                $job_open['users_ids'][$cnt] = $val->id;
                $cnt++;
            }

            $users_array = $job_open['users_ids'];

            if($all_jobs_perm) {
                $job_open['access'] = '1';
            }
            else if($job_open_detail->hiring_manager_id == $user_id) {
                $job_open['access'] = '1';
            }
            else if(in_array($user_id,$users_array)) {
                $job_open['access'] = '0';
            }
            else if ($isClient && $job_open_detail->client_id == $client_id) {
                $job_open['access'] = '1';
            }
            else {
                $job_open['access'] = '0';
                return view('errors.403');
            }

            if ($job_open_detail->lacs_from >= '100') {
                $min_ctc = '100+';
            }
            else {
                $lacs_from = $job_open_detail->lacs_from*100000;
                $thousand_from = $job_open_detail->thousand_from*1000;
                $mictc = $lacs_from+$thousand_from;
                $minctc = $mictc/100000;
                $min_ctc = number_format($minctc,2);
            }

            if ($job_open_detail->lacs_to >= '100') {
                $max_ctc = '100+';
            }
            else {
                $lacs_to = $job_open_detail->lacs_to*100000;
                $thousand_to = $job_open_detail->thousand_to*1000;
                $mactc = $lacs_to+$thousand_to;
                $maxctc = $mactc/100000;
                $max_ctc = number_format($maxctc,2);
            }

            if (isset($job_open_detail->level_name) && $job_open_detail->level_name != '') {
                $job_open['posting_title'] = $job_open_detail->level_name." - ".$job_open_detail->posting_title;
            }
            else {
                $job_open['posting_title'] = $job_open_detail->posting_title;   
            }

            $job_open['job_id'] = $job_open_detail->job_id;
            $job_open['client_name'] = $job_open_detail->client_name . "-" . $job_open_detail->bill_city;
            $job_open['client_id'] = $job_open_detail->client_id;
            $job_open['desired_candidate'] = $job_open_detail->desired_candidate;
            $job_open['hiring_manager_name'] = $job_open_detail->hiring_manager_name;
            $job_open['no_of_positions'] = $job_open_detail->no_of_positions;
            $job_open['target_date'] = $dateClass->changeYMDtoDMY($job_open_detail->target_date);
            $job_open['date_opened'] = $dateClass->changeYMDtoDMY($job_open_detail->date_opened);
            $job_open['job_type'] = $job_open_detail->job_type;
            $job_open['industry_name'] = $job_open_detail->industry_name;
            $job_open['description'] = $job_open_detail->job_description;
            $job_open['work_experience_from'] = $job_open_detail->work_exp_from;
            $job_open['work_experience_to']= $job_open_detail->work_exp_to;
            $job_open['min_salary'] = $min_ctc;
            $job_open['max_salary'] = $max_ctc;
            $job_open['country'] = $job_open_detail->country;
            $job_open['state'] = $job_open_detail->state;
            $job_open['city'] = $job_open_detail->city;
            $job_open['education_qualification'] = $job_open_detail->qualifications;
            $job_open['priority'] = $job_open_detail->priority;

            // already added posting,massmail and job search options
            $selected_posting = array();
            $selected_mass_mail = array();
            $selected_job_search = array();

            $mo_posting = '';
            $mo_mass_mail='';
            $mo_job_search = '';

            if(isset($job_open_detail->posting) && $job_open_detail->posting!='') {
                $mo_posting = $job_open_detail->posting;
                $selected_posting = explode(",",$mo_posting);
            }
            if(isset($job_open_detail->mass_mail) && $job_open_detail->mass_mail!='') {
                $mo_mass_mail = $job_open_detail->mass_mail;
                $selected_mass_mail = explode(",",$mo_mass_mail);
            }
            if(isset($job_open_detail->job_search) && $job_open_detail->job_search!='') {
                $mo_job_search = $job_open_detail->job_search;
                $selected_job_search = explode(",",$mo_job_search);
            }
        }

        $count = 0;
        foreach ($check_visible_users as $key => $value) {
            $job_open['users'][$count] = $value->name;
            $count++;
        }

        $jobopen_model = new JobOpen();
        $upload_type = $jobopen_model->upload_type;

        $i = 0;
        $job_open['doc'] = array();
        $utils = new Utils();

        $jobopen_doc = \DB::table('job_openings_doc')
        ->join('users', 'users.id', '=', 'job_openings_doc.uploaded_by')
        ->select('job_openings_doc.*', 'users.name as upload_name')->where('job_id', '=', $id)->get();

        foreach ($jobopen_doc as $key => $value) {

            $job_open['doc'][$i]['name'] = $value->name;
            $job_open['doc'][$i]['id'] = $value->id;
            $job_open['doc'][$i]['url'] = "../" . $value->file;
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

        // For Set Financial Year
        $get_month =  date('m',strtotime($job_open['date_opened']));

        if($get_month == '01' || $get_month == '02' || $get_month == '03') {

            $get_next_year =  date('Y',strtotime($job_open['date_opened']));
            $get_current_year = $get_next_year - 1;
        }
        else {

            $get_current_year =  date('Y',strtotime($job_open['date_opened']));
            $get_next_year = $get_current_year + 1;
        }
        $year = $get_current_year."-4, ".$get_next_year."-3";

        return view('adminlte::jobopen.show', array('jobopen' => $job_open, 'upload_type' => $upload_type,'posting_status'=>$posting_status,'job_search'=>$job_search,'selected_posting'=>$selected_posting,'selected_mass_mail'=>$selected_mass_mail,'selected_job_search'=>$selected_job_search,'job_status'=>$job_status, 'role_id' => $role_id, 'isClient' => $isClient,'year' => $year));   
    }

    /*public function edit($id,$year)*/
    public function edit($id) {

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
        $lacs[''] = 'lacs';
        for($i=0;$i<=50;$i++){
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
        for($i=0;$i<=30;$i++){
            $work_from[$i] = $i;
        }

        //Work experience to dropdown
        $work_to = array('0'=>'Work Experience To');
        for($i=0;$i<=30;$i++){
            $work_to[$i] = $i;
        }

        $user = \Auth::user();
        $loggedin_user_id = $user->id;

        $all_client_perm = $user->can('display-client');
        $userwise_client_perm = $user->can('display-account-manager-wise-client');
        $all_jobs_perm = $user->can('display-jobs');

        $client = array();

        if($all_client_perm) {
            
            // get all clients
            $client_res = ClientBasicinfo::getLoggedInUserClients(0);
        }
        else if($userwise_client_perm) {

            // get logged in user clients
            $client_res = ClientBasicinfo::getLoggedInUserClients($loggedin_user_id);
        }

        if (sizeof($client_res) > 0) {
            foreach ($client_res as $r) {
                $client[$r->id] = $r->name." - ".$r->coordinator_name." - ".$r->billing_city;
            }
        }

        // For account manager
        $users = User::getAllUsers(NULL,'Yes');
        $select_all_users = User::getAllUsers(NULL,'Yes');

        // job type
        $job_type = JobOpen::getJobTypes();

        // job priority
        $job_priorities = JobOpen::getJobPriorities();

        // get Client hierarchy names
        //$client_hierarchy_name = ClientHeirarchy::getAllClientHeirarchyName();
        $client_hierarchy_name = JobOpen::getAllHierarchyName();

        $job_open = JobOpen::find($id);

        // Client Secondline Account Manager
        $client_info = ClientBasicinfo::getClientInfoByJobId($id);
        
        $client_second_line_am = $client_info['second_line_am'];

        if($all_jobs_perm || ($job_open->hiring_manager_id == $loggedin_user_id) || ($loggedin_user_id == $client_second_line_am)) { 

            $user_id = $job_open->hiring_manager_id;
            $lacs_from = $job_open->lacs_from;
            $thousand_from = $job_open->thousand_from;
            $lacs_to = $job_open->lacs_to;
            $thousand_to = $job_open->thousand_to;
            $work_exp_from = $job_open->work_exp_from;
            $work_exp_to = $job_open->work_exp_to;

            $date_opened = $dateClass->changeYMDtoDMY($job_open->date_opened);
            $target_date = $dateClass->changeYMDtoDMY($job_open->target_date);

            // Get visible users
            $job_visible_users = JobVisibleUsers::where('job_id',$id)->get();
            $selected_users = array();

            if(isset($job_visible_users) && sizeof($job_visible_users)>0) {
                foreach($job_visible_users as $row) {
                    $selected_users[] = $row->user_id;
                }
            }

            $jobopen_model = new JobOpen();
            $upload_type = $jobopen_model->upload_type;

            $job_open['doc'] = JobOpenDoc::getJobDocByJobId($id);

            foreach ($job_open['doc'] as $key => $value) {

                if (array_search($value['category'], $upload_type)) {
                    unset($upload_type[array_search($value['category'], $upload_type)]);
                }
            }

            $upload_type['Others'] = 'Others';

            $job_open_checkbox = $job_open->job_open_checkbox;
            $adler_career_checkbox = $job_open->adler_career_checkbox;
            $adler_job_disclosed_checkbox = $job_open->adler_job_disclosed_checkbox;
        }
        else {
            return view('errors.403');
        }

        $action = "edit";

        // For job open or not after 48 hours

        $super_admin_user_id = getenv('SUPERADMINUSERID');
        $strategy_user_id = getenv('STRATEGYUSERID');
        $bhagyashree_user_id = getenv('BHAGYASHREEUSERID');
        $arjun_user_id = getenv('ARJUNUSERID');
        $tanisha_user_id = getenv('TANISHAUSERID');

        return view('adminlte::jobopen.edit', compact('user_id','action', 'industry', 'client', 'users','job_type','job_priorities', 'job_open', 'date_opened', 'target_date','selected_users','lacs','thousand','lacs_from','thousand_from','lacs_to','thousand_to','work_from','work_to','work_exp_from','work_exp_to','select_all_users','upload_type','client_hierarchy_name','super_admin_user_id','loggedin_user_id','strategy_user_id','bhagyashree_user_id','arjun_user_id','tanisha_user_id','job_open_checkbox','adler_career_checkbox','adler_job_disclosed_checkbox'));
    }

    public function editClosedJob($id,$year) {

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
        $lacs[''] = 'lacs';
        for($i=0;$i<=50;$i++){
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
        for($i=0;$i<=30;$i++){
            $work_from[$i] = $i;
        }

        //Work experience to dropdown
        $work_to = array('0'=>'Work Experience To');
        for($i=0;$i<=30;$i++){
            $work_to[$i] = $i;
        }

        $user = \Auth::user();
        $user_id = $user->id;
        $loggedin_user_id = $user->id;
        
        $all_client_perm = $user->can('display-client');
        $userwise_client_perm = $user->can('display-account-manager-wise-client');
        $all_jobs_perm = $user->can('display-jobs');

        $client = array();

        if($all_client_perm) {

            // get all clients
            $client_res = ClientBasicinfo::getLoggedInUserClients(0);
        }
        else if($userwise_client_perm) {

            // get logged in user clients
            $client_res = ClientBasicinfo::getLoggedInUserClients($user_id);
        }

        if (sizeof($client_res) > 0) {
            foreach ($client_res as $r) {
                $client[$r->id] = $r->name." - ".$r->coordinator_name." - ".$r->billing_city;
            }
        }

        // For account manager
        $users = User::getAllUsers(NULL,'Yes');
        $select_all_users = User::getAllUsers(NULL,'Yes');

        // job type
        $job_type = JobOpen::getJobTypes();

        // job priority
        $job_priorities = JobOpen::getJobPriorities();

        // get Client hierarchy names
        //$client_hierarchy_name = ClientHeirarchy::getAllClientHeirarchyName();
        $client_hierarchy_name = JobOpen::getAllHierarchyName();

        $job_open = JobOpen::find($id);

        if($all_jobs_perm || ($job_open->hiring_manager_id == $user_id)) {

            $user_id = $job_open->hiring_manager_id;
            $lacs_from = $job_open->lacs_from;
            $thousand_from = $job_open->thousand_from;
            $lacs_to = $job_open->lacs_to;
            $thousand_to = $job_open->thousand_to;
            $work_exp_from = $job_open->work_exp_from;
            $work_exp_to = $job_open->work_exp_to;
            $date_opened = $dateClass->changeYMDtoDMY($job_open->date_opened);
            $target_date = $dateClass->changeYMDtoDMY($job_open->target_date);
            $job_visible_users = JobVisibleUsers::where('job_id',$id)->get();
           
            $selected_users = array();
            if(isset($job_visible_users) && sizeof($job_visible_users)>0){
                foreach($job_visible_users as $row){
                    $selected_users[] = $row->user_id;
                }
            }

            $jobopen_model = new JobOpen();
            $upload_type = $jobopen_model->upload_type;

            $job_open['doc'] = JobOpenDoc::getJobDocByJobId($id);
            foreach ($job_open['doc'] as $key => $value) {
                if (array_search($value['category'], $upload_type)) {
                    unset($upload_type[array_search($value['category'], $upload_type)]);
                }
            }
            $upload_type['Others'] = 'Others';

            $job_open_checkbox = $job_open->job_open_checkbox;
            $adler_career_checkbox = $job_open->adler_career_checkbox;
            $adler_job_disclosed_checkbox = $job_open->adler_job_disclosed_checkbox;
        }
        else {
            return view('errors.403');
        }

        $action = "edit";

        // For job open or not after 48 hours

        $super_admin_user_id = getenv('SUPERADMINUSERID');
        $strategy_user_id = getenv('STRATEGYUSERID');
        $bhagyashree_user_id = getenv('BHAGYASHREEUSERID');
        $arjun_user_id = getenv('ARJUNUSERID');
        $tanisha_user_id = getenv('TANISHAUSERID');

        return view('adminlte::jobopen.edit', compact('user_id','action', 'industry', 'client', 'users', 'job_type','job_priorities', 'job_open', 'date_opened', 'target_date','selected_users','lacs','thousand','lacs_from','thousand_from','lacs_to','thousand_to','work_from','work_to','work_exp_from','work_exp_to','select_all_users','upload_type','client_hierarchy_name','year','loggedin_user_id','super_admin_user_id','strategy_user_id','bhagyashree_user_id','arjun_user_id','tanisha_user_id','job_open_checkbox','adler_career_checkbox','adler_job_disclosed_checkbox'));
    }

    public function update(Request $request, $id) {

        $dateClass = new Date();

        $user_id = \Auth::user()->id;
        $input = $request->all();

        if(isset($input['year']) && $input['year'] != '') {
            $year = $input['year'];
        }

        $max_id = JobOpen::find(\DB::table('job_openings')->max('id'));
        if (isset($max_id->id) && $max_id->id != '')
            $max_id = $max_id->id;
        else
            $max_id = 0;

        $posting_title = $input['posting_title'];
        $hiring_manager_id = $input['hiring_manager_id'];
        $industry_id = $input['industry_id'];
        $client_id = $input['client_id'];
        $no_of_positions = $input['no_of_positions'];
        $date_open = $input['date_opened'];
        $target_date = $input['target_date'];
        $job_type = $input['job_type'];
        $job_description = $input['job_description'];
        $city = $input['city'];
        $state = $input['state'];
        $country = $input['country'];
        $job_priority = $input['job_priority'];
        $users = $input['user_ids'];
        $desired_candidate = $input['desired_candidate'];
        $qualifications = $input['qualifications'];
        $lacs_from = $input['lacs_from'];
        $thousand_from = $input['thousand_from'];
        $lacs_to = $input['lacs_to'];
        $thousand_to = $input['thousand_to'];
        $work_exp_from = $input['work_experience_from'];
        $work_exp_to = $input['work_experience_to'];

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

        $level_id = $input['level_id'];

        $increment_id = $max_id + 1;
        $job_unique_id = "TT-JO-$increment_id";

        // For not open job afer 48 hours
        if(isset($input['job_open_checkbox']) && $input['job_open_checkbox'] != '') {
            $job_open_checkbox = '1';
        }
        else {
            $job_open_checkbox = '0';
        }

        // For display job in career page of adler website
        if(isset($input['adler_career_checkbox']) && $input['adler_career_checkbox'] != '') {
            $adler_career_checkbox = '1';
        }
        else {
            $adler_career_checkbox = '0';
        }

        // For display job salary in career page of adler website
        if(isset($input['adler_job_disclosed_checkbox']) && $input['adler_job_disclosed_checkbox'] != '') {
            $adler_job_disclosed_checkbox = '1';
        }
        else {
            $adler_job_disclosed_checkbox = '0';
        }

        $job_open = JobOpen::find($id);
        $job_open->posting_title = $posting_title;
        $job_open->hiring_manager_id = $hiring_manager_id;
        $job_open->industry_id = $industry_id;
        $job_open->client_id = $client_id;
        $job_open->no_of_positions = $no_of_positions;
        $job_open->date_opened = $dateClass->changeDMYtoYMD($date_open);
        $job_open->target_date = $dateClass->changeDMYtoYMD($target_date);
        $job_open->job_type = $job_type;
        $job_open->job_description = $job_description;
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
        $job_open->level_id = $level_id;
        $job_open->job_open_checkbox = $job_open_checkbox;
        $job_open->adler_career_checkbox = $adler_career_checkbox;
        $job_open->adler_job_disclosed_checkbox = $adler_job_disclosed_checkbox;

        $validator = \Validator::make(Input::all(),$job_open::$rules);

        if($validator->fails()){
            return redirect('jobs/'.$id.'/edit')->withInput(Input::all())->withErrors($validator->errors());
        }

        $job_open->save();
        $job_id = $job_open->id;

        // Delete Previous job visible users
        JobVisibleUsers::where('job_id',$job_id)->delete();

        if(isset($users) && sizeof($users) > 0) {

            foreach ($users as $key => $value) {

                $job_visible_users = new JobVisibleUsers();
                $job_visible_users->job_id = $job_id;
                $job_visible_users->user_id = $value;
                $job_visible_users->save();
            }
        }

        // Get job_visible_user count and check to all users then update open_to_all field

        $users_id = User::getAllUsers(NULL,'Yes');
        $user_count = sizeof($users_id);

        $job_users = sizeof($users);
        if ($job_users == $user_count) {
            //\DB::statement("UPDATE job_openings SET open_to_all = '1' where id=$job_id");
        }
        else {
            \DB::statement("UPDATE job_openings SET open_to_all = '0' where id=$job_id");
        }

        $upload_type = $request->upload_type;
        $file = $request->file('file');

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
            }
            else {

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
            return redirect('jobs/'.$id.'/edit')->with('success','Attachment Uploaded Successfully.');
        }

        if(isset($year) && $year != '') {
            return redirect()->route('jobopen.close')->with('success', 'Job Opening Updated Successfully.')->with('selected_year',$year);
        }
        else {
            return redirect()->route('jobopen.index')->with('success', 'Job Opening Updated Successfully.');
        }
    }

    public function jobClone($id) {

        $user = \Auth::user();
        $user_id = $user->id;
        $loggedin_user_id = $user->id;

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
        $lacs[''] = 'lacs';
        for($i=0;$i<=50;$i++){
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
        for($i=0;$i<=30;$i++){
            $work_from[$i] = $i;
        }

        //Work experience to dropdown
        $work_to = array('0'=>'Work Experience To');
        for($i=0;$i<=30;$i++){
            $work_to[$i] = $i;
        }

        $all_client_perm = $user->can('display-client');
        $userwise_client_perm = $user->can('display-account-manager-wise-client');

        $client = array();

        if($all_client_perm) {

            // get all clients
            $client_res = ClientBasicinfo::getLoggedInUserClients(0);
        }
        else if($userwise_client_perm) {

            // get logged in user clients
            $client_res = ClientBasicinfo::getLoggedInUserClients($user_id);
        }

        if (sizeof($client_res) > 0) {
            foreach ($client_res as $r) {
                $client[$r->id] = $r->name." - ".$r->coordinator_name." - ".$r->billing_city;
            }
        }

        // For account manager
        $users = User::getAllUsers(NULL,'Yes');
        $select_all_users = User::getAllUsers(NULL,'Yes');

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

        // Get visible users
        $job_visible_users = JobVisibleUsers::where('job_id',$id)->get();   
        $selected_users = array();

        if(isset($job_visible_users) && sizeof($job_visible_users)>0) {

            foreach($job_visible_users as $row) {
                $selected_users[] = $row->user_id;
            }
        }

        $jobopen_model = new JobOpen();
        $upload_type = $jobopen_model->upload_type;

        $job_open['doc'] = JobOpenDoc::getJobDocByJobId($id);

        foreach ($job_open['doc'] as $key => $value) {

            if (array_search($value['category'], $upload_type)) {
                unset($upload_type[array_search($value['category'], $upload_type)]);
            }
        }
        $upload_type['Others'] = 'Others';

        $job_open_checkbox = $job_open->job_open_checkbox;
        $adler_career_checkbox = $job_open->adler_career_checkbox;
        $adler_job_disclosed_checkbox = $job_open->adler_job_disclosed_checkbox;

        // job type
        $job_type = JobOpen::getJobTypes();

        // job priority
        $job_priorities = JobOpen::getJobPriorities();

        // get Client hierarchy names
        //$client_hierarchy_name = ClientHeirarchy::getAllClientHeirarchyName();
        $client_hierarchy_name = JobOpen::getAllHierarchyName();

        $action = "clone";

        // For job open or not after 48 hours

        $super_admin_user_id = getenv('SUPERADMINUSERID');
        $strategy_user_id = getenv('STRATEGYUSERID');
        $bhagyashree_user_id = getenv('BHAGYASHREEUSERID');
        $arjun_user_id = getenv('ARJUNUSERID');
        $tanisha_user_id = getenv('TANISHAUSERID');

        return view('adminlte::jobopen.create', compact('no_of_positions','posting_title','job_open','user_id','action', 'industry', 'client', 'users','job_type','job_priorities','selected_users','lacs','thousand','lacs_from','thousand_from','lacs_to','thousand_to','work_from','work_to','work_exp_from','work_exp_to','select_all_users','client_hierarchy_name','upload_type','loggedin_user_id','super_admin_user_id','strategy_user_id','bhagyashree_user_id','arjun_user_id','tanisha_user_id','job_open_checkbox','adler_career_checkbox','adler_job_disclosed_checkbox'));
    }

    public function clonestore(Request $request) {

        $dateClass = new Date();

        $user_id = \Auth::user()->id;
        $user_name = \Auth::user()->name;
        $input = $request->all();

        $max_id = JobOpen::find(\DB::table('job_openings')->max('id'));
        if (isset($max_id->id) && $max_id->id != '')
            $max_id = $max_id->id;
        else
            $max_id = 0;

        $posting_title = $input['posting_title'];
        $hiring_manager_id = $input['hiring_manager_id'];
        $job_priority = $input['job_priority'];
        $industry_id = $input['industry_id'];
        $client_id = $input['client_id'];
        $no_of_positions = $input['no_of_positions'];
        $date_open = $input['date_opened'];
        $target_date = $input['target_date'];
        $job_type = $input['job_type'];
        $job_description = $input['job_description'];
        $city = $input['city'];
        $state = $input['state'];
        $country = $input['country'];
        $job_show = 0;
        $users = $input['user_ids'];
        $desired_candidate = $input['desired_candidate'];
        $qualifications = $input['qualifications'];
        $lacs_from = $input['lacs_from'];
        $thousand_from = $input['thousand_from'];
        $lacs_to = $input['lacs_to'];
        $thousand_to = $input['thousand_to'];
        $work_exp_from = $input['work_experience_from'];
        $work_exp_to = $input['work_experience_to'];

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
        else {
            $open_to_all = date('Y-m-d H:i:s',strtotime("$date +2 days"));
        }

        $change_date = date('Y-m-d',strtotime("$open_to_all"));
        $fixed_date = Holidays::getFixedLeaveDate();

        if (in_array($change_date, $fixed_date)) {
            $open_to_all_day = date('l',strtotime("$open_to_all"));

            if ($open_to_all_day == 'Saturday') {
                $open_to_all = date('Y-m-d H:i:s',strtotime("$open_to_all +2 days"));
            }
            else {
                $open_to_all = date('Y-m-d H:i:s',strtotime("$open_to_all +1 days"));   
            }
        }

        $level_id = $input['level_id'];

        $increment_id = $max_id + 1;
        $job_unique_id = "TT-JO-$increment_id";

        // For not open job afer 48 hours
        if(isset($input['job_open_checkbox']) && $input['job_open_checkbox'] != '') {
            $job_open_checkbox = '1';
        }
        else {
            $job_open_checkbox = '0';
        }

        // For display job in career page of adler website
        if(isset($input['adler_career_checkbox']) && $input['adler_career_checkbox'] != '') {
            $adler_career_checkbox = '1';
        }
        else {
            $adler_career_checkbox = '0';
        }

        // For display job salary in career page of adler website
        if(isset($input['adler_job_disclosed_checkbox']) && $input['adler_job_disclosed_checkbox'] != '') {
            $adler_job_disclosed_checkbox = '1';
        }
        else {
            $adler_job_disclosed_checkbox = '0';
        }

        $job_open = new JobOpen();
        $job_open->job_id = $job_unique_id;
        $job_open->job_show = $job_show;
        $job_open->posting_title = $posting_title;
        $job_open->hiring_manager_id = $hiring_manager_id;
        $job_open->industry_id = $industry_id;
        $job_open->client_id = $client_id;
        $job_open->no_of_positions = $no_of_positions;
        $job_open->date_opened = $dateClass->changeDMYtoYMD($date_open);
        $job_open->target_date = $dateClass->changeDMYtoYMD($target_date);
        $job_open->job_type = $job_type;
        $job_open->job_description = $job_description;
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
        $job_open->level_id = $level_id;
        $job_open->job_open_checkbox = $job_open_checkbox;
        $job_open->adler_career_checkbox = $adler_career_checkbox;
        $job_open->adler_job_disclosed_checkbox = $adler_job_disclosed_checkbox;

        $validator = \Validator::make(Input::all(),$job_open::$rules);

        if($validator->fails()){
            return redirect('jobs/create')->withInput(Input::all())->withErrors($validator->errors());
        }

        $job_open->save();
        $job_id = $job_open->id;

        if (isset($job_id) && $job_id > 0) {

            if(isset($users) && sizeof($users)>0) {

                foreach ($users as $key=>$value) {

                    $job_visible_users = new JobVisibleUsers();
                    $job_visible_users->job_id = $job_id;
                    $job_visible_users->user_id = $value;
                    $job_visible_users->save();
                }
            }

            \DB::statement("UPDATE job_openings SET open_to_all = '0' where id=$job_id");

            // Attched Documents from old job

            $old_job_id = $request->input('job_id');
            $job_docs = JobOpenDoc::getJobDocByJobId($old_job_id);

            if(isset($job_docs) && sizeof($job_docs) > 0) {

                File::makeDirectory("uploads/jobs/$job_id", 0777, true);
                File::copyDirectory(public_path()."/uploads/jobs/".$old_job_id."/",public_path()."/uploads/jobs/".$job_id."/");

                foreach ($job_docs as $key => $value) {

                    $job_open_doc = new JobOpenDoc();
                    $job_open_doc->job_id = $job_id;
                    $job_open_doc->category = $value['category'];
                    $job_open_doc->file = "uploads/jobs/".$job_id."/".$value['name'];
                    $job_open_doc->name = $value['name'];
                    $job_open_doc->uploaded_by = $user_id;
                    $job_open_doc->size = $value['org_size'];
                    $job_open_doc->created_at = time();
                    $job_open_doc->updated_at = time();
                    $job_open_doc->save();
                }
            }

            //Notifications : On creating job openings : send notification to selected users that new job openings is added (except user who created jobopening), default send notificaations to admin user

            if(isset($users) && sizeof($users)>0) {

                $user_emails = array();
                $user_arr = array();

                foreach ($users as $key=> $value) {

                    if($user_id != $value) {

                        $module_id = $job_id;
                        $module = 'Job Openings';
                        $message = $user_name . " added new job";
                        $link = route('jobopen.show',$job_id);
                        $user_arr = trim($value);

                        event(new NotificationEvent($module_id, $module, $message, $link, $user_arr));

                        $email = User::getUserEmailById($value);
                        $user_emails[] = $email;
                    }
                }

                // Email Notification : data store in datebase
                $superadminuserid = getenv('SUPERADMINUSERID');
                $superadminsecondemail = User::getUserEmailById($superadminuserid);
                $loggedin_email = \Auth::user()->email;

                $cc_users_array = array($loggedin_email,$superadminsecondemail);

                $module = "Job Open";
                $sender_name = $user_id;
                $to = implode(",",$user_emails);
                $cc = implode(",",$cc_users_array);

                $client_name = ClientBasicinfo::getCompanyOfClientByID($client_id);
                $client_city = ClientBasicinfo::getBillingCityOfClientByID($client_id);
                //$level_nm = ClientHeirarchy::getClientHeirarchyNameById($level_id);
                        
                $subject = "Job Opening - " . $posting_title . "@" . $client_name . " - " . $client_city;

                $message = "<tr><th>" . $posting_title . "/" . $job_unique_id . "</th></tr>";
                $module_id = $job_id;

                event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
            }
        }
        return redirect()->route('jobopen.index')->with('success', 'Job Opening Created Successfully.');
    }

    public function destroy(Request $request, $id) {

        $year = $request->input('year');
        $title = $request->input('title');

        DB::table('job_associate_candidates')->where('job_id', '=', $id)->delete();
        DB::table('job_openings_doc')->where('job_id', '=', $id)->delete();
        DB::table('job_visible_users')->where('job_id', '=', $id)->delete();
        DB::table('job_candidate_joining_date')->where('job_id', '=', $id)->delete();

        // Delete from notifications table
        Notifications::where('module','=','Job Openings')->where('module_id','=',$id)->delete();

        JobOpen::where('id',$id)->delete();

        if(isset($year) && $year != '') {

            if(isset($title) && $title == 'Job Close') {
                return redirect()->route('jobopen.close')
                ->with('success', 'Closed Job Deleted Successfully.')->with('selected_year',$year);
            }
        }
        else if(isset($title) && $title == 'Job Open') {

            return redirect()->route('jobopen.index')->with('success', 'Job Opening Deleted Successfully.');
        }
        else if(isset($title) && $title == 'Applicant Job') {

            return redirect()->route('jobopen.applicant')->with('success', 'Job Opening Deleted Successfully.');
        }
    }

    public function upload(Request $request) {

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
            }
            else {

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
        return redirect()->route('jobopen.show', [$job_id])->with('success', 'Attachment Uploaded Successfully.');
    }

    public function attachmentsDestroy(Request $request,$docid) {

        $file_name = JobOpenDoc::where('id', $docid)->first();
        $delete_file_name = $file_name->file;

        if (isset($delete_file_name) && $delete_file_name != '') {
            unlink("$delete_file_name");
        }

        JobOpenDoc::where('id', $docid)->delete();

        $id = $_POST['id'];
        $type = $_POST['type'];

        if ($type == 'show') {
            return redirect()->route('jobopen.show', [$id])->with('success', 'Attachment Deleted Successfully.');
        }
        else if($type == 'edit') {
            return redirect()->route('jobopen.edit', [$id])->with('success', 'Attachment Deleted Successfully.');   
        }
    }

    public function associateCandidate($id) {

        // get job name from id
        $jobopen_response = JobOpen::where('id', $id)->first();
        $posting_title = $jobopen_response->posting_title;

        // get candidate ids already associated with job
        $candidate_response = JobAssociateCandidates::where('job_id', '=', $id)->get();
        $candidates = array();
        foreach ($candidate_response as $key => $value) {
            $candidates[] = $value->candidate_id;
        }

        $letter = 'Z';
        $letter_array = array();
        $range = range("A", "Z");
        foreach ($range as $key => $value) {
            $letter_array[$value] = $value;
        }

        return view('adminlte::jobopen.associate_candidate', array('job_id' => $id, 'posting_title' => $posting_title, 'message' => '','letter' => $letter,'letter_array' => $letter_array));
    }

    public function getAllAssociateCandidates() {

        $job_id = $_GET['job_id'];
        $initial_letter = $_GET['initial_letter'];

        $limit = $_GET['length'];
        $offset = $_GET['start'];
        $draw = $_GET['draw'];
        $search = $_GET['search']['value'];
        $order = $_GET['order'][0]['column'];
        $type = $_GET['order'][0]['dir'];

        // get candidate ids already associated with job
        $candidate_response = JobAssociateCandidates::where('job_id', '=', $job_id)->get();
        $candidates = array();
        foreach ($candidate_response as $key => $value) {
            $candidates[] = $value->candidate_id;
        }

        $response = CandidateBasicInfo::getAssociateCandidates($limit,$offset,$search,$initial_letter,$candidates);

        $candidate_details = array();
        $i = 0;$count=0;

        foreach ($response as $key => $value) {

            $checkbox = '';
            $checkbox .= '<input type=checkbox name=candidate value='.$value['id'].' class=others_cbs id='.$value['id'].'/>';

            $data = array($checkbox,$value['fname'],$value['owner'],$value['email']);
            $candidate_details[$i] = $data;
            $i++;
        }

        $json_data = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            "data" => $candidate_details
        );
        
        echo json_encode($json_data);exit;
    }

    public function postAssociateCandidates() {

        $user_id = \Auth::user()->id;
        $job_id = $_POST['jobid'];
        $candidate_ids = $_POST['candidate_ids'];
        $title = $_POST['title'];
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

            if($title == 'Applicant') {

                DB::statement("UPDATE candidate_otherinfo SET owner_id = '$user_id' where candidate_id=$value");
            }

            // Candidate Vacancy Details email
            $candidate_vacancy_details = CandidateBasicInfo::candidateAssociatedEmail($value,$user_id,$job_id);
        }

        /*$jobDetail = JobOpen::find($job_id);

        $hiring_manager_id = $jobDetail->hiring_manager_id;
        $job_show = $jobDetail->job_show;

        $authUserTeamId = TeamMates::where('user_id',$hiring_manager_id)->first();

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

        if($title == 'Applicant') {

            return redirect()->route('jobopen.applicant_candidates_get', [$job_id])->with('success', 'Candidate Associated Successfully.');
        }

        if($title == 'Associate') {
            return redirect()->route('jobopen.associate_candidate_get', [$job_id])->with('success', 'Candidate Associated Successfully.');
        }
    }

    public function associateCandidateCount() {

        $job_id = $_POST['jobid'];

        $count = JobAssociateCandidates::where('job_id', '=', $job_id)->count();

        $response['returnvalue'] = "valid";
        $response['count'] = $count;

        echo json_encode($response);
        exit;
    }

    public function associatedCandidates($id) {

        $user = \Auth::user();
        $user_id = $user->id;
        $all_jobs_perm = $user->can('display-jobs');

        // get job name from id
        $jobopen_response = JobOpen::where('id', $id)->first();
        $hiring_manager_id = $jobopen_response->hiring_manager_id;

        $access = false;
        
        if($all_jobs_perm) {
            $access = true;
        }
        else if ($hiring_manager_id == $user->id) {
            $access = true;
        }

        $client_id = $jobopen_response->client_id;
        $posting_title = $jobopen_response->posting_title;

        $candidateDetails = JobAssociateCandidates::getAssociatedCandidatesByJobId($id);

        // get candidate status
        /*$candidateresult = CandidateStatus::orderBy('name','asc')->select('id','name')->get()->toArray();
        foreach ($candidateresult as $key=>$value){
            $candidateStatus[$value['id']] = $value['name'];
        }*/

        $candidateStatus = CandidateStatus::getCandidateStatus();

        $shortlist_type = JobOpen::getShortlistType();

        $type = Interview::getTypeArray();
        $status = Interview::getCreateInterviewStatus();
        $users = User::getAllUsers();

        return view('adminlte::jobopen.associated_candidate', array('job_id' => $id, 'posting_title' => $posting_title,'message' => '','candidates'=>$candidateDetails ,'candidatestatus'=>$candidateStatus,'type'=>$type,'status' => $status,'users' => $users,'client_id'=>$client_id, 'shortlist_type'=>$shortlist_type,'access'=>$access,'user_id'=>$user_id));
    }

    public function deAssociateCandidates() {

        $user_id = \Auth::user()->id;
        $job_id = $_POST['jobid'];
        $candidate_id = $_POST['candidate_id'];

        JobAssociateCandidates::where('candidate_id',$candidate_id)->where('job_id',$job_id)
        ->forceDelete();

        // TODO :: DeAssociating Candidate : send notification to team/all (except user who deassociate Candidate). default send notificaations to admin user.

        /* $jobDetail = JobOpen::find($job_id);

        $hiring_manager_id = $jobDetail->hiring_manager_id;
        $job_show = $jobDetail->job_show;

        $authUserTeamId = TeamMates::where('user_id',$hiring_manager_id)->first();

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

        return redirect()->route('jobopen.associated_candidates_get', [$job_id])->with('success', 'Candidate Deassociated Successfully.');
    }

    public function updateCandidateStatus() {

        $user_id = \Auth::user()->id;
        $job_id = $_POST['jobid'];
        $candidate_id = $_POST['candidate_id'];
        $status_id = $_POST['status_id'];

        $today_date = date('Y-m-d');

        $response = JobAssociateCandidates::where('candidate_id',$candidate_id)
        ->where('job_id',$job_id)->first();

        if($status_id == '1') {

            if($response->shortlisted == '0') {

                DB::statement("UPDATE job_associate_candidates SET shortlisted = '1' where candidate_id in ($candidate_id) and job_id = $job_id");
            }
            else if($response->shortlisted == '1') {

                DB::statement("UPDATE job_associate_candidates SET shortlisted = '2' where candidate_id in ($candidate_id) and job_id = $job_id");
            }
            else if($response->shortlisted == '2') {

                DB::statement("UPDATE job_associate_candidates SET shortlisted = '3' where candidate_id in ($candidate_id) and job_id = $job_id");
            }
        }
        else if ($status_id == '2') {

            DB::statement("UPDATE job_associate_candidates SET shortlisted = '2' where candidate_id in ($candidate_id) and job_id = $job_id");
        }
        else if ($status_id == '3') {

            DB::statement("UPDATE job_associate_candidates SET shortlisted = '3' where candidate_id in ($candidate_id) and job_id = $job_id");

            // Update selected date

            DB::statement("UPDATE job_associate_candidates SET selected_date = '$today_date' where candidate_id in ($candidate_id) and job_id = $job_id");
        }
        
        DB::statement("UPDATE job_associate_candidates SET status_id = '$status_id' where  candidate_id = $candidate_id and job_id = $job_id");

        // Update shortlisted date
        DB::statement("UPDATE job_associate_candidates SET shortlisted_date = '$today_date' where candidate_id in ($candidate_id) and job_id = $job_id");
        

        //DB::statement("UPDATE  candidate_otherinfo SET status_id =$status_id where candidate_id = $candidate_id");

        /*$jobDetail = JobOpen::find($job_id);

        $hiring_manager_id = $jobDetail->hiring_manager_id;
        $job_show = $jobDetail->job_show;

        $authUserTeamId = TeamMates::where('user_id',$hiring_manager_id)->first();

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

        if ($status_id == '2') {

            return redirect()->route('jobopen.associated_candidates_get', [$job_id])
            ->with('success','Candidates Shortlisted & Scheduled Interview.')
            ->with('candidate_id',$candidate_id);
        }
        else {

            return redirect()->route('jobopen.associated_candidates_get', [$job_id])->with('success', 'Candidate Status Update Successfully.');
        }
    }

    public function scheduleInterview(Request $request) {

        $user_id = \Auth::user()->id;

        $today_date = date('Y-m-d');
        $job_id = $request->get('job_id');

        $data = array();
        $dateClass = new Date();

        $data['interviewer_id'] = $request->get('interviewer_id');
        $data['interview_date'] = $dateClass->changeDMYHMStoYMDHMS($request->get('interview_date'));
        $data['location'] = $request->get('location');
        $data['comments'] = '';
        $data['posting_title'] =  $request->get('job_id');
        $data['type'] = $request->get('type');
        $data['about'] = '';
        $data['status'] = $request->get('status');
        $data['interview_owner_id'] = $user_id;
        $data['skype_id'] = $request->get('skype_id');
        $data['round'] = '1';
        $data['candidate_location'] = $request->get('candidate_location');
        $data['interview_location'] = $request->get('interview_location');

        // For single candidate
        if($request->get('candidate_id') != '') {

            $candidate_id = $request->get('candidate_id');
            $data['candidate_id'] = $candidate_id;
            $interview = Interview::createInterview($data);
            $interview->save();

            $interview_id = $interview->id;
            $posting_title = $request->get('job_id');

            $candidate_mail = Interview::getCandidateEmail($user_id,$candidate_id,$posting_title,$interview_id);
            $scheduled_mail = Interview::getScheduleEmail($candidate_id,$posting_title,$interview_id);

            // Update single candidate status
            $response = JobAssociateCandidates::where('candidate_id',$candidate_id)->where('job_id',$job_id)->first();

            if($response->shortlisted == '0') {

                DB::statement("UPDATE job_associate_candidates SET shortlisted = '1' where candidate_id in ($candidate_id) and job_id = $job_id");
            }
            else if($response->shortlisted == '1') {

                DB::statement("UPDATE job_associate_candidates SET shortlisted = '2' where candidate_id in ($candidate_id) and job_id = $job_id");
            }
            else if($response->shortlisted == '2') {

                DB::statement("UPDATE job_associate_candidates SET shortlisted = '3' where candidate_id in ($candidate_id) and job_id = $job_id");
            }
              
            DB::statement("UPDATE job_associate_candidates SET status_id = '2' where candidate_id = $candidate_id and job_id = $job_id");
            
            // Update shortlisted date
            DB::statement("UPDATE job_associate_candidates SET shortlisted_date = '$today_date' where candidate_id in ($candidate_id) and job_id = $job_id");
        }
        else if($request->get('hid_can') != '') {

            $candidate_id = $request->get('hid_can');
            $data['candidate_id'] = $candidate_id;
            $interview = Interview::createInterview($data);
            $interview->save();

            $interview_id = $interview->id;
            $posting_title = $request->get('job_id');

            $candidate_mail = Interview::getCandidateEmail($user_id,$candidate_id,$posting_title,$interview_id);
            $scheduled_mail = Interview::getScheduleEmail($candidate_id,$posting_title,$interview_id);

            // Update single candidate status

            $response = JobAssociateCandidates::where('candidate_id',$candidate_id)->where('job_id',$job_id)->first();

            if($response->shortlisted == '0') {

                DB::statement("UPDATE job_associate_candidates SET shortlisted = '1' where candidate_id in ($candidate_id) and job_id = $job_id");
            }
            else if($response->shortlisted == '1') {

                DB::statement("UPDATE job_associate_candidates SET shortlisted = '2' where candidate_id in ($candidate_id) and job_id = $job_id");
            }
            else if($response->shortlisted == '2') {

                DB::statement("UPDATE job_associate_candidates SET shortlisted = '3' where candidate_id in ($candidate_id) and job_id = $job_id");
            }
              
            DB::statement("UPDATE job_associate_candidates SET status_id = '2' where candidate_id = $candidate_id and job_id = $job_id");
            
            // Update shortlisted date

            DB::statement("UPDATE job_associate_candidates SET shortlisted_date = '$today_date' where candidate_id in ($candidate_id) and job_id = $job_id");
        }

        // For single candidate End

        // For multiple candidate
        else {
        
            $all_can_ids_interview = $_POST['all_can_ids_interview'];
            $ids = explode(",", $all_can_ids_interview);

            if(isset($ids) && sizeof($ids) > 0) {

                foreach ($ids as $key => $value) {
                    
                    $data['candidate_id'] = $value;
                    $interview = Interview::createInterview($data);
                    $interview->save();

                    $interview_id = $interview->id;
                    $job_id = $request->get('job_id');

                    $candidate_mail = Interview::getCandidateEmail($user_id,$value,$job_id,$interview_id);
                    $scheduled_mail = Interview::getScheduleEmail($value,$job_id,$interview_id);

                    // Update single candidate status

                    $response = JobAssociateCandidates::where('candidate_id',$value)->where('job_id',$job_id)->first();

                    if($response->shortlisted == '0') {

                        DB::statement("UPDATE job_associate_candidates SET shortlisted = '1' where candidate_id in ($value) and job_id = $job_id");
                    }
                    else if($response->shortlisted == '1') {

                        DB::statement("UPDATE job_associate_candidates SET shortlisted = '2' where candidate_id in ($value) and job_id = $job_id");
                    }
                    else if($response->shortlisted == '2') {

                        DB::statement("UPDATE job_associate_candidates SET shortlisted = '3' where candidate_id in ($value) and job_id = $job_id");
                    }
                      
                    DB::statement("UPDATE job_associate_candidates SET status_id = '2' where candidate_id = $value and job_id = $job_id");
                
                    // Update shortlisted date
                    DB::statement("UPDATE job_associate_candidates SET shortlisted_date = '$today_date' where candidate_id in ($value) and job_id = $job_id");
                }
            }
        }
        // For multiple candidate End

        return redirect('jobs/'.$job_id.'/associated_candidates')->with('success','Interview Scheduled Successfully.');
    }

    public function addJoiningDate() {

        $jobid = $_POST['jobid'];
        $candidate_id = $_POST['candidate_id'];
        $dateClass = new Date();

        // First check joining date already added den update date else add joining date
        $id = JobCandidateJoiningdate::checkJoiningDateAdded($jobid,$candidate_id);

        if($id > 0) {

            $jobCandidateJoiningDate = JobCandidateJoiningdate::find($id);
            $jobCandidateJoiningDate->joining_date = $dateClass->changeDMYtoYMD($_POST['joining_date']);;
            $jobCandidateJoiningDate->save();
        }
        else {

            $jobCandidateJoiningDate = new JobCandidateJoiningdate;
            $jobCandidateJoiningDate->job_id = $jobid;
            $jobCandidateJoiningDate->joining_date = $dateClass->changeDMYtoYMD($_POST['joining_date']);
            $jobCandidateJoiningDate->candidate_id = $candidate_id;
            $jobCandidateJoiningDate->save();
        }

        return redirect('jobs/'.$_POST['jobid'].'/associated_candidates')->with('success','Joining Date Added Successfully.');
    }

    public function shortlisted(Request $request,$job_id) {

        $input = $request->all();

        $shortlist = $input['shortlist_type'];
        $candidate_id = $input['job_candidate_id'];

        DB::statement("UPDATE job_associate_candidates SET shortlisted = $shortlist where candidate_id = $candidate_id and job_id = $job_id");

        return redirect()->route('jobopen.associated_candidates_get', [$job_id])->with('success','Candidate Shortlisted Successfully.');
    }

    public function undoshortlisted(Request $request,$job_id) {

        $input = $request->all();

        $undoshortlist = $input['undoshortlisted'];
        $candidate_id = $input['job_undo_candidate_id'];

        DB::statement("UPDATE job_associate_candidates SET shortlisted = $undoshortlist where candidate_id = $candidate_id and job_id = $job_id");

        DB::statement("UPDATE job_associate_candidates SET status_id = '4' where candidate_id = $candidate_id and job_id = $job_id");

        DB::statement("UPDATE job_associate_candidates SET shortlisted_date = NULL, selected_date = NULL where candidate_id = $candidate_id and job_id = $job_id");

        return redirect()->route('jobopen.associated_candidates_get', [$job_id])->with('success','Undo Shortlisted Candidate Successfully.');
    }

    public function moreOptions(Request $request) {

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

        if($response) {
            return redirect()->route('jobopen.show', [$job_id])->with('success', 'Job Opening additional Information Added Successfully.');
        }
        else {
            return redirect()->route('jobopen.show', [$job_id])->with('success', 'Error while updating data.');
        }
    }

    public function status(Request $request) {

        $priority = $request->get('job_priority');
        $job_id = $request->get('job_id');
        $display_name = $request->get('display_name');

        // Get Selected Year
        $year = $request->get('year');

        $job_open = JobOpen::find($job_id);

        if (isset($priority) && $priority != '') {
            $job_open->priority = $priority;
            $job_open->save();
        }
    
        if(isset($year) && $year != '') {

            if ($display_name == 'Job Close') {
                return redirect()->route('jobopen.close')
                ->with('success', 'Job Priority Updated Successfully.')->with('selected_year',$year);
            }
            else if ($display_name == 'Job Open') {
                return redirect()->route('jobopen.index')
                ->with('success', 'Job Priority Updated Successfully.')->with('selected_year',$year);
            }
            else if($display_name == 'Applicant Job') {
                return redirect()->route('jobopen.applicant')
                ->with('success', 'Job Priority Updated Successfully.')->with('selected_year',$year);
            }
        }
        else {

            if ($display_name == 'Job Close') {
                return redirect()->route('jobopen.close')->with('success', 'Job Priority Updated Successfully.');
            }
            else if ($display_name == 'Job Open') {
                return redirect()->route('jobopen.index')->with('success', 'Job Priority Updated Successfully.');
            }
            else if($display_name == 'Applicant Job') {
                return redirect()->route('jobopen.applicant')
                ->with('success', 'Job Priority Updated Successfully.');
            }
        }
    }

    public function close(Request $request) {

        $dateClass = new Date();

        $client_id = $request->client_id;
        $job_open_id = $request->job_id;
        $posting_title_id = $request->posting_title;
        $city_id = $request->city;

        // logged in user with role 'Administrator,Director,Manager can see all the open jobs
        // Rest other users can only see the jobs assigned to them

        $user = \Auth::user();
        $user_id = $user->id;

        $all_jobs_perm = $user->can('display-closed-jobs');
        $user_jobs_perm = $user->can('display-closed-jobs-by-loggedin-user');

        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);
        $user_obj = new User();
        $isClient = $user_obj::isClient($role_id);

        // for get client id by email
        $user_email = $user->email;
        $client_id = ClientBasicinfo::getClientIdByEmail($user_email);;

        // Year Data
        $starting_year = '2017';
        $ending_year = date('Y',strtotime('+1 year'));
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
            else{
                $n = $y-1;
                $year = $n.'-4, '.$y.'-3';
            }
        }

        $year_data = explode(", ", $year);
        $year1 = $year_data[0];
        $year2 = $year_data[1]; 
        $current_year = date('Y-m-d h:i:s',strtotime("first day of $year1"));
        $next_year = date('Y-m-d h:i:s',strtotime("last day of $year2"));

        if($all_jobs_perm) {
            $job_response = JobOpen::getClosedJobs(1,$user_id,0,0,0,NULL,'DESC',$current_year,$next_year);
            $job_priority_data = JobOpen::getPriorityWiseJobs(1,$user_id,NULL,$current_year,$next_year);
        }
        else if ($isClient) {

            $job_response = JobOpen::getClosedJobsByClient($client_id,0,0,0,NULL,'DESC',$current_year,$next_year);
            $job_priority_data = JobOpen::getPriorityWiseJobsByClient($client_id,NULL,$current_year,$next_year,0);
        }
        else if ($user_jobs_perm) {
            $job_response = JobOpen::getClosedJobs(0,$user_id,0,0,0,NULL,'DESC',$current_year,$next_year);
            $job_priority_data = JobOpen::getPriorityWiseJobs(0,$user_id,NULL,$current_year,$next_year);
        }

        $count = sizeof($job_response);

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

        $close_priority = array();
        $close_priority['priority_4'] = $priority_4++;
        $close_priority['priority_9'] = $priority_9++;
        $close_priority['priority_10'] = $priority_10++;

        $viewVariable = array();
        $viewVariable['jobList'] = $job_response;
        $viewVariable['job_priority'] = JobOpen::getJobPriorities();
        $viewVariable['count'] = $count;
        $viewVariable['close_priority'] = $close_priority;
        $viewVariable['year_array'] = $year_array;
        $viewVariable['year'] = $year;

        return view('adminlte::jobopen.close',$viewVariable);   
    }

    public function getAllCloseJobDetails() {

        $limit = $_GET['length'];
        $offset = $_GET['start'];
        $draw = $_GET['draw'];
        $search = $_GET['search']['value'];
        $order = $_GET['order'][0]['column'];
        $type = $_GET['order'][0]['dir'];
        $year = $_GET['year'];

        $year_data = explode(", ", $year);
        $year1 = $year_data[0];
        $year2 = $year_data[1];
        $current_year = date('Y-m-d h:i:s',strtotime("first day of $year1"));
        $next_year = date('Y-m-d h:i:s',strtotime("last day of $year2"));
        
        $order_column_name = self::getJobOrderColumnName($order);

        $user = \Auth::user();
        $user_id = $user->id;

        $all_jobs_perm = $user->can('display-closed-jobs');
        $user_jobs_perm = $user->can('display-closed-jobs-by-loggedin-user');
        $change_priority_perm = $user->can('change-job-priority');
        $clone_perm = $user->can('clone-job');
        $delete_perm = $user->can('job-delete');

        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);
        $user_obj = new User();
        $isClient = $user_obj::isClient($role_id);

        // for get client id by email
        $user_email = $user->email;
        $client_id = ClientBasicinfo::getClientIdByEmail($user_email);

        if($all_jobs_perm) {

            $job_response = JobOpen::getClosedJobs(1,$user_id,$limit,$offset,$search,$order_column_name,$type,$current_year,$next_year);
            $count = JobOpen::getAllClosedJobsCount(1,$user_id,$search,$current_year,$next_year);
            $job_priority_data = JobOpen::getPriorityWiseJobs(1,$user_id,NULL,$current_year,$next_year);
        }
        else if ($isClient) {

            $job_response = JobOpen::getClosedJobsByClient($client_id,$limit,$offset,$search,$order_column_name,$type,$current_year,$next_year);
            $count = sizeof($job_response);
            $job_priority_data = JobOpen::getPriorityWiseJobsByClient($client_id,NULL,$current_year,$next_year,0);
        }
        else if ($user_jobs_perm) {

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
        
        $close_priority = array();
        $close_priority['priority_4'] = $priority_4++;
        $close_priority['priority_9'] = $priority_9++;
        $close_priority['priority_10'] = $priority_10++;

        $job_priority = JobOpen::getJobPriorities();
        $jobs = array();
        $i = 0;$j = 0;

        foreach ($job_response as $key => $value) {

            $action = '';

            $action .= '<a title="Show"  class="fa fa-circle" href="'.route('jobopen.show',$value['id']).'" style="margin:3px;"></a>';

            if(isset($value['access']) && $value['access'] == 1) {
                $action .= '<a title="Edit" class="fa fa-edit" href="'.route('jobclose.edit',['id' => $value['id'],'year' => $year]).'" style="margin:3px;"></a>';
        
                if($change_priority_perm) {
                    $status_view = \View::make('adminlte::partials.jobstatus',['data' => $value, 'name' => 'jobopen', 'display_name'=>'Job Close', 'job_priority' => $job_priority,'year' => $year]);
                    $status = $status_view->render();
                    $action .= $status;
                }
            }
            if ($delete_perm) {
                $delete_view = \View::make('adminlte::partials.jobdelete',['data' => $value, 'name' => 'jobopen', 'display_name'=>'Job','year' => $year,'title' => 'Job Close']);
                $delete = $delete_view->render();
                $action .= $delete;
            }
            if(isset($value['access']) && $value['access']==1){

                if($clone_perm) {
                    $action .= '<a title="Clone Job"  class="fa fa-clone" href="'.route('jobopen.clone',$value['id']).'"></a>';
                }
            }

            $managed_by = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['am_name'].'</a>';
            $company_name = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['display_name'].'</a>';
            $posting_title = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['posting_title'].'</a>';
            $qual = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['qual'].'</a>';

            if ($isClient) {
                $associated_count = '<a title="Show Candidates Details" href="'.route('jobopen.candidates_details_get',$value['id']).'">'.$value['associate_candidate_cnt'].'</a>';
            }
            else {
                $associated_count = '<a title="Show Associated Candidates" href="'.route('jobopen.associated_candidates_get',$value['id']).'">'.$value['associate_candidate_cnt'].'</a>';
            }

            $data = array(++$j,$action,$job_priority[$value['priority']],$managed_by,$company_name,$posting_title,$associated_count,$value['city'],$value['min_ctc'],$value['max_ctc'],$value['created_date'],$value['no_of_positions'],$qual,$value['coordinator_name'],$value['industry'],$value['desired_candidate'],$value['priority']);
            $jobs[$i] = $data;
            $i++;
        }

        $json_data = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            'close_priority' => $close_priority,
            'year' => $year,
            'job_priority' => $job_priority,
            "data" => $jobs
        );

        echo json_encode($json_data);exit;
    }

    public function importExport() {
        return view('adminlte::jobopen.import');
    }

    public function importExcel(Request $request) {

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

    public function getAssociatedcandidates() {

        $job_id = $_GET['job_id'];
        $associated_candidates = JobAssociateCandidates::getAssociatedCandidatesByJobId($job_id);

        $response = array();
        $response['returnvalue'] = 'invalid';
        $response['data'] = array();

        $i = 1;
        if(isset($associated_candidates) && sizeof($associated_candidates)>0) {
            $response['returnvalue'] = 'valid';
            $response['data'][0]['value'] = 'Select';

            foreach ($associated_candidates as $k => $v) {

                $response['data'][$i]['id'] = $v->id;
                $response['data'][$i]['value'] = $v->fname;
                $i++;
            }
        }
        echo json_encode($response);exit;
    }

    public function associatedCVS($month,$year) {

        $user = \Auth::user();
        $user_id = $user->id;

        $all_jobs_perm = $user->can('display-jobs');
        $user_jobs_perm = $user->can('display-jobs-by-loggedin-user');

        if($all_jobs_perm) {
            $response = JobAssociateCandidates::getAssociatedCvsByUseridMonthWise(0,$month,$year);
            $count = sizeof($response);
        }
        else if($user_jobs_perm) {
            $response = JobAssociateCandidates::getAssociatedCvsByUseridMonthWise($user_id,$month,$year);
            $count = sizeof($response);
        }
        else {
            $response = '';
            $count = '';
        }  

        $short_month_name = date("M", mktime(0, 0, 0, $month, 10)); 
        return view ('adminlte::jobopen.associatedcvs',compact('response','count','short_month_name','year'));
    }

    public function checkJobId() {

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
        else {
            $msg['err'] = '<b>Please select job</b>';
            $msg['msg'] = "fail";
        }
        return $msg;
    }

    public  function MultipleJobPriority() {

        $job_ids = $_POST['job_ids'];
        $priority = $_POST['priority'];
        $updated_at = date('Y-m-d H:i:s');
        
        $job_ids_array = explode(",", $job_ids);

        foreach ($job_ids_array as $key => $value) {

            DB::statement("UPDATE job_openings SET priority = '$priority', updated_at='$updated_at' where id=$value");
        }
        return redirect()->route('jobopen.index')->with('success', 'Job Priority Updated Successfully.');
    }

    // For check wherther associated candidate selected for mail or not
    public function CheckIds() {

        if (isset($_POST['can_ids']) && $_POST['can_ids'] != '') {
            $can_ids = $_POST['can_ids'];
        }

        if (isset($can_ids) && sizeof($can_ids) > 0) {

            $html = '';
            $html .= '<h3>Are you sure want send mail ?</h3>';

            $msg['success'] = 'success';
            $msg['mail'] = $html;
        }
        else {
            $msg['err'] = '<b>Please select Candidate</b>';
            $msg['msg'] = "fail";
        }
        return $msg;
    }

    // Users get for send mail
    public function UsersforSendMail() {

        if (isset($_POST['job_id']) && $_POST['job_id'] != '') {
            $job_id = $_POST['job_id'];
        }

        $users = JobVisibleUsers::getAllUsersByJobId($job_id);

        $view = \View::make('adminlte::partials.jobvisibleuserforassociatemail',['users' => $users]);
        $html = $view->render();

        $msg['success'] = 'success';
        $msg['mail'] = $html;

        return $msg;
    }

    // For send mail of associated candidate
    public function AssociatedCandidateMail() {

        $can_ids = $_POST['can_ids'];
        $posting_title = $_POST['posting_title'];
        $job_id = $_POST['job_id'];
        $user_ids = $_POST['user_ids'];

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
    public function getCandidateDetailsByJob($id) {

        $candidate_details = JobAssociateCandidates::getAssociatedCandidatesDetailsByJobId($id);
        $name = 'Associated';
        
        return view('adminlte::jobopen.candidatedetails',compact('candidate_details','name'));
    }

    // For check wherther associated candidate selected for shortlist or not
    public function CheckCandidateIds() {

        if (isset($_POST['can_ids']) && $_POST['can_ids'] != '') {
            $can_ids = $_POST['can_ids'];
        }

        if (isset($can_ids) && sizeof($can_ids) > 0) {
            $msg['success'] = 'success';
        }
        else{
            $msg['err'] = '<b>Please Select Candidate.</b>';
            $msg['msg'] = "fail";
        }
        return $msg;
    }

    // For shortlist associated candidate
    public function shortlistedCandidates(Request $request) {

        $input = $request->all();
        /*$shortlist = $input['shortlist_type'];

        $all_can_ids = $_POST['all_can_ids'];
        $ids = explode(",", $all_can_ids);

        $job_id = $_POST['job_id'];

        foreach ($ids as $key => $value)
        {
            DB::statement("UPDATE job_associate_candidates SET shortlisted = $shortlist where candidate_id = $value and job_id = $job_id");
        }

        return redirect()->route('jobopen.associated_candidates_get', [$job_id])->with('success','Candidates Shortlisted Successfully.');*/
        

        $update_status_id = $input['update_status_id'];
        $job_id = $_POST['job_id'];

        $all_can_ids = $_POST['all_can_ids'];
        $ids = explode(",", $all_can_ids);

        $today_date = date('Y-m-d');

        foreach ($ids as $key => $value) {
            
            $response = JobAssociateCandidates::where('candidate_id',$value)->where('job_id',$job_id)
            ->first();

            if($update_status_id == '1') {

                if($response->shortlisted == '0') {

                    DB::statement("UPDATE job_associate_candidates SET shortlisted = '1' where candidate_id in ($value) and job_id = $job_id");
                }
                else if($response->shortlisted == '1') {

                    DB::statement("UPDATE job_associate_candidates SET shortlisted = '2' where candidate_id in ($value) and job_id = $job_id");
                }
                else if($response->shortlisted == '2') {

                    DB::statement("UPDATE job_associate_candidates SET shortlisted = '3' where candidate_id in ($value) and job_id = $job_id");
                }
            }
            else if ($update_status_id == '2') {

                DB::statement("UPDATE job_associate_candidates SET shortlisted = '2' where candidate_id in ($value) and job_id = $job_id");
            }
            else if ($update_status_id == '3') {

                DB::statement("UPDATE job_associate_candidates SET shortlisted = '3' where candidate_id in ($value) and job_id = $job_id");

                // Update selected date
            
                DB::statement("UPDATE job_associate_candidates SET selected_date = '$today_date' where candidate_id in ($value) and job_id = $job_id");
            }

            DB::statement("UPDATE job_associate_candidates SET status_id = '$update_status_id' where candidate_id in ($value) and job_id = $job_id");

            // Update shortlisted date
            DB::statement("UPDATE job_associate_candidates SET shortlisted_date = '$today_date' where candidate_id in ($value) and job_id = $job_id");
        }

        if ($update_status_id == '2') {

            return redirect()->route('jobopen.associated_candidates_get', [$job_id])
            ->with('success','Candidates Shortlisted & Scheduled Interview.')
            ->with('candidate_id',$value);
        }
        else {
            return redirect()->route('jobopen.associated_candidates_get', [$job_id])->with('success','Candidates Status Updated Successfully.');
        }
    }

    public function getAllPositionsJobs() {

        $count = JobOpen::getAllJobsPostingTitleCount();

        return view('adminlte::jobopen.alljobs', compact('count'));
    }

    public function getOrderColumnName($order) {

        $order_column_name = '';
        if (isset($order) && $order >= 0) {
            if ($order == 0) {
                $order_column_name = "job_openings.id";
            }
            else if ($order == 1) {
                $order_column_name = "job_openings.posting_title";
            }
            else if ($order == 2) {
                $order_column_name = "job_openings.city";
            }
            else if ($order == 3) {
                $order_column_name = "count";
            }
        }
        return $order_column_name;
    }

    public function getAllPositionsJobsByAJAX() {

        $draw = $_GET['draw'];
        $limit = $_GET['length'];
        $offset = $_GET['start'];
        $search = $_GET['search']['value'];
        $order = $_GET['order'][0]['column'];
        $type = $_GET['order'][0]['dir'];

        $order_column_name = self::getOrderColumnName($order);
        $jobList = JobOpen::getAllJobsPostingTitle($limit,$offset,$search,$order_column_name,$type);
        $count = JobOpen::getAllJobsPostingTitleCount($search);

        $job_data = array();
        $i = 0; $j = 0;
        $total_order = 0;

        foreach ($jobList as $key => $value) {
         
            $associated_cvs_count = '<center><a title="Show Associated Candidates" href="'.route('alljobs.associated_candidates_get',$value['id']).'">'.$value['associate_candidate_cnt'].'</a></center>';

            $total_order = $total_order + $value['associate_candidate_cnt'];

            $data = array(++$j,$value['posting_title'],$value['city'],$associated_cvs_count);

            $job_data[$i] = $data;
            $i++;
        }

        $json_data = array(
            'draw' => intval($draw),
            'recordsTotal' => intval($count),
            'recordsFiltered' => intval($count),
            "data" => $job_data,
            "total" => number_format($total_order)
        );

        echo json_encode($json_data);exit;
    }

    public function getAllJobsAssociatedCandidates($id) {

        $candidateDetails = JobAssociateCandidates::getAssociatedCandidatesByJobId($id);
        $count = sizeof($candidateDetails);

        $job_details = JobOpen::getJobById($id);

        $posting_title = $job_details['new_posting_title'];

        return view('adminlte::jobopen.alljobs_associated_candidate',compact('candidateDetails','posting_title','count'));
    }

    public function shortlistedCVS($month,$year) {

        $user = \Auth::user();
        $user_id = $user->id;

        $all_jobs_perm = $user->can('display-jobs');
        $user_jobs_perm = $user->can('display-jobs-by-loggedin-user');

        if($all_jobs_perm) {
            $response = JobAssociateCandidates::getShortlistedCvsByUseridMonthWise(0,$month,$year);
            $count = sizeof($response);
        }
        else if($user_jobs_perm) {
            $response = JobAssociateCandidates::getShortlistedCvsByUseridMonthWise($user_id,$month,$year);
            $count = sizeof($response);
        }
        else {
            $response = '';
            $count = '';
        }  

        $short_month_name = date("M", mktime(0, 0, 0, $month, 10)); 
        return view ('adminlte::jobopen.shortlistedcvs',compact('response','count','short_month_name','year'));
    }

    public function getClientInfos() {

        $client_id = $_GET['client_id'];

        $client = ClientBasicinfo::getClientDetailsById($client_id);

        $user = \Auth::user();
        $user_id = $user->id;

        if($user_id == $client['account_manager_id']) {

            $answer = "False";
        }
        else {

            $answer = "True";
        }

        $response['answer'] = $answer;
        $response['am_id'] = $client['account_manager_id'];
        $response['user_id'] = $user_id;

        echo json_encode($response);exit;
    }

    public function getAllAPIJobs() {

        $limit = $_GET['limit'];
        $offset = $_GET['offset'];

        $jobs_list = JobOpen::getAllAPIJobsDetails($limit,$offset);

        return response()->json(['data'=>$jobs_list]);

        //return json_encode($jobs_list);
    }

    public function getJobDetailsById($id) {

        $job_details = JobOpen::getAPIJobDetailsById($id);

        return response()->json(['data'=>$job_details]);
    }

    public function applicantCandidates($id) {

        $user = \Auth::user();
        $user_id = $user->id;

        // get job name from id
        $jobopen_response = JobOpen::where('id', $id)->first();

        $client_id = $jobopen_response->client_id;
        $posting_title = $jobopen_response->posting_title;

        $candidateDetails = CandidateOtherInfo::getApplicantCandidatesByJobId($id);

        return view('adminlte::jobopen.applicant_candidate', array('job_id' => $id, 'posting_title' => $posting_title,'candidates'=>$candidateDetails ,'client_id'=>$client_id,'user_id'=>$user_id));
    }

    public function applicant(Request $request) {

        $user = \Auth::user();
        $user_id = $user->id;
        $all_jobs_perm = $user->can('display-jobs');
        $user_jobs_perm = $user->can('display-jobs-by-loggedin-user');

        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);

        // for get client id by email
        $user_obj = new User();
        $isClient = $user_obj::isClient($role_id);
        $user_email = $user->email;
        $client_id = ClientBasicinfo::getClientIdByEmail($user_email);

        if($all_jobs_perm) {

            $count = JobOpen::getAllApplicantJobsCount(1,$user_id,NUll);
            $job_priority_data = JobOpen::getPriorityWiseApplicantJobs(1,$user_id,NULL);
        }
        else if ($isClient) {

            $job_response = JobOpen::getAllApplicantJobsByCLient($client_id,0,0,0,NULL,'');
            $count = sizeof($job_response);
            $job_priority_data = JobOpen::getPriorityWiseApplicantJobsByClient($client_id,NULL);
        }
        else if ($user_jobs_perm) {

            $count = JobOpen::getAllApplicantJobsCount(0,$user_id,NULL);
            $job_priority_data = JobOpen::getPriorityWiseApplicantJobs(0,$user_id,NULL);
        }

        $priority_0 = 0;
        $priority_1 = 0;
        $priority_2 = 0;
        $priority_3 = 0;
        $priority_5 = 0;
        $priority_6 = 0;
        $priority_7 = 0;
        $priority_8 = 0;

        foreach ($job_priority_data as $job_priority) {

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
        }

        
        $viewVariable = array();
        $viewVariable['job_priority'] = JobOpen::getJobPriorities();
        $viewVariable['count'] = $count;
        $viewVariable['isClient'] = $isClient;

        $viewVariable['priority_0'] = $priority_0;
        $viewVariable['priority_1'] = $priority_1;
        $viewVariable['priority_2'] = $priority_2;
        $viewVariable['priority_3'] = $priority_3;
        $viewVariable['priority_5'] = $priority_5;
        $viewVariable['priority_6'] = $priority_6;
        $viewVariable['priority_7'] = $priority_7;
        $viewVariable['priority_8'] = $priority_8;

        return view('adminlte::jobopen.applicant', $viewVariable);
    }

    public static function getApplicantJobOrderColumnName($order) {

        $order_column_name = '';

        if (isset($order) && $order >= 0) {

            if ($order == 0) {
                $order_column_name = "job_openings.id";
            }
            else if ($order == 3) {
                $order_column_name = "users.name";
            }
            else if ($order == 4) {
                $order_column_name = "client_basicinfo.display_name";
            }
            else if ($order == 5) {
                $order_column_name = "job_openings.posting_title";
            }
            else if ($order == 6) {
                $order_column_name = "count";
            }
            else if ($order == 7) {
                $order_column_name = "applicant_count";
            }
            else if ($order == 8) {
                $order_column_name = "job_openings.city";
            }
            else if ($order == 9) {
                $order_column_name = "job_openings.lacs_from";
            }
            else if($order == 10) {
                $order_column_name = "job_openings.lacs_to";
            }
            else if ($order == 11) {
                $order_column_name = "job_openings.created_at";
            }
            else if ($order == 12) {
                $order_column_name = "job_openings.updated_at";
            }
            else if ($order == 13) {
                $order_column_name = "job_openings.no_of_positions";
            }
            else if ($order == 14) {
                $order_column_name = "client_basicinfo.coordinator_name";
            }
        }
        return $order_column_name;
    }

    public function getAllApplicantJobsDetails() {

        $limit = $_GET['length'];
        $offset = $_GET['start'];
        $draw = $_GET['draw'];
        $search = $_GET['search']['value'];
        $order = $_GET['order'][0]['column'];
        $type = $_GET['order'][0]['dir'];

        $order_column_name = self::getApplicantJobOrderColumnName($order);

        $user = \Auth::user();
        $user_id = $user->id;

        $all_jobs_perm = $user->can('display-jobs');
        $user_jobs_perm = $user->can('display-jobs-by-loggedin-user');
        $change_priority_perm = $user->can('change-job-priority');
        $change_multiple_priority_perm = $user->can('update-multiple-jobs-priority');
        $clone_perm = $user->can('clone-job');
        $delete_perm = $user->can('job-delete');

        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);
        $user_obj = new User();
        $isClient = $user_obj::isClient($role_id);

        // for get client id by email
        $user_email = $user->email;
        $client_id = ClientBasicinfo::getClientIdByEmail($user_email);

        $job_priority = JobOpen::getJobPriorities();

        if($all_jobs_perm) {
            
            $job_response = JobOpen::getApplicantJobs(1,$user_id,$limit,$offset,$search,$order_column_name,$type);
            $count = JobOpen::getAllApplicantJobsCount(1,$user_id,$search);
            $job_priority_data = JobOpen::getPriorityWiseApplicantJobs(1,$user_id,NULL);
        }
        else if ($isClient) {

            $job_response = JobOpen::getAllApplicantJobsByCLient($client_id,$limit,$offset,$search,$order_column_name,$type);
            $count = sizeof($job_response);
            $job_priority_data = JobOpen::getPriorityWiseApplicantJobsByClient($client_id,NULL);
        }
        else if ($user_jobs_perm) {

            $job_response = JobOpen::getApplicantJobs(0,$user_id,$limit,$offset,$search,$order_column_name,$type);
            $count = JobOpen::getAllApplicantJobsCount(0,$user_id,$search);

            $job_priority_data = JobOpen::getPriorityWiseApplicantJobs(0,$user_id,NULL);
        }

        $jobs = array();
        $i = 0;$j = 0;

        foreach ($job_response as $key => $value) {

            $action = '';
            $checkbox = '';

            $action .= '<a title="Show"  class="fa fa-circle" href="'.route('jobopen.show',$value['id']).'" style="margin:3px;"></a>';

            if(isset($value['access']) && $value['access'] == 1) {

                $action .= '<a title="Edit" class="fa fa-edit" href="'.route('jobopen.edit',$value['id']).'" style="margin:3px;"></a>';

                if($change_priority_perm) {

                    $status_view = \View::make('adminlte::partials.jobstatus',['data' => $value, 'name' => 'jobopen', 'display_name'=>'Applicant Job','job_priority' => $job_priority]);
                    $status = $status_view->render();
                    $action .= $status;
                }
            }

            if ($delete_perm) {

                $delete_view = \View::make('adminlte::partials.jobdelete',['data' => $value, 'name' => 'jobopen', 'display_name'=>'Job','title' => 'Applicant Job']);
                $delete = $delete_view->render();
                $action .= $delete;
            }

            if(isset($value['access']) && $value['access'] == 1) {

                if($clone_perm) {
                    $action .= '<a title="Clone Job"  class="fa fa-clone" href="'.route('jobopen.clone',$value['id']).'"></a>';
                }

                if($change_multiple_priority_perm) {
                    $checkbox .= '<input type=checkbox name=job_ids value='.$value['id'].' class=multiple_jobs id='.$value['id'].'/>';
                }
                else {
                    $checkbox .= '';
                }
            }

            $managed_by = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['am_name'].'</a>';

            $company_name = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['display_name'].'</a>';

            $posting_title = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['posting_title'].'</a>';

            $qual = '<a style="white-space: pre-wrap; word-wrap: break-word; color:black; text-decoration:none;">'.$value['qual'].'</a>';

            if ($isClient) {

                $associated_count = '<a title="Show Candidates Details" href="'.route('jobopen.candidates_details_get',$value['id']).'">'.$value['associate_candidate_cnt'].'</a>';
                $applicant_count = '<a title="Show Applicant Candidates" href="'.route('applicantjobopen.candidates_details_get',$value['id']).'">'.$value['applicant_count'].'</a>';
            }
            else {

                $associated_count = '<a title="Show Associated Candidates" href="'.route('jobopen.associated_candidates_get',$value['id']).'">'.$value['associate_candidate_cnt'].'</a>';

                $applicant_count = '<a title="Show Applicant Candidates" href="'.route('jobopen.applicant_candidates_get',$value['id']).'">'.$value['applicant_count'].'</a>';
            }

            $data = array(++$j,$checkbox,$action,$managed_by,$company_name,$posting_title,$associated_count,$applicant_count,$value['city'],$value['min_ctc'],$value['max_ctc'],$value['created_date'],$value['updated_date'],$value['no_of_positions'],$qual,$value['coordinator_name'],$value['industry'],$value['desired_candidate'],$value['priority']);
            $jobs[$i] = $data;
            $i++;
        }

        $priority_0 = 0; $priority_1 = 0; $priority_2 = 0; $priority_3 = 0;
        $priority_5 = 0; $priority_6 = 0; $priority_7 = 0; $priority_8 = 0;

        foreach ($job_priority_data as $value) {

           if($value['priority'] == 0) {
                $priority_0++;
           }
           else if($value['priority'] == 1) {
                $priority_1++;
           }
            else if($value['priority'] == 2) {
                $priority_2++;
           }
            else if($value['priority'] == 3) {
                $priority_3++;
           }
            else if($value['priority'] == 5) {
                $priority_5++;
           }
            else if($value['priority'] == 6) {
                $priority_6++;
           }
            else if($value['priority'] == 7) {
                $priority_7++;
           }
            else if($value['priority'] == 8) {
                $priority_8++;
           }
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
            "data" => $jobs,
            "priority" => $priority,
            "job_priority" => $job_priority,
        );

        echo json_encode($json_data);exit;
    }

    // Function for associated candidates details by job for clinet login show page 
    public function getCandidateDetailsByApplicantJob($id) {

        $candidate_details = CandidateOtherInfo::getAssociatedCandidatesDetailsByApplicantJobId($id);
        $name = 'Applicant';
        
        return view('adminlte::jobopen.candidatedetails',compact('candidate_details','name'));
    }

    public function priorityWiseApplicant($priority) {

        $user = \Auth::user();
        $user_id = $user->id;

        $all_jobs_perm = $user->can('display-jobs');
        $user_jobs_perm = $user->can('display-jobs-by-loggedin-user');

        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);

        $user_obj = new User();
        $isClient = $user_obj::isClient($role_id);

        // for get client id by email
        $user_email = $user->email;
        $client_id = ClientBasicinfo::getClientIdByEmail($user_email);

        if($all_jobs_perm) {

            $job_response = JobOpen::getPriorityWiseApplicantJobs(1,$user_id,$priority);
        }
        else if($isClient) {

            $job_response = JobOpen::getPriorityWiseApplicantJobsByClient($client_id,$priority);
        }
        else if($user_jobs_perm) {

            $job_response = JobOpen::getPriorityWiseApplicantJobs(0,$user_id,$priority);
        }

        $count = sizeof($job_response);

        $viewVariable = array();
        $viewVariable['jobList'] = $job_response;
        $viewVariable['job_priority'] = JobOpen::getJobPriorities();
        $viewVariable['count'] = $count;
        $viewVariable['priority'] = $priority;
        $viewVariable['isClient'] = $isClient;

        return view('adminlte::jobopen.prioritywisejob', $viewVariable);
    }

    public function getJobDetailsBySearch() {

        $key_skill = $_GET['key_skill'];
        $desired_location = $_GET['desired_location'];
        $min_experience = $_GET['min_experience'];
        $max_experience = $_GET['max_experience'];
        $min_ctc = $_GET['min_ctc'];
        $max_ctc = $_GET['max_ctc'];
        $limit = $_GET['limit'];
        $offset = $_GET['offset'];

        $search_job_details = JobOpen::getJobDetailsBySearchArea($key_skill,$desired_location,$min_experience,$max_experience,$min_ctc,$max_ctc,$limit,$offset);

        return response()->json(['data'=>$search_job_details]);
    }
}