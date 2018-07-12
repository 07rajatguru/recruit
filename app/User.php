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

    public static function getAllUsers($type=NULL){
        $status = 'Inactive';
        $status_array = array($status);
        $user_query = User::query();

        if($type!=NULL){
            $user_query = $user_query->where('type','=',$type);
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

    public static function getAllUsersCopy($type=NULL){
        $status = 'Inactive';
        $status_array = array($status);
        
        $user_query = User::query();

        if($type!=NULL){
            $user_query = $user_query->where('type','=',$type);
        }

        $user_query = $user_query->whereNotIn('status',$status_array);
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
        // $status = 'Inactive';
        // $status_array = array($status);
        $query = User::query();
        $query = $query->join('role_user','role_user.user_id','=','users.id');
        $query = $query->select('users.*','role_user.role_id as role_id');
       // $query = $query->whereNotIn('status',$status_array);
        $query = $query->whereNotIn('role_id',$superadmin);

        if($user_id>0){
            $query = $query->where('id','=',$user_id);
        }

        $user_response = $query->get();

        $list = array();
        if(sizeof($query)>0){
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

    public static function getUsersReportToEmail($key){

        $user_query = User::query();
        $user_query = $user_query->select('users.id','u1.email');
        $user_query = $user_query->join('users as u1','u1.id','=','users.reports_to');
        $user_query = $user_query->where('users.id','=',$key);
        $user_report = $user_query->first();

        return $user_report;
    }

    public static function getUsersFloorInchargeEmail($key){

        $user_query = User::query();
        $user_query = $user_query->select('users.id','u1.email');
        $user_query = $user_query->join('users as u1','u1.id','=','users.floor_incharge');
        $user_query = $user_query->where('users.id','=',$key);
        $user_floor_incharge = $user_query->first();

        return $user_floor_incharge;
    }

}