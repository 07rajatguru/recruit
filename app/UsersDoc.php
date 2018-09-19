<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersDoc extends Model
{
    //
    public $table = "users_doc";

    public static function getUserPhotoInfo($user_id)
    {
    	$query = UsersDoc::query();
    	$query = $query->where('user_id','=',$user_id);
        $query = $query->where('type','=','Photo');
    	$query = $query->select('users_doc.*');

    	$response = $query->first();

    	return $response;
    }
}
