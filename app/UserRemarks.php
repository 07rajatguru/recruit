<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRemarks extends Model
{
    public $table = 'user_remarks';

    public static function getUserRemarksByUserid($user_id = 0) {

    	$query = UserRemarks::query();
        $query = $query->join('users','users.id','=','user_remarks.user_id');
    	$query = $query->select('user_remarks.*','users.name as user_name');
    	if (isset($user_id) && $user_id > 0) {
    		$query = $query->where('user_remarks.user_id',$user_id);
    	}
    	$res = $query->get();

        $remarks = array();
        $i=0;
        if(isset($res) && sizeof($res)>0) {
            foreach ($res as $key => $value) {
                $remarks[$i]['id'] = $value->id;
                $remarks[$i]['user_id'] = $value->user_id;
                $remarks[$i]['user_name'] = $value->user_name;
                $remarks[$i]['remark_date'] = $value->date;
                $remarks[$i]['converted_date'] = date("j S",strtotime($value->date));
                $remarks[$i]['remarks'] = $value->remarks;
                $i++;
            }
        }
    	return $remarks;
    }
}
