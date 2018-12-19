<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrainingVisibleUser extends Model
{
    public $table = "training_visible_users";
    public $timestamps = false;

    public static function getUserIdCount($training_id){

    	$status = 'Inactive';
        $status_array = array($status);

    	$query = TrainingVisibleUser::query();
    	$query = $query->join('users','users.id','=','training_visible_users.user_id');
    	$query = $query->where('training_id',$training_id);
    	$query = $query->whereNotIn('status',$status_array);
    	$res = $query->count();

    	return $res;
    }
}
