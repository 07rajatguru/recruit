<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserOthersInfo extends Model
{
    //
    public $table="users_otherinfo";

    public static function getUserOtherInfo($user_id)
    {
        $query = UserOthersInfo::query();
        $query = $query->where('user_id','=',$user_id);
        $query = $query->select('user_id','id','date_of_joining','fixed_salary');
        $response = $query->first();

        return $response;
    }
}
