<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Eligibilityworking;
use App\User;
use Excel;
use App\UserOthersInfo;
use App\Bills;

class EligibilityReportController extends Controller
{
    public function index(){

        $user =  \Auth::user();
        $userRole = $user->roles->pluck('id','id')->toArray();
        $role_id = key($userRole);

        $user_obj = new User();
        $isSuperAdmin = $user_obj::isSuperAdmin($role_id);
        $isAccountant = $user_obj::isAccountant($role_id);
        if ($isSuperAdmin || $isAccountant) {
        	// Year Data
            $starting_year = '2017';
            $ending_year = date('Y',strtotime('+2 year'));
            $year_array = array();
            for ($y=$starting_year; $y < $ending_year ; $y++) {
                $next = $y+1;
                $year_array[$y.'-4-'.$next.'-3'] = 'April-' .$y.' to March-'.$next;
            }

            if(isset($_POST['year']) && $_POST['year'] != ''){
            	$year = $_POST['year'];
            }
            else{
            	$y = date('Y');
                $n = $y-1;
                $year = $n.'-4-'.$y.'-3';
            }

            $year_data = explode('-', $year);
            $current_year = $year_data[0];
            $current_month = $year_data[1];
            $next_year = $year_data[2];
            $next_month = $year_data[3];

            $users = User::getAllUsers('recruiter');

            for ($m=$current_month; $m <= 15 ; $m=$m+3) { 
                if ($m==13) {
                    $m = 1;

                    $first_month = $m;
                    $next_month = $first_month+2;
                    foreach ($users as $key => $value) {
                        $eligible_data[$value][$first_month.'-'.$next_month] = Eligibilityworking::getEligibilityDataByUser($key,$next_year,$first_month,$next_month);
                    }
                    $m = 13;
                }
                else{
                    $first_month = $m;
                    $next_month = $first_month+2;
                    foreach ($users as $key => $value) {
                        $eligible_data[$value][$first_month.'-'.$next_month] = Eligibilityworking::getEligibilityDataByUser($key,$current_year,$first_month,$next_month);
                    }
                }
            }

            for ($i=$current_month; $i < 10 ; $i=$i+6) { 
                
                $first = $i;
                $next = $first+5;
                foreach ($users as $key => $value) {
                    $eligible_detail[$value][$first.'-'.$next] = Eligibilityworking::getEligibilityDataByUser($key,$current_year,$first,$next);
                }
            }

            for ($i=$current_month; $i < 12 ; $i=$i+8) { 
                
                $first = $i;
                $next = $first+8;
                foreach ($users as $key => $value) {
                    $eligible_detail[$value][$first.'-'.$next] = Eligibilityworking::getEligibilityDataByUser($key,$current_year,$first,$next);
                }
            }
            //print_r($eligible_detail);exit();

        	return view('adminlte::reports.eligibilityreport',compact('year_array','year','eligible_data','eligible_detail'));
        }
        else {
            return view('errors.403');
        }
    }

    public function export(){

        Excel::create('Eligibility Working Report',function($excel){
            $excel->sheet('sheet 1',function($sheet){
                
                if(isset($_POST['year']) && $_POST['year'] != ''){
                    $year = $_POST['year'];
                }
                else{
                    $y = date('Y');
                    $n = $y-1;
                    $year = $n.'-4-'.$y.'-3';
                }

                $year_data = explode('-', $year);
                $current_year = $year_data[0];
                $current_month = $year_data[1];
                $next_year = $year_data[2];
                $next_month = $year_data[3];

                $users = User::getAllUsers('recruiter');

                for ($m=$current_month; $m <= 15 ; $m=$m+3) { 
                    if ($m==13) {
                        $m = 1;

                        $first_month = $m;
                        $next_month = $first_month+2;
                        foreach ($users as $key => $value) {
                            $eligible_data[$value][$first_month.'-'.$next_month] = Eligibilityworking::getEligibilityDataByUser($key,$next_year,$first_month,$next_month);
                        }
                        $m = 13;
                    }
                    else{
                        $first_month = $m;
                        $next_month = $first_month+2;
                        foreach ($users as $key => $value) {
                            $eligible_data[$value][$first_month.'-'.$next_month] = Eligibilityworking::getEligibilityDataByUser($key,$current_year,$first_month,$next_month);
                        }
                    }
                }
                
                $sheet->loadview('adminlte::reports.eligibilityreportexport')->with('eligible_data',$eligible_data);
            });
        })->export('xls');
    }

    public function create(){
        
        // Month data
        $month_array = array();
        for ($i=1; $i <=12 ; $i++) { 
            $month_array[$i] = date('M',mktime(0,0,0,$i));
        }

        // Year Data
        $starting_year = '2017'; /*date('Y',strtotime('-1 year'))*/;
        $ending_year = date('Y',strtotime('+3 year'));
        $default = date('Y');

        $year_array = array();
        for ($y=$starting_year; $y < $ending_year ; $y++) { 
            $year_array[$y] = $y;
        }
        $month = date('m');
        $year = date('Y');

        return view('adminlte::reports.eligibilityreportcreate',compact('month_array','year_array','month','year'));
    }

    public function store(Request $request){

        $month = $request->get('month');
        $year = $request->get('year');

        $users = User::getAllUsers('recruiter');
        foreach ($users as $key => $value) {
            $user_data = UserOthersInfo::getUserOtherInfo($key);
            $user_salary = $user_data['fixed_salary'];

            $target = $user_salary * 3.5;
            $achieved = 0;

            $month_name = date('M',mktime(0, 0, 0, $month, 1));
            $month_data = $month_name.'-'.$year;
            $start_month = date('Y-m-d',strtotime("first day of $month_data"));
            $last_month = date('Y-m-d',strtotime("last day of $month_data"));
            
            // get user billing amount
            $user_bill_data = Bills::getPersonwiseReportData($key,$start_month,$last_month);
            //print_r($user_bill_data);exit;
            foreach ($user_bill_data as $key1 => $value1) {
                $achieved = $achieved + $value1['person_billing'];
            }
            // Check Eligibility
            if ($achieved >= $target) {
                $eligibility = 'true';
            }
            else {
                $eligibility = 'false';
            }

            // Add data in eligibility table
            $eligibility_data_id = Eligibilityworking::getCheckuserworkingreport($key,$month,$year);
            if (isset($eligibility_data_id) && $eligibility_data_id != '') {
                $eligible = Eligibilityworking::find($eligibility_data_id);
                $eligible->user_id = $key;
                $eligible->target = $target;
                $eligible->achieved = $achieved;
                $eligible->eligibility = $eligibility;
                $eligible->month = $month;
                $eligible->year = $year;
                $eligible->save();
            }
            else {
                $eligible = new Eligibilityworking();
                $eligible->user_id = $key;
                $eligible->target = $target;
                $eligible->achieved = $achieved;
                $eligible->eligibility = $eligibility;
                $eligible->month = $month;
                $eligible->year = $year;
                $eligible->save();
            }
        }

        return redirect('/eligibility-report');
    }
}
