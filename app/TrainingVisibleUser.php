<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrainingVisibleUser extends Model
{
    public $table = "training_visible_users";
    public $timestamps = false;

    public static function getUserIdCount(){

    	$query = TrainingVisibleUser::query();
    	$query = $query->select(\DB::raw("COUNT(training_visible_users.user_id) as count"));
    	$query = $query->groupBy('training_visible_users.training_id');
    	$res = $query->get();

    	return $res;
    }
}
