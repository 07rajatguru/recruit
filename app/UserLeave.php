<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserLeave extends Model
{
    //
    public $table="user_leave";

    public static function getLeaveType(){

        $type = array();
        $type['']='Select Leave Type';
        $type['Early/Late'] = 'Early Go/Late In';
        $type['Half'] = 'Half Day';
        $type['Full'] = 'Full Day';
        
        return $type;
    }

    public static function getLeaveCategory(){

        $type = array();
        $type['']='Select Leave Category';
        $type['Casual'] = 'Casual Leave';
        $type['Medical'] = 'Medical Leave';

        return $type;
    }
}
