<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\WorkPlanningList;

class WorkPlanning extends Model
{
    public $table = "work_planning";

    public static function getTimeArray() {

        $time_array = array();
        
        $time_array['00:00:00'] = 'Select Time';

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
        
        $work_type['WFO'] = 'WFO';
        $work_type['WFH'] = 'WFH';

        return $work_type;
    }

    public static function getWorkPlanningDetails($user_id,$month,$year,$status='',$post_discuss_status='') {

        $query = WorkPlanning::query();
        $query = $query->leftjoin('users','users.id','=','work_planning.added_by');
        $query = $query->orderBy('work_planning.added_date','DESC');
        
        if(isset($user_id) && $user_id != 0) {
            $query = $query->where('work_planning.added_by','=',$user_id);
        }
        
        if ($month != '' && $year != '') {
            $query = $query->where(\DB::raw('month(work_planning.added_date)'),'=',$month);
            $query = $query->where(\DB::raw('year(work_planning.added_date)'),'=',$year);
        }

        if(isset($status) && $status != '') {
            $query = $query->where('work_planning.status','=',$status);

            if(isset($status) && $status == '1' && $post_discuss_status == '') {
                $query = $query->where('work_planning.post_discuss_status','=',NULL);
            }
        }

        if(isset($post_discuss_status) && $post_discuss_status != '') {
            $query = $query->where('work_planning.post_discuss_status','=',$post_discuss_status);
        }

        $query = $query->select('work_planning.*','users.first_name as fnm','users.last_name as lnm');
        $response = $query->get();

        $i=0;
        $work_planning_res = array();

        // Get All Saturday dates of current month
        $date = "$year-$month-01";
        $first_day = date('N',strtotime($date));
        $first_day = 6 - $first_day + 1;
        $last_day =  date('t',strtotime($date));
        $saturdays = array();

        for($i = $first_day; $i <= $last_day; $i = $i+7 ) {
            $saturdays[] = $i;
        }

        // Check Saturday Date
        $saturday_date = $year."-".$month."-".$saturdays[2];

        if(isset($response) && sizeof($response) > 0) {

            foreach ($response as $key => $value) {

                // Check for sundays
                $added_day = date('l', strtotime("$value->added_date"));
                $get_date = date('Y-m-d', strtotime("$value->added_date"));
                $current_date = date('Y-m-d');

                // Check for holidays
                $holidays_dates = Holidays::checkUsersHolidays($value->added_by);

                if (in_array($get_date,$holidays_dates) && $value->loggedin_time == '' && $get_date >= $current_date) {
                }
                else if($added_day == 'Sunday' && $value->loggedin_time == '' && $get_date > $current_date) {
                }
                else if($added_day == 'Saturday' && $value->loggedin_time == '' && $get_date == $saturday_date) {
                }
                else if($value->loggedin_time == 'CO') {
                }
                else {

                    $work_planning_res[$i]['id'] = $value->id;
                    $work_planning_res[$i]['added_by_id'] = $value->added_by;
                    $work_planning_res[$i]['added_by'] = $value->fnm . " " . $value->lnm;
                    $work_planning_res[$i]['work_type'] = $value->work_type;
                    $work_planning_res[$i]['added_date'] = date('d-m-Y', strtotime("$value->added_date"));
                    $work_planning_res[$i]['created_at'] = date('d-m-Y', strtotime("$value->created_at"));

                    // Convert Logged in time
                    if($value->loggedin_time != '') {

                        $utc_login = $value->loggedin_time;
                        $dt_login = new \DateTime($utc_login);
                        $tz_login = new \DateTimeZone('Asia/Kolkata');

                        $dt_login->setTimezone($tz_login);
                        $loggedin_time = $dt_login->format('H:i:s');
                        $loggedin_time = date("g:i A", strtotime($loggedin_time));
                    }
                    else {

                        $loggedin_time = '';
                    }

                    $work_planning_res[$i]['loggedin_time'] = $loggedin_time;

                    // Convert Logged in time
                    if($value->loggedout_time != '') {

                        $utc_logout = $value->loggedout_time;
                        $dt_logout = new \DateTime($utc_logout);
                        $tz_logout = new \DateTimeZone('Asia/Kolkata');

                        $dt_logout->setTimezone($tz_logout);
                        $loggedout_time = $dt_logout->format('H:i:s');
                        $loggedout_time = date("g:i A", strtotime($loggedout_time));
                    }
                    else {

                        $loggedout_time = '';
                    }

                    $work_planning_res[$i]['loggedout_time'] = $loggedout_time;

                    // Convert Work Planning Time
                    if($value->work_planning_time != '') {

                        $utc_wp = $value->work_planning_time;
                        $dt_wp = new \DateTime($utc_wp);
                        $tz_wp = new \DateTimeZone('Asia/Kolkata');

                        $dt_wp->setTimezone($tz_wp);
                        $work_planning_time = $dt_wp->format('g:i A');
                        $work_planning_res[$i]['work_planning_time'] = $work_planning_time;
                    }
                    else {

                        $work_planning_res[$i]['work_planning_time'] = '';
                    }
            
                    // Convert Work Planning Status Time
                    if($value->work_planning_status_time != '') {

                        $utc_wp_status = $value->work_planning_status_time;
                        $dt_wp_status = new \DateTime($utc_wp_status);
                        $tz_wp_status = new \DateTimeZone('Asia/Kolkata');

                        $dt_wp_status->setTimezone($tz_wp_status);
                        $work_planning_status_time = $dt_wp_status->format('g:i A');
                        $work_planning_res[$i]['work_planning_status_time'] = $work_planning_status_time;
                    }
                    else {

                        $work_planning_res[$i]['work_planning_status_time'] = '';
                    }
                
                    // For Pending/Approved/Rejected
                    $work_planning_res[$i]['status'] = $value->status;
                    $work_planning_res[$i]['post_discuss_status'] = $value->post_discuss_status;

                    // For Delay Report
                    $work_planning_res[$i]['report_delay'] = $value->report_delay;
                    $work_planning_res[$i]['report_delay_content'] = $value->report_delay_content;

                    // Get All Task List
                    $work_planning_res[$i]['task_list'] = WorkPlanningList::getWorkPlanningList($value->id);

                    // Get Actual Database Login Logout Time
                    $work_planning_res[$i]['actual_login_time'] = date("H:i:s", strtotime($loggedin_time));
                    $work_planning_res[$i]['actual_logout_time'] = date("H:i:s", strtotime($loggedout_time));
                
                    // Get Total Projected & Actual Time
                    $work_planning_res[$i]['total_projected_time'] = $value->total_projected_time;
                    $work_planning_res[$i]['total_actual_time'] = $value->total_actual_time;

                    // Get Day from added date
                    $work_planning_res[$i]['added_day'] = date('l', strtotime("$value->added_date"));

                    // Get Delay Counter
                    $work_planning_res[$i]['delay_counter'] = $value->delay_counter;

                    // Get Work Planning Status Date
                    $status_date = date('d-m-Y', strtotime("$value->work_planning_status_date"));

                    if(isset($status_date) && $status_date != '01-01-1970') {

                        $work_planning_res[$i]['status_date'] = $status_date;
                    }
                    else {

                        $work_planning_res[$i]['status_date'] = '';
                    }

                    // Get Work Planning Attendance
                    $work_planning_res[$i]['attendance'] = $value->attendance;
                }

                $i++;
            }
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
            $work_planning_res['created_at'] = date('d-m-Y', strtotime("$response->created_at"));

            // Convert Logged in time
            if($response->loggedin_time != '') {

                $utc_login = $response->loggedin_time;
                $dt_login = new \DateTime($utc_login);
                $tz_login = new \DateTimeZone('Asia/Kolkata');

                $dt_login->setTimezone($tz_login);
                $loggedin_time = $dt_login->format('H:i:s');
                $loggedin_time = date("g:i A", strtotime($loggedin_time));

                $work_planning_res['loggedin_time'] = $loggedin_time;
            }
            else {

                $work_planning_res['loggedin_time'] = '';
            }

            // Convert Logged in time
            if($response->loggedout_time != '') {

                $utc_logout = $response->loggedout_time;
                $dt_logout = new \DateTime($utc_logout);
                $tz_logout = new \DateTimeZone('Asia/Kolkata');

                $dt_logout->setTimezone($tz_logout);
                $loggedout_time = $dt_logout->format('H:i:s');
                $loggedout_time = date("g:i A", strtotime($loggedout_time));

                $work_planning_res['loggedout_time'] = $loggedout_time;
            }
            else {

                $work_planning_res['loggedout_time'] = '';
            }

            // Convert Work Planning Time
            if($response->work_planning_time != '') {

                $utc_wp = $response->work_planning_time;
                $dt_wp = new \DateTime($utc_wp);
                $tz_wp = new \DateTimeZone('Asia/Kolkata');

                $dt_wp->setTimezone($tz_wp);
                $work_planning_time = $dt_wp->format('g:i A');
                $work_planning_res['work_planning_time'] = $work_planning_time;
            }
            else {

                $work_planning_res['work_planning_time'] = '';
            }
                
            // Convert Work Planning Status Time
            if($response->work_planning_status_time != '') {

                $utc_wp_status = $response->work_planning_status_time;
                $dt_wp_status = new \DateTime($utc_wp_status);
                $tz_wp_status = new \DateTimeZone('Asia/Kolkata');

                $dt_wp_status->setTimezone($tz_wp_status);
                $work_planning_status_time = $dt_wp_status->format('g:i A');
                $work_planning_res['work_planning_status_time'] = $work_planning_status_time;
            }
            else {

                $work_planning_res['work_planning_status_time'] = '';
            }

            $work_planning_res['remaining_time'] = $response->remaining_time;
            $work_planning_res['attendance'] = $response->attendance;
            $work_planning_res['status'] = $response->status;
            $work_planning_res['added_by_id'] = $response->added_by;
            $work_planning_res['appr_rejct_by'] = $response->approved_by;
            $work_planning_res['report_delay'] = $response->report_delay;
            $work_planning_res['report_delay_content'] = $response->report_delay_content;
            $work_planning_res['link'] = $response->link;
            $work_planning_res['reject_reply'] = $response->reject_reply;
            $work_planning_res['reason_of_rejection'] = $response->reason_of_rejection;
            $work_planning_res['total_projected_time'] = $response->total_projected_time;
            $work_planning_res['total_actual_time'] = $response->total_actual_time;
            $work_planning_res['evening_status'] = $response->evening_status;
            $work_planning_res['delay_counter'] = $response->delay_counter;
            $work_planning_res['approval_reply'] = $response->approval_reply;

            // Get Work Planning Status Date

            $status_date = date('d-m-Y',strtotime($response->work_planning_status_date));

            if(isset($status_date) && $status_date != '01-01-1970') {

                $work_planning_res['status_date'] = $status_date;
            }
            else {

                $work_planning_res['status_date'] = '';
            }
        }
        return $work_planning_res;
    }

    public static function getUsersAttendanceByWorkPlanning($user_id=0,$month,$year,$department_id='') {

        $superadmin_role_id =  getenv('SUPERADMIN');
        $client_role_id =  getenv('CLIENT');
        $it_role_id =  getenv('IT');
        $superadmin = array($superadmin_role_id,$client_role_id,$it_role_id);
        
        $status = 'Inactive';
        $status_array = array($status);

        $query = WorkPlanning::query();
        $query = $query->leftjoin('users','users.id','=','work_planning.added_by');
        $query = $query->join('role_user','role_user.user_id','=','users.id');
        $query = $query->leftjoin('department','department.id','=','users.type');
        
        if($month!=0 && $year!=0) {
            $query = $query->where(\DB::raw('MONTH(work_planning.added_date)'),'=', $month);
            $query = $query->where(\DB::raw('year(work_planning.added_date)'),'=', $year);
        }

        $query = $query->whereNotIn('users.status',$status_array);
        $query = $query->whereNotIn('role_id',$superadmin);

        if(isset($department_id) && $department_id != '') {

            if($department_id == 0) {
            }
            else {
                $query = $query->where('users.type','=',$department_id);
            }
        }
        else {

            if($user_id > 0) {
                $query = $query->where('users.reports_to','=',$user_id);
            }
        }

        $query = $query->select('users.id' ,'users.name','users.first_name','users.last_name','users.working_hours as working_hours','work_planning.added_date','work_planning.attendance','users.joining_date','department.name as department_name','work_planning.status','work_planning.loggedin_time','users.employment_type as employment_type');
        
        $response = $query->get();
        return $response;
    }

    public static function getWorkPlanningsByDate($from_date,$to_date) {

        $query = WorkPlanning::query();
        $query = $query->orderBy('work_planning.added_date','DESC');
        $query = $query->where('work_planning.evening_status','=',0);
        
        if(isset($from_date) && $from_date != '' && isset($to_date) && $to_date != '') {

            $query = $query->where('work_planning.added_date','>=',"$from_date");
            $query = $query->where('work_planning.added_date','<=',"$to_date");
        }

        $query = $query->select('work_planning.id','work_planning.added_by','work_planning.added_date');
        $work_planning_res = $query->get();

        return $work_planning_res;
    }

    public static function getPendingWorkPlanningDetails($user_id,$month,$year) {

        $query = WorkPlanning::query();
        $query = $query->leftjoin('users','users.id','=','work_planning.added_by');
        $query = $query->orderBy('work_planning.added_date','DESC');
        
        if(isset($user_id) && $user_id != 0) {
            $query = $query->where('work_planning.added_by','=',$user_id);
        }
        
        if ($month != '' && $year != '') {
            $query = $query->where(\DB::raw('month(work_planning.added_date)'),'=',$month);
            $query = $query->where(\DB::raw('year(work_planning.added_date)'),'=',$year);
        }
        $query = $query->where('work_planning.status','=',0);

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
            $work_planning_res[$i]['status'] = $value->status;
            $work_planning_res[$i]['post_discuss_status'] = $value->post_discuss_status;

            // Convert Logged in time
            if($value->loggedin_time != '') {

                $utc_login = $value->loggedin_time;
                $dt_login = new \DateTime($utc_login);
                $tz_login = new \DateTimeZone('Asia/Kolkata');

                $dt_login->setTimezone($tz_login);
                $loggedin_time = $dt_login->format('H:i:s');
                $loggedin_time = date("g:i A", strtotime($loggedin_time));
            }
            else {

                $loggedin_time = '';
            }

            $work_planning_res[$i]['loggedin_time'] = $loggedin_time;

            // Convert Logged out time
            if($value->loggedout_time != '') {

                $utc_logout = $value->loggedout_time;
                $dt_logout = new \DateTime($utc_logout);
                $tz_logout = new \DateTimeZone('Asia/Kolkata');

                $dt_logout->setTimezone($tz_logout);
                $loggedout_time = $dt_logout->format('H:i:s');
                $loggedout_time = date("g:i A", strtotime($loggedout_time));
            }
            else {

                $loggedout_time = '';
            }

            $work_planning_res[$i]['loggedout_time'] = $loggedout_time;

            // Convert Work Planning Time
            if($value->work_planning_time != '') {

                $utc_wp = $value->work_planning_time;
                $dt_wp = new \DateTime($utc_wp);
                $tz_wp = new \DateTimeZone('Asia/Kolkata');

                $dt_wp->setTimezone($tz_wp);
                $work_planning_time = $dt_wp->format('g:i A');
            }
            else {

                $work_planning_time = '';
            }
            $work_planning_res[$i]['work_planning_time'] = $work_planning_time;
                
            // Convert Work Planning Status Time
            if($value->work_planning_status_time != '') {

                $utc_wp_status = $value->work_planning_status_time;
                $dt_wp_status = new \DateTime($utc_wp_status);
                $tz_wp_status = new \DateTimeZone('Asia/Kolkata');

                $dt_wp_status->setTimezone($tz_wp_status);
                $work_planning_status_time = $dt_wp_status->format('g:i A');
            }
            else {

                $work_planning_status_time = '';
            }
            
            $work_planning_res[$i]['work_planning_status_time'] = $work_planning_status_time;

            // Get Actual Database Login Logout Time
            $work_planning_res[$i]['actual_login_time'] = date("H:i:s", strtotime($loggedin_time));
            $work_planning_res[$i]['actual_logout_time'] = date("H:i:s", strtotime($loggedout_time));

            // Get Total Projected & Actual Time
            $work_planning_res[$i]['total_projected_time'] = $value->total_projected_time;
            $work_planning_res[$i]['total_actual_time'] = $value->total_actual_time;

            // Get Day from added date
            $work_planning_res[$i]['added_day'] = date('l', strtotime("$value->added_date"));

            // Get Work Planning Status Date
            $status_date = date('d-m-Y',strtotime($value->work_planning_status_date));

            if(isset($status_date) && $status_date != '01-01-1970') {
                $work_planning_res[$i]['status_date'] = $status_date;
            }
            else {
                $work_planning_res[$i]['status_date'] = '';
            }

            $i++;
        }
        return $work_planning_res;
    }

    public static function getWorkPlanningByUserID($user_id,$month,$year) {

        $query = WorkPlanning::query();
        $query = $query->leftjoin('users','users.id','=','work_planning.added_by');
        $query = $query->leftjoin('users_otherinfo','users_otherinfo.user_id','=','users.id');
        $query = $query->join('role_user','role_user.user_id','=','users.id');
        $query = $query->leftjoin('department','department.id','=','users.type');

        $query = $query->where('users.id','=',$user_id);
        
        if($month!=0 && $year!=0) {
            $query = $query->where(\DB::raw('MONTH(work_planning.added_date)'),'=', $month);
            $query = $query->where(\DB::raw('year(work_planning.added_date)'),'=', $year);
        }

        // Get Previous data from joining date
        $check_date = $year."-".$month."-31";
        $query = $query->where('users.joining_date','<=',$check_date);

        $query = $query->select('users.id' ,'users.name','users.first_name','users.last_name','users.working_hours as working_hours','work_planning.added_date','work_planning.attendance','users_otherinfo.date_of_joining as joining_date','department.name as department_name','work_planning.status','work_planning.loggedin_time','users.employment_type as employment_type');

        $response = $query->get();
        return $response;
    }

    public static function getAttendanceByWorkPlanning($user_id=0) {

        $query = WorkPlanning::query();
        $query = $query->leftjoin('users','users.id','=','work_planning.added_by');
        $query = $query->where('work_planning.added_by','=',$user_id);

        $query = $query->select('users.id' ,'users.name','users.first_name','users.last_name','users.working_hours as working_hours','work_planning.added_date','work_planning.attendance','users.joining_date','work_planning.status','work_planning.loggedin_time');
        
        $response = $query->get();
        return $response;
    }

    public static function getWorkPlanningByAddedDateAndUserID($date,$user_id) {

        $query = WorkPlanning::query();
        $query = $query->where('work_planning.added_date','=',"$date");
        $query = $query->where('work_planning.added_by','=',$user_id);
        $query = $query->select('work_planning.id','work_planning.attendance','work_planning.status','work_planning.total_actual_time','work_planning.added_date');
        $work_planning_res = $query->first();

        return $work_planning_res;
    }

    public static function getPendingWorkPlanningsByDate($user_ids,$from_date,$to_date) {

        $query = WorkPlanning::query();
        $query = $query->leftjoin('users','users.id','=','work_planning.added_by');
        
        $query = $query->where('work_planning.status','=',0);
        $query = $query->whereIn('work_planning.added_by',$user_ids);

        if(isset($from_date) && $from_date != '' && isset($to_date) && $to_date != '') {

            $query = $query->where('work_planning.added_date','>=',"$from_date");
            $query = $query->where('work_planning.added_date','<=',"$to_date");
        }

        $query = $query->orderBy('work_planning.added_date','DESC');
        $query = $query->select('work_planning.*','users.first_name as fnm','users.last_name as lnm');
        $response = $query->get();

        $i=0;
        $work_planning_res = array();

        if(isset($response) && sizeof($response) > 0) {

            foreach ($response as $key => $value) {

                $work_planning_res[$i]['id'] = $value->id;
                $work_planning_res[$i]['added_by'] = $value->fnm . " " . $value->lnm;
                $work_planning_res[$i]['work_type'] = $value->work_type;
                $work_planning_res[$i]['added_date'] = date('d-m-Y', strtotime("$value->added_date"));

                // Convert Logged in time
                if($value->loggedin_time != '') {

                    $utc_login = $value->loggedin_time;
                    $dt_login = new \DateTime($utc_login);
                    $tz_login = new \DateTimeZone('Asia/Kolkata');

                    $dt_login->setTimezone($tz_login);
                    $loggedin_time = $dt_login->format('H:i:s');
                    $loggedin_time = date("g:i A", strtotime($loggedin_time));
                }
                else {

                    $loggedin_time = '';
                }

                $work_planning_res[$i]['loggedin_time'] = $loggedin_time;

                // Convert Logged in time
                if($value->loggedout_time != '') {

                    $utc_logout = $value->loggedout_time;
                    $dt_logout = new \DateTime($utc_logout);
                    $tz_logout = new \DateTimeZone('Asia/Kolkata');

                    $dt_logout->setTimezone($tz_logout);
                    $loggedout_time = $dt_logout->format('H:i:s');
                    $loggedout_time = date("g:i A", strtotime($loggedout_time));
                }
                else {

                    $loggedout_time = '';
                }

                $work_planning_res[$i]['loggedout_time'] = $loggedout_time;

                // Convert Work Planning Time
                if($value->work_planning_time != '') {

                    $utc_wp = $value->work_planning_time;
                    $dt_wp = new \DateTime($utc_wp);
                    $tz_wp = new \DateTimeZone('Asia/Kolkata');

                    $dt_wp->setTimezone($tz_wp);
                    $work_planning_time = $dt_wp->format('g:i A');
                    $work_planning_res[$i]['work_planning_time'] = $work_planning_time;
                }
                else {

                    $work_planning_res[$i]['work_planning_time'] = '';
                }
            
                // Convert Work Planning Status Time
                if($value->work_planning_status_time != '') {

                    $utc_wp_status = $value->work_planning_status_time;
                    $dt_wp_status = new \DateTime($utc_wp_status);
                    $tz_wp_status = new \DateTimeZone('Asia/Kolkata');

                    $dt_wp_status->setTimezone($tz_wp_status);
                    $work_planning_status_time = $dt_wp_status->format('g:i A');
                    $work_planning_res[$i]['work_planning_status_time'] = $work_planning_status_time;
                }
                else {

                    $work_planning_res[$i]['work_planning_status_time'] = '';
                }

                // Get Work Planning Status Date
                $status_date = date('d-m-Y', strtotime("$value->work_planning_status_date"));

                if(isset($status_date) && $status_date != '01-01-1970') {

                    $work_planning_res[$i]['status_date'] = $status_date;
                }
                else {

                    $work_planning_res[$i]['status_date'] = '';
                }

                $i++;
            }
        }
        return $work_planning_res;
    }

    public static function getDelayWorkPlanningDetails($user_id,$month,$year) {

        $query = WorkPlanning::query();
        $query = $query->where('work_planning.delay_counter','=',1);
        $query = $query->where('work_planning.added_by','=',$user_id);
        
        if ($month != '' && $year != '') {
            $query = $query->where(\DB::raw('month(work_planning.added_date)'),'=',$month);
            $query = $query->where(\DB::raw('year(work_planning.added_date)'),'=',$year);
        }

        $query = $query->select('work_planning.*');
        $response = $query->get();

        $i=0;
        $delay_work_planning = array();

        if(isset($response) && sizeof($response) > 0) {

            foreach ($response as $key => $value) {

                $delay_work_planning[$i]['id'] = $value->id;
                $delay_work_planning[$i]['delay_counter'] = $value->delay_counter;
                
                $i++;
            }
        }
        return $delay_work_planning;
    }

    public static function getUserTimeByWorkPlanning($user_id,$month,$year) {

        $query = WorkPlanning::query();
        $query = $query->where('work_planning.added_by','=',$user_id);
        $query = $query->where('work_planning.status','=',1);
        $query = $query->where('work_planning.loggedin_time','>','05:01:00');

        if ($month != '' && $year != '') {

            $query = $query->where(\DB::raw('month(work_planning.added_date)'),'=',$month);
            $query = $query->where(\DB::raw('year(work_planning.added_date)'),'=',$year);
        }
        
        $query = $query->select('work_planning.id','work_planning.added_date','work_planning.attendance','work_planning.status','work_planning.loggedin_time','work_planning.loggedout_time');
        
        $response = $query->get();
        return $response;
    }
}