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
    public function index() {

        // get logged in user
        $user =  \Auth::user();
        $all_perm = $user->can('display-eligibility-report-of-all-users');

        if ($all_perm) {

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
                $m = date('m');
                if ($m > 3) {
                    $n = $y + 1;
                    $year = $y.'-4-'.$n.'-3';
                }
                else{
                    $n = $y-1;
                    $year = $n.'-4-'.$y.'-3';
                }
            }

            $year_data = explode('-', $year); // [result : Array ( [0] => 2018 [1] => 4 [2] => 2019 [3] => 3 )] by default
            $current_year = $year_data[0]; // [result : 2018]
            $current_month = $year_data[1]; // [result : 4]
            $next_year = $year_data[2]; // [result : 2019]
            $next_month = $year_data[3]; // [result : 3]

            $users = User::getAllUsersForEligibilityReport();

            // For Quater wise[Q1, Q2, Q3, Q4]
            for ($m=$current_month; $m <= 15 ; $m=$m+3) {
                // For Quater 4 
                if ($m==13) {
                    $m = 1;

                    $next_month_three = $m+2;
                    $month_name = date("M", mktime(0, 0, 0, $m, 1));
                    $next_month_name = date("M", mktime(0, 0, 0, $next_month_three, 1));

                    $month_year = $month_name.'-'.$next_year;
                    $next_month_year = $next_month_name.'-'.$next_year;
                    $first_three = date('Y-m-d',strtotime("first day of $month_year"));
                    $last_three = date('Y-m-d',strtotime("last day of $next_month_year"));
                    
                    foreach ($users as $key => $value) {
                        $eligible_data[$value][$m.'-'.$next_month_three] = Eligibilityworking::getEligibilityDataByUser($key,$first_three,$last_three);
                    }
                    $m = 13;
                }
                // For Quater 1, 2, 3
                else{

                    $next_month_three = $m+2;
                    $month_name = date("M", mktime(0, 0, 0, $m, 1));
                    $next_month_name = date("M", mktime(0, 0, 0, $next_month_three, 1));

                    $month_year = $month_name.'-'.$current_year;
                    $next_month_year = $next_month_name.'-'.$current_year;
                    $first_three = date('Y-m-d',strtotime("first day of $month_year"));
                    $last_three = date('Y-m-d',strtotime("last day of $next_month_year"));
                    
                    foreach ($users as $key => $value) {
                        $eligible_data[$value][$m.'-'.$next_month_three] = Eligibilityworking::getEligibilityDataByUser($key,$first_three,$last_three);
                    }
                }
            }

            // For 6 months[April to Sept]
            for ($i=$current_month; $i < 10 ; $i=$i+6) { 
                
                $next_month_six = $i+5;
                $month_name = date("M", mktime(0, 0, 0, $i, 1));
                $next_month_name = date("M", mktime(0, 0, 0, $next_month_six, 1));

                $month_year = $month_name.'-'.$current_year;
                $next_month_year = $next_month_name.'-'.$current_year;
                $first_six = date('Y-m-d',strtotime("first day of $month_year"));
                $last_six = date('Y-m-d',strtotime("last day of $next_month_year"));
                
                foreach ($users as $key => $value) {
                    $eligible_detail[$value][$i.'-'.$next_month_six] = Eligibilityworking::getEligibilityDataByUser($key,$first_six,$last_six);
                }
            }

            // For 9 months[April to Dec]
            for ($i=$current_month; $i < 12 ; $i=$i+9) { 
                
                $next_month_nine = $i+8;
                $month_name = date("M", mktime(0, 0, 0, $i, 1));
                $next_month_name = date("M", mktime(0, 0, 0, $next_month_nine, 1));

                $month_year = $month_name.'-'.$current_year;
                $next_month_year = $next_month_name.'-'.$current_year;
                $first_nine = date('Y-m-d',strtotime("first day of $month_year"));
                $last_nine = date('Y-m-d',strtotime("last day of $next_month_year"));
                
                foreach ($users as $key => $value) {
                    $eligible_detail[$value][$i.'-'.$next_month_nine] = Eligibilityworking::getEligibilityDataByUser($key,$first_nine,$last_nine);
                }
            }

            // For 12 months[April to March]
            $month_name = date("M", mktime(0, 0, 0, $current_month, 1));
            $next_month_name = date("M", mktime(0, 0, 0, $next_month, 1));
            
            $month_year = $month_name.'-'.$current_year;
            $next_month_year = $next_month_name.'-'.$next_year;
            $first_year = date('Y-m-d',strtotime("first day of $month_year"));
            $last_year = date('Y-m-d',strtotime("last day of $next_month_year"));
            
            foreach ($users as $key => $value) {
                $eligible_detail[$value][$year] = Eligibilityworking::getEligibilityDataByUser($key,$first_year,$last_year);
            }

        	return view('adminlte::reports.eligibilityreport',compact('year_array','year','eligible_data','eligible_detail'));
        }
        else {
            return view('errors.403');
        }
    }

    public function export() {

        Excel::create('Eligibility Working Report',function($excel) {

            $excel->sheet('sheet 1',function($sheet) {
                
                if(isset($_POST['year']) && $_POST['year'] != '') {

                    $year = $_POST['year'];
                }
                else{
                    $y = date('Y');
                    $m = date('m');
                    if ($m > 3) {
                        $n = $y + 1;
                        $year = $y.'-4-'.$n.'-3';
                    }
                    else{
                        $n = $y-1;
                        $year = $n.'-4-'.$y.'-3';
                    }
                }

                $year_data = explode('-', $year);
                $current_year = $year_data[0];
                $current_month = $year_data[1];
                $next_year = $year_data[2];
                $next_month = $year_data[3];

                $users = User::getAllUsersForEligibilityReport();

                // For Quater wise[Q1, Q2, Q3, Q4]
                for ($m=$current_month; $m <= 15 ; $m=$m+3) {
                    // For Quater 4 
                    if ($m==13) {
                        $m = 1;

                        $next_month_three = $m+2;
                        $month_name = date("M", mktime(0, 0, 0, $m, 1));
                        $next_month_name = date("M", mktime(0, 0, 0, $next_month_three, 1));

                        $month_year = $month_name.'-'.$next_year;
                        $next_month_year = $next_month_name.'-'.$next_year;
                        $first_three = date('Y-m-d',strtotime("first day of $month_year"));
                        $last_three = date('Y-m-d',strtotime("last day of $next_month_year"));
                        
                        foreach ($users as $key => $value) {
                            $eligible_data[$value][$m.'-'.$next_month_three] = Eligibilityworking::getEligibilityDataByUser($key,$first_three,$last_three);
                        }
                        $m = 13;
                    }
                    // For Quater 1, 2, 3
                    else{
                        $next_month_three = $m+2;
                        $month_name = date("M", mktime(0, 0, 0, $m, 1));
                        $next_month_name = date("M", mktime(0, 0, 0, $next_month_three, 1));

                        $month_year = $month_name.'-'.$current_year;
                        $next_month_year = $next_month_name.'-'.$current_year;
                        $first_three = date('Y-m-d',strtotime("first day of $month_year"));
                        $last_three = date('Y-m-d',strtotime("last day of $next_month_year"));
                        
                        foreach ($users as $key => $value) {
                            $eligible_data[$value][$m.'-'.$next_month_three] = Eligibilityworking::getEligibilityDataByUser($key,$first_three,$last_three);
                        }
                    }
                }

                // For 6 months[April to Sept]
                for ($i=$current_month; $i < 10 ; $i=$i+6) { 
                    
                    $next_month_six = $i+5;
                    $month_name = date("M", mktime(0, 0, 0, $i, 1));
                    $next_month_name = date("M", mktime(0, 0, 0, $next_month_six, 1));

                    $month_year = $month_name.'-'.$current_year;
                    $next_month_year = $next_month_name.'-'.$current_year;
                    $first_six = date('Y-m-d',strtotime("first day of $month_year"));
                    $last_six = date('Y-m-d',strtotime("last day of $next_month_year"));
                    
                    foreach ($users as $key => $value) {
                        $eligible_detail[$value][$i.'-'.$next_month_six] = Eligibilityworking::getEligibilityDataByUser($key,$first_six,$last_six);
                    }
                }

                // For 9 months[April to Dec]
                for ($i=$current_month; $i < 12 ; $i=$i+9) { 
                    
                    $next_month_nine = $i+8;
                    $month_name = date("M", mktime(0, 0, 0, $i, 1));
                    $next_month_name = date("M", mktime(0, 0, 0, $next_month_nine, 1));

                    $month_year = $month_name.'-'.$current_year;
                    $next_month_year = $next_month_name.'-'.$current_year;
                    $first_nine = date('Y-m-d',strtotime("first day of $month_year"));
                    $last_nine = date('Y-m-d',strtotime("last day of $next_month_year"));
                    
                    foreach ($users as $key => $value) {
                        $eligible_detail[$value][$i.'-'.$next_month_nine] = Eligibilityworking::getEligibilityDataByUser($key,$first_nine,$last_nine);
                    }
                }

                // For 12 months[April to March]
                $month_name = date("M", mktime(0, 0, 0, $current_month, 1));
                $next_month_name = date("M", mktime(0, 0, 0, $next_month, 1));
                
                $month_year = $month_name.'-'.$current_year;
                $next_month_year = $next_month_name.'-'.$next_year;
                $first_year = date('Y-m-d',strtotime("first day of $month_year"));
                $last_year = date('Y-m-d',strtotime("last day of $next_month_year"));
                
                foreach ($users as $key => $value) {
                    $eligible_detail[$value][$year] = Eligibilityworking::getEligibilityDataByUser($key,$first_year,$last_year);
                }
                $eligible['eligible_data'] = $eligible_data;
                $eligible['eligible_detail'] = $eligible_detail;

                $sheet->loadview('adminlte::reports.eligibilityreportexport')->with('eligible',$eligible);
            });
        })->export('xls');
    }

    public function create() {

        // get logged in user
        $user =  \Auth::user();
        $all_perm = $user->can('display-eligibility-report-of-all-users');

        if ($all_perm) {

            $month_array = array();
            for ($i=1; $i <=12 ; $i++) { 
                $month_array[$i] = date('M',mktime(0,0,0,$i));
            }

            // Year Data
            $starting_year = '2017';
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
        else {
            return view('errors.403');
        }
    }

    public function store(Request $request) {

        $month = $request->get('month');
        $year = $request->get('year');

        $users = User::getAllUsersForEligibilityReport();
        
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

            foreach ($user_bill_data as $key1 => $value1) {
                $achieved = $achieved + $value1['person_billing'];
            }
            // Check Eligibility
            if ($achieved == 0) {
                $eligibility = 'false';
            }
            else if ($achieved >= $target) {
                $eligibility = 'true';
            }
            else {
                $eligibility = 'false';
            }

            $date = date('Y-m-d',strtotime("first day of $month_data"));

            // Add data in eligibility table
            $eligibility_data_id = Eligibilityworking::getCheckuserworkingreport($key,$month,$year);

            if (isset($eligibility_data_id) && $eligibility_data_id != '') {

                $eligible = Eligibilityworking::find($eligibility_data_id);
                $eligible->user_id = $key;
                $eligible->target = $target;
                $eligible->achieved = $achieved;
                $eligible->eligibility = $eligibility;
                $eligible->date = $date;
                $eligible->save();
            }
            else {

                $eligible = new Eligibilityworking();
                $eligible->user_id = $key;
                $eligible->target = $target;
                $eligible->achieved = $achieved;
                $eligible->eligibility = $eligibility;
                $eligible->date = $date;
                $eligible->save();
            }
        }
        return redirect('/eligibility-report');
    }
}