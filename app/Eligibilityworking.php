<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Eligibilityworking extends Model
{

	public $table = "eligibility_working";

    public static function getCheckuserworkingreport($user_id,$month,$year){

    	$query = Eligibilityworking::query();
    	$query = $query->where('user_id',$user_id);
    	$query = $query->where('month',$month);
        $query = $query->where('year',$year);
    	$res = $query->first();

    	if (isset($res) && sizeof($res)>0) {
    		$eligibility_id = $res->id;
    	}
    	else {
    		$eligibility_id = '';
    	}

    	return $eligibility_id;
    }

    public static function getEligibilityDataByUser($user_id,$year,$first_month,$next_month){

        $query = Eligibilityworking::query();
        $query = $query->where('user_id',$user_id);
        $query = $query->where('year',$year);
        $query = $query->where('month','>=',"$first_month");
        $query = $query->where('month','<=',"$next_month");
        $query = $query->select('eligibility_working.*');
        $res = $query->get();

        $data = array();
        $i = 0;
        foreach ($res as $key => $value) {
            $data[$i]['id'] = $value->id;
            $data[$i]['user_id'] = $value->user_id;
            $data[$i]['target'] = $value->target;
            $data[$i]['achieved'] = $value->achieved;
            $data[$i]['eligibility'] = $value->eligibility;
            $data[$i]['month'] = $value->month;
            $data[$i]['year'] = $value->year;
            $i++;
        }

        return $data;
    }
}
