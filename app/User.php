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
        'name', 'email', 'password','company_id','type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function getUserArray($user_id){
        $users = User::select('*')
            ->where('users.id','!=',$user_id)
            ->get();

        $userArr = array();
        if(isset($users) && sizeof($users)){
            foreach ($users as $user) {
                $userArr[$user->id] = $user->name;
            }
        }

        return $userArr;
    }

    public static function getAllUsersExpectSuperAdmin($type=NULL){
        $superadmin = getenv('SUPERADMINUSERID');
        $super_array = array($superadmin);

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

    public static function getAllUsers($type=NULL,$am=NULL){
        $status = 'Inactive';

        $status_array = array($status);
        $user_query = User::query();

        if($type!=NULL){
            $user_query = $user_query->where('type','=',$type);
        }

        if($am!=NULL){
            $user_query = $user_query->where('account_manager','=',$am);
        }

        $user_query = $user_query->whereNotIn('status',$status_array);
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

    public static function getAllUsersWithInactive($type=NULL){

        $user_query = User::query();

        if($type!=NULL){
            $user_query = $user_query->where('type','=',$type);
        }

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


    public static function getAllUsersEmails($type=NULL){

        $status = 'Inactive';
        $superadmin = getenv('SUPERADMINUSERID');
        $status_array = array($status);
        $super_array = array($superadmin);

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
                $userArr[$user->id] = $user->email;
            }
        }

        return $userArr;
    }

    public static function getAllUsersSecondaryEmails($type=NULL)
    {
        $status = 'Inactive';
        $superadmin = getenv('SUPERADMINUSERID');
        $status_array = array($status);
        $super_array = array($superadmin);

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
                $userArr[$user->id] = $user->secondary_email;
            }
        }

        return $userArr;
    }

    public static function getAllUsersCopy($type=NULL){
        // $status = 'Inactive';
        // $status_array = array($status);
        
        $user_query = User::query();
        if($type!=NULL){
            $user_query = $user_query->where('type','=',$type);
        }
        // $user_query = $user_query->whereNotIn('status',$status_array);
        $user_query = $user_query->orderBy('name');
        $users = $user_query->get();

        /*$users = User::select('*')
            ->get();*/

        $userArr = array();
        $userArr[0] = '';
        if(isset($users) && sizeof($users)){
            foreach ($users as $user) {
                $userArr[$user->id] = $user->name;
            }
            // available inactive users for 6 months from the date of inactive
            foreach ($users as $user) {
                $user_status[$user->id] = $user->status;
                $user_updated_at[$user->id] = $user->updated_at;

                $user_date = $user_updated_at[$user->id];
                $after_six[$user->id] = date('Y-m-d',strtotime("$user_date +6 months"));
                $today = date('Y-m-d');
                if ($user_status[$user->id] == 'Inactive') {
                    if ($after_six[$user->id] < $today ) {
                        if (array_search($user->name,$userArr)) {
                            unset($userArr[array_search($user->name,$userArr)]);
                        }
                    }
                }
            }
        }

        return $userArr;
    }

    public static function getAllUsersCopyWithInactive($type=NULL){
        
        $user_query = User::query();

        if($type!=NULL){
            $user_query = $user_query->where('type','=',$type);
        }

        $user_query = $user_query->orderBy('name');
        $users = $user_query->get();

        $userArr = array();
        $userArr[0] = '';
        if(isset($users) && sizeof($users)){
            foreach ($users as $user) {
                $userArr[$user->id] = $user->name;
            }
        }

        return $userArr;
    }

    public static function getTypeArray(){
        $type = array();
        $type['admin'] = 'Admin Team';
        $type['recruiter'] = 'Recruitment Team';
        $type['it'] = 'IT Team';

        return $type;
    }

    public static function getAllUsersByCompanyId($company_id){
        $users = User::select('*')
            ->where('company_id','=',$company_id)
            ->get();

        $userArr = array();
        if(isset($users) && sizeof($users)){
            foreach ($users as $user) {
                $userArr[$user->id] = $user->name;
            }
        }

        return $userArr;
    }

    public static function getInterviewerArray(){
        $interviewerArray = array('' => 'Select Interviewer');

        $usersDetails = User::join('role_user','role_user.user_id','=','users.id')
            ->join('roles','roles.id','=','role_user.role_id')
            ->where('roles.name','=','Interviewer')
            ->select('users.id as id', 'users.name as name')
            ->get();

        if(isset($usersDetails) && sizeof($usersDetails) > 0){
            foreach ($usersDetails as $usersDetail) {
                $interviewerArray[$usersDetail->id] = $usersDetail->name;
            }
        }

        return $interviewerArray;
    }

    public static function isAdmin($user_role_id)
    {

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

    public static function isSuperAdmin($user_role_id)
    {
        $admin_role_id = env('SUPERADMIN');
        if ($admin_role_id == $user_role_id) {
            return true;
        }
        return false;
    }

    public static function isAccountant($user_role_id)
    {
        $admin_role_id = env('ACCOUNTANT');
        if ($admin_role_id == $user_role_id) {
            return true;
        }
        return false;
    }

    public static function isOfficeAdmin($user_role_id)
    {
        $admin_role_id = env('OFFICEADMIN');
        if ($admin_role_id == $user_role_id) {
            return true;
        }
        return false;
    }

    public  static function isStrategyCoordination($user_role_id){
        $admin_role_id = getenv('STRATEGY');
        if ($admin_role_id == $user_role_id) {
            return true;
        }
        return false;
    }

    public static function getUserIdByName($name){
        $user_id = 0;

        $user_query = User::query();
        $user_query = $user_query->where('name','like',$name);
        $user_query = $user_query->first();

        if(isset($user_query)){
            $user_id = $user_query->id;
        }

        return $user_id;

    }

    public static function getUserNameByEmail($email){
        $user_name = '';

        $user_query = User::query();
        $user_query = $user_query->where('email','like',$email);
        $user_query = $user_query->first();

        if(isset($user_query)){
            $user_name = $user_query->name;
        }

        return $user_name;

    }

    public static function getLoggedinUserRole($user){

        $roles = $user->roles->toArray();

        $user_role_id = $roles[0]['id'];

        return $user_role_id;
    }

    public static function getUsers($user_id=0){

        $query = User::query();

        if($user_id>0){
            $query = $query->where('id','=',$user_id);
        }

        $user_response = $query->get();

        $list = array();
        if(sizeof($user_response)>0){
            foreach ($user_response as $key => $value) {
                $list[$value->name]= "";
            }
        }

        return $list;
    }

    public static function getOtherUsers($user_id=0){

        $superadmin_role_id =  getenv('SUPERADMIN');
        $superadmin = array($superadmin_role_id);
        $status = 'Inactive';
        $status_array = array($status);
        $query = User::query();
        $query = $query->join('role_user','role_user.user_id','=','users.id');
        $query = $query->select('users.*','role_user.role_id as role_id');
        $query = $query->whereNotIn('status',$status_array);
        $query = $query->whereNotIn('role_id',$superadmin);

        if($user_id>0){
            $query = $query->where('id','=',$user_id);
        }

        $user_response = $query->get();

        $list = array();
        if(sizeof($user_response)>0)
        {
            foreach ($user_response as $key => $value) {
                $list[$value->name]= "";
            }
        }

//        print_r($user_response);exit;
        return $list;
    }

    public static function getUserNameById($user_id){

        $user_name = '';

        $user_query = User::query();
        $user_query = $user_query->where('id','=',$user_id);
        $user_query = $user_query->first();

        if(isset($user_query)){
            $user_name = $user_query->name;
        }

        return $user_name;
    }

    public static function getUserEmailById($user_id){

        $user_email = '';

        $user_query = User::query();
        $user_query = $user_query->where('id','=',$user_id);
        $user_query = $user_query->first();

        if(isset($user_query)){
            $user_email = $user_query->email;
        }

        return $user_email;
    }

    public static function getUserSecondaryEmailById($user_id)
    {
        $user_email = '';

        $user_query = User::query();
        $user_query = $user_query->where('id','=',$user_id);
        $user_query = $user_query->first();

        if(isset($user_query)){
            $user_email = $user_query->secondary_email;
        }

        return $user_email;
    }

    public static function getUsersReportToEmail($key){

        $user_query = User::query();
        $user_query = $user_query->select('users.id','u1.email','u1.secondary_email');
        $user_query = $user_query->join('users as u1','u1.id','=','users.reports_to');
        $user_query = $user_query->where('users.id','=',$key);
        $user_report = $user_query->first();

        return $user_report;
    }

    public static function getUsersFloorInchargeEmail($key){

        $user_query = User::query();
        $user_query = $user_query->select('users.id','u1.email','u1.secondary_email');
        $user_query = $user_query->join('users as u1','u1.id','=','users.floor_incharge');
        $user_query = $user_query->where('users.id','=',$key);
        $user_floor_incharge = $user_query->first();

        return $user_floor_incharge;
    }

    public static function getAssignedUsers($user_id,$type){

        $user_query = User::query();
        if($type!=NULL){
            $user_query = $user_query->where('type','=',$type);
        }

        $user_query = $user_query->where(function($user_query) use ($user_id){
            $user_query = $user_query->where('reports_to',$user_id);
            $user_query = $user_query->orwhere('floor_incharge',$user_id);
            $user_query = $user_query->orwhere('id',$user_id);
        });

        $user_query = $user_query->where('status','like','Active');
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

    public static function getReportsToUsersEmail($key){

        $user_query = User::query();
        $user_query = $user_query->select('users.id','u1.email as remail','u2.email as femail','u1.secondary_email as rsemail','u2.secondary_email as fsemail');
        $user_query = $user_query->join('users as u1','u1.id','=','users.reports_to');
        $user_query = $user_query->join('users as u2','u2.id','=','users.floor_incharge');
        $user_query = $user_query->where('users.id',$key);
        $user = $user_query->first();

        return $user;
    }

    public static function getRoleIdByUserId($user_id)
    {
        $query = User::query();
        $query = $query->join('role_user','role_user.user_id','=','users.id');
        $query = $query->select('role_user.role_id as role_id');
       // $query = $query->whereNotIn('status',$status_array);
        $query = $query->where('users.id',$user_id);

        $response = $query->first();

        return $response;
    }

    public static function getProfileInfo($user_id)
    {
        $query = User::query();
        $query = $query->leftjoin('role_user','role_user.user_id','=','users.id');
        $query = $query->leftjoin('roles','roles.id','=','role_user.role_id');
        $query = $query->leftjoin('users_otherinfo','users_otherinfo.user_id','=','users.id');
        $query = $query->leftjoin('users_doc','users_doc.user_id','=','users.id');
        $query = $query->select('users.name','users.email','users.secondary_email','roles.display_name as designation','users_otherinfo.*','users_doc.id as doc_id','users_doc.file','users_doc.type');
        $query = $query->where('users.id' ,'=',$user_id);
       
        $response = $query->get();

        return $response;
    }

    public static function getFloorInchargeById($user_id)
    {
        $user_floor_incharge = '';

        $query = User::query();
        $query = $query->select('floor_incharge');
      
        $query = $query->where('users.id',$user_id);

        $response = $query->first();

        if(isset($response)){
            $user_floor_incharge = $response->floor_incharge;
        }

        return $user_floor_incharge;
    }

    public static function getReportsToById($user_id)
    {
        $user_reports_to = '';

        $query = User::query();
        $query = $query->select('reports_to');
      
        $query = $query->where('users.id',$user_id);

        $response = $query->first();

        if(isset($response)){
            $user_reports_to = $response->reports_to;
        }

        return $user_reports_to;
    }
}