<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Eligibilityworking;
use App\User;

class EligibilityReportController extends Controller
{
    public function index(){

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

        for ($m=$current_month; $m <= 15 ; $m+3) { 
            if ($m == 13) {
                $m = 1;

                $first_month = $m;
            	$next_month = $first_month + 2;
                $m = 13;
            }
            else {
            	$first_month = $m;
            	$next_month = $first_month + 2;

            	foreach ($users as $key => $value) {
		        	$eligible_data[$key] = Eligibilityworking::getEligibilityDataByUser($key,$year,$first_month,$next_month);
		        }
		        print_r($eligible_data);exit;
        	}
		}

    	return view('adminlte::reports.eligibilityreport',compact('year_array','year'));
    }
}
