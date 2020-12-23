<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersEmailPwd extends Model
{
    public $table = "users_email_pwd";

    public static function getUserEmailDetails($user_id) {

        $user_query = UsersEmailPwd::query();
        $user_query = $user_query->where('users_email_pwd.user_id','=',$user_id);
        $user_query = $user_query->select('users_email_pwd.*');
        $response = $user_query->first();

        return $response;
    }
}