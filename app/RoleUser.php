<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    public $table = "role_user";
    public $timestamps = false;

    public static function getUserIdByRoleId($role_id) {

    	$query = RoleUser::query();
    	$query = $query->where('role_id',$role_id);
    	$query = $query->select('user_id');
    	$res = $query->first();

    	$user_id = 0;
    	if (isset($res) && $res != '') {
    		$user_id = $res->user_id;
    	}
    	return $user_id;
    }

    public static function getRoleIdByUserId($user_id) {

        $query = RoleUser::query();
        $query = $query->where('user_id',$user_id);
        $query = $query->select('role_id');
        $res = $query->first();

        $role_id = 0;
        if (isset($res) && $res != '') {
            $role_id = $res->role_id;
        }
        return $role_id;
    }
}