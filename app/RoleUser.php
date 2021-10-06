<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    public $table = "role_user";
    public $timestamps = false;

    public static function getUserIdsByRoleId($role_id) {

    	$query = RoleUser::query();
    	$query = $query->where('role_id',$role_id);
    	$query = $query->select('user_id');
    	$response = $query->get();

    	$user_id_array = array();
        $i=0;

    	if (isset($response) && $response != '') {

            foreach ($response as $key => $value) {
                
                $user_id_array[$i] = $value->user_id;
                $i++;
            }
    	}
    	return $user_id_array;
    }

    public static function getRoleIdByUserId($user_id) {

        $query = RoleUser::query();
        $query = $query->where('user_id',$user_id);
        $query = $query->select('role_id');
        $response = $query->first();

        $role_id = 0;

        if (isset($response) && $response != '') {

            $role_id = $res->role_id;
        }
        return $role_id;
    }
}