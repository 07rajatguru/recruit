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
        $query = $query->select('user_id','id');

        $response = $query->first();

        return $response;
    }

    public static function getLeaveType()
    {
        $type = array();
        $type['']='Select Leave Type';
        $type['Early'] = 'Early Go';
        $type['Half'] = 'Half Day';
        $type['Full'] = 'Full Day';
        return $type;
    }

    public static function getLeaveCategory()
    {
        $type = array();
        $type['']='Select Leave Category';
        $type['Casual'] = 'Casual Leave';
        $type['Medical'] = 'Medical Leave';
        return $type;
    }
}
