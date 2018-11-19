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

    // Check job id and user id added to database or not
    public static function getCheckJobUserIdAdded($job_id,$user_id){

        $query = JobVisibleUsers::query();
        $query = $query->where('job_visible_users.job_id','=',$job_id);
        $query = $query->where('job_visible_users.user_id','=',$user_id);
        $res = $query->first();

        if (isset($res) && $res != '') {
            return true;
        }
        else{
            return false;
        }
    }
}
