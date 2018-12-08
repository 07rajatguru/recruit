<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProcessVisibleUser extends Model
{
    public $table = "process_visible_users";
    public $timestamps = false;

    public static function getProcessUserIdCount($process_id){

    	$query = ProcessVisibleUser::query();
    	$query = $query->where('process_id',$process_id);
    	$res = $query->count();

    	return $res;
    }
}
