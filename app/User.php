<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{
    use Notifiable;
    use EntrustUserTrait; // add this trait to your user model

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','company_id','type','session_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public $users_upload_type = array(

        'SSC Marksheet'=>'SSC Marksheet',
        'HSC Marksheet'=>'HSC Marksheet',
        'University Certificate'=>'University Certificate',
        'Offer Letter'=>'Offer Letter',
        'Appraisal Letter'=>'Appraisal Letter',
        'Relieving Letter'=>'Relieving Letter',
        'Resignation Letter'=>'Resignation Letter',
        'Appointment Letter'=>'Appointment Letter',
        'Experience Letter'=>'Experience Letter',
        'Pay Slips'=>'Pay Slips',
        'Form - 26'=>'Form - 26',
        'ID Proof'=>'ID Proof',
        'Passport'=>'Passport',
        'PAN Card'=>'PAN Card',
        'Cancelled Cheque'=>'Cancelled Cheque',
        'Address Proof'=>'Address Proof',
        'Aadhar Card'=>'Aadhar Card',
        'Resume'=>'Resume',
        'Passport Photo'=>'Passport Photo'
    );

    public static function getHoursArray() {

        $hours_array = array();

        $hours_array['01:00:00'] = '1 Hours';
        $hours_array['01:15:00'] = '1:15 Hours';
        $hours_array['01:30:00'] = '1:30 Hours';
        $hours_array['01:45:00'] = '1:45 Hours';

        $hours_array['02:00:00'] = '2 Hours';
        $hours_array['02:15:00'] = '2:15 Hours';
        $hours_array['02:30:00'] = '2:30 Hours';
        $hours_array['02:45:00'] = '2:45 Hours';

        $hours_array['03:00:00'] = '3 Hours';
        $hours_array['03:15:00'] = '3:15 Hours';
        $hours_array['03:30:00'] = '3:30 Hours';
        $hours_array['03:45:00'] = '3:45 Hours';

        $hours_array['04:00:00'] = '4 Hours';
        $hours_array['04:15:00'] = '4:15 Hours';
        $hours_array['04:30:00'] = '4:30 Hours';
        $hours_array['04:45:00'] = '4:45 Hours';

        $hours_array['05:00:00'] = '5 Hours';
        $hours_array['05:15:00'] = '5:15 Hours';
        $hours_array['05:30:00'] = '5:30 Hours';
        $hours_array['05:45:00'] = '5:45 Hours';

        $hours_array['06:00:00'] = '6 Hours';
        $hours_array['06:15:00'] = '6:15 Hours';
        $hours_array['06:30:00'] = '6:30 Hours';
        $hours_array['06:45:00'] = '6:45 Hours';

        $hours_array['07:00:00'] = '7 Hours';
        $hours_array['07:15:00'] = '7:15 Hours';
        $hours_array['07:30:00'] = '7:30 Hours';
        $hours_array['07:45:00'] = '7:45 Hours';

        $hours_array['08:00:00'] = '8 Hours';
        $hours_array['08:15:00'] = '8:15 Hours';
        $hours_array['08:30:00'] = '8:30 Hours';
        $hours_array['08:45:00'] = '8:45 Hours';

        $hours_array['09:00:00'] = '9 Hours';
        $hours_array['09:15:00'] = '9:15 Hours';
        $hours_array['09:30:00'] = '9:30 Hours';
        $hours_array['09:45:00'] = '9:45 Hours';

        $hours_array['10:00:00'] = '10 Hours';
        $hours_array['10:15:00'] = '10:15 Hours';
        $hours_array['10:30:00'] = '10:30 Hours';
        $hours_array['10:45:00'] = '10:45 Hours';

        $hours_array['11:00:00'] = '11 Hours';

        return $hours_array;
    }

    public static function getAttendanceType() {

        $attendance_type = array();

        $attendance_type['self'] = 'Self';
        $attendance_type['team'] = 'Team';
        $attendance_type['adler'] = 'Adler';
        $attendance_type['recruitment'] = 'Recruitment';
        $attendance_type['hr-advisory'] = 'HR Advisory';
        $attendance_type['operations'] = 'Operations';

        return $attendance_type;
    }

    public static function getAttendanceValue() {

        $attendance_value = array();

        $attendance_value[''] = 'Select Attendance';
        $attendance_value['HD'] = 'HD - Half Day';
        $attendance_value['F'] = 'P - Present';
        $attendance_value['A'] = 'A - Absent';
        $attendance_value['PL'] = 'PL - Paid Leave';
        $attendance_value['SL'] = 'SL - Sick Leave';
        $attendance_value['UL'] = 'UL - Unapproved Leave';
        $attendance_value['PH'] = 'PH - Paid Holiday';
        $attendance_value['CO'] = 'CO - Compensatory Off';
        $attendance_value['H'] = 'H - Holiday';

        return $attendance_value;
    }

    public static function getTeamType() {

        $team_type = array();

        $team_type['recruitment'] = 'Recruitment';
        $team_type['hr-advisory'] = 'HR Advisory';
        $team_type['adler'] = 'Adler';

        return $team_type;
    }

    public static function getEmploymentType() {

        $employment_type = array();

        $employment_type['Employee'] = 'Employee';
        $employment_type['Professional'] = 'Professional';
        $employment_type['Trainee'] = 'Trainee';
        $employment_type['Intern'] = 'Intern';

        return $employment_type;
    }

    public static function getUserArray($user_id) {

        $status = 'Inactive';
        $status_array = array($status);

        $client = getenv('EXTERNAL');
        $client_type = array($client);

        $recruitment = getenv('RECRUITMENT');
        $hr_advisory = getenv('HRADVISORY');
        $management = getenv('MANAGEMENT');
        $type_array = array($recruitment,$hr_advisory,$management);
        
        $user_query = User::query();

        $user_query = $user_query->whereNotIn('status',$status_array);
        $user_query = $user_query->whereNotIn('type',$client_type);
        $user_query = $user_query->whereIn('type',$type_array);
        $user_query = $user_query->where('id','!=',$user_id);
        $user_query = $user_query->orderBy('name');
        $users = $user_query->get();

        $userArr = array();

        if(isset($users) && sizeof($users) > 0) {

            foreach ($users as $user) {

                $userArr[$user->id] = $user->name;
            }
        }
        return $userArr;
    }

    public static function getAllUsersExpectSuperAdmin($type=NULL) {

        $superadmin = getenv('SUPERADMINUSERID');
        $saloni_user_id = getenv('SALONIUSERID');
        $bizpos_user_id = getenv('BIZPOSUSERID');
        $super_array = array($superadmin,$saloni_user_id,$bizpos_user_id);

        $status = 'Inactive';
        $status_array = array($status);

        $client = getenv('EXTERNAL');
        $client_type = array($client);
        
        $user_query = User::query();

        if($type != NULL) {
            $user_query = $user_query->whereIn('type',$type);
        }

        $user_query = $user_query->whereNotIn('status',$status_array);
        $user_query = $user_query->whereNotIn('id',$super_array);
        $user_query = $user_query->whereNotIn('type',$client_type);
        $user_query = $user_query->orderBy('name');

        $users = $user_query->get();

        $userArr = array();
        if(isset($users) && sizeof($users)){
            foreach ($users as $user) {
                $userArr[$user->id] = $user->name;
            }
        }
        return $userArr;
    }

    public static function getAllUsers($type=NULL,$am=NULL) {

        $status = 'Inactive';
        $status_array = array($status);

        $client = getenv('EXTERNAL');
        $client_type = array($client);

        $saloni_user_id = getenv('SALONIUSERID');

        $user_query = User::query();

        if($type!=NULL){
            $user_query = $user_query->whereIn('type',$type);
        }

        if($am!=NULL){
            $user_query = $user_query->where('account_manager','=',$am);
        }

        $user_query = $user_query->whereNotIn('status',$status_array);
        $user_query = $user_query->whereNotIn('type',$client_type);
        $user_query = $user_query->where('id','!=',$saloni_user_id);
        $user_query = $user_query->orderBy('name');

        $users = $user_query->get();

        $userArr = array();
        if(isset($users) && sizeof($users)){
            foreach ($users as $user) {
                $userArr[$user->id] = $user->name;
            }
        }
        return $userArr;
    }

    public static function getAllUsersEmails($type=NULL,$report=NULL,$am=NULL,$user_id=0) {

        $status = 'Inactive';
        $status_array = array($status);

        $superadmin = getenv('SUPERADMINUSERID');
        $saloni_user_id = getenv('SALONIUSERID');
        $super_array = array($superadmin,$saloni_user_id);

        $user_query = User::query();

        if($type!=NULL) {
            $user_query = $user_query->where('type','=',$type);
        }

        if($report!=NULL) {
            $user_query = $user_query->where('daily_report','=',$report);
        }

        if($am!=NULL) {
            $user_query = $user_query->where('account_manager','=',$am);
        }

        if(isset($user_id) && $user_id > 0) {

            $user_query = $user_query->where(function($user_query) use ($user_id) {
                $user_query = $user_query->where('reports_to',$user_id);
            });
        }

        $user_query = $user_query->whereNotIn('status',$status_array);
        $user_query = $user_query->whereNotIn('id',$super_array);
        $user_query = $user_query->orderBy('name');

        $users = $user_query->get();

        $userArr = array();
        if(isset($users) && sizeof($users)) {
            foreach ($users as $user) {
                $userArr[$user->id] = $user->email;
            }
        }
        return $userArr;
    }

    public static function getTypeArray() {

        $type = array();
        $type['admin'] = 'Admin Team';
        $type['recruiter'] = 'Recruitment Team';
        $type['it'] = 'IT Team';
        $type['client'] = 'Client';
        $type['hr'] = 'HR';

        return $type;
    }

    public static function getInterviewerArray() {

        $interviewerArray = array('' => 'Select Interviewer');

        $usersDetails = User::join('role_user','role_user.user_id','=','users.id')
            ->join('roles','roles.id','=','role_user.role_id')->where('roles.name','=','Interviewer')
            ->select('users.id as id', 'users.name as name')->get();

        if(isset($usersDetails) && sizeof($usersDetails) > 0){
            foreach ($usersDetails as $usersDetail) {
                $interviewerArray[$usersDetail->id] = $usersDetail->name;
            }
        }
        return $interviewerArray;
    }

    public static function isClient($user_role_id) {

        $admin_role_id = getenv('CLIENT');
        if ($admin_role_id == $user_role_id) {
            return true;
        }
        return false;
    }

    public static function getUserIdByName($name) {

        $user_id = 0;

        $user_query = User::query();
        $user_query = $user_query->where('name','like',$name);
        $user_query = $user_query->first();

        if(isset($user_query)){
            $user_id = $user_query->id;
        }
        return $user_id;
    }

    public static function getUserNameByEmail($email) {

        $user_name = '';

        $user_query = User::query();
        $user_query = $user_query->where('email','like',$email);
        $user_query = $user_query->first();

        if(isset($user_query)){
            $user_name = $user_query->first_name . " " . $user_query->last_name;
        }
        return $user_name;
    }

    public static function getOtherUsers($user_id=0) {

        $superadmin_role_id =  getenv('SUPERADMIN');
        $client_role_id =  getenv('CLIENT');
        $it_role_id =  getenv('IT');
        $superadmin = array($superadmin_role_id,$client_role_id,$it_role_id);

        $status = 'Inactive';
        $status_array = array($status);
        
        $query = User::query();
        $query = $query->join('role_user','role_user.user_id','=','users.id');
        $query = $query->select('users.*','role_user.role_id as role_id');
        $query = $query->whereNotIn('status',$status_array);
        $query = $query->whereNotIn('role_id',$superadmin);

        if($user_id>0) {
            $query = $query->where('id','=',$user_id);
        }

        $query = $query->orderBy('users.joining_date','ASC');
        $user_response = $query->get();

        $list = array();
        if(sizeof($user_response)>0) {

            foreach ($user_response as $key => $value) {

                $full_name = $value->first_name."-".$value->last_name;
                $list[$full_name]= "";
            }
        }
        return $list;
    }

    public static function getOtherUsersNew($user_id=0,$department_id='',$month,$year) {

        if($month <= 9) {
            $month = "0".$month;
        }

        $superadmin_user_id = getenv('SUPERADMINUSERID');
        $saloni_user_id = getenv('SALONIUSERID');
        $bizpos_user_id = getenv('BIZPOSUSERID');

        $super_array = array($superadmin_user_id,$saloni_user_id,$bizpos_user_id);

        $status = 'Inactive';
        $status_array = array($status);

        $client = getenv('EXTERNAL');
        $client_type = array($client);
        
        $query = User::query();
        $query = $query->join('role_user','role_user.user_id','=','users.id');
        $query = $query->leftjoin('department','department.id','=','users.type');

        $query = $query->select('users.*','role_user.role_id as role_id','department.name as department_name');

        $query = $query->whereNotIn('users.status',$status_array);
        $query = $query->whereNotIn('users.id',$super_array);
        $query = $query->whereNotIn('users.type',$client_type);

        if(isset($department_id) && $department_id != '') {

            if($department_id == 0) {
            }
            else {
                $query = $query->where('users.type','=',$department_id);
            }
        }
        else {

            if($user_id > 0) {

                $query = $query->where('users.reports_to','=',$user_id);
            }
        }

        // Get Previous data from joining date
        $check_date = $year."-".$month."-31";
        $query = $query->where('users.joining_date','<=',$check_date);
        $query = $query->orderBy('users.joining_date','ASC');
        $user_response = $query->get();

        $list = array();
        if(sizeof($user_response)>0) {

            foreach ($user_response as $key => $value) {

                $joining_date = date('d/m/Y', strtotime("$value->joining_date"));
                $full_name = $value->first_name."-".$value->last_name.",".$value->department_name.",".$value->employment_type.",".$value->working_hours.",".$joining_date;
               
                $list[$full_name] = "";
            }
        }

        return $list;
    }

    public static function getUserNameById($user_id) {

        $user_name = '';

        $user_query = User::query();
        $user_query = $user_query->where('id','=',$user_id);
        $user_query = $user_query->first();

        if(isset($user_query)){
            $user_name = $user_query->name;
        }
        return $user_name;
    }

    public static function getUserEmailById($user_id) {

        $user_email = '';

        $user_query = User::query();
        $user_query = $user_query->where('id','=',$user_id);
        $user_query = $user_query->first();

        if(isset($user_query)){
            $user_email = $user_query->email;
        }
        return $user_email;
    }

    public static function getAssignedUsers($user_id) {

        $status = 'Inactive';
        $status_array = array($status);

        $client = getenv('EXTERNAL');
        $client_type = array($client);

        $user_query = User::query();
        
        $user_query = $user_query->where(function($user_query) use ($user_id) {
            $user_query = $user_query->where('reports_to',$user_id);
            $user_query = $user_query->orwhere('id',$user_id);
        });

        $user_query = $user_query->whereNotIn('status',$status_array);
        $user_query = $user_query->whereNotIn('type',$client_type);
        $user_query = $user_query->orderBy('name');

        $users = $user_query->get();

        $userArr = array();
        if(isset($users) && sizeof($users)){
            foreach ($users as $user) {
                $userArr[$user->id] = $user->name;
            }
        }
        return $userArr;
    }

    public static function getProfileInfo($user_id) {

        $query = User::query();
        $query = $query->leftjoin('role_user','role_user.user_id','=','users.id');
        $query = $query->leftjoin('roles','roles.id','=','role_user.role_id');
        $query = $query->leftjoin('users_otherinfo','users_otherinfo.user_id','=','users.id');
        $query = $query->leftjoin('users_doc','users_doc.user_id','=','users.id');
        $query = $query->leftjoin('companies','companies.id','=','users.company_id');
        $query = $query->leftjoin('department','department.id','=','users.type');
        $query = $query->leftjoin('users as u1','u1.id','=','users.reports_to');
        $query = $query->select('users.*','roles.display_name as designation','users_otherinfo.*','users_doc.id as doc_id','users_doc.file','users_doc.type','companies.name as company_name','department.name as department_name','u1.first_name as report_first_name','u1.last_name as report_last_name','role_user.role_id','users.type as department_id');
        $query = $query->where('users.id' ,'=',$user_id);
        $response = $query->first();

        return $response;
    }

    public static function getReportsToUsersEmail($key) {

        $user_query = User::query();
        $user_query = $user_query->select('users.id','u1.email as remail','u1.secondary_email as rsemail','users.type');
        $user_query = $user_query->join('users as u1','u1.id','=','users.reports_to');
        $user_query = $user_query->where('users.id',$key);
        $user = $user_query->first();

        return $user;
    }

    public static function getReportsToById($user_id) {

        $user_reports_to = '';

        $query = User::query();
        $query = $query->select('reports_to');
        $query = $query->where('users.id',$user_id);
        $response = $query->first();

        if(isset($response)) {
            $user_reports_to = $response->reports_to;
        }
        return $user_reports_to;
    }

    public static function getCompanyDetailsByUserID($user_id) {

        $user_query = User::query();
        $user_query = $user_query->leftjoin('companies','companies.id','=','users.company_id');
        $user_query = $user_query->where('users.id',$user_id);
        $user_query = $user_query->select('companies.*');
        $response = $user_query->first();

        return $response;
    }

    // function for user remarks dropdown
    public static function getAllUsersForRemarks($user_id,$department_id) {

        $superadmin_user_id = getenv('SUPERADMINUSERID');
        $saloni_user_id = getenv('SALONIUSERID');
        $bizpos_user_id = getenv('BIZPOSUSERID');
        
        $super_array = array($superadmin_user_id,$saloni_user_id,$bizpos_user_id);

        $status = 'Inactive';
        $status_array = array($status);

        $client = getenv('EXTERNAL');
        $client_type = array($client);

        $user_query = User::query();

        $user_query = $user_query->whereNotIn('status',$status_array);
        $user_query = $user_query->whereNotIn('id',$super_array);
        $user_query = $user_query->whereNotIn('type',$client_type);

        if(isset($department_id) && $department_id > 0) {
            $user_query = $user_query->where('users.type',$department_id);
        }
        else {

            if($user_id>0) {
                $user_query = $user_query->where('reports_to','=',$user_id);
            }
        }        

        $user_query = $user_query->orderBy('name');
        $users = $user_query->get();

        $userArr = array();
        if(isset($users) && sizeof($users)) {
            $userArr[""] = "Select User";
            foreach ($users as $user) {
                $userArr[$user->id] = $user->name;
            }
        }
        return $userArr;
    }

    public static function getAllDetailsByUserID($user_id) {

        $user_query = User::query();
        $user_query = $user_query->where('users.id',$user_id);
        $user_query = $user_query->select('users.*');
        $response = $user_query->first();

        return $response;
    }

    public static function getAllFloorInchargeUsers() {

        $status = 'Inactive';
        $status_array = array($status);

        $user_query = User::query();
        $user_query = $user_query->whereNotIn('status',$status_array);
        $user_query = $user_query->where('check_floor_incharge','Yes');
        $user_query = $user_query->orderBy('name');

        $users = $user_query->get();

        $userArr = array();
        if(isset($users) && sizeof($users)){
            foreach ($users as $user) {
                $userArr[$user->id] = $user->name;
            }
        }
        return $userArr;
    }

    public static function getAllUsersByJoiningDate() {

        $superadmin_role_id =  getenv('SUPERADMIN');
        $client_role_id =  getenv('CLIENT');
        $superadmin = array($superadmin_role_id,$client_role_id);

        $user_query = User::query();
        $user_query = $user_query->leftjoin('users_otherinfo','users_otherinfo.user_id','=','users.id');
        $user_query = $user_query->join('role_user','role_user.user_id','=','users.id');
        $user_query = $user_query->whereNotIn('role_id',$superadmin);
        $user_query = $user_query->orderBy('users_otherinfo.date_of_joining','ASC');
        $user_query = $user_query->select('users.id as id', 'users.name as name','users_otherinfo.date_of_joining as date_of_joining');
        $users = $user_query->get();

        $userArr = array();
        $i=0;

        if(isset($users) && sizeof($users)) {

            foreach ($users as $user) {

                $userArr[$i]['id'] = $user->id;
                $userArr[$i]['name'] = $user->name;
                $userArr[$i]['date_of_joining'] = $user->date_of_joining;
                $i++;
            }
        }
        return $userArr;
    }

    public static function getAllUsersForBenchmarkModal($type=NULL) {

        $superadmin = getenv('SUPERADMINUSERID');
        $allclient = getenv('ALLCLIENTVISIBLEUSERID');
        $strtegy = getenv('STRATEGYUSERID');
        $jasmine = getenv('JASMINEUSERID');

        $super_array = array($superadmin,$allclient,$strtegy,$jasmine);

        $status = 'Inactive';
        $status_array = array($status);
        
        $user_query = User::query();

        if($type!=NULL){
            $user_query = $user_query->where('type','=',$type);
        }

        $user_query = $user_query->whereNotIn('status',$status_array);
        $user_query = $user_query->whereNotIn('id',$super_array);
        $user_query = $user_query->orderBy('name');

        $users = $user_query->get();

        $userArr = array();
        if(isset($users) && sizeof($users)){
            foreach ($users as $user) {
                $userArr[$user->id] = $user->name;
            }
        }
        return $userArr;
    }

    public static function getAllUsersWithEmails() {

        $status = 'Inactive';
        $status_array = array($status);

        $client = getenv('EXTERNAL');
        $client_type = array($client);

        $user_query = User::query();

        $user_query = $user_query->whereNotIn('status',$status_array);
        $user_query = $user_query->whereNotIn('type',$client_type);
        $user_query = $user_query->orderBy('id');

        $users = $user_query->get();

        $userArr = array();

        if(isset($users) && sizeof($users)) {
            
            foreach ($users as $user) {
                $userArr[$user->id] = $user->email;
            }
        }
        return $userArr;
    }

    public static function getAllUsersForEligibilityReport($next_year=NULL,$type_array=NULL) {

        $status = 'Inactive';
        $status_array = array($status);

        $superadmin = getenv('SUPERADMINUSERID');
        $saloni_user_id = getenv('SALONIUSERID');
        $jasmine = getenv('JASMINEUSERID');
        $farhin = getenv('ALLCLIENTVISIBLEUSERID');
        $super_array = array($superadmin,$saloni_user_id,$jasmine,$farhin);
        
        $user_query = User::query();

        if(isset($next_year) && $next_year != NULL) {
            $user_query = $user_query->where('created_at','<=',$next_year);
        }

        if(isset($type_array) && $type_array != NULL) {
            $user_query = $user_query->whereIn('type',$type_array);
        }

        $user_query = $user_query->whereNotIn('status',$status_array);
        $user_query = $user_query->whereNotIn('id',$super_array);
        $user_query = $user_query->orderBy('name');
        $users = $user_query->get();

        $userArr = array();

        if(isset($users) && sizeof($users)) {
            
            foreach ($users as $user) {

                if($user->type == '2') {
                    if($user->hr_adv_recruitemnt == 'Yes') {
                        $userArr[$user->id] = $user->name;
                    }
               }
               else {
                    $userArr[$user->id] = $user->name;
               }
            }
        }
        return $userArr;
    }

    public static function getBefore7daysUsersDetails() {

        $date = date('Y-m-d h:m:s', strtotime('-7 days'));

        $status = 'Inactive';
        $status_array = array($status);

        $client = getenv('EXTERNAL');
        $client_type = array($client);

        $superadmin = getenv('SUPERADMINUSERID');
        $saloni_user_id = getenv('SALONIUSERID');
        $super_array = array($superadmin,$saloni_user_id);

        $query = User::query();

        // Get before 7 days users list
        $query = $query->where('users.created_at','<=',$date);
        $query = $query->whereNotIn('users.status',$status_array);
        $query = $query->whereNotIn('type',$client_type);
        $query = $query->whereNotIn('id',$super_array);
        $query = $query->select('users.id','users.first_name','users.last_name','users.email');

        $query = $query->groupBy('users.id');
        $query = $query->orderBy('users.id','ASC');
        $response = $query->get();

        $full_name_array = array();
        $i=0;

        if(isset($response) && sizeof($response) > 0) {

            foreach ($response as $key => $value) {

                $users_otherinfo = UserOthersInfo::getUserOtherInfo($value->id);

                if(isset($users_otherinfo->date_of_joining) && $users_otherinfo->date_of_joining != '') {
                }
                else {

                    $full_name_array[$i]['name'] = $value->first_name." - ".$value->last_name;
                    $full_name_array[$i]['email'] = $value->email;
                    $i++;
                }
            }
        }
        return $full_name_array;
    }

    public static function getUsersByDepartmentId($department_id) {

        $status = 'Inactive';
        $status_array = array($status);

        $client = getenv('EXTERNAL');
        $client_type = array($client);

        $saloni_user_id = getenv('SALONIUSERID');

        $query = User::query();
        $query = $query->whereNotIn('users.status',$status_array);
        $query = $query->whereNotIn('type',$client_type);

        if(isset($department_id) && $department_id > 0) {
            $query = $query->where('users.type',$department_id);
        }

        $query = $query->where('id','!=',$saloni_user_id);
        $query = $query->select('users.id','users.name','users.type');
        $query = $query->orderBy('users.name');
        $response = $query->get();

        $user_name_array = array();
        $i=0;

        if(isset($response) && sizeof($response) > 0) {

            foreach ($response as $key => $value) {

                $user_name_array[$i]['id'] = $value->id;
                $user_name_array[$i]['name'] = $value->name;
                $user_name_array[$i]['type'] = $value->type;
                
                $i++;
            }
        }
        return $user_name_array;
    }

    public static function getUsersByDepartmentIDArray($department_ids) {

        $department_ids_array = explode(",", $department_ids);
        
        $status = 'Inactive';
        $status_array = array($status);

        $client = getenv('EXTERNAL');
        $client_type = array($client);

        $saloni_user_id = getenv('SALONIUSERID');

        $query = User::query();
        $query = $query->whereNotIn('users.status',$status_array);
        $query = $query->whereNotIn('type',$client_type);
        $query = $query->whereIn('users.type',$department_ids_array);
        $query = $query->where('id','!=',$saloni_user_id);
        $query = $query->select('users.id','users.name','users.type');
        $query = $query->orderBy('users.name');
        $response = $query->get();

        $user_name_array = array();
        $i=0;

        if(isset($response) && sizeof($response) > 0) {

            foreach ($response as $key => $value) {

                $user_name_array[$i]['id'] = $value->id;
                $user_name_array[$i]['name'] = $value->name;
                $user_name_array[$i]['type'] = $value->type;
                
                $i++;
            }
        }
        return $user_name_array;
    }

    public static function getAllUsersBirthDateString() {

        $status = 'Inactive';
        $status_array = array($status);

        $client = getenv('EXTERNAL');
        $client_type = array($client);

        $superadmin = getenv('SUPERADMINUSERID');
        $saloni_user_id = getenv('SALONIUSERID');
        $super_array = array($superadmin,$saloni_user_id);

        $query = User::query();
        $query = $query->leftjoin('users_otherinfo','users_otherinfo.user_id','=','users.id');
        $query = $query->whereNotIn('users.status',$status_array);
        $query = $query->whereNotIn('users.type',$client_type);
        $query = $query->whereNotIn('users.id',$super_array);
        $query = $query->select('users.first_name','users.last_name','users_otherinfo.date_of_birth');
        $response = $query->get();

        $user_name_array = array();
        $i=0;
        $birthday_date_string = '';
        $today_date = date('d-m');
        $date_class = new Date();

        if(isset($response) && sizeof($response) > 0) {

            foreach ($response as $key => $value) {

                if(isset($value->date_of_birth) && $value->date_of_birth != NULL) {

                    $birth_date = date('d-m',strtotime($value->date_of_birth));

                    if($today_date == $birth_date) {

                        if($birthday_date_string == '') {
                            $birthday_date_string = $value->first_name;
                        }
                        else {
                            $birthday_date_string .= " & " . $value->first_name;
                        }
                    }
                }
                $i++;
            }
        }
        else {

            $birthday_date_string = '';
        }

        return $birthday_date_string;
    }

    public static function getAllUsersWorkAnniversaryDateString() {

        $status = 'Inactive';
        $status_array = array($status);

        $client = getenv('EXTERNAL');
        $client_type = array($client);

        $superadmin = getenv('SUPERADMINUSERID');
        $saloni_user_id = getenv('SALONIUSERID');
        $super_array = array($superadmin,$saloni_user_id);

        $query = User::query();
        $query = $query->leftjoin('users_otherinfo','users_otherinfo.user_id','=','users.id');
        $query = $query->whereNotIn('users.status',$status_array);
        $query = $query->whereNotIn('users.type',$client_type);
        $query = $query->whereNotIn('users.id',$super_array);
        $query = $query->select('users.first_name','users.last_name','users_otherinfo.date_of_joining');
        $response = $query->get();

        $user_name_array = array();
        $i=0;
        $work_ani_date_string = '';
        $today_date = date('d-m');
        $date_class = new Date();

        if(isset($response) && sizeof($response) > 0) {

            foreach ($response as $key => $value) {

                $date1 = date('Y');
                $date2 = date('Y',strtotime($value->date_of_joining));
                $year_diff = $date1 - $date2;
                $convert = date("S", mktime(0, 0, 0, 0, $year_diff, 0));
                $number = $year_diff.$convert;
                $joining_date = date('d-m',strtotime($value->date_of_joining));

                if(isset($value->date_of_joining) && $value->date_of_joining != NULL) {

                    if($today_date == $joining_date) {

                        if($work_ani_date_string == '') {

                            if($year_diff > 0) {
                                $work_ani_date_string = $value->first_name . "'s " . $number . " Year";
                            }
                        }
                        else {
                            if($year_diff > 0) {
                                $work_ani_date_string .= " & " . $value->first_name . "'s " . $number . " Year";
                            }
                        }
                    }
                }
                $i++;
            }
        }
        else {

            $work_ani_date_string = '';
        }
        
        return $work_ani_date_string;
    }

    public static function getDashboardUsers() {

        $status = 'Inactive';
        $status_array = array($status);

        $client = getenv('EXTERNAL');
        $client_type = array($client);

        $saloni_user_id = getenv('SALONIUSERID');

        $user_query = User::query();
        $user_query = $user_query->leftjoin('role_user','role_user.user_id','=','users.id');
        $user_query = $user_query->leftjoin('roles','roles.id','=','role_user.role_id');

        $user_query = $user_query->whereNotIn('users.status',$status_array);
        $user_query = $user_query->whereNotIn('users.type',$client_type);
        $user_query = $user_query->where('users.id','!=',$saloni_user_id);

        $user_query = $user_query->orderBy('users.id');
        $user_query = $user_query->select('users.id as uid','users.first_name as fnm','users.last_name as lnm','roles.name as role_name');

        $users = $user_query->get();

        $userArr = array();
        $i=0;

        if(isset($users) && sizeof($users)) {

            foreach ($users as $user) {

                $userArr[$i]['id'] = $user->uid;
                $userArr[$i]['name'] = $user->fnm . " " . $user->lnm;
                $userArr[$i]['role_name'] = $user->role_name;

                $user_doc_info = UsersDoc::getUserDocInfoByIDType($user->uid,'Photo');

                if(isset($user_doc_info) && $user_doc_info != '') {

                    $userArr[$i]['photo'] = $user_doc_info->file;
                }
                else {

                    $userArr[$i]['photo'] = '';
                }

                $i++;
            }
        }
        return $userArr;
    }

    public static function getBefore7daysUserSalaryDetails() {

        $date = date('Y-m-d h:m:s', strtotime('-7 days'));

        $status = 'Inactive';
        $status_array = array($status);

        $client = getenv('EXTERNAL');
        $client_type = array($client);

        $superadmin = getenv('SUPERADMINUSERID');
        $saloni_user_id = getenv('SALONIUSERID');
        $super_array = array($superadmin,$saloni_user_id);

        $query = User::query();

        // Get before 7 days users list
        $query = $query->where('users.created_at','<=',$date);
        $query = $query->whereNotIn('users.status',$status_array);
        $query = $query->whereNotIn('type',$client_type);
        $query = $query->whereNotIn('id',$super_array);
        $query = $query->select('users.id','users.first_name','users.last_name','users.email');

        $query = $query->groupBy('users.id');
        $query = $query->orderBy('users.id','ASC');
        $response = $query->get();

        $full_name_array = array();
        $i=0;

        if(isset($response) && sizeof($response) > 0) {

            foreach ($response as $key => $value) {

                $users_otherinfo = UserOthersInfo::getUserOtherInfo($value->id);

                if(isset($users_otherinfo->fixed_salary) && $users_otherinfo->fixed_salary != '') {
                }
                else {

                    $full_name_array[$i]['name'] = $value->first_name." - ".$value->last_name;
                    $full_name_array[$i]['email'] = $value->email;
                    $i++;
                }
            }
        }
        return $full_name_array;
    }

    public static function getJobUsersByDepartmentId($department_id) {

        $status = 'Inactive';
        $status_array = array($status);

        $client = getenv('EXTERNAL');
        $client_type = array($client);

        $saloni_user_id = getenv('SALONIUSERID');

        $query = User::query();
        $query = $query->whereNotIn('users.status',$status_array);
        $query = $query->whereNotIn('type',$client_type);

        if(isset($department_id) && $department_id > 0) {
            $query = $query->where('users.type',$department_id);
        }

        $query = $query->where('id','!=',$saloni_user_id);
        $query = $query->select('users.id','users.name','users.type','users.hr_adv_recruitemnt');
        $query = $query->orderBy('users.name');
        $response = $query->get();

        $user_name_array = array();
        $i=0;

        if(isset($response) && sizeof($response) > 0) {

            foreach ($response as $key => $value) {

                if($department_id == 2) {

                    if($value->hr_adv_recruitemnt == 'Yes') {

                        $user_name_array[$i]['id'] = $value->id;
                        $user_name_array[$i]['name'] = $value->name;
                        $user_name_array[$i]['type'] = $value->type;
                    }
                }
                else {

                    $user_name_array[$i]['id'] = $value->id;
                    $user_name_array[$i]['name'] = $value->name;
                    $user_name_array[$i]['type'] = $value->type;
                }
                $i++;
            }
        }
        return $user_name_array;
    }

    public static function getJobUsersByDepartmentIDArray($department_ids) {

        $department_ids_array = explode(",", $department_ids);
        
        $status = 'Inactive';
        $status_array = array($status);

        $client = getenv('EXTERNAL');
        $client_type = array($client);

        $saloni_user_id = getenv('SALONIUSERID');

        $query = User::query();
        $query = $query->whereNotIn('users.status',$status_array);
        $query = $query->whereNotIn('type',$client_type);
        $query = $query->whereIn('users.type',$department_ids_array);
        $query = $query->where('id','!=',$saloni_user_id);
        $query = $query->select('users.id','users.name','users.type','users.hr_adv_recruitemnt');
        $query = $query->orderBy('users.name');
        $response = $query->get();

        $user_name_array = array();
        $i=0;

        if(isset($response) && sizeof($response) > 0) {

            foreach ($response as $key => $value) {

                if($value->type == 2) {

                    if($value->hr_adv_recruitemnt == 'Yes') {

                        $user_name_array[$i]['id'] = $value->id;
                        $user_name_array[$i]['name'] = $value->name;
                        $user_name_array[$i]['type'] = $value->type;
                    }
                }
                else {

                    $user_name_array[$i]['id'] = $value->id;
                    $user_name_array[$i]['name'] = $value->name;
                    $user_name_array[$i]['type'] = $value->type;
                }
                $i++;
            }
        }
        return $user_name_array;
    }

    public static function getUserIdByBothName($u_name_array) {

        $values_array = explode("-", $u_name_array);

        $user_id = 0;

        $user_query = User::query();
        $user_query = $user_query->where('first_name','=',$values_array[0]);
        $user_query = $user_query->where('last_name','=',$values_array[1]);
        $user_query = $user_query->first();

        if(isset($user_query)) {
            $user_id = $user_query->id;
        }
        return $user_id;
    }

    public static function getUsersWorkAnniversaryDatesByMonth($month) {

        $year = date('Y');

        $status = 'Inactive';
        $status_array = array($status);

        $client = getenv('EXTERNAL');
        $client_type = array($client);

        $saloni_user_id = getenv('SALONIUSERID');
        $super_array = array($saloni_user_id);

        $query = User::query();
        $query = $query->whereNotIn('users.status',$status_array);
        $query = $query->whereNotIn('users.type',$client_type);
        $query = $query->whereNotIn('users.id',$super_array);

        if ($month != '') {

            $query = $query->where(\DB::raw('month(users.joining_date)'),'=',$month);
            $query = $query->where(\DB::raw('year(users.joining_date)'),'!=',$year);
        }

        $query = $query->select('users.first_name','users.last_name','users.joining_date');
        $query = $query->orderBy('users.joining_date','ASC');
        $response = $query->get();

        $users_array = array();

        if(isset($response) && sizeof($response) > 0) {

            foreach ($response as $key => $value) {

                $date1 = date('Y');
                $date2 = date('Y',strtotime($value->joining_date));
                $year_diff = $date1 - $date2;
                $convert = date("S", mktime(0, 0, 0, 0, $year_diff, 0));
                $number = $year_diff.$convert;

                $joining_date = date('jS F',strtotime($value->joining_date));
                $users_array[$value->first_name . " " . $value->last_name] = $joining_date;
            }
        }
        return $users_array;
    }

    public static function getUserBirthDatesByMonth($month) {

        $status = 'Inactive';
        $status_array = array($status);

        $client = getenv('EXTERNAL');
        $client_type = array($client);

        $saloni_user_id = getenv('SALONIUSERID');
        $super_array = array($saloni_user_id);

        $query = User::query();
        $query = $query->leftjoin('users_otherinfo','users_otherinfo.user_id','=','users.id');
        $query = $query->whereNotIn('users.status',$status_array);
        $query = $query->whereNotIn('users.type',$client_type);
        $query = $query->whereNotIn('users.id',$super_array);

        if ($month != '') {

            $query = $query->where(\DB::raw('month(users_otherinfo.date_of_birth)'),'=',$month);
        }

        $query = $query->select('users.first_name','users.last_name','users_otherinfo.date_of_birth');
        $query = $query->orderBy('users_otherinfo.date_of_birth','ASC');
        $response = $query->get();

        $users_array = array();
        
        if(isset($response) && sizeof($response) > 0) {

            foreach ($response as $key => $value) {

                $birth_date = date('jS F',strtotime($value->date_of_birth));

                $users_array[$value->first_name . " " . $value->last_name] = $birth_date;
            }
        }
        return $users_array;
    }

    public static function getUsersByJoiningDate($user_id,$department_id='') {

        $client = getenv('EXTERNAL');
        $client_type = array($client);

        $superadmin = getenv('SUPERADMINUSERID');
        $saloni_user_id = getenv('SALONIUSERID');
        $super_array = array($superadmin,$saloni_user_id);

        $user_query = User::query();

        if(isset($user_id) && $user_id > 0) {
            $user_query = $user_query->where('reports_to',$user_id);
        }

        if(isset($department_id) && $department_id != '') {
            $user_query = $user_query->whereIn('type',$department_id);
        }

        $user_query = $user_query->whereNotIn('type',$client_type);
        $user_query = $user_query->whereNotIn('id',$super_array);
        $user_query = $user_query->orderBy('joining_date','ASC');

        $users = $user_query->get();

        $userArr = array();

        if(isset($users) && sizeof($users)) {

            foreach ($users as $user) {

                $userArr[$user->id] = $user->name;
            }
        }
        return $userArr;
    }

    public static function getAllUserEmailsByID($user_ids) {

        $user_query = User::query();
        
        if(isset($user_ids) && $user_ids != '') {
            $user_query = $user_query->whereIn('id',$user_ids);
        }

        $user_query = $user_query->orderBy('name');

        $users = $user_query->get();

        $userArr = array();
        if(isset($users) && sizeof($users)) {
            foreach ($users as $user) {
                $userArr[$user->id] = $user->email;
            }
        }
        return $userArr;
    }

    public static function getDepartmentById($user_id) {

        $user_dept = '';

        $user_query = User::query();
        $user_query = $user_query->where('id','=',$user_id);
        $user_query = $user_query->first();

        if(isset($user_query)){
            $user_dept = $user_query->type;
        }
        return $user_dept;
    }
}