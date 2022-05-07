<?php

namespace App\Http\Controllers;

use App\Companies;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Input;
use App\Role;
use DB;
use Hash;
use App\Date;
use App\UserOthersInfo;
use App\UsersDoc;
use App\Utils;
use App\UserLeave;
use App\UsersLog;
use App\UsersFamily;
use App\Training;
use App\TrainingVisibleUser;
use App\ProcessManual;
use App\ProcessVisibleUser;
use App\JobOpen;
use App\JobVisibleUsers;
use App\RoleUser;
use App\ModuleVisibleUser;
use App\CandidateBasicInfo;
use App\UsersEmailPwd;
use App\Department;
use App\Events\NotificationMail;
use App\UserBenchMark;
use App\RolewiseUserBenchmark;
use App\Holidays;
use App\HolidaysUsers;
use App\WorkPlanning;

class UserController extends Controller
{
    public function index(Request $request) {

        $users = User::orderBy('status','ASC')
        ->leftjoin('department','department.id','=','users.type')
        ->select('users.*','department.name as department')->get();

        $count = sizeof($users);

        $active = 0;
        $inactive = 0;

        foreach($users as $user) {

            if($user['status'] == 'Active') {
                $active++;
            }
            else if ($user['status'] == 'Inactive') {
                $inactive++;
            }
        }
        return view('adminlte::users.index',compact('users','count','active','inactive'));
    }

    public function create() {

        $user_id = \Auth::user()->id;
        $superadmin = getenv('SUPERADMINUSERID');
        $saloni_user_id = getenv('SALONIUSERID');

        $roles = array();
        $roles_id = '';

        $reports_to = User::getUserArray($user_id);
        $reports_to = array_fill_keys(array(0),'Select Reports to')+$reports_to;

        $floor_incharge = User::getAllFloorInchargeUsers();
        $floor_incharge = array_fill_keys(array(0),'Select Floor Incharge')+$floor_incharge;

        $companies = Companies::getCompanies();
        $companies = array_fill_keys(array(''),'Select Company')+$companies;

        $company_id = Companies::getCompanyIdByName('Adler');

        $type  = User::getTypeArray();
        $type = array_fill_keys(array(''),'Select type')+$type;

        // Replace Type with Department

        $department_res = Department::orderBy('id','ASC')->get();
        $departments = array();

        if($user_id == $superadmin || $user_id == $saloni_user_id) {

            if(sizeof($department_res) > 0) {
                foreach($department_res as $r) {
                    $departments[$r->id] = $r->name;
                }
            }
        }
        else {

            if(sizeof($department_res) > 0) {
                foreach($department_res as $r) {

                    if($r->name == 'Management') {
                    }
                    else {
                        $departments[$r->id] = $r->name;
                    }
                }
            }
        }
        $department_id = '';

        $departments = array_fill_keys(array(''),'Select Department')+$departments;

        $hours_array = User::getHoursArray();
        $selected_working_hours = '';
        $selected_half_day_working_hours = '';

        $employment_type  = User::getEmploymentType();
        $employment_type = array_fill_keys(array(''),'Select Employment Type')+$employment_type;

        return view('adminlte::users.create',compact('roles','roles_id','reports_to','companies','company_id','type','floor_incharge','departments','department_id','hours_array','selected_working_hours','selected_half_day_working_hours','employment_type'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request) {

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required',
            'type' => 'required',
        ]);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);

        // Attach Role of user
        /*foreach ($request->input('roles') as $key => $value) {
            $user->attachRole($value);
        }*/

        $role = $request->input('roles');
        $user->attachRole($role);

        $reports_to = $request->input('reports_to');
        $floor_incharge = $request->input('floor_incharge');
        $check_report = $request->input('daily_report');
        $status = $request->input('status');
        $account_manager = $request->input('account_manager');
        $role_id = $request->input('roles');

        $type = $request->input('type');
        $hr_adv_recruitemnt = $request->input('hr_adv_recruitemnt');

        // Start Report Status

        $cv_report = $request->input('cv_report');

        if(isset($cv_report) && $cv_report != '') {
            $user->cv_report = $cv_report;
        }
        else {
            $user->cv_report = 'No';
        }

        $interview_report = $request->input('interview_report');

        if(isset($interview_report) && $interview_report != '') {
            $user->interview_report = $interview_report;
        }
        else {
            $user->interview_report = 'No';
        }

        $lead_report = $request->input('lead_report');

        if(isset($lead_report) && $lead_report != '') {
            $user->lead_report = $lead_report;
        }
        else {
            $user->lead_report = 'No';
        }
        // End Report Status

        // Get first & last name
        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');

        // Floor incharge or not
        $check_floor_incharge = $request->input('check_floor_incharge');

        // Working Hours
        $working_hours = $request->input('working_hours');
        $half_day_working_hours = $request->input('half_day_working_hours');

        // Get Joining Date
        $dateClass = new Date();
        $joining_date = $request->input('joining_date');

        // Get Employement Type
        $employment_type = $request->input('employment_type');
        $intern_month = $request->input('intern_month');

        $user->secondary_email = $request->input('semail');
        $user->daily_report = $check_report;
        $user->reports_to = $reports_to;
        $user->floor_incharge = $floor_incharge;
        $user->status = $status;
        $user->account_manager = $account_manager;
        $user->first_name = $first_name;
        $user->last_name = $last_name;
        $user->check_floor_incharge = $check_floor_incharge;
        $user->type = $type;
        $user->hr_adv_recruitemnt = $hr_adv_recruitemnt;
        $user->working_hours = $working_hours;
        $user->half_day_working_hours = $half_day_working_hours;

        if(isset($joining_date) && $joining_date != '') {
            $user->joining_date = $dateClass->changeDMYtoYMD($joining_date);
        }
        else {
            $user->joining_date = NULL;
        }

        $user->employment_type = $employment_type;
        $user->intern_month = $intern_month;

        $users = $user->save();

        // Get variables
        $user_id = $user->id;
        $user_email = $user->email;

        if ($status == 'Active') {

            // Add new User to training & process manual if it is display to all users.

            if($type == '1' || ($type == '2' && $hr_adv_recruitemnt == 'Yes')) {

                $training_ids = Training::getAlltrainingIds(1);

                if (isset($training_ids) && $training_ids != '') {

                    foreach ($training_ids as $key => $value) {

                        $training_visible_users = new TrainingVisibleUser;
                        $training_visible_users->training_id = $value;
                        $training_visible_users->user_id = $user_id;
                        $training_visible_users->save();
                    }
                }
            }

            $process_ids = ProcessManual::getAllprocessmanualIds(1);

            if (isset($process_ids) && $process_ids != '') {

                foreach ($process_ids as $key1 => $value1) {

                    $process_visible_users = new ProcessVisibleUser();
                    $process_visible_users->process_id = $value1;
                    $process_visible_users->user_id = $user_id;
                    $process_visible_users->save();
                }
            }

            // If job_open_to_all = 1 then new user visible that all jobs

            $job_ids = JobOpen::getAllJobsId(1);

            if (isset($job_ids) && $job_ids != '') {

                foreach ($job_ids as $key2 => $value2) {
                    
                    $job_visible_users = new JobVisibleUsers();
                    $job_visible_users->job_id = $value2;
                    $job_visible_users->user_id = $user_id;
                    $job_visible_users->save();
                }
            }
        }

        // Add new user module visibility by it's role id not require now because module visibility is not in use
        /*if (isset($role_id) && $role_id > 0) {

            $other_user_id = RoleUser::getUserIdByRoleId($role_id);

            if (isset($other_user_id) && $other_user_id > 0) {

                $module_arr = ModuleVisibleUser::getModuleByUserId($other_user_id);

                $module_id = array();
                $i = 0;

                if (isset($module_arr) && sizeof($module_arr)>0) {

                    foreach ($module_arr as $key => $value) {

                        if(isset($key) && $key > 0) {
                            $module_id[$i] = $key;
                            $i++;
                        }
                    }

                    if (isset($module_id) && sizeof($module_id)>0) {
                        
                        foreach ($module_id as $key1 => $value1) {

                            $module_user_add = new ModuleVisibleUser();
                            $module_user_add->user_id = $user_id;
                            $module_user_add->module_id = $value1;
                            $module_user_add->save();
                        }
                    }
                }
            }
        }*/

        // Add entry in email password table for send lead & client bulk email functionality

        $users_email_pwd = new UsersEmailPwd();
        $users_email_pwd->user_id = $user_id;
        $users_email_pwd->email = $request->input('email');
        $users_email_pwd->save();

        // Add entry in user otherinfo table
        $users_otherinfo = new UserOthersInfo();
        $users_otherinfo->user_id = $user_id;

        if(isset($joining_date) && $joining_date != '') {
            $users_otherinfo->date_of_joining = $dateClass->changeDMYtoYMD($joining_date);
        }
        else {
            $users_otherinfo->date_of_joining = NULL;
        }
        $users_otherinfo->save();
    
        // Get Benchmark from rolewise table
        $role_bench_mark = RolewiseUserBenchmark::getBenchMarkByRoleID($role);

        if(isset($role_bench_mark) && sizeof($role_bench_mark) > 0) {

            // Add entry in user benchmark table
            $user_bench_mark = new UserBenchMark();
            $user_bench_mark->user_id = $user_id;
            $user_bench_mark->no_of_resumes = $role_bench_mark['no_of_resumes'];
            $user_bench_mark->shortlist_ratio = $role_bench_mark['shortlist_ratio'];
            $user_bench_mark->interview_ratio = $role_bench_mark['interview_ratio'];
            $user_bench_mark->selection_ratio = $role_bench_mark['selection_ratio'];
            $user_bench_mark->offer_acceptance_ratio = $role_bench_mark['offer_acceptance_ratio'];
            $user_bench_mark->joining_ratio = $role_bench_mark['joining_ratio'];
            $user_bench_mark->after_joining_success_ratio = $role_bench_mark['after_joining_success_ratio'];
            $user_bench_mark->save();
        }

        // Set variables for email notifications

        $logged_in_user_id = \Auth::user()->id;
        $user_name = \Auth::user()->name;

        $admin_userid = getenv('ADMINUSERID');
        $admin_email = User::getUserEmailById($admin_userid);

        $super_admin_userid = getenv('SUPERADMINUSERID');
        $superadminemail = User::getUserEmailById($super_admin_userid);

        $hr_userid = getenv('HRUSERID');
        $hr_email = User::getUserEmailById($hr_userid);

        // Send email notification when new user is add

        $cc_users_array = array($admin_email,$superadminemail);

        $module = "New User";
        $sender_name = $logged_in_user_id;
        $to = $hr_email;
        $subject = "New User - " . $first_name . " " . $last_name . " - Added By - " . $user_name;
        $message = "<tr><td>" . $user_name . " added new User</td></tr>";
        $module_id = $user_id;
        $cc = implode(",",$cc_users_array);

        event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));

        // Send email notification to user for select optional leaves

        //Get Reports to Email
        $report_res = User::getReportsToUsersEmail($user_id);

        if(isset($report_res->remail) && $report_res->remail != '') {

            $report_email = $report_res->remail;
            $cc_users_array = array($report_email,$admin_email,$superadminemail,$hr_email);
        }
        else {

            $cc_users_array = array($admin_email,$superadminemail,$hr_email);
        }

        $module = "List of Holidays";
        $sender_name = $logged_in_user_id;
        $to = $user_email;
        $subject = "List of Holidays";
        $message = "List of Holidays";
        $module_id = $user_id;
        $cc = implode(",",$cc_users_array);

        event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));

        // Assign Fixed Holidays to new user

        $year = date('Y');
        $fixed_holidays = Holidays::getFinancialYearHolidaysList();

        if(isset($fixed_holidays) && sizeof($fixed_holidays) > 0){

            foreach ($fixed_holidays as $key => $value) {

                if($value['type'] == 'Fixed Leave') {

                    $holiday_user = new HolidaysUsers();
                    $holiday_user->holiday_id = $value['id'];
                    $holiday_user->user_id = $user_id;
                    $holiday_user->save();
                }
            }
        }

        // Get All Sunday dates of current month and add in work planning

        $month = date('m');
        $year = date('Y');
        $date = "$year-$month-01";
        $first_day = date('N',strtotime($date));
        $first_day = 7 - $first_day + 1;
        $last_day =  date('t',strtotime($date));
        $sundays = array();

        for($i = $first_day; $i <= $last_day; $i = $i+7 ) {

            if($i < 10) {
                $i = "0$i";
            }
            $sundays[] = $i;
        }

        if(isset($sundays) && sizeof($sundays) > 0) {

            $joining_dt = $dateClass->changeDMYtoYMD($joining_date);

            foreach ($sundays as $k => $v) {

                $sunday_date = "$year-$month-$v";

                if($sunday_date >= $joining_dt) {

                    $work_planning = new WorkPlanning();
                    $work_planning->added_date = $sunday_date;
                    $work_planning->added_by = $user_id;
                    $work_planning->save();
                }
            }
        }
        return redirect()->route('users.index')->with('success','User Added Successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function show($id) {

        $user = \DB::table('users')
        ->leftjoin('department','department.id','=','users.type')
        ->leftjoin('role_user','role_user.user_id','=','users.id')
        ->leftjoin('roles','roles.id','=','role_user.role_id')
        ->select('users.*','department.name as department','roles.display_name as display_name')->where('users.id','=',$id)->first();

        return view('adminlte::users.show',compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id) {

        $user_id = \Auth::user()->id;
        $superadmin = getenv('SUPERADMINUSERID');
        $saloni_user_id = getenv('SALONIUSERID');

        $user = User::find($id);
        $roles = Role::orderBy('display_name','ASC')->pluck('display_name','id');

        $reports_to = User::getUserArray($id);
        $reports_to = array_fill_keys(array(0),'Select Reports to')+$reports_to;

        //$floor_incharge = User::getAllUsers();
        $floor_incharge = User::getAllFloorInchargeUsers();
        $floor_incharge = array_fill_keys(array(0),'Select Floor Incharge')+$floor_incharge;

        $roles_id = $user->roles->pluck('id','id')->toArray();
        $userReportsTo = $user->reports_to;
        $userFloorIncharge = $user->floor_incharge;
        $semail = $user->secondary_email;

        $companies = Companies::getCompanies();
        $companies = array_fill_keys(array(''),'Select Company')+$companies;

        $type  = User::getTypeArray();
        $type = array_fill_keys(array(''),'Select type')+$type;
      
        // Replace Type with Department

        $department_res = Department::orderBy('id','ASC')->get();
        $departments = array();

        if($user_id == $superadmin || $user_id == $saloni_user_id) {

            if(sizeof($department_res) > 0) {
                foreach($department_res as $r) {
                    $departments[$r->id] = $r->name;
                }
            }
        }
        else {

            if(sizeof($department_res) > 0) {
                foreach($department_res as $r) {

                    if($r->name == 'Management') {
                    }
                    else {
                        $departments[$r->id] = $r->name;
                    }
                }
            }
        }
        
        $department_id = $user->type;
        $hr_adv_recruitemnt = $user->hr_adv_recruitemnt;

        if($user->cv_report == 'Yes') {
            $cv_report = '1';
        }
        else {
            $cv_report = '0';
        }

        if($user->interview_report == 'Yes') {
            $interview_report = '1';
        }
        else {
            $interview_report = '0';
        }

        if($user->lead_report == 'Yes') {
            $lead_report = '1';
        }
        else {
            $lead_report = '0';
        }

        if(isset($user->joining_date) && $user->joining_date != NULL) {

            $joining_date = date('d-m-Y',strtotime($user->joining_date));
        }
        else {

            $joining_date = NULL;
        }
        

        $hours_array = User::getHoursArray();
        $selected_working_hours = $user->working_hours;
        $selected_half_day_working_hours = $user->half_day_working_hours;

        $employment_type  = User::getEmploymentType();
        $employment_type = array_fill_keys(array(''),'Select Employment Type')+$employment_type;
        $intern_month = $user->intern_month;

        return view('adminlte::users.edit',compact('id','user','roles','roles_id', 'reports_to', 'userReportsTo','userFloorIncharge','companies','type','floor_incharge','semail','departments','department_id','hr_adv_recruitemnt','cv_report','interview_report','lead_report','hours_array','selected_working_hours','selected_half_day_working_hours','joining_date','employment_type','intern_month'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id) {

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'roles' => 'required',
            'type' => 'required',
        ]);

        $input = $request->all();

        if(!empty($input['password'])){
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = array_except($input,array('password'));
        }

        if(!empty($input['reports_to'])){
            $input['reports_to'] = $input['reports_to'];
        }else{
            $input = array_except($input,array('reports_to'));
        }

        // Get New Value

        $first_name = $request->input('first_name');
        $last_name = $request->input('last_name');
        $name = $request->input('name');

        $email = $request->input('email');
        $semail = $request->input('semail');

        $company_id = $request->input('company_id');
        $department_id = $request->input('type');
        $hr_adv_recruitemnt = $request->input('hr_adv_recruitemnt');
        $role = $request->input('roles');
        $reports_to = $request->input('reports_to');
        $working_hours = $request->input('working_hours');
        $half_day_working_hours = $request->input('half_day_working_hours');
        
        $check_report = $request->input('daily_report');
        $cv_report = $request->input('cv_report');
        $interview_report = $request->input('interview_report');
        $lead_report = $request->input('lead_report');
        $status = $request->input('status');
        $account_manager = $request->input('account_manager');

        // Get Joining Date
        $dateClass = new Date();
        $joining_date = $request->input('joining_date');

        // Get Employement Type
        $employment_type = $request->input('employment_type');
        $intern_month = $request->input('intern_month');
        
        // Start Report Status

        if($check_report == 'Yes') {

            if(isset($cv_report) && $cv_report != '') {
                $set_cv_report = $cv_report;
            }
            else {
                $set_cv_report = 'No';
            }

            if(isset($interview_report) && $interview_report != '') {
                $set_interview_report = $interview_report;
            }
            else {
                $set_interview_report = 'No';
            }

            if(isset($lead_report) && $lead_report != '') {
                $set_lead_report = $lead_report;
            }
            else {
                $set_lead_report = 'No';
            }
        }
        else {

            $set_cv_report = 'No';
            $set_interview_report = 'No';
            $set_lead_report = 'No';
        }
        // End Report Status

        $new_value_array = array();
        $new_value_array['first_name'] = $first_name;
        $new_value_array['last_name'] = $last_name;
        $new_value_array['email'] = $email;
        $new_value_array['semail'] = $semail;

        $new_value_array['company_name'] = Companies::getCompanyNameById($company_id);
        $new_value_array['department_name'] = Department::getDepartmentNameById($department_id);
        $new_value_array['hr_adv_recruitemnt'] = $hr_adv_recruitemnt;
        $new_value_array['designation'] = Role::getRoleNameById($role);
        $new_value_array['reports_to'] = User::getUserNameById($reports_to);
        $new_value_array['working_hours'] = $working_hours;
        $new_value_array['half_day_working_hours'] = $half_day_working_hours;

        if(isset($joining_date) && $joining_date != '') {
            $new_value_array['joining_date'] = $joining_date;
        }
        else {
            $new_value_array['joining_date'] = '';
        }

        $new_value_array['employment_type'] = $employment_type;
        $new_value_array['intern_month'] = $intern_month;

        $new_value_array['check_report'] = $check_report;
        $new_value_array['cv_report'] = $set_cv_report;
        $new_value_array['interview_report'] = $set_interview_report;
        $new_value_array['lead_report'] = $set_lead_report;
        $new_value_array['status'] = $status;
        $new_value_array['account_manager'] = $account_manager;

        // Get Old Value

        $user_all_info = User::getProfileInfo($id);

        $old_first_name = $user_all_info->first_name;
        $old_last_name = $user_all_info->last_name;
        $old_name = $user_all_info->name;

        $old_email = $user_all_info->email;
        $old_semail = $user_all_info->secondary_email;

        $old_company_id = $user_all_info->company_id;
        $old_department = $user_all_info->department_id;
        $old_hr_adv_recruitemnt = $user_all_info->hr_adv_recruitemnt;
        $old_role_id = $user_all_info->role_id;
        $old_reports_to = $user_all_info->reports_to;
        $old_working_hours = $user_all_info->working_hours;
        $old_half_day_working_hours = $user_all_info->half_day_working_hours;

        if(isset($user_all_info->joining_date) && $user_all_info->joining_date != NULL) {
            $old_joining_date = date('d-m-Y',strtotime($user_all_info->joining_date));
        }
        else {
            $old_joining_date = '';
        }

        $old_employment_type = $user_all_info->employment_type;
        $old_intern_month = $user_all_info->intern_month;

        $old_check_report = $user_all_info->daily_report;
        $old_cv_report = $user_all_info->cv_report;
        $old_interview_report = $user_all_info->interview_report;
        $old_lead_report = $user_all_info->lead_report;
        $old_status = $user_all_info->status;
        $old_account_manager = $user_all_info->account_manager;

        $old_value_array = array();
        $old_value_array['first_name'] = $old_first_name;
        $old_value_array['last_name'] = $old_last_name;
        $old_value_array['email'] = $old_email;
        $old_value_array['semail'] = $old_semail;

        $old_value_array['company_name'] = Companies::getCompanyNameById($old_company_id);
        $old_value_array['department_name'] = Department::getDepartmentNameById($old_department);
        $old_value_array['hr_adv_recruitemnt'] = $old_hr_adv_recruitemnt;
        $old_value_array['designation'] = Role::getRoleNameById($old_role_id);
        $old_value_array['reports_to'] = User::getUserNameById($old_reports_to);
        $old_value_array['working_hours'] = $old_working_hours;
        $old_value_array['half_day_working_hours'] = $old_half_day_working_hours;
        $old_value_array['joining_date'] = $old_joining_date;
        $old_value_array['employment_type'] = $old_employment_type;
        $old_value_array['intern_month'] = $old_intern_month;

        $old_value_array['check_report'] = $old_check_report;
        $old_value_array['cv_report'] = $old_cv_report;
        $old_value_array['interview_report'] = $old_interview_report;
        $old_value_array['lead_report'] = $old_lead_report;
        $old_value_array['status'] = $old_status;
        $old_value_array['account_manager'] = $old_account_manager;

        if($first_name != $old_first_name) {
            $first_name_value = 1;
        }
        else {
            $first_name_value = 0;
        }

        if($last_name != $old_last_name) {
            $last_name_value = 1;
        }
        else {
            $last_name_value = 0;
        }

        if($name != $old_name) {
            $name_value = 1;
        }
        else {
            $name_value = 0;
        }

        if($email != $old_email) {
            $email_value = 1;
        }
        else {
            $email_value = 0;
        }

        if($semail != $old_semail) {
            $semail_value = 1;
        }
        else {
            $semail_value = 0;
        }

        if($company_id != $old_company_id) {
            $company_id_value = 1;
        }
        else {
            $company_id_value = 0;
        }

        if($department_id != $old_department) {
            $department_value = 1;
        }
        else {
            $department_value = 0;
        }

        if($hr_adv_recruitemnt != $old_hr_adv_recruitemnt) {
            $hr_adv_recruitemnt_value = 1;
        }
        else {
            $hr_adv_recruitemnt_value = 0;
        }

        if($role != $old_role_id) {
            $role_id_value = 1;
        }
        else {
            $role_id_value = 0;
        }

        if($reports_to != $old_reports_to) {
            $reports_to_value = 1;
        }
        else {
            $reports_to_value = 0;
        }

        if($check_report != $old_check_report) {
            $check_report_value = 1;
        }
        else {
            $check_report_value = 0;
        }

        if($cv_report != $old_cv_report) {
            $cv_report_value = 1;
        }
        else {
            $cv_report_value = 0;
        }

        if($interview_report != $old_interview_report) {
            $interview_report_value = 1;
        }
        else {
            $interview_report_value = 0;
        }

        if($lead_report != $old_lead_report) {
            $lead_report_value = 1;
        }
        else {
            $lead_report_value = 0;
        }

        if($status != $old_status) {
            $status_value = 1;
        }
        else {
            $status_value = 0;
        }

        if($account_manager != $old_account_manager) {
            $account_manager_value = 1;
        }
        else {
            $account_manager_value = 0;
        }

        if($working_hours != $old_working_hours) {
            $working_hours_value = 1;
        }
        else {
            $working_hours_value = 0;
        }

        if($half_day_working_hours != $old_half_day_working_hours) {
            $half_day_working_hours_value = 1;
        }
        else {
            $half_day_working_hours_value = 0;
        }

        if($joining_date != $old_joining_date) {
            $joining_date_value = 1;
        }
        else {
            $joining_date_value = 0;
        }

        if($employment_type != $old_employment_type) {
            $employment_type_value = 1;
        }
        else {
            $employment_type_value = 0;
        }

        if($intern_month != $old_intern_month) {
            $intern_month_value = 1;
        }
        else {
            $intern_month_value = 0;
        }
        
        // Save new value

        // Delete Old Role
        //DB::table('role_user')->where('user_id',$id)->delete();
        DB::statement("UPDATE role_user SET role_id = '$role' where user_id = '$id'");

        $user = User::find($id);
        $user->update($input);

        $user->first_name = $first_name;
        $user->last_name = $last_name;
        $user->name = $name;
        $user->email = $email;
        $user->secondary_email = $semail;
        $user->hr_adv_recruitemnt = $hr_adv_recruitemnt;
        //$user->attachRole($role);
        $user->reports_to = $reports_to;
        $user->daily_report = $check_report;
        $user->cv_report = $set_cv_report;
        $user->interview_report = $set_interview_report;
        $user->lead_report = $set_lead_report;
        $user->account_manager = $account_manager;
        $user->status = $status;
        $user->working_hours = $working_hours;
        $user->half_day_working_hours = $half_day_working_hours;

        if(isset($joining_date) && $joining_date != '') {
            $user->joining_date = $dateClass->changeDMYtoYMD($joining_date);
        }
        else {
            $user->joining_date = NULL;
        }

        $user->employment_type = $employment_type;
        $user->intern_month = $intern_month;

        $user->save();
        
        // Send email notification when user information is update

        if($first_name_value != 0 || $last_name_value != 0 || $name_value != 0 || $email_value != 0 || $semail_value != 0 || $company_id_value != 0 || $department_value != 0 || $hr_adv_recruitemnt_value != 0 || $role_id_value != 0 || $reports_to_value != 0 || $check_report_value != 0 || $cv_report_value != 0 || $interview_report_value != 0 || $lead_report_value != 0 || $status_value != 0 || $account_manager_value != 0 || $working_hours_value != 0 || $half_day_working_hours_value != 0 || $joining_date_value != 0 || $employment_type_value != 0 || $intern_month_value != 0) {

            $logged_in_user_id = \Auth::user()->id;
            $logged_in_user_name = \Auth::user()->name;

            $hr_userid = getenv('HRUSERID');
            $hr_email = User::getUserEmailById($hr_userid);

            $admin_userid = getenv('ADMINUSERID');
            $admin_email = User::getUserEmailById($admin_userid);

            $super_admin_userid = getenv('SUPERADMINUSERID');
            $superadminemail = User::getUserEmailById($super_admin_userid);

            $cc_users_array = array($admin_email,$superadminemail);

            $module = "Update User";
            $sender_name = $logged_in_user_id;
            $to = $hr_email;
            $subject = "Details of - " . $first_name . " " . $last_name . " - Updated By - " . $logged_in_user_name;
            $message = "<tr><td>" . $logged_in_user_name . " update user information.</td></tr>";
            $module_id = $id;
            $cc = implode(",",$cc_users_array);

            $from_name = getenv('FROM_NAME');
            $from_address = getenv('FROM_ADDRESS');
            $app_url = getenv('APP_URL');

            $input = array();
            $input['from_name'] = $from_name;
            $input['from_address'] = $from_address;
            $input['subject'] = $subject;
            $input['to'] = $to;
            $input['cc'] = $cc_users_array;
            $input['app_url'] = $app_url;
            $input['new_value_array'] = $new_value_array;
            $input['old_value_array'] = $old_value_array;

            \Mail::send('adminlte::emails.userupdatemail', $input, function ($message) use($input) {
                    
                $message->from($input['from_address'], $input['from_name']);
                $message->to($input['to'])->cc($input['cc'])->subject($input['subject']);
            });

            event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
        }

        if($joining_date != $old_joining_date) {

            $u_joining_date = $dateClass->changeDMYtoYMD($joining_date);

            // Update joining date in users otherinfo table
            DB::statement("UPDATE `users_otherinfo` SET `date_of_joining` = '$u_joining_date' where `user_id` = $id");

            // Set variables for email notifications

            $logged_in_user_id = \Auth::user()->id;

            $admin_userid = getenv('ADMINUSERID');
            $admin_email = User::getUserEmailById($admin_userid);

            $super_admin_userid = getenv('SUPERADMINUSERID');
            $superadminemail = User::getUserEmailById($super_admin_userid);

            $hr_userid = getenv('HRUSERID');
            $hr_email = User::getUserEmailById($hr_userid);

            // Send email notification to user for select optional leaves

            //Get Reports to Email
            $report_res = User::getReportsToUsersEmail($id);

            if(isset($report_res->remail) && $report_res->remail != '') {

                $report_email = $report_res->remail;
                $cc_users_array = array($report_email,$admin_email,$superadminemail,$hr_email);
            }
            else {

                $cc_users_array = array($admin_email,$superadminemail,$hr_email);
            }

            $module = "List of Holidays";
            $sender_name = $logged_in_user_id;
            $to = $email;
            $subject = "List of Holidays";
            $message = "List of Holidays";
            $module_id = $id;
            $cc = implode(",",$cc_users_array);

            event(new NotificationMail($module,$sender_name,$to,$subject,$message,$module_id,$cc));
        }

        if (isset($status) && $status == 'Active') {

            // Add active User to training & process manual if it is display to all users.

            if($department_id == '1' || ($department_id == '2' && $hr_adv_recruitemnt == 'Yes')) {

                $training_ids = Training::getAlltrainingIds(1);

                if (isset($training_ids) && $training_ids != '') {

                    foreach ($training_ids as $key => $value) {

                        $training_visible_users = new TrainingVisibleUser;
                        $training_visible_users->training_id = $value;
                        $training_visible_users->user_id = $id;
                        $training_visible_users->save();
                    }
                }
            }

            $process_ids = ProcessManual::getAllprocessmanualIds(1);

            if (isset($process_ids) && $process_ids != '') {

                foreach ($process_ids as $key1 => $value1) {

                    $process_visible_users = new ProcessVisibleUser();
                    $process_visible_users->process_id = $value1;
                    $process_visible_users->user_id = $id;
                    $process_visible_users->save();
                }
            }
            
            // If job_open_to_all = 1 then active user visible that all jobs

            $job_ids = JobOpen::getAllJobsId(1);

            if (isset($job_ids) && $job_ids != '') {

                foreach ($job_ids as $key2 => $value2) {
                    
                    $job_visible_users = new JobVisibleUsers();
                    $job_visible_users->job_id = $value2;
                    $job_visible_users->user_id = $id;
                    $job_visible_users->save();
                }
            }

            //return redirect()->route('users.index')->with('success','User Updated Successfully, please add this user manually in training and process module.');

            return redirect()->route('users.index')->with('success','User Updated Successfully.');
        }

        // If status is inactive then delete this user process and training
        if (isset($status) && $status == 'Inactive') {

            ProcessVisibleUser::where('user_id',$id)->delete();
            TrainingVisibleUser::where('user_id',$id)->delete();
            JobVisibleUsers::where('user_id',$id)->delete();
            HolidaysUsers::where('user_id',$id)->delete();
        }

        return redirect()->route('users.index')->with('success','User Updated Successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function destroy($id) {

        return redirect()->route('users.index')->with('error','User can not be delete because associated with other modules.');

        $user_photo = \DB::table('users_doc')->select('file','user_id')->where('user_id','=',$id)->first();

        if(isset($user_photo)) {

            $photo_path = "uploads/users/" . $user_photo->user_id . "/photo/";

            $photo_files = glob($photo_path . "/*");

            foreach($photo_files as $file_nm) {
                if(is_file($file_nm)) {
                    unlink($file_nm);
                }
            }

            rmdir($photo_path);

            $path = "uploads/users/" . $user_photo->user_id;
            $files = glob($path . "/*");

            foreach($files as $file_nm) {
                if(is_file($file_nm)) {
                    unlink($file_nm);
                }
            }

            $user_id = $user_photo->user_id;
            $path1 = "uploads/users/" . $user_id . "/";
            rmdir($path1);

            $user_doc = UsersDoc::where('user_id','=',$id)->delete();
        }

        UserOthersInfo::where('user_id','=',$id)->delete();
        ProcessVisibleUser::where('user_id',$id)->delete();
        TrainingVisibleUser::where('user_id',$id)->delete();
        HolidaysUsers::where('user_id',$id)->delete();
        UsersFamily::where('user_id',$id)->delete();
        UsersEmailPwd::where('user_id',$id)->delete();
        RoleUser::where('user_id','=',$id)->delete();
        User::where('id','=',$id)->delete();

        return redirect()->route('users.index')->with('success','User Deleted Successfully.');
    }

    public function uploadSignatureImage(Request $request) {

        $user_id = \Auth::user()->id;

        $CKEditor = $request->input('CKEditor');
        $funcNum  = $request->input('CKEditorFuncNum');
        $message  = $url = '';

        if (Input::hasFile('upload')) {

            $file = Input::file('upload');
            if ($file->isValid()) {

                $filename = $file->getClientOriginalName();
                $file->move(public_path().'/uploads/users/'.$user_id.'/signature/', $filename);
                $url = url('uploads/users/'.$user_id.'/signature/' . $filename);
            }
            else {

                $message = 'An error occurred while uploading the file.';
            }
        }
        else {

            $message = 'No file uploaded.';
        }
        return '<script>window.parent.CKEDITOR.tools.callFunction('.$funcNum.', "'.$url.'", "'.$message.'")</script>';
    }

    public function profileShow($user_id) {

        $user = \Auth::user();
        $loggedin_user_id =  \Auth::user()->id;
        $edit_perm = $user->can('edit-user-profile');

        if ($loggedin_user_id == $user_id || $edit_perm) {

            $dateClass = new Date();
            $user = array();

            // profile photo
            $user_doc_info = UsersDoc::getUserDocInfoByIDType($user_id,'Photo');

            if(isset($user_doc_info)){
                $user['photo'] = $user_doc_info->file;
                $user['type'] = $user_doc_info->type;
            }
            else{
                $user['photo'] = '';
                $user['type'] = '';
            }

            $user_info = User::getProfileInfo($user_id);

            if(isset($user_info) && $user_info != '') {

                // Official email & gmail
                $user['id'] = $user_id;
                $user['name'] = $user_info->first_name . " " . $user_info->last_name;
                $user['email'] = $user_info->email;
                $user['semail'] = $user_info->secondary_email;
                $user['department_name'] = $user_info->department_name;
                $user['designation'] = $user_info->designation;
                $user['working_hours'] = $user_info->working_hours;
                $user['half_day_working_hours'] = $user_info->half_day_working_hours;

                // User Otherinfo
                $user['personal_email'] = $user_info->personal_email;
                $user['date_of_birth'] = $dateClass->changeYMDtoDMY($user_info->date_of_birth);
                $user['date_of_joining'] = $dateClass->changeYMDtoDMY($user_info->date_of_joining);
                $user['blood_group'] = $user_info->blood_group;
                $user['date_of_confirmation'] = $dateClass->changeYMDtoDMY($user_info->date_of_confirmation);
                $user['contact_number'] = $user_info->contact_number;
                $user['date_of_anniversary'] = $dateClass->changeYMDtoDMY($user_info->date_of_anniversary);
                $user['date_of_exit'] = $dateClass->changeYMDtoDMY($user_info->date_of_exit);
                $user['contact_no_official'] = $user_info->contact_no_official;
                $user['current_address'] = $user_info->current_address;
                $user['permanent_address'] = $user_info->permanent_address;
                $user['marital_status'] = $user_info->marital_status;
                $user['gender'] = $user_info->gender;
                $user['hobbies'] = $user_info->hobbies;
                $user['interests'] = $user_info->interests;
                $user['uan_no'] = $user_info->uan_no;
                $user['esic_no'] = $user_info->esic_no;

                // Get Signature
                $user['signature'] = $user_info->signature;

                // Get Bank Details
                $user['bank_name'] = $user_info->bank_name;
                $user['branch_name'] = $user_info->branch_name;
                $user['acc_no'] = $user_info->acc_no;
                $user['ifsc_code'] = $user_info->ifsc_code;
                $user['user_full_name'] = $user_info->user_full_name;
                $user['payment_mode'] = $user_info->payment_mode;

                // Salary Information
                $user['fixed_salary'] = $user_info->fixed_salary;
                $user['performance_bonus'] = $user_info->performance_bonus;
                $user['total_salary'] = $user_info->total_salary;
            }

            // User Family Details show
            $user_family = UsersFamily::getAllFamilyDetailsofUser($user_id);

            $type_array = array('Photo');
            $userModel = new User();
            $users_upload_type = $userModel->users_upload_type;
            $j=0;
            $user['doc'] = array();
            $users_docs = \DB::table('users_doc')->select('users_doc.*')->where('user_id','=',$user_id)->whereNotIn('type',$type_array)->get();

            $utils = new Utils();

            foreach($users_docs as $key => $value) {

                $user['doc'][$j]['name'] = $value->name;
                $user['doc'][$j]['id'] = $value->id;
                $user['doc'][$j]['url'] = "../".$value->file;
                $user['doc'][$j]['size'] = $utils->formatSizeUnits($value->size);
                $user['doc'][$j]['type'] = $value->type;

                if (array_search($value->type, $users_upload_type)) {
                    unset($users_upload_type[array_search($value->type, $users_upload_type)]);
                }
                $j++;
            }

            return view('adminlte::users.myprofile',array('user' => $user),compact('user_id','user_family','users_upload_type'));
        }
        else {
            return view('errors.403');
        }
    }

    public function editProfile($user_id) {

        $user = \Auth::user();
        $loggedin_user_id =  \Auth::user()->id;
        $edit_perm = $user->can('edit-user-profile');

        if ($loggedin_user_id == $user_id || $edit_perm) {

            $dateClass = new Date();
            $user = array();

            // profile photo
            $user_doc_info = UsersDoc::getUserDocInfoByIDType($user_id,'Photo');
            if(isset($user_doc_info)){

                $user['photo'] = $user_doc_info->file;
                $user['type'] = $user_doc_info->type;
                $user_photo_updated_date = date('Y-m-d',strtotime($user_doc_info->updated_at));
                $next_date =  date('Y-m-d',strtotime("$user_photo_updated_date  +30 days"));
            }
            else{
                $user['photo'] = '';
                $user['type'] = '';
                $next_date = date('Y-m-d');
            }

            $curr_date = date('Y-m-d');

            if($next_date == $curr_date || $next_date < $curr_date) {
                $user['edit_photo'] = '1';
            }
            else {
                $user['edit_photo'] = '0';
            }

            $user_info = User::getProfileInfo($user_id);

            if(isset($user_info) && $user_info != ''){

                // Official email & gmail & designation
                $user['id'] = $user_id;
                $user['user_id'] = $user_id;
                $user['employee_id'] = $user_info->employee_id;
                $user['name'] = $user_info->name;
                $user['email'] = $user_info->email;
                $user['semail'] = $user_info->secondary_email;
                $user['designation'] = $user_info->designation;

                // User Otherinfo
                $user['personal_email'] = $user_info->personal_email;
                $user['date_of_birth'] = $dateClass->changeYMDtoDMY($user_info->date_of_birth);
                $user['date_of_joining'] = $dateClass->changeYMDtoDMY($user_info->date_of_joining);
                $user['blood_group'] = $user_info->blood_group;
                $user['date_of_confirmation'] = $dateClass->changeYMDtoDMY($user_info->date_of_confirmation);
                $user['contact_number'] = $user_info->contact_number;
                $user['date_of_anniversary'] = $dateClass->changeYMDtoDMY($user_info->date_of_anniversary);
                $user['date_of_exit'] = $dateClass->changeYMDtoDMY($user_info->date_of_exit);
                $user['contact_no_official'] = $user_info->contact_no_official;
                $user['current_address'] = $user_info->current_address;
                $user['permanent_address'] = $user_info->permanent_address;
                $user['marital_status'] = $user_info->marital_status;
                $user['gender'] = $user_info->gender;
                $user['hobbies'] = $user_info->hobbies;
                $user['interests'] = $user_info->interests;
                $user['uan_no'] = $user_info->uan_no;
                $user['esic_no'] = $user_info->esic_no;

                // Get Signature
                $user['signature'] = $user_info->signature;

                // Get Bank Details
                $user['bank_name'] = $user_info->bank_name;
                $user['branch_name'] = $user_info->branch_name;
                $user['acc_no'] = $user_info->acc_no;
                $user['ifsc_code'] = $user_info->ifsc_code;
                $user['user_full_name'] = $user_info->user_full_name;
                $user['payment_mode'] = $user_info->payment_mode;

                // Salary Information
                $user['fixed_salary'] = $user_info->fixed_salary;
                $user['performance_bonus'] = $user_info->performance_bonus;
                $user['total_salary'] = $user_info->total_salary;

                // Family Details                
                for ($i=1; $i <= 5 ; $i++) {

                    $users_family = UsersFamily::getFamilyDetailsofUser($user_id,$i);

                    if (isset($users_family) && $users_family != '') {

                        $user['name_'.$i] = $users_family->name;
                        $user['relationship_'.$i] = $users_family->relationship;
                        $user['occupation_'.$i] = $users_family->occupation;
                        $user['contact_no_'.$i] = $users_family->contact_no;
                    }
                    else {
                        $user['name_'.$i] = '';
                        $user['relationship_'.$i] = '';
                        $user['occupation_'.$i] = '';
                        $user['contact_no_'.$i] = '';
                    }
                }
            }

            $type_array = array('Photo');
            $userModel = new User();
            $users_upload_type = $userModel->users_upload_type;

            $j=0;
            $user['doc'] = array();
            $users_docs = \DB::table('users_doc')->select('users_doc.*')->where('user_id','=',$user_id)
            ->whereNotIn('type',$type_array)->get();

            $utils = new Utils();

            if(isset($users_docs) && sizeof($users_docs) > 0) {

                foreach($users_docs as $key=>$value){

                    $user['doc'][$j]['name'] = $value->name;
                    $user['doc'][$j]['id'] = $value->id;
                    $user['doc'][$j]['url'] = "../".$value->file;
                    $user['doc'][$j]['size'] = $utils->formatSizeUnits($value->size);
                    $user['doc'][$j]['type'] = $value->type;

                    if (array_search($value->type, $users_upload_type)) {
                        unset($users_upload_type[array_search($value->type, $users_upload_type)]);
                    }
                    $j++;
                }
            }

            //$users_upload_type['Others'] = 'Others';

            $gender = CandidateBasicInfo::getTypeArray();
            $maritalStatus = CandidateBasicInfo::getMaritalStatusArray();

            // Generate Emp ID Incrment Number

            $max_id = UserOthersInfo::find(\DB::table('users_otherinfo')->max('id'));

            if(isset($max_id->employee_id_increment) && $max_id->employee_id_increment != '') {
                $number = $max_id->employee_id_increment;
            }
            else {
                $number = 0;
            }

            $employee_id_increment = $number + 1;

            if($employee_id_increment < 10) {
                $employee_id_increment = '0' . $employee_id_increment;
            }
            else {
                $employee_id_increment = $employee_id_increment;
            }

            return view('adminlte::users.editprofile',array('user' => $user),compact('user_id','users_upload_type','gender','maritalStatus','employee_id_increment'));
        }
        else {
            return view('errors.403');
        }
    }

    public function profileStore($user_id,Request $request) {

        $dateClass = new Date();

        $users =  \Auth::user();
        $loggedin_user_id = $users->id;
        $userRole = $users->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);
        $superadmin_role_id = env('SUPERADMIN');

        //  Update in main table
        $user_basic_info = User::find($user_id);

        // Official email & gmail
        $user_basic_info->email = Input::get('email');
        $user_basic_info->secondary_email = Input::get('semail');
        $user_basic_info->save();

        // Update in users email password table

        $email = Input::get('email');
        DB::statement("UPDATE `users_email_pwd` SET `email` = '$email' where `user_id` = $user_id");

        // User Otherinfo
        $personal_email = Input::get('personal_email');
        $date_of_birth = Input::get('date_of_birth');
        $date_of_joining = Input::get('date_of_joining');
        $blood_group = Input::get('blood_group');
        $date_of_confirmation = Input::get('date_of_confirmation');
        $contact_number = Input::get('contact_number');
        $date_of_anniversary = Input::get('date_of_anniversary');
        $contact_no_official = Input::get('contact_no_official');
        $date_of_exit = Input::get('date_of_exit');
        $current_address = Input::get('current_address');
        $permanent_address = Input::get('permanent_address');
        $marital_status = Input::get('marital_status');
        $gender = Input::get('gender');
        $hobbies = Input::get('hobbies');
        $interests = Input::get('interests');
        $uan_no = Input::get('uan_no');
        $esic_no = Input::get('esic_no');

        // Get Signature
        $signature = Input::get('signature');
            
        // Get Bank Details
        $bank_name = Input::get('bank_name');
        $branch_name = Input::get('branch_name');
        $acc_no = Input::get('acc_no');
        $ifsc_code = Input::get('ifsc_code');
        $user_full_name = Input::get('user_full_name');
        $payment_mode = Input::get('payment_mode');

        // Salary Information
        $fixed_salary = Input::get('fixed_salary');
        $performance_bonus = Input::get('performance_bonus');
        $total_salary = Input::get('total_salary');

        // Get Emp ID Increment Number

        $employee_id_increment = Input::get('employee_id_increment');

        $user_other_info = UserOthersInfo::getUserOtherInfo($user_id);

        if(isset($user_other_info) && $user_other_info != '') {

            $users_otherinfo_update = UserOthersInfo::find($user_other_info->id);   
        }
        else {

            $users_otherinfo_update = new UserOthersInfo();
            $users_otherinfo_update->user_id = $user_id;

            if($superadmin_role_id == $role_id) {
 
            }
            else {
                // Generate Emp ID

                if(isset($date_of_joining) && $date_of_joining != '') {

                    $users_otherinfo_update->employee_id_increment = $employee_id_increment;
                    $join_year = date('y',strtotime($date_of_joining));
                    $next_year =  $join_year + 1;
                    $employee_id = 'ATS' . $join_year . $next_year . $employee_id_increment;
                    $users_otherinfo_update->employee_id = $employee_id;
                }
            }
        }
        
        $users_otherinfo_update->personal_email = $personal_email;

        if(isset($date_of_birth) && $date_of_birth != '') {
            $users_otherinfo_update->date_of_birth = $dateClass->changeDMYtoYMD($date_of_birth);
        }
        else {
            $users_otherinfo_update->date_of_birth = NULL;
        }

        if(isset($date_of_joining) && $date_of_joining != '') {
            $users_otherinfo_update->date_of_joining = $dateClass->changeDMYtoYMD($date_of_joining);
        }
        else {
            $users_otherinfo_update->date_of_joining = NULL;
        }

        if(isset($date_of_confirmation) && $date_of_confirmation != '') {
            $users_otherinfo_update->date_of_confirmation = $dateClass->changeDMYtoYMD($date_of_confirmation);
        }
        else {
            $users_otherinfo_update->date_of_confirmation = NULL;
        }

        if(isset($date_of_anniversary) && $date_of_anniversary != '') {
            $users_otherinfo_update->date_of_anniversary = $dateClass->changeDMYtoYMD($date_of_anniversary);
        }
        else {
            $users_otherinfo_update->date_of_anniversary = NULL;
        }

        if(isset($date_of_exit) && $date_of_exit != '') {
            $users_otherinfo_update->date_of_exit = $dateClass->changeDMYtoYMD($date_of_exit);
        }
        else {
            $users_otherinfo_update->date_of_exit = NULL;
        }

        $users_otherinfo_update->blood_group = $blood_group;
        $users_otherinfo_update->contact_number = $contact_number;
        $users_otherinfo_update->contact_no_official = $contact_no_official;
        $users_otherinfo_update->current_address = $current_address;
        $users_otherinfo_update->permanent_address = $permanent_address;
        $users_otherinfo_update->marital_status = $marital_status;
        $users_otherinfo_update->gender = $gender;
        $users_otherinfo_update->hobbies = $hobbies;
        $users_otherinfo_update->interests = $interests;
        $users_otherinfo_update->uan_no = $uan_no;
        $users_otherinfo_update->esic_no = $esic_no;

        // Signature
        $users_otherinfo_update->signature = $signature;

        // Bank Details
        $users_otherinfo_update->bank_name = $bank_name;
        $users_otherinfo_update->branch_name = $branch_name;
        $users_otherinfo_update->acc_no = $acc_no;
        $users_otherinfo_update->ifsc_code = $ifsc_code;
        $users_otherinfo_update->user_full_name = $user_full_name;
        $users_otherinfo_update->payment_mode = $payment_mode;

        // Salary Information
        $users_otherinfo_update->fixed_salary = $fixed_salary;
        $users_otherinfo_update->performance_bonus = $performance_bonus;
        $users_otherinfo_update->total_salary = $total_salary;

        // Save Data
        $users_otherinfo_update->save();
        
        // Delete previous data
        $user_family_delete = UsersFamily::where('user_id',$user_id)->delete();

        // User Family Details Update
        for ($i=1; $i <=5 ; $i++) {

            $name = Input::get('name_'.$i);
            $relationship = Input::get('relationship_'.$i);
            $occupation = Input::get('occupation_'.$i);
            $contact_no = Input::get('contact_no_'.$i);

            if (isset($name) && $name != '') {
                $users_family = new UsersFamily();
                $users_family->user_id = $user_id;
                $users_family->name = $name;
                $users_family->relationship = $relationship;
                $users_family->occupation = $occupation;
                $users_family->contact_no = $contact_no;
                $users_family->family_id = $i;
                $users_family->save();
            }
        }

        // Stored photo
        $user_photo_info = UsersDoc::getUserDocInfoByIDType($user_id,'Photo');
        $upload_profile_photo = $request->file('image');

        if (isset($upload_profile_photo) && $upload_profile_photo->isValid()) {
            
            $file_name = $upload_profile_photo->getClientOriginalName();
            $file_extension = $upload_profile_photo->getClientOriginalExtension();
            $file_realpath = $upload_profile_photo->getRealPath();
            $file_size = $upload_profile_photo->getSize();

            $path = "uploads/users/" . $user_id . '/photo';

            $files = glob($path . "/*");
            foreach($files as $file){
                if(is_file($file)){
                    unlink($file);
                }
            }

            $dir = 'uploads/users/' . $user_id . '/photo/';
            if (!file_exists($dir) && !is_dir($dir)) {
                mkdir($dir, 0777, true);
                chmod($dir, 0777);
            }

            $upload_profile_photo->move($dir, $file_name);

            $file_path = $dir . $file_name;

            if(isset($user_photo_info)){
                $users_doc = UsersDoc::find($user_photo_info->id);
            }
            else{
                $users_doc = new UsersDoc();
            }
            $users_doc->user_id = $user_id;
            $users_doc->file = $file_path;
            $users_doc->name = $file_name;
            $users_doc->size = $file_size;
            $users_doc->type = "Photo";
            $users_doc->save();
        }

        //Educational Credentials Start
        // Stored SSC Marksheet
        $ssc_marksheet = $request->file('ssc_marksheet');

        if (isset($ssc_marksheet) && $ssc_marksheet->isValid()){

            $type = 'SSC Marksheet';

            $user_doc_info = UsersDoc::getUserDocInfoByIDType($user_id,$type);

            // Remove Old Document & It's entry from table

            if(isset($user_doc_info) && $user_doc_info != '') {

                $path = "uploads/users/" . $user_doc_info->user_id . "/" . $user_doc_info->name;
                unlink($path);

                UsersDoc::where('type','=',$type)->delete();
            }
            
            $file_name = $ssc_marksheet->getClientOriginalName();
            $file_size = $ssc_marksheet->getSize();

            $dir = 'uploads/users/' . $user_id . '/';
            if (!file_exists($dir) && !is_dir($dir)) {
                mkdir($dir, 0777, true);
                chmod($dir, 0777);
            }

            $ssc_marksheet->move($dir, $file_name);

            $file_path = $dir . $file_name;

            $users_doc = new UsersDoc();
            $users_doc->user_id = $user_id;
            $users_doc->file = $file_path;
            $users_doc->name = $file_name;
            $users_doc->size = $file_size;
            $users_doc->type = $type;
            $users_doc->save();
        }

        // Stored HSC Marksheet
        $hsc_marksheet = $request->file('hsc_marksheet');

        if (isset($hsc_marksheet) && $hsc_marksheet->isValid()){

            $type = 'HSC Marksheet';

            $user_doc_info = UsersDoc::getUserDocInfoByIDType($user_id,$type);

            // Remove Old Document & It's entry from table

            if(isset($user_doc_info) && $user_doc_info != '') {

                $path = "uploads/users/" . $user_doc_info->user_id . "/" . $user_doc_info->name;
                unlink($path);

                UsersDoc::where('type','=',$type)->delete();
            }
            
            $file_name = $hsc_marksheet->getClientOriginalName();
            $file_size = $hsc_marksheet->getSize();

            $dir = 'uploads/users/' . $user_id . '/';
            if (!file_exists($dir) && !is_dir($dir)) {
                mkdir($dir, 0777, true);
                chmod($dir, 0777);
            }

            $hsc_marksheet->move($dir, $file_name);

            $file_path = $dir . $file_name;

            $users_doc = new UsersDoc();
            $users_doc->user_id = $user_id;
            $users_doc->file = $file_path;
            $users_doc->name = $file_name;
            $users_doc->size = $file_size;
            $users_doc->type = $type;
            $users_doc->save();
        }

        // Stored University Certificate
        $university_certificate = $request->file('university_certificate');

        if (isset($university_certificate) && $university_certificate->isValid()){

            $type = 'University Certificate';
            
            $user_doc_info = UsersDoc::getUserDocInfoByIDType($user_id,$type);

            // Remove Old Document & It's entry from table

            if(isset($user_doc_info) && $user_doc_info != '') {

                $path = "uploads/users/" . $user_doc_info->user_id . "/" . $user_doc_info->name;
                unlink($path);

                UsersDoc::where('type','=',$type)->delete();
            }

            $file_name = $university_certificate->getClientOriginalName();
            $file_size = $university_certificate->getSize();

            $dir = 'uploads/users/' . $user_id . '/';
            if (!file_exists($dir) && !is_dir($dir)) {
                mkdir($dir, 0777, true);
                chmod($dir, 0777);
            }

            $university_certificate->move($dir, $file_name);

            $file_path = $dir . $file_name;

            $users_doc = new UsersDoc();
            $users_doc->user_id = $user_id;
            $users_doc->file = $file_path;
            $users_doc->name = $file_name;
            $users_doc->size = $file_size;
            $users_doc->type = $type;
            $users_doc->save();
        }
        //Educational Credentials End

        //Company Credentials Start
        // Stored Offer Letter
        $offer_letter = $request->file('offer_letter');

        if (isset($offer_letter) && $offer_letter->isValid()){

            $type = 'Offer Letter';

            $user_doc_info = UsersDoc::getUserDocInfoByIDType($user_id,$type);

            // Remove Old Document & It's entry from table

            if(isset($user_doc_info) && $user_doc_info != '') {

                $path = "uploads/users/" . $user_doc_info->user_id . "/" . $user_doc_info->name;
                unlink($path);

                UsersDoc::where('type','=',$type)->delete();
            }
            
            $file_name = $offer_letter->getClientOriginalName();
            $file_size = $offer_letter->getSize();

            $dir = 'uploads/users/' . $user_id . '/';
            if (!file_exists($dir) && !is_dir($dir)) {
                mkdir($dir, 0777, true);
                chmod($dir, 0777);
            }

            $offer_letter->move($dir, $file_name);

            $file_path = $dir . $file_name;

            $users_doc = new UsersDoc();
            $users_doc->user_id = $user_id;
            $users_doc->file = $file_path;
            $users_doc->name = $file_name;
            $users_doc->size = $file_size;
            $users_doc->type = $type;
            $users_doc->save();
        }

        // Stored Appraisal Letter
        $appraisal_letter = $request->file('appraisal_letter');

        if (isset($appraisal_letter) && $appraisal_letter->isValid()){

            $type = 'Appraisal Letter';

            $user_doc_info = UsersDoc::getUserDocInfoByIDType($user_id,$type);

            // Remove Old Document & It's entry from table

            if(isset($user_doc_info) && $user_doc_info != '') {

                $path = "uploads/users/" . $user_doc_info->user_id . "/" . $user_doc_info->name;
                unlink($path);

                UsersDoc::where('type','=',$type)->delete();
            }
            
            $file_name = $appraisal_letter->getClientOriginalName();
            $file_size = $appraisal_letter->getSize();

            $dir = 'uploads/users/' . $user_id . '/';
            if (!file_exists($dir) && !is_dir($dir)) {
                mkdir($dir, 0777, true);
                chmod($dir, 0777);
            }

            $appraisal_letter->move($dir, $file_name);

            $file_path = $dir . $file_name;

            $users_doc = new UsersDoc();
            $users_doc->user_id = $user_id;
            $users_doc->file = $file_path;
            $users_doc->name = $file_name;
            $users_doc->size = $file_size;
            $users_doc->type = $type;
            $users_doc->save();
        }

        // Stored Relieving Letter
        $relieving_letter = $request->file('relieving_letter');

        if (isset($relieving_letter) && $relieving_letter->isValid()){

            $type = 'Relieving Letter';

            $user_doc_info = UsersDoc::getUserDocInfoByIDType($user_id,$type);

            // Remove Old Document & It's entry from table

            if(isset($user_doc_info) && $user_doc_info != '') {

                $path = "uploads/users/" . $user_doc_info->user_id . "/" . $user_doc_info->name;
                unlink($path);

                UsersDoc::where('type','=',$type)->delete();
            }
            
            $file_name = $relieving_letter->getClientOriginalName();
            $file_size = $relieving_letter->getSize();

            $dir = 'uploads/users/' . $user_id . '/';
            if (!file_exists($dir) && !is_dir($dir)) {
                mkdir($dir, 0777, true);
                chmod($dir, 0777);
            }

            $relieving_letter->move($dir, $file_name);

            $file_path = $dir . $file_name;

            $users_doc = new UsersDoc();
            $users_doc->user_id = $user_id;
            $users_doc->file = $file_path;
            $users_doc->name = $file_name;
            $users_doc->size = $file_size;
            $users_doc->type = $type;
            $users_doc->save();
        }

        // Stored Resignation Letter
        $resignation_letter = $request->file('resignation_letter');

        if (isset($resignation_letter) && $resignation_letter->isValid()){

            $type = 'Resignation Letter';

            $user_doc_info = UsersDoc::getUserDocInfoByIDType($user_id,$type);

            // Remove Old Document & It's entry from table

            if(isset($user_doc_info) && $user_doc_info != '') {

                $path = "uploads/users/" . $user_doc_info->user_id . "/" . $user_doc_info->name;
                unlink($path);

                UsersDoc::where('type','=',$type)->delete();
            }
            
            $file_name = $resignation_letter->getClientOriginalName();
            $file_size = $resignation_letter->getSize();

            $dir = 'uploads/users/' . $user_id . '/';
            if (!file_exists($dir) && !is_dir($dir)) {
                mkdir($dir, 0777, true);
                chmod($dir, 0777);
            }

            $resignation_letter->move($dir, $file_name);

            $file_path = $dir . $file_name;

            $users_doc = new UsersDoc();
            $users_doc->user_id = $user_id;
            $users_doc->file = $file_path;
            $users_doc->name = $file_name;
            $users_doc->size = $file_size;
            $users_doc->type = $type;
            $users_doc->save();
        }

        // Stored Appointment Letter
        $appointment_letter = $request->file('appointment_letter');

        if (isset($appointment_letter) && $appointment_letter->isValid()){

            $type = 'Appointment Letter';

            $user_doc_info = UsersDoc::getUserDocInfoByIDType($user_id,$type);

            // Remove Old Document & It's entry from table

            if(isset($user_doc_info) && $user_doc_info != '') {

                $path = "uploads/users/" . $user_doc_info->user_id . "/" . $user_doc_info->name;
                unlink($path);

                UsersDoc::where('type','=',$type)->delete();
            }
            
            $file_name = $appointment_letter->getClientOriginalName();
            $file_size = $appointment_letter->getSize();

            $dir = 'uploads/users/' . $user_id . '/';
            if (!file_exists($dir) && !is_dir($dir)) {
                mkdir($dir, 0777, true);
                chmod($dir, 0777);
            }

            $appointment_letter->move($dir, $file_name);

            $file_path = $dir . $file_name;

            $users_doc = new UsersDoc();
            $users_doc->user_id = $user_id;
            $users_doc->file = $file_path;
            $users_doc->name = $file_name;
            $users_doc->size = $file_size;
            $users_doc->type = $type;
            $users_doc->save();
        }

        // Stored Experience Letter
        $experience_letter = $request->file('experience_letter');

        if (isset($experience_letter) && $experience_letter->isValid()){

            $type = 'Experience Letter';

            $user_doc_info = UsersDoc::getUserDocInfoByIDType($user_id,$type);

            // Remove Old Document & It's entry from table

            if(isset($user_doc_info) && $user_doc_info != '') {

                $path = "uploads/users/" . $user_doc_info->user_id . "/" . $user_doc_info->name;
                unlink($path);

                UsersDoc::where('type','=',$type)->delete();
            }
            
            $file_name = $experience_letter->getClientOriginalName();
            $file_size = $experience_letter->getSize();

            $dir = 'uploads/users/' . $user_id . '/';
            if (!file_exists($dir) && !is_dir($dir)) {
                mkdir($dir, 0777, true);
                chmod($dir, 0777);
            }

            $experience_letter->move($dir, $file_name);

            $file_path = $dir . $file_name;

            $users_doc = new UsersDoc();
            $users_doc->user_id = $user_id;
            $users_doc->file = $file_path;
            $users_doc->name = $file_name;
            $users_doc->size = $file_size;
            $users_doc->type = $type;
            $users_doc->save();
        }

        // Stored Pay Slips
        $pay_slips = $request->file('pay_slips');

        if (isset($pay_slips) && $pay_slips->isValid()){

            $type = 'Pay Slips';

            $user_doc_info = UsersDoc::getUserDocInfoByIDType($user_id,$type);

            // Remove Old Document & It's entry from table

            if(isset($user_doc_info) && $user_doc_info != '') {

                $path = "uploads/users/" . $user_doc_info->user_id . "/" . $user_doc_info->name;
                unlink($path);

                UsersDoc::where('type','=',$type)->delete();
            }
            
            $file_name = $pay_slips->getClientOriginalName();
            $file_size = $pay_slips->getSize();

            $dir = 'uploads/users/' . $user_id . '/';
            if (!file_exists($dir) && !is_dir($dir)) {
                mkdir($dir, 0777, true);
                chmod($dir, 0777);
            }

            $pay_slips->move($dir, $file_name);

            $file_path = $dir . $file_name;

            $users_doc = new UsersDoc();
            $users_doc->user_id = $user_id;
            $users_doc->file = $file_path;
            $users_doc->name = $file_name;
            $users_doc->size = $file_size;
            $users_doc->type = $type;
            $users_doc->save();
        }

        // Stored Form - 26
        $form_26 = $request->file('form_26');

        if (isset($form_26) && $form_26->isValid()){

            $type = 'Form - 26';

            $user_doc_info = UsersDoc::getUserDocInfoByIDType($user_id,$type);

            // Remove Old Document & It's entry from table

            if(isset($user_doc_info) && $user_doc_info != '') {

                $path = "uploads/users/" . $user_doc_info->user_id . "/" . $user_doc_info->name;
                unlink($path);

                UsersDoc::where('type','=',$type)->delete();
            }
            
            $file_name = $form_26->getClientOriginalName();
            $file_size = $form_26->getSize();

            $dir = 'uploads/users/' . $user_id . '/';
            if (!file_exists($dir) && !is_dir($dir)) {
                mkdir($dir, 0777, true);
                chmod($dir, 0777);
            }

            $form_26->move($dir, $file_name);

            $file_path = $dir . $file_name;

            $users_doc = new UsersDoc();
            $users_doc->user_id = $user_id;
            $users_doc->file = $file_path;
            $users_doc->name = $file_name;
            $users_doc->size = $file_size;
            $users_doc->type = $type;
            $users_doc->save();
        }
        //Company Credentials Start

        //Personal Credentials Start
        // Stored ID Proof
        $id_proof = $request->file('id_proof');

        if (isset($id_proof) && $id_proof->isValid()){

            $type = 'ID Proof';

            $user_doc_info = UsersDoc::getUserDocInfoByIDType($user_id,$type);

            // Remove Old Document & It's entry from table

            if(isset($user_doc_info) && $user_doc_info != '') {

                $path = "uploads/users/" . $user_doc_info->user_id . "/" . $user_doc_info->name;
                unlink($path);

                UsersDoc::where('type','=',$type)->delete();
            }
            
            $file_name = $id_proof->getClientOriginalName();
            $file_size = $id_proof->getSize();

            $dir = 'uploads/users/' . $user_id . '/';
            if (!file_exists($dir) && !is_dir($dir)) {
                mkdir($dir, 0777, true);
                chmod($dir, 0777);
            }

            $id_proof->move($dir, $file_name);

            $file_path = $dir . $file_name;

            $users_doc = new UsersDoc();
            $users_doc->user_id = $user_id;
            $users_doc->file = $file_path;
            $users_doc->name = $file_name;
            $users_doc->size = $file_size;
            $users_doc->type = $type;
            $users_doc->save();
        }

        // Stored Passport
        $passport = $request->file('passport');

        if (isset($passport) && $passport->isValid()){

            $type = 'Passport';

            $user_doc_info = UsersDoc::getUserDocInfoByIDType($user_id,$type);

            // Remove Old Document & It's entry from table

            if(isset($user_doc_info) && $user_doc_info != '') {

                $path = "uploads/users/" . $user_doc_info->user_id . "/" . $user_doc_info->name;
                unlink($path);

                UsersDoc::where('type','=',$type)->delete();
            }
            
            $file_name = $passport->getClientOriginalName();
            $file_size = $passport->getSize();

            $dir = 'uploads/users/' . $user_id . '/';
            if (!file_exists($dir) && !is_dir($dir)) {
                mkdir($dir, 0777, true);
                chmod($dir, 0777);
            }

            $passport->move($dir, $file_name);

            $file_path = $dir . $file_name;

            $users_doc = new UsersDoc();
            $users_doc->user_id = $user_id;
            $users_doc->file = $file_path;
            $users_doc->name = $file_name;
            $users_doc->size = $file_size;
            $users_doc->type = $type;
            $users_doc->save();
        }

        // Stored PAN Card
        $pan_card = $request->file('pan_card');

        if (isset($pan_card) && $pan_card->isValid()){

            $type = 'PAN Card';
            
            $user_doc_info = UsersDoc::getUserDocInfoByIDType($user_id,$type);

            // Remove Old Document & It's entry from table

            if(isset($user_doc_info) && $user_doc_info != '') {

                $path = "uploads/users/" . $user_doc_info->user_id . "/" . $user_doc_info->name;
                unlink($path);

                UsersDoc::where('type','=',$type)->delete();
            }

            $file_name = $pan_card->getClientOriginalName();
            $file_size = $pan_card->getSize();

            $dir = 'uploads/users/' . $user_id . '/';
            if (!file_exists($dir) && !is_dir($dir)) {
                mkdir($dir, 0777, true);
                chmod($dir, 0777);
            }

            $pan_card->move($dir, $file_name);

            $file_path = $dir . $file_name;

            $users_doc = new UsersDoc();
            $users_doc->user_id = $user_id;
            $users_doc->file = $file_path;
            $users_doc->name = $file_name;
            $users_doc->size = $file_size;
            $users_doc->type = $type;
            $users_doc->save();
        }

        // Stored Cancelled Cheque
        $cancelled_cheque = $request->file('cancelled_cheque');

        if (isset($cancelled_cheque) && $cancelled_cheque->isValid()){

            $type = 'Cancelled Cheque';

            $user_doc_info = UsersDoc::getUserDocInfoByIDType($user_id,$type);

            // Remove Old Document & It's entry from table

            if(isset($user_doc_info) && $user_doc_info != '') {

                $path = "uploads/users/" . $user_doc_info->user_id . "/" . $user_doc_info->name;
                unlink($path);

                UsersDoc::where('type','=',$type)->delete();
            }
            
            $file_name = $cancelled_cheque->getClientOriginalName();
            $file_size = $cancelled_cheque->getSize();

            $dir = 'uploads/users/' . $user_id . '/';
            if (!file_exists($dir) && !is_dir($dir)) {
                mkdir($dir, 0777, true);
                chmod($dir, 0777);
            }

            $cancelled_cheque->move($dir, $file_name);

            $file_path = $dir . $file_name;

            $users_doc = new UsersDoc();
            $users_doc->user_id = $user_id;
            $users_doc->file = $file_path;
            $users_doc->name = $file_name;
            $users_doc->size = $file_size;
            $users_doc->type = $type;
            $users_doc->save();
        }

        // Stored Address Proof
        $address_proof = $request->file('address_proof');

        if (isset($address_proof) && $address_proof->isValid()){

            $type = 'Address Proof';

            $user_doc_info = UsersDoc::getUserDocInfoByIDType($user_id,$type);

            // Remove Old Document & It's entry from table

            if(isset($user_doc_info) && $user_doc_info != '') {

                $path = "uploads/users/" . $user_doc_info->user_id . "/" . $user_doc_info->name;
                unlink($path);

                UsersDoc::where('type','=',$type)->delete();
            }
            
            $file_name = $address_proof->getClientOriginalName();
            $file_size = $address_proof->getSize();

            $dir = 'uploads/users/' . $user_id . '/';
            if (!file_exists($dir) && !is_dir($dir)) {
                mkdir($dir, 0777, true);
                chmod($dir, 0777);
            }

            $address_proof->move($dir, $file_name);

            $file_path = $dir . $file_name;

            $users_doc = new UsersDoc();
            $users_doc->user_id = $user_id;
            $users_doc->file = $file_path;
            $users_doc->name = $file_name;
            $users_doc->size = $file_size;
            $users_doc->type = $type;
            $users_doc->save();
        }

        // Stored Aadhar Card
        $aadhar_card = $request->file('aadhar_card');

        if (isset($aadhar_card) && $aadhar_card->isValid()){
            
            $type = 'Aadhar Card';

            $user_doc_info = UsersDoc::getUserDocInfoByIDType($user_id,$type);

            // Remove Old Document & It's entry from table

            if(isset($user_doc_info) && $user_doc_info != '') {

                $path = "uploads/users/" . $user_doc_info->user_id . "/" . $user_doc_info->name;
                unlink($path);

                UsersDoc::where('type','=',$type)->delete();
            }

            $file_name = $aadhar_card->getClientOriginalName();
            $file_size = $aadhar_card->getSize();

            $dir = 'uploads/users/' . $user_id . '/';
            if (!file_exists($dir) && !is_dir($dir)) {
                mkdir($dir, 0777, true);
                chmod($dir, 0777);
            }

            $aadhar_card->move($dir, $file_name);

            $file_path = $dir . $file_name;

            $users_doc = new UsersDoc();
            $users_doc->user_id = $user_id;
            $users_doc->file = $file_path;
            $users_doc->name = $file_name;
            $users_doc->size = $file_size;
            $users_doc->type = $type;
            $users_doc->save();
        }

        // Stored Resume
        $resume = $request->file('resume');

        if (isset($resume) && $resume->isValid()){

            $type = 'Resume';

            $user_doc_info = UsersDoc::getUserDocInfoByIDType($user_id,$type);

            // Remove Old Document & It's entry from table

            if(isset($user_doc_info) && $user_doc_info != '') {

                $path = "uploads/users/" . $user_doc_info->user_id . "/" . $user_doc_info->name;
                unlink($path);

                UsersDoc::where('type','=',$type)->delete();
            }
            
            $file_name = $resume->getClientOriginalName();
            $file_size = $resume->getSize();

            $dir = 'uploads/users/' . $user_id . '/';
            if (!file_exists($dir) && !is_dir($dir)) {
                mkdir($dir, 0777, true);
                chmod($dir, 0777);
            }

            $resume->move($dir, $file_name);

            $file_path = $dir . $file_name;

            $users_doc = new UsersDoc();
            $users_doc->user_id = $user_id;
            $users_doc->file = $file_path;
            $users_doc->name = $file_name;
            $users_doc->size = $file_size;
            $users_doc->type = $type;
            $users_doc->save();
        }

        // Stored Passport Photo
        $passport_photo = $request->file('passport_photo');

        if (isset($passport_photo) && $passport_photo->isValid()){

            $type = 'Passport Photo';

            $user_doc_info = UsersDoc::getUserDocInfoByIDType($user_id,$type);

            // Remove Old Document & It's entry from table

            if(isset($user_doc_info) && $user_doc_info != '') {

                $path = "uploads/users/" . $user_doc_info->user_id . "/" . $user_doc_info->name;
                unlink($path);

                UsersDoc::where('type','=',$type)->delete();
            }
            
            $file_name = $passport_photo->getClientOriginalName();
            $file_size = $passport_photo->getSize();

            $dir = 'uploads/users/' . $user_id . '/';
            if (!file_exists($dir) && !is_dir($dir)) {
                mkdir($dir, 0777, true);
                chmod($dir, 0777);
            }

            $passport_photo->move($dir, $file_name);

            $file_path = $dir . $file_name;

            $users_doc = new UsersDoc();
            $users_doc->user_id = $user_id;
            $users_doc->file = $file_path;
            $users_doc->name = $file_name;
            $users_doc->size = $file_size;
            $users_doc->type = $type;
            $users_doc->save();
        }
        //Personal Credentials End

        return redirect()->route('users.myprofile',$user_id)->with('success','Profile Updated Successfully.'); 
    }

    public function Upload(Request $request) {

        $file = $request->file('file');
        $users_upload_type = Input::get('users_upload_type');

        $id = $request->id;

        if (isset($file) && $file->isValid()) {

            $doc_name = $file->getClientOriginalName();
            $doc_filesize = filesize($file);

            $dir_name = "uploads/users/".$id."/";
            $others_doc_key = "uploads/users/".$id."/".$doc_name;

            if (!file_exists($dir_name)) {
                mkdir("uploads/users/$id", 0777,true);
            }

            if(!$file->move($dir_name, $doc_name)) {
                return false;
            }
            else {

                $users_doc = new UsersDoc();
                $users_doc->user_id = $id;
                $users_doc->file = $others_doc_key;
                $users_doc->name = $doc_name;
                $users_doc->size = $doc_filesize;
                $users_doc->type = $users_upload_type;
                $users_doc->save();
            }
        }

        return redirect()->route('users.myprofile',$id)->with('success','Attachment Uploaded Successfully.'); 
    }

    public function attachmentsDestroy($docid,Request $request) {

        $user_id = \Auth::user()->id;
        $type = $request->input('type');

        $doc_attach = \DB::table('users_doc')
        ->select('users_doc.*')
        ->where('id','=',$docid)
        ->first();

        if(isset($doc_attach)) {
            
            $path = "uploads/users/" . $doc_attach->user_id . "/" . $doc_attach->name;
            unlink($path);

            $id = $doc_attach->user_id;
            $doc = UsersDoc::where('id','=',$docid)->delete();
        }

        if($type == 'EditProfile') {

            return redirect()->route('users.editprofile',$user_id)->with('success','Attachment Deleted Successfully.'); 
        }

        if($type == 'MyProfile') {

            return redirect()->route('users.myprofile',$user_id)->with('success','Attachment Deleted Successfully.'); 
        }
    }

    public function UserAttendanceAdd() {

        $users = User::getAllUsers();
        $type = UsersLog::getattendancetype();

        return view('adminlte::users.attendanceadd',compact('users','type'));
    }

    public function UserAttendanceStore(Request $request) {

        $dateClass = new Date();
        $user = $request->users;
        $type = $request->type;
        $date_time = $request->date_time;
        $date_convert = $dateClass->changeDMYHMStoYMDHMS($date_time);

        $dt = new \DateTime($date_convert, new \DateTimeZone('Asia/Kolkata'));
        $dt->setTimeZone(new \DateTimeZone('UTC'));
        $final_date = $dt->format("Y-m-d H:i:s") ;

        $date_array = explode(" ", $final_date);
        $date = $date_array[0];
        $time = $date_array[1];

        $users_log= new UsersLog();
        $users_log->user_id = $user;
        $users_log->date = $date;
        $users_log->time = $time;
        $users_log->type = $type;
        $users_log->created_at = $final_date;
        $users_log->updated_at = $final_date;
        $users_log->save();

        return redirect()->route('users.attendance')->with('success','Added Successfully');
    }

    public function setJobOpentoAll() {

        $user_id = $_POST['id'];
        $checked = $_POST['checked'];

        \DB::statement("UPDATE users SET job_open_to_all = '$checked' where id = '$user_id'");

        // If job_open_to_all = 1 then new user visible that all jobs

        $job_ids = JobOpen::getAllJobsId(1);

        if (isset($job_ids) && $job_ids != '') {

            foreach ($job_ids as $key => $value){

                // Delete Old Record
                JobVisibleUsers::where('job_id',$value)->where('user_id','=',$user_id)->delete();

                $job_visible_users = new JobVisibleUsers();
                $job_visible_users->job_id = $value;
                $job_visible_users->user_id = $user_id;
                $job_visible_users->save();
            }
        }
        return json_encode($checked);
    }

    public function getAllUsersByStatus($status) {

        $all_users = User::orderBy('status','ASC')->get();

        $status_wise_users = User::orderBy('status','ASC')
        ->leftjoin('department','department.id','=','users.type')
        ->select('users.*','department.name as department')->where('status','=',$status)->get();

        $count = sizeof($status_wise_users);

        $active = 0;
        $inactive = 0;

        foreach($all_users as $user) {

            if($user['status'] == 'Active') {
                $active++;
            }
            else if ($user['status'] == 'Inactive') {
                $inactive++;
            }
        }
        return view('adminlte::users.usersstatusindex',compact('status_wise_users','status','count','active','inactive'));
    }

    public function storeSalaryInfo() {

        $user_id = $_POST['user_id'];
        $fixed_salary = $_POST['fixed_salary'];
        $performance_bonus = $_POST['performance_bonus'];
        $total_salary = $_POST['total_salary'];

        \DB::statement("UPDATE users_otherinfo SET fixed_salary = '$fixed_salary', performance_bonus = '$performance_bonus', total_salary = '$total_salary' WHERE user_id  = '$user_id'");

        $data = "Success";
        return json_encode($data);
    }

    public function getUsersByDepartment() {

        $department_id = $_GET['department_id'];

        // get user names
        $users = User::getUsersByDepartmentId($department_id);

        return $users;
    }

    public function getJobUsersByDepartment() {

        $department_id = $_GET['department_id'];

        // get user names
        $users = User::getJobUsersByDepartmentId($department_id);

        $user_array = array();
        $i=0;

        if(isset($users) && sizeof($users) > 0) {

            foreach ($users as $key => $value) {
                
                $user_array[$i]['id'] = $value['id'];
                $user_array[$i]['name'] = $value['name'];
                $user_array[$i]['type'] = $value['type'];
                $i++;
            }
        }
        return $user_array;
    }

    public function addSignature($user_id) {

        $user = \Auth::user();
        $loggedin_user_id =  \Auth::user()->id;
        $superadmin_user_id = getenv('SUPERADMINUSERID');
        $saloni_user_id = getenv('SALONIUSERID');

        if ($loggedin_user_id == $user_id || $superadmin_user_id == $loggedin_user_id || $saloni_user_id == $loggedin_user_id) {

            $user_info = User::getProfileInfo($user_id);

            if(isset($user_info) && $user_info != '') {

                // Get Signature
                $signature = $user_info->signature;
            }

            return view('adminlte::users.mysignature',compact('user_id','signature'));
        }
        else {
            return view('errors.403');
        }
    }

    public function saveSignature($user_id) {

        // Get user info
        $user_other_info = UserOthersInfo::getUserOtherInfo($user_id);

        // Get Signature value
        $signature = Input::get('signature');

        // Update Signature in table
        $users_otherinfo_update = UserOthersInfo::find($user_other_info->id);   
        $users_otherinfo_update->signature = $signature;
        $users_otherinfo_update->save();

        return redirect()->route('users.myprofile',$user_id)->with('success','Signature Updated Successfully.'); 
    }
}