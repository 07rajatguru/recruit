<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HolidaysUsers extends Model
{
    public $table = "holidays_users";

    public static function checkUserHoliday($user_id,$holiday_id) {

        $query = HolidaysUsers::query();
        $query = $query->where('holidays_users.user_id','=',$user_id);
        $query = $query->where('holidays_users.holiday_id','=',$holiday_id);
        $response = $query->first();

        return $response;
    }
}