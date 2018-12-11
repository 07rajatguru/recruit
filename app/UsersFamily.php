<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersFamily extends Model
{
    public $table = "users_family";

    public static function getAllFamilyDetailsofUser($user_id,$i){

    	$query = UsersFamily::query();
    	$query = $query->select('users_family.*');
    	$query = $query->where('user_id',$user_id);
    	$query = $query->where('family_id',$i);
    	$res = $query->first();

    	return $res;
    }
}
