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
use DB;
use App\CandidateStatus;
use Illuminate\Queue\Jobs\Job;
use Illuminate\Support\Facades\Input;
use Excel;

class JobOpenController extends Controller
{
    public function index(Request $request){

        $dateClass = new Date();

        $client_id = $request->client_id;
        $job_open_id = $request->job_id;
        $posting_title_id = $request->posting_title;
        $job_opening_status_id = $request->job_opening_status;
        $city_id = $request->city;

        // logged in user with role 'Administrator,Director,Manager can see all the open jobs
        // Rest other users can only see the jobs assigned to them

        $user = \Auth::user();
        $user_id = $user->id;
        $user_role_id = User::getLoggedinUserRole($user);

        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $manager_role_id = env('MANAGER');
        $superadmin_role_id = env('SUPERADMIN');

        $access_roles_id = array($admin_role_id,$director_role_id,$manager_role_id,$superadmin_role_id);
        if(in_array($user_role_id,$access_roles_id)){
            $job_response = JobOpen::getAllJobs(1,$user_id);
        }
        else{
            $job_response = JobOpen::getAllJobs(0,$user_id);
        }

        $viewVariable = array();
        $viewVariable['jobList'] = $job_response;
        $viewVariable['job_status'] = JobOpen::getJobStatus();

        return view('adminlte::jobopen.index', $viewVariable);


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

        $user_role_id = User::getLoggedinUserRole($user);
        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $manager_role_id = env('MANAGER');
        $superadmin_role_id = env('SUPERADMIN');

        $access_roles_id = array($admin_role_id,$director_role_id,$manager_role_id,$superadmin_role_id);
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
         
        $users = User::getAllUsers('recruiter');
        //print_r($users);exit;
      //  $team_mates = $user_id;

        // job opening status
        $job_open_status = JobOpen::getJobOpenStatus();

        // job type
        $job_type = JobOpen::getJobTypes();

        // job priority
        $job_priorities = JobOpen::getJobPriorities();

        $action = "add";

        $super_admin_user_id = getenv('SUPERADMINUSERID');
        $selected_users = array($user_id,$super_admin_user_id);
        return view('adminlte::jobopen.create', compact('user_id','action', 'industry', 'client', 'users', 'job_open_status', 'job_type','job_priorities','selected_users'));

    }

    public function store(Request $request)
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
        //$target_date = $input['target_date'];
        // $formatted_target_date = Carbon::parse($target_date)->format('Y/m/d');
        $job_opening_status = $input['job_opening_status'];
        $job_priority = $input['job_priority'];
        $industry_id = $input['industry_id'];
        $client_id = $input['client_id'];
        $no_of_positions = $input['no_of_positions'];
        $date_open = $input['date_opened'];
        //$formatted_date_open = Carbon::parse($date_open)->format('Y/m/d');
        $job_type = $input['job_type'];
        $job_description = $input['job_description'];
        $work_experience_from = $input['work_experience_from'];
        $work_experience_to = $input['work_experience_to'];
        $salary_from = $input['salary_from'];
        $salary_to = $input['salary_to'];
        $city = $input['city'];
        $state = $input['state'];
        $country = $input['country'];
        $job_show = 0;
        $users = $input['user_ids'];
        //print_r($users);exit;
        $desired_candidate = $input['desired_candidate'];
        $qualifications = $input['qualifications'];

        if (isset($work_experience_from) && $work_experience_from == '')
            $work_experience_from = 0;
        if (isset($work_experience_to) && $work_experience_to == '')
            $work_experience_to = 0;
        if (isset($salary_from) && $salary_from == '')
            $salary_from = 0;
        if (isset($salary_to) && $salary_to == '')
            $salary_to = 0;
        if (isset($qualifications) && $qualifications == '')
            $qualifications = '';
        if (isset($desired_candidate) && $desired_candidate == '')
            $desired_candidate = '';

        $increment_id = $max_id + 1;
        $job_unique_id = "TT-JO-$increment_id";
        $job_open = new JobOpen();
        $job_open->job_id = $job_unique_id;
        $job_open->job_show = $job_show;
        $job_open->posting_title = $posting_title;
        $job_open->hiring_manager_id = $hiring_manager_id;
        //$job_open->target_date = 'NULL';//$dateClass->changeDMYtoYMD($target_date);//'2016-01-01';// $formatted_target_date;
        $job_open->job_opening_status = $job_opening_status;
        $job_open->industry_id = $industry_id;
        $job_open->client_id = $client_id;
        $job_open->no_of_positions = $no_of_positions;
        $job_open->date_opened = $dateClass->changeDMYtoYMD($date_open); //'2016-01-01';//$formatted_date_open;
        $job_open->job_type = $job_type;
        $job_open->job_description = $job_description;
        $job_open->work_experience_from = $work_experience_from;
        $job_open->work_experience_to = $work_experience_to;
        $job_open->salary_from = $salary_from;
        $job_open->salary_to = $salary_to;
        $job_open->city = $city;
        $job_open->state = $state;
        $job_open->country = $country;
        $job_open->priority = $job_priority;
        $job_open->desired_candidate = $desired_candidate;
        $job_open->qualifications = $qualifications;

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
            $message = "New job opening is added";
            $link = route('jobopen.show',$job_id);

            $user_arr = array();

            if(isset($users) && sizeof($users)>0){
                foreach ($users as $key=>$value){
                    if($user_id!=$value){
                        $user_arr[] = $value;
                    }
                }
            }

            event(new NotificationEvent($module_id, $module, $message, $link, $user_arr));

        }

        return redirect()->route('jobopen.index')->with('success', 'Job Opening Created Successfully');
    }

    public function show($id)
    {
        $job_open = array();

        $job_open_detail = \DB::table('job_openings')
            ->join('client_basicinfo', 'client_basicinfo.id', '=', 'job_openings.client_id')
            ->join('users', 'users.id', '=', 'job_openings.hiring_manager_id')
            ->join('industry', 'industry.id', '=', 'job_openings.industry_id')
            ->select('job_openings.*', 'client_basicinfo.name as client_name', 'users.name as hiring_manager_name', 'industry.name as industry_name')
            ->where('job_openings.id', '=', $id)
            ->get();

        $job_open['id'] = $id;

        foreach ($job_open_detail as $key => $value) {
            $job_open['posting_title'] = $value->posting_title;
            $job_open['job_id'] = $value->job_id;
            $job_open['client_name'] = $value->client_name;
            $job_open['client_id'] = $value->client_id;
            $job_open['job_opening_status'] = $value->job_opening_status;
            $job_open['desired_candidate'] = $value->desired_candidate;
            $job_open['hiring_manager_name'] = $value->hiring_manager_name;
            //$job_open['hiring_manager_id'] = $value->hiring_manager_id;
            $job_open['no_of_positions'] = $value->no_of_positions;
            $job_open['target_date'] = $value->target_date;
            $job_open['date_opened'] = $value->date_opened;
            $job_open['job_type'] = $value->job_type;
            $job_open['industry_name'] = $value->industry_name;
            $job_open['description'] = strip_tags($value->job_description);
            $job_open['work_experience'] = $value->work_experience_from . "-" . $value->work_experience_to;
            $job_open['salary'] = $value->salary_from . "-" . $value->salary_to;
            $job_open['country'] = $value->country;
            $job_open['state'] = $value->state;
            $job_open['city'] = $value->city;


            $user = \Auth::user();
            $user_id = $user->id;
            $user_role_id = User::getLoggedinUserRole($user);

            $admin_role_id = env('ADMIN');
            $director_role_id = env('DIRECTOR');
            $manager_role_id = env('MANAGER');
            $superadmin_role_id = env('SUPERADMIN');

            $access_roles_id = array($admin_role_id,$director_role_id,$manager_role_id,$superadmin_role_id);

            if(in_array($user_role_id,$access_roles_id)){
                $job_open['access'] = '1';
            }
            else{
                if($value->hiring_manager_id==$user_id){
                    $job_open['access'] = '1';
                }
                else{
                    $job_open['access'] = '0';
                }
            }


            // already added posting,massmail and job search options
            $selected_posting = array();
            $selected_mass_mail = array();
            $selected_job_search = array();

            $mo_posting = '';
            $mo_mass_mail='';
            $mo_job_search = '';
            if(isset($value->posting) && $value->posting!=''){
                $mo_posting = $value->posting;
                $selected_posting = explode(",",$mo_posting);
            }
            if(isset($value->mass_mail) && $value->mass_mail!=''){
                $mo_mass_mail = $value->mass_mail;
                $selected_mass_mail = explode(",",$mo_mass_mail);
            }
            if(isset($value->job_search) && $value->job_search!=''){
                $mo_job_search = $value->job_search;
                $selected_job_search = explode(",",$mo_job_search);
            }
        }

        $job_visible_users = \DB::table('job_visible_users')
            ->select('users.id','users.name')
            ->join('users','users.id','=','job_visible_users.user_id')
            ->where('job_visible_users.job_id',$id)
            ->get();
        $count = 0;
        foreach ($job_visible_users as $key => $value) {
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
                    'job_search'=>$job_search,'selected_posting'=>$selected_posting,'selected_mass_mail'=>$selected_mass_mail,'selected_job_search'=>$selected_job_search,'job_status'=>$job_status));
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

        $user = \Auth::user();
        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $manager_role_id = env('MANAGER');
        $superadmin_role_id = env('SUPERADMIN');

        $user_role_id = User::getLoggedinUserRole($user);
        $user_id = $user->id;
        $access_roles_id = array($admin_role_id,$director_role_id,$manager_role_id,$superadmin_role_id);
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
         
        $users = User::getAllUsers('recruiter');
        
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

        $job_open = JobOpen::find($id);
        $user_id = $job_open->hiring_manager_id;
        //print_r($job_open);exit;
        $target_date = '';//$dateClass->changeYMDtoDMY($job_open->target_date);
        $date_opened = $dateClass->changeYMDtoDMY($job_open->date_opened);

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

        $action = "edit";

        return view('adminlte::jobopen.edit', compact('user_id','action', 'industry', 'client', 'users', 'job_open_status', 'job_type','job_priorities', 'job_open', 'date_opened', 'target_date','team_mates','selected_users'));

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
        $job_opening_status = $input['job_opening_status'];
        $industry_id = $input['industry_id'];
        $client_id = $input['client_id'];
        $no_of_positions = $input['no_of_positions'];
        $date_open = $input['date_opened'];
        //$formatted_date_open = Carbon::parse($date_open)->format('Y/m/d');
        $job_type = $input['job_type'];
        $job_description = $input['job_description'];
        $work_experience_from = $input['work_experience_from'];
        $work_experience_to = $input['work_experience_to'];
        $salary_from = $input['salary_from'];
        $salary_to = $input['salary_to'];
        $city = $input['city'];
        $state = $input['state'];
        $country = $input['country'];
        $job_priority = $input['job_priority'];
        //print_r($input);exit;
        $users = $input['user_ids'];
        $desired_candidate = $input['desired_candidate'];
        $qualifications = $input['qualifications'];

        if (isset($work_experience_from) && $work_experience_from == '')
            $work_experience_from = 0;
        if (isset($work_experience_to) && $work_experience_to == '')
            $work_experience_to = 0;
        if (isset($salary_from) && $salary_from == '')
            $salary_from = 0;
        if (isset($salary_to) && $salary_to == '')
            $salary_to = 0;
        if (isset($desired_candidate) && $desired_candidate == '')
            $desired_candidate = '';
        if (isset($qualifications) && $qualifications == '')
            $qualifications = '';

        $increment_id = $max_id + 1;
        $job_unique_id = "TT-JO-$increment_id";
        $job_open = JobOpen::find($id);
        $job_open->job_id = $job_unique_id;
        //$job_open->job_show = $job_show;
        $job_open->posting_title = $posting_title;
        $job_open->hiring_manager_id = $hiring_manager_id;
       // $job_open->target_date = 'NULL';//$dateClass->changeDMYtoYMD($target_date);//'2016-01-01';// $formatted_target_date;
        $job_open->job_opening_status = $job_opening_status;
        $job_open->industry_id = $industry_id;
        $job_open->client_id = $client_id;
        $job_open->no_of_positions = $no_of_positions;
        $job_open->date_opened = $dateClass->changeDMYtoYMD($date_open); //'2016-01-01';//$formatted_date_open;
        $job_open->job_type = $job_type;
        $job_open->job_description = $job_description;
        $job_open->work_experience_from = $work_experience_from;
        $job_open->work_experience_to = $work_experience_to;
        $job_open->salary_from = $salary_from;
        $job_open->salary_to = $salary_to;
        $job_open->city = $city;
        $job_open->state = $state;
        $job_open->country = $country;
        $job_open->priority = $job_priority;
        $job_open->qualifications = $qualifications;
        $job_open->desired_candidate = $desired_candidate;

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

        return redirect()->route('jobopen.index')->with('success', 'Job Opening Updated Successfully');
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
            ->select('candidate_basicinfo.id as id', 'candidate_basicinfo.fname as fname', 'candidate_basicinfo.lname as lname',
                'candidate_basicinfo.email as email', 'users.name as owner')
            ->whereNotIn('candidate_basicinfo.id', $candidates)
            ->get();

        return view('adminlte::jobopen.associate_candidate', array('candidates' => $candidateDetails, 'job_id' => $id, 'posting_title' => $posting_title, 'message' => ''));
    }

    public function postAssociateCandidates()
    {
        $user_id = \Auth::user()->id;
        $job_id = $_POST['jobid'];
        $candidate_ids = $_POST['candidate_ids'];
        $status_id = env('associate_candidate_status', 10);

        $candidate_ids_array = explode(",", $candidate_ids);

        foreach ($candidate_ids_array as $key => $value) {
            $job_associate_candidate = new JobAssociateCandidates();
            $job_associate_candidate->job_id = $job_id;
            $job_associate_candidate->candidate_id = $value;
            $job_associate_candidate->status_id = $status_id;
            $job_associate_candidate->created_at = time();
            $job_associate_candidate->updated_at = time();
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

        // get job name from id
        $jobopen_response = JobOpen::where('id', $id)->first();
        $client_id = $jobopen_response->client_id;
        $posting_title = $jobopen_response->posting_title;

        // get candidate ids already associated with job
        $candidate_response = JobAssociateCandidates::where('job_id', '=', $id)->get();
        $candidates = array();
        foreach ($candidate_response as $key => $value) {
            $candidates[] = $value->candidate_id;
        }

        $candidateDetails = CandidateBasicInfo::leftjoin('candidate_otherinfo', 'candidate_otherinfo.candidate_id', '=', 'candidate_basicinfo.id')
            ->leftjoin('users', 'users.id', '=', 'candidate_otherinfo.owner_id')
            ->select('candidate_basicinfo.id as id', 'candidate_basicinfo.fname as fname', 'candidate_basicinfo.lname as lname',
                'candidate_basicinfo.email as email', 'users.name as owner')
            ->whereIn('candidate_basicinfo.id', $candidates)
            ->get();


        // get candidate status
        $candidateresult = CandidateStatus::orderBy('name','asc')->select('id','name')->get()->toArray();
        foreach ($candidateresult as $key=>$value){
            $candidateStatus[$value['id']] = $value['name'];
        }

        $type = Interview::getTypeArray();
        $status = Interview::getInterviewStatus();
        $users = User::getAllUsers();
        return view('adminlte::jobopen.associated_candidate', array('job_id' => $id, 'posting_title' => $posting_title,
            'message' => '','candidates'=>$candidateDetails ,'candidatestatus'=>$candidateStatus,'type'=>$type,
            'status' => $status,'users' => $users,'client_id'=>$client_id));
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
        $candidate_ids = $_POST['candidate_ids'];
        $status_id = $_POST['status_id'];

        DB::statement("UPDATE job_associate_candidates SET status_id = $status_id where candidate_id in ($candidate_ids) and job_id = $job_id");

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
        $data['interview_name'] = $request->get('interview_name');
        $data['candidate_id'] = $request->get('candidate_id');
        $data['interviewer_id'] = $request->get('interviewer_id');
        $data['client'] = $request->get('client_id');
        $data['interview_date'] = $dateClass->changeDMYHMStoYMDHMS($request->get('interview_date'));
        $data['location'] = $request->get('location');
        $data['comments'] = '';
        $data['posting_title'] =  $request->get('job_id');
        $data['type'] = $request->get('type');
        $data['status'] = $request->get('status');
        $data['interview_owner_id'] = $user_id;

        $job_id = $request->get('job_id');

        $interview = Interview::createInterview($data);

        $validator = \Validator::make(Input::all(),$interview::$rules);

        if($validator->fails()){
            return redirect('interview/create')->withInput(Input::all())->withErrors($validator->errors());
        }

        $interviewStored = $interview->save();

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

    public function getOpenJobs(){

        // logged in user with role 'Administrator,Director,Manager can see all the open jobs
        // Rest other users can only see the jobs assigned to them

        $user = \Auth::user();
        $user_role_id = User::getLoggedinUserRole($user);

        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $manager_role_id = env('MANAGER');
        $superadmin_role_id = env('SUPERADMIN');

        $access_roles_id = array($admin_role_id,$director_role_id,$manager_role_id,$superadmin_role_id);
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
        $job_status = $request->get('job_status');
        $job_id = $request->get('job_id');

        $job_open = JobOpen::find($job_id);

        $job = '';
        if (isset($job_status) && sizeof($job_status)>0){

                 $job = $job_status;
            
        }
        $job_open->job_status = $job;
         
        $job_open->save();

        
            return redirect()->route('jobopen.index')->with('success', 'Job Opening Status added successfully');
       
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
}
