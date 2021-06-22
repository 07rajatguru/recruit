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

    public static function getUserArray($user_id) {

        $status = 'Inactive';
        $status_array = array($status);

        $client = getenv('EXTERNAL');
        $client_type = array($client);

        $recruitment = getenv('RECRUITMENT');
        $hr_advisory = getenv('HRADVISORY');
        $type_array = array($recruitment,$hr_advisory);
        
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
        $super_array = array($superadmin,$saloni_user_id);

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

    public static function getAllUsersEmails($type=NULL,$report=NULL,$am=NULL) {

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

    public static function isAdmin($user_role_id) {

        $admin_role_id = env('ADMIN');
        $director_role_id = env('DIRECTOR');
        $super_admin_role_id = env('SUPERADMIN');

        if ($admin_role_id == $user_role_id) {
            return true;
        }
        if($director_role_id == $user_role_id){
            return true;
        }
        if($super_admin_role_id == $user_role_id){
            return true;
        }
        return false;
    }

    public static function isSuperAdmin($user_role_id) {

        $admin_role_id = env('SUPERADMIN');
        if ($admin_role_id == $user_role_id) {
            return true;
        }
        return false;
    }

    public static function isAccountant($user_role_id) {

        $admin_role_id = env('ACCOUNTANT');
        if ($admin_role_id == $user_role_id) {
            return true;
        }
        return false;
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
            $user_name = $user_query->name;
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

        $user_query = User::query();

        $status = 'Inactive';
        $status_array = array($status);

        $client = getenv('EXTERNAL');
        $client_type = array($client);

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
        $query = $query->select('users.*','roles.display_name as designation','users_otherinfo.*','users_doc.id as doc_id','users_doc.file','users_doc.type','companies.name as company_name','department.name as department_name','u1.first_name as report_first_name','u1.last_name as report_last_name');
        $query = $query->where('users.id' ,'=',$user_id);
        $response = $query->first();

        return $response;
    }

    public static function getReportsToUsersEmail($key) {

        $user_query = User::query();
        $user_query = $user_query->select('users.id','u1.email as remail','u1.secondary_email as rsemail');
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
    public static function getAllUsersForRemarks() {

        $superadmin = getenv('SUPERADMINUSERID');
        $saloni_user_id = getenv('SALONIUSERID');
        $super_array = array($superadmin,$saloni_user_id);

        $status = 'Inactive';
        $status_array = array($status);

        $client = getenv('EXTERNAL');
        $client_type = array($client);

        $user_query = User::query();

        $user_query = $user_query->whereNotIn('status',$status_array);
        $user_query = $user_query->whereNotIn('id',$super_array);
        $user_query = $user_query->whereNotIn('type',$client_type);
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
        $super_array = array($superadmin,$allclient,$strtegy);

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

    public static function getAllUsersForEligibilityReport() {

        $status = 'Inactive';
        $status_array = array($status);

        $recruitment = getenv('RECRUITMENT');
        $hr_advisory = getenv('HRADVISORY');
        $type_array = array($recruitment,$hr_advisory);
        
        $user_query = User::query();

        $user_query = $user_query->whereNotIn('status',$status_array);
        $user_query = $user_query->whereIn('type',$type_array);
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

                if(isset($users_otherinfo) && $users_otherinfo != '') {
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

        $saloni_user_id = getenv('SALONIUSERID');

        $query = User::query();
        $query = $query->leftjoin('users_otherinfo','users_otherinfo.user_id','=','users.id');
        $query = $query->whereNotIn('users.status',$status_array);
        $query = $query->whereNotIn('users.type',$client_type);
        $query = $query->where('users.id','!=',$saloni_user_id);
        $query = $query->select('users.first_name','users.last_name','users_otherinfo.date_of_birth');
        $response = $query->get();

        $user_name_array = array();
        $i=0;
        $birthday_date_string = '';
        $today_date = date('d-m');
        $date_class = new Date();

        if(isset($response) && sizeof($response) > 0) {

            foreach ($response as $key => $value) {

                $birth_date = date('d-m',strtotime($value->date_of_birth));

                if($today_date == $birth_date) {

                    if($birthday_date_string == '') {
                        $birthday_date_string = $value->first_name;
                    }
                    else {
                        $birthday_date_string .= " & " . $value->first_name;
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

        $saloni_user_id = getenv('SALONIUSERID');

        $query = User::query();
        $query = $query->leftjoin('users_otherinfo','users_otherinfo.user_id','=','users.id');
        $query = $query->whereNotIn('users.status',$status_array);
        $query = $query->whereNotIn('users.type',$client_type);
        $query = $query->where('users.id','!=',$saloni_user_id);
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
}