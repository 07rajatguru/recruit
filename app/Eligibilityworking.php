<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Eligibilityworking extends Model
{

	public $table = "eligibility_working";

    public static function getCheckuserworkingreport($user_id,$month,$year){

    	$query = Eligibilityworking::query();
    	$query = $query->where('user_id',$user_id);
    	$query = $query->where(\DB::raw('MONTH(date)'),'=',$month);
        $query = $query->where(\DB::raw('YEAR(date)'),'=',$year);
    	$res = $query->first();

    	if (isset($res) && sizeof($res)>0) {
    		$eligibility_id = $res->id;
    	}
    	else {
    		$eligibility_id = '';
    	}

    	return $eligibility_id;
    }

    public static function getEligibilityDataByUser($user_id,$first,$last){

        $query = Eligibilityworking::query();
        $query = $query->where('user_id',$user_id);
        $query = $query->where('date','>=',$first);
        $query = $query->where('date','<=',$last);
        $query = $query->select('eligibility_working.*');
        $res = $query->get();

        $data = array();
        $target = 0;
        $achieved = 0;
        foreach ($res as $key => $value) {
            $target = $target + $value->target;
            $achieved = $achieved + $value->achieved;

            if ($achieved >= $target) {
                $eligibility = 'True';
            }
            else {
                $eligibility = 'False';
            }
            $data['target'] = $target;
            $data['achieved'] = $achieved;
            $data['eligibility'] = $eligibility;
        }

        return $data;
    }
}
