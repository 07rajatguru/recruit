<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    public $table = "role_user";
    public $timestamps = false;

    public static function getUserIdByRoleId($role_id){

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
}
