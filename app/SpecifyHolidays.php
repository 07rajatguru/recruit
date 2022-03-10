<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SpecifyHolidays extends Model
{
    public $table = "specify_holidays";

    public static function getUserHoliday($user_id) {

        $query = SpecifyHolidays::query();
        $query = $query->where('specify_holidays.user_id','=',$user_id);
        $query = $query->select('specify_holidays.*');
        $response = $query->first();

        return $response;
    }
}