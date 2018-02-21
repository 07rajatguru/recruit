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
        $user_query = User::query();

        if($type!=NULL){
            $user_query = $user_query->where('type','=','recruiter');
        }

        $user_query = $user_query->orderBy('name');

        $users = $user_query->get();

        /*$users = User::select('*')
            ->get();*/

        $userArr = array();
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


}
