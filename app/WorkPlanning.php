<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkPlanning extends Model
{
    public $table = "work_planning";

    public static function getTimeArray() {

        $time_array = array();
        
        $time_array['00:15'] = '15 Min.';
        $time_array['00:30'] = '30 Min.';
        $time_array['00:45'] = '45 Min.';

        $time_array['01:00'] = '1 Hours';
        $time_array['01:15'] = '1:15 Hours';
        $time_array['01:30'] = '1:30 Hours';
        $time_array['01:45'] = '1:45 Hours';

        $time_array['02:00'] = '2 Hours';
        $time_array['02:15'] = '2:15 Hours';
        $time_array['02:30'] = '2:30 Hours';
        $time_array['02:45'] = '2:45 Hours';

        $time_array['03:00'] = '3 Hours';
        $time_array['03:15'] = '3:15 Hours';
        $time_array['03:30'] = '3:30 Hours';
        $time_array['03:45'] = '3:45 Hours';

        $time_array['04:00'] = '4 Hours';
        $time_array['04:15'] = '4:15 Hours';
        $time_array['04:30'] = '4:30 Hours';
        $time_array['04:45'] = '4:45 Hours';

        $time_array['05:00'] = '5 Hours';
        $time_array['05:15'] = '5:15 Hours';
        $time_array['05:30'] = '5:30 Hours';
        $time_array['05:45'] = '5:45 Hours';

        $time_array['06:00'] = '6 Hours';
        $time_array['06:15'] = '6:15 Hours';
        $time_array['06:30'] = '6:30 Hours';
        $time_array['06:45'] = '6:45 Hours';

        $time_array['07:00'] = '7 Hours';
        $time_array['07:15'] = '7:15 Hours';
        $time_array['07:30'] = '7:30 Hours';
        $time_array['07:45'] = '7:45 Hours';

        $time_array['08:00'] = '8 Hours';
        $time_array['08:15'] = '8:15 Hours';
        $time_array['08:30'] = '8:30 Hours';
        $time_array['08:45'] = '8:45 Hours';

        $time_array['09:00'] = '9 Hours';
        $time_array['09:15'] = '9:15 Hours';
        $time_array['09:30'] = '9:30 Hours';
        $time_array['09:45'] = '9:45 Hours';

        $time_array['10:00'] = '10 Hours';
        $time_array['10:15'] = '10:15 Hours';
        $time_array['10:30'] = '10:30 Hours';
        $time_array['10:45'] = '10:45 Hours';

        return $time_array;
    }

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

        $query = $query->select('work_planning.*','users.first_name as fnm','users.last_name as lnm');
        $response = $query->get();

        $i=0;
        $work_planning_res = array();

        foreach ($response as $key => $value) {

            $work_planning_res[$i]['id'] = $value->id;
            $work_planning_res[$i]['added_by'] = $value->fnm . " " . $value->lnm;
            $work_planning_res[$i]['work_type'] = $value->work_type;
            $work_planning_res[$i]['added_date'] = date('d-m-Y', strtotime("$value->added_date"));
            $work_planning_res[$i]['loggedin_time'] = date("g:i A", strtotime($value->loggedin_time));
            $work_planning_res[$i]['loggedout_time'] = date("g:i A", strtotime($value->loggedout_time));

            // Convert Work Planning Time
            $utc_wp = $value->work_planning_time;
            $dt_wp = new \DateTime($utc_wp);
            $tz_wp = new \DateTimeZone('Asia/Kolkata');

            $dt_wp->setTimezone($tz_wp);
            $work_planning_time = $dt_wp->format('g:i A');
            $work_planning_res[$i]['work_planning_time'] = $work_planning_time;
                
            // Convert Work Planning Status Time
            $utc_wp_status = $value->work_planning_status_time;
            $dt_wp_status = new \DateTime($utc_wp_status);
            $tz_wp_status = new \DateTimeZone('Asia/Kolkata');

            $dt_wp_status->setTimezone($tz_wp_status);
            $work_planning_status_time = $dt_wp_status->format('g:i A');
            $work_planning_res[$i]['work_planning_status_time'] = $work_planning_status_time;
            
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
            $work_planning_res['loggedin_time'] = date("g:i A", strtotime($response->loggedin_time));
            $work_planning_res['loggedout_time'] = date("g:i A", strtotime($response->loggedout_time));

            // Convert Work Planning Time
            $utc_wp = $response->work_planning_time;
            $dt_wp = new \DateTime($utc_wp);
            $tz_wp = new \DateTimeZone('Asia/Kolkata');

            $dt_wp->setTimezone($tz_wp);
            $work_planning_time = $dt_wp->format('g:i A');
            $work_planning_res['work_planning_time'] = $work_planning_time;
                
            // Convert Work Planning Status Time
            $utc_wp_status = $response->work_planning_status_time;
            $dt_wp_status = new \DateTime($utc_wp_status);
            $tz_wp_status = new \DateTimeZone('Asia/Kolkata');

            $dt_wp_status->setTimezone($tz_wp_status);
            $work_planning_status_time = $dt_wp_status->format('g:i A');
            $work_planning_res['work_planning_status_time'] = $work_planning_status_time;
        }
        return $work_planning_res;
    }
}