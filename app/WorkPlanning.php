<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkPlanning extends Model
{
    public $table = "work_planning";

    public static function getWorkType() {

        $work_type = array();
        
        $work_type['WEH'] = 'WFH';
        $work_type['WEO'] = 'WEO';

        return $work_type;
    }

    public static function getWorkPlanningDetails($all,$user_id) {

        $query = WorkPlanning::query();
        $query = $query->leftjoin('users','users.id','=','work_planning.added_by');
        $query = $query->orderBy('work_planning.id','DESC');

        if($all == 0) {
            $query = $query->where('work_planning.added_by','=',$user_id);
        }

        $query = $query->select('work_planning.*','users.name as added_by');
        $response = $query->get();

        $i=0;
        $work_planning_res = array();

        foreach ($response as $key => $value) {

            $work_planning_res[$i]['id'] = $value->id;
            $work_planning_res[$i]['added_by'] = $value->added_by;
            $work_planning_res[$i]['work_type'] = $value->work_type;
            $work_planning_res[$i]['added_date'] = date('d-m-Y', strtotime("$value->added_date"));
            $work_planning_res[$i]['loggedin_time'] = $value->loggedin_time;
            $work_planning_res[$i]['loggedout_time'] = $value->loggedout_time;
            
            $i++;
        }
        return $work_planning_res;
    }
}