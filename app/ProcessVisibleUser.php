<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProcessVisibleUser extends Model
{
    public $table = "process_visible_users";
    public $timestamps = false;

    public static function getProcessUserIdCount($process_id){

    	$status = 'Inactive';
        $status_array = array($status);

    	$query = ProcessVisibleUser::query();
    	$query = $query->join('users','users.id','=','process_visible_users.user_id');
    	$query = $query->where('process_id',$process_id);
    	$query = $query->whereNotIn('status',$status_array);
    	$res = $query->count();

    	return $res;
    }
}
