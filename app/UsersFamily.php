<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersFamily extends Model
{
    public $table = "users_family";

    public static function getFamilyDetailsofUser($user_id,$i){

    	$query = UsersFamily::query();
    	$query = $query->select('users_family.*');
    	$query = $query->where('user_id',$user_id);
    	$query = $query->where('family_id',$i);
    	$res = $query->first();

    	return $res;
    }

    public static function getAllFamilyDetailsofUser($user_id){

        $query = UsersFamily::query();
        $query = $query->select('users_family.*');
        $query = $query->where('user_id',$user_id);
        $res = $query->get();

        $family = array();
        $i = 0;
        foreach ($res as $key => $value) {
            $family[$i]['id'] = $value->id;
            $family[$i]['name'] = $value->name;
            $family[$i]['relationship'] = $value->relationship;
            $family[$i]['occupation'] = $value->occupation;
            $family[$i]['contact_no'] = $value->contact_no;
            $i++;
        }

        return $family;
    }
}
