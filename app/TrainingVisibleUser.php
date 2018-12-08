<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrainingVisibleUser extends Model
{
    public $table = "training_visible_users";
    public $timestamps = false;

    public static function getUserIdCount($training_id){

    	$query = TrainingVisibleUser::query();
    	$query = $query->where('training_id',$training_id);
    	$res = $query->count();

    	return $res;
    }
}
