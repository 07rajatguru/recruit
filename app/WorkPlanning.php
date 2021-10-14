<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WorkPlanning extends Model
{
    public $table = "work_planning";

    public static function getTimeArray() {

        $time_array = array();
        
        $time_array[0] = 'Select Time';

        $time_array['00:15:00'] = '15 Min.';
        $time_array['00:30:00'] = '30 Min.';
        $time_array['00:45:00'] = '45 Min.';

        $time_array['01:00:00'] = '1 Hours';
        $time_array['01:15:00'] = '1:15 Hours';
        $time_array['01:30:00'] = '1:30 Hours';
        $time_array['01:45:00'] = '1:45 Hours';

        $time_array['02:00:00'] = '2 Hours';
        $time_array['02:15:00'] = '2:15 Hours';
        $time_array['02:30:00'] = '2:30 Hours';
        $time_array['02:45:00'] = '2:45 Hours';

        $time_array['03:00:00'] = '3 Hours';
        $time_array['03:15:00'] = '3:15 Hours';
        $time_array['03:30:00'] = '3:30 Hours';
        $time_array['03:45:00'] = '3:45 Hours';

        $time_array['04:00:00'] = '4 Hours';
        $time_array['04:15:00'] = '4:15 Hours';
        $time_array['04:30:00'] = '4:30 Hours';
        $time_array['04:45:00'] = '4:45 Hours';

        $time_array['05:00:00'] = '5 Hours';
        $time_array['05:15:00'] = '5:15 Hours';
        $time_array['05:30:00'] = '5:30 Hours';
        $time_array['05:45:00'] = '5:45 Hours';

        $time_array['06:00:00'] = '6 Hours';
        $time_array['06:15:00'] = '6:15 Hours';
        $time_array['06:30:00'] = '6:30 Hours';
        $time_array['06:45:00'] = '6:45 Hours';

        $time_array['07:00:00'] = '7 Hours';
        $time_array['07:15:00'] = '7:15 Hours';
        $time_array['07:30:00'] = '7:30 Hours';
        $time_array['07:45:00'] = '7:45 Hours';

        $time_array['08:00:00'] = '8 Hours';
        $time_array['08:15:00'] = '8:15 Hours';
        $time_array['08:30:00'] = '8:30 Hours';
        $time_array['08:45:00'] = '8:45 Hours';

        $time_array['09:00:00'] = '9 Hours';
        $time_array['09:15:00'] = '9:15 Hours';
        $time_array['09:30:00'] = '9:30 Hours';
        $time_array['09:45:00'] = '9:45 Hours';

        $time_array['10:00:00'] = '10 Hours';
        $time_array['10:15:00'] = '10:15 Hours';
        $time_array['10:30:00'] = '10:30 Hours';
        $time_array['10:45:00'] = '10:45 Hours';

        $time_array['11:00:00'] = '11 Hours';

        return $time_array;
    }

    public static function getWorkType() {

        $work_type = array();
        
        $work_type['WFH'] = 'WFH';
        $work_type['WFO'] = 'WFO';

        return $work_type;
    }

    public static function getWorkPlanningDetails($all,$user_ids,$month,$year,$status='') {

        $query = WorkPlanning::query();
        $query = $query->leftjoin('users','users.id','=','work_planning.added_by');
        $query = $query->orderBy('work_planning.id','DESC');

        if($all == 0) {
            $query = $query->whereIn('work_planning.added_by',$user_ids);
        }

        if ($month != '' && $year != '') {
            $query = $query->where(\DB::raw('month(work_planning.added_date)'),'=',$month);
            $query = $query->where(\DB::raw('year(work_planning.added_date)'),'=',$year);
        }

        if(isset($status) && $status != '') {
            $query = $query->where('work_planning.status','=',$status);
        }

        $query = $query->select('work_planning.*','users.first_name as fnm','users.last_name as lnm');
        $response = $query->get();

        $i=0;
        $work_planning_res = array();

        foreach ($response as $key => $value) {

            $work_planning_res[$i]['id'] = $value->id;
            $work_planning_res[$i]['added_by_id'] = $value->added_by;
            $work_planning_res[$i]['added_by'] = $value->fnm . " " . $value->lnm;
            $work_planning_res[$i]['work_type'] = $value->work_type;
            $work_planning_res[$i]['added_date'] = date('d-m-Y', strtotime("$value->added_date"));

            // Convert Logged in time
            $utc_login = $value->loggedin_time;
            $dt_login = new \DateTime($utc_login);
            $tz_login = new \DateTimeZone('Asia/Kolkata');

            $dt_login->setTimezone($tz_login);
            $loggedin_time = $dt_login->format('H:i:s');
            $loggedin_time = date("g:i A", strtotime($loggedin_time));

            // Convert Logged in time
            $utc_logout = $value->loggedout_time;
            $dt_logout = new \DateTime($utc_logout);
            $tz_logout = new \DateTimeZone('Asia/Kolkata');

            $dt_logout->setTimezone($tz_logout);
            $loggedout_time = $dt_logout->format('H:i:s');
            $loggedout_time = date("g:i A", strtotime($loggedout_time));

            $work_planning_res[$i]['loggedin_time'] = $loggedin_time;
            $work_planning_res[$i]['loggedout_time'] = $loggedout_time;

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
            
            // For Pending/Approved/Rejected
            $work_planning_res[$i]['status'] = $value->status;
            $work_planning_res[$i]['report_delay'] = $value->report_delay;
            
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

            // Convert Logged in time
            $utc_login = $response->loggedin_time;
            $dt_login = new \DateTime($utc_login);
            $tz_login = new \DateTimeZone('Asia/Kolkata');

            $dt_login->setTimezone($tz_login);
            $loggedin_time = $dt_login->format('H:i:s');
            $loggedin_time = date("g:i A", strtotime($loggedin_time));

            // Convert Logged in time
            $utc_logout = $response->loggedout_time;
            $dt_logout = new \DateTime($utc_logout);
            $tz_logout = new \DateTimeZone('Asia/Kolkata');

            $dt_logout->setTimezone($tz_logout);
            $loggedout_time = $dt_logout->format('H:i:s');
            $loggedout_time = date("g:i A", strtotime($loggedout_time));

            $work_planning_res['loggedin_time'] = $loggedin_time;
            $work_planning_res['loggedout_time'] = $loggedout_time;

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

            $work_planning_res['remaining_time'] = $response->remaining_time;
            $work_planning_res['attendance'] = $response->attendance;
            $work_planning_res['status'] = $response->status;
            $work_planning_res['added_by_id'] = $response->added_by;
            $work_planning_res['appr_rejct_by'] = $response->approved_by;
            $work_planning_res['report_delay'] = $response->report_delay;
        }
        return $work_planning_res;
    }

    public static function getUsersAttendanceByWorkPlanning($user_id=0,$month,$year) {

        $superadmin_role_id =  getenv('SUPERADMIN');
        $client_role_id =  getenv('CLIENT');
        $it_role_id =  getenv('IT');
        $superadmin = array($superadmin_role_id,$client_role_id,$it_role_id);
        
        $status = 'Inactive';
        $status_array = array($status);

        $query = WorkPlanning::query();
        $query = $query->leftjoin('users','users.id','=','work_planning.added_by');
        $query = $query->leftjoin('users_otherinfo','users_otherinfo.user_id','=','users.id');
        $query = $query->join('role_user','role_user.user_id','=','users.id');
        $query = $query->leftjoin('department','department.id','=','users.type');
        
        if($month!=0 && $year!=0) {
            $query = $query->where(\DB::raw('MONTH(work_planning.added_date)'),'=', $month);
            $query = $query->where(\DB::raw('year(work_planning.added_date)'),'=', $year);
        }

        if($user_id>0) {
            $query = $query->where('users.id','=',$user_id);
        }
        else{
            $query = $query->whereNotIn('role_id',$superadmin);
        }

        $query = $query->select('users.id' ,'users.name','users.first_name','users.last_name','users.working_hours as working_hours','work_planning.added_date','work_planning.attendance','users_otherinfo.date_of_joining as joining_date','department.name as department_name');
        $query = $query->whereNotIn('status',$status_array);

        $response = $query->get();
        return $response;
    }
}