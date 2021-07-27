<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkPlanning extends Model
{
    public $table = "work_planning";

    public static function getWorkType() {

        $work_type = array();
        
        $work_type['WFH'] = 'WFH';
        $work_type['WFO'] = 'WFO';

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

    public static function getWorkPlanningDetailsById($id) {

        $query = WorkPlanning::query();
        $query = $query->leftjoin('users','users.id','=','work_planning.added_by');
        $query = $query->where('work_planning.id',$id);
        $query = $query->select('work_planning.*','users.first_name as fnm','users.last_name as lnm');
        $response = $query->first();

        $work_planning_res = array();

        if(isset($response) && $response != '') {

            $work_planning_res['id'] = $response->id;
            $work_planning_res['added_by'] = $response->fnm . " " . $response->lnm;
            $work_planning_res['work_type'] = $response->work_type;
            $work_planning_res['added_date'] = date('d-m-Y', strtotime("$response->added_date"));
            $work_planning_res['loggedin_time'] = $response->loggedin_time;
            $work_planning_res['loggedout_time'] = $response->loggedout_time;

            // Convert Time
            $utc_wp = $response->work_planning_time;
            $dt_wp = new \DateTime($utc_wp);
            $tz_wp = new \DateTimeZone('Asia/Kolkata');

            $dt_wp->setTimezone($tz_wp);
            $work_planning_time = $dt_wp->format('H:i:s');
            $work_planning_res['work_planning_time'] = $work_planning_time;

            if($response->work_planning_status_time == '') {

                $work_planning_res['work_planning_status_time'] = $response->work_planning_status_time;
            }
            else {
                
                $utc_wp_status = $response->work_planning_status_time;
                $dt_wp_status = new \DateTime($utc_wp_status);
                $tz_wp_status = new \DateTimeZone('Asia/Kolkata'); // or whatever zone you're after

                $dt_wp_status->setTimezone($tz_wp_status);
                $work_planning_status_time = $dt_wp_status->format('H:i:s');
                $work_planning_res['work_planning_status_time'] = $work_planning_status_time;
            }
        }
        return $work_planning_res;
    }
}