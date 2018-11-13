<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobVisibleUsers extends Model
{
    public $table = "job_visible_users";
    public $timestamps = false;

    public static function getAllUsersByJobId($job_id){

    	$query = JobVisibleUsers::query();
    	$query = $query->join('users','users.id','=','job_visible_users.user_id');
    	$query = $query->select('users.name as uname','users.id as uid');
    	$query = $query->where('job_visible_users.job_id','=',$job_id);
    	$res = $query->get();

    	$users_name = array();
    	foreach ($res as $key => $value) {
    		$users_name[$value->uid] = $value->uname;
    	}

    	return $users_name;
    }
}
