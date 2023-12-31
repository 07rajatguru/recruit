<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\User;
use App\Bills;
use App\Utils;
use App\Date;
use Excel;

class UserwiseReportController extends Controller
{
    public function index() {

    	$user = \Auth::user();
        $user_id = $user->id;

        $recruitment = getenv('RECRUITMENT');
        $hr_advisory = getenv('HRADVISORY');
        $management = getenv('MANAGEMENT');
        $type_array = array($recruitment,$hr_advisory,$management);

        $users_array = User::getAllUsers($type_array);
        $users = array();

        if(isset($users_array) && sizeof($users_array) > 0) {

            foreach ($users_array as $k1 => $v1) {
                           
                $user_details = User::getAllDetailsByUserID($k1);

                if($user_details->type == '2') {
                    if($user_details->hr_adv_recruitemnt == 'Yes') {
                        $users[$k1] = $v1;
                    }
                }
                else {
                    $users[$k1] = $v1;
                }    
            }
        }

    	$select = array('0'=>'Custom','1'=>'Monthly','2'=>'Quarterly');

    	// Month data
    	$month_array = array();
    	for ($i=1; $i <=12 ; $i++) { 
    		$month_array[$i] = date('M',mktime(0,0,0,$i));
        }

        // Year Data
        $starting_year = '2014'; /*date('Y',strtotime('-1 year'))*/;
        $ending_year = date('Y',strtotime('+2 year'));
        $default = date('Y');

        $year_array = array();
    	for ($y=$starting_year; $y < $ending_year ; $y++) { 
    		$year_array[$y] = $y;
        }

		// Quarter data
        $quater = array();
        $quater['0'] = 'Quarter 1(Apr-Jun)';
        $quater['1'] = 'Quarter 2(July-Sept)';
        $quater['2'] = 'Quarter 3(Oct-Dec)';
        $quater['3'] = 'Quarter 4(Jan-Mar)';

        $userdata   = Input::get('user');
        $user_name = User::getUserNameById($userdata);
        $selectdata = Input::get('select');
        $quaterdata = Input::get('quater');
      
        //print_r($selectdata);exit;

        // Custom wish
        if ($selectdata == 0) {
        	if(isset($_POST['from_date']) && $_POST['from_date']!=''){
                $month = $_POST['from_date'];
            }
            else{
                $month = '';
            }
            if(isset($_POST['to_date']) && $_POST['to_date']!=''){
                $year = $_POST['to_date'];
            }
            else{
                $year = '';
            }

            $date_class = new Date();


            $userwise_report = Bills::getUserwiseReport($userdata,'','',$month,$year);

            $userwise = array();
            $i = 0;
            $total = 0;
            foreach ($userwise_report as $key => $value) {

                //$fixed_salary = $value->fixed_salary;
                $salary = str_replace(",", "", $value->fixed_salary);
                $fixed_salary = round($salary);

                $percentage_charged = $value->percentage_charged;

                if($percentage_charged <= 0) {

                    $billing='0';
                }
                else {

                    $billing = ($fixed_salary * $percentage_charged) / 100;
                }
    
                $userwise[$i]['candidate_name'] = $value->fname;
                $userwise[$i]['company_name'] = $value->company_name;
                $userwise[$i]['position'] = $value->position;
                $userwise[$i]['fixed_salary'] = Utils::IND_money_format(round($fixed_salary));
                $userwise[$i]['billing'] = Utils::IND_money_format(round($billing));
                $userwise[$i]['joining_date'] = $date_class->changeYMDtoDMY($value->date_of_joining);

                $efforts = Bills::getEmployeeEffortsNameById($value->id);
            	$efforts_str = '';
            	foreach ($efforts as $key => $value) {
                    if($user_name == $key)
                    {
                        $t = ($billing * $value) / 100;
                        $total += round($t);   
                    }
                	if($efforts_str == ''){
                    	$efforts_str = $key . '(' . (int)$value . '%)';
                	}
                	else{
                    	$efforts_str .= ',' . $key . '(' . (int)$value . '%)';
                	}
            	}
            	$userwise[$i]['efforts'] = $efforts_str;
                $i++;
            }
        }

        // Month wish
        else if ($selectdata == 1) {
            if(isset($_POST['month']) && $_POST['month']!=''){
                $month = $_POST['month'];
            }
            else{
                $month = '';
            }
            if(isset($_POST['year']) && $_POST['year']!=''){
                $year = $_POST['year'];
            }
            else{
                $year = '';
            }

            $date_class = new Date();

            $userwise_report = Bills::getUserwiseReport($userdata,'','',$month,$year);
            $userwise = array();
            $i = 0;
            $total = 0;
            foreach ($userwise_report as $key => $value) {

                //$fixed_salary = $value->fixed_salary;
                $salary = str_replace(",", "", $value->fixed_salary);
                $fixed_salary = round($salary);

                $percentage_charged = $value->percentage_charged;

                if($percentage_charged <= 0) {

                    $billing='0';
                }
                else {

                    $billing = ($fixed_salary * $percentage_charged) / 100;
                }
                 
                $userwise[$i]['candidate_name'] = $value->fname;
                $userwise[$i]['company_name'] = $value->company_name;
                $userwise[$i]['position'] = $value->position;
                $userwise[$i]['fixed_salary'] = Utils::IND_money_format(round($fixed_salary));
                $userwise[$i]['billing'] = Utils::IND_money_format(round($billing));
                $userwise[$i]['joining_date'] = $date_class->changeYMDtoDMY($value->date_of_joining);

                $efforts = Bills::getEmployeeEffortsNameById($value->id);
            	$efforts_str = '';
            	foreach ($efforts as $key => $value) {
                    if($user_name == $key)
                    {
                        $t = ($billing * $value) / 100;
                        $total += round($t);  
                    }
                	if($efforts_str == ''){
                    	$efforts_str = $key . '(' . (int)$value . '%)';
                	}
                	else{
                    	$efforts_str .= ',' . $key . '(' . (int)$value . '%)';
                	}
            	}
            	$userwise[$i]['efforts'] = $efforts_str;
                $i++;
            }
        }

        // Ouater wise
        else if ($selectdata == 2) {
            if(isset($_POST['year']) && $_POST['year']!=''){
                $year = $_POST['year'];
            }
            else{
                $year = '';
            }

            // Get Quater 1-4
            if ($quaterdata == 0) {

                $m1 = date('m-d',strtotime("first day of april"));
                $m2 = date('m-d',strtotime("last day of june"));

                $date_class = new Date();

                $userwise_report = Bills::getUserwiseReport($userdata,$m1,$m2,'',$year);
                $userwise = array();
                $i = 0;
                $total = 0;

                foreach ($userwise_report as $key => $value) {

                    //$fixed_salary = $value->fixed_salary;
                    $salary = str_replace(",", "", $value->fixed_salary);
                    $fixed_salary = round($salary);

                    $percentage_charged = $value->percentage_charged;

                    if($percentage_charged <= 0) {
 
                        $billing = '0';
                    }
                    else {

                        $billing = ($fixed_salary * $percentage_charged) / 100;
                    }
                              
                    $userwise[$i]['candidate_name'] = $value->fname;
                    $userwise[$i]['company_name'] = $value->company_name;
                    $userwise[$i]['position'] = $value->position;
                    $userwise[$i]['fixed_salary'] = Utils::IND_money_format(round($fixed_salary));
                    $userwise[$i]['billing'] = Utils::IND_money_format(round($billing));
                    $userwise[$i]['joining_date'] = $date_class->changeYMDtoDMY($value->date_of_joining);

                    $efforts = Bills::getEmployeeEffortsNameById($value->id);
                	$efforts_str = '';
                	foreach ($efforts as $key => $value) {
                        if($user_name == $key)
                        {
                            $t = ($billing * $value) / 100;
                            $total += round($t);
                        }
                    	if($efforts_str == ''){
                        	$efforts_str = $key . '(' . (int)$value . '%)';
                    	}
                    	else{
                        	$efforts_str .= ',' . $key . '(' . (int)$value . '%)';
                    	}
                	}
                	$userwise[$i]['efforts'] = $efforts_str;
                    $i++;
                }
            }

        	if ($quaterdata == 1) {

                $m1 = date('m-d',strtotime("first day of july"));
                $m2 = date('m-d',strtotime("last day of september"));

                $date_class = new Date();

                $userwise_report = Bills::getUserwiseReport($userdata,$m1,$m2,'',$year);
                $userwise = array();
                $i = 0;
                $total = 0;

                foreach ($userwise_report as $key => $value) {

                    //$fixed_salary = $value->fixed_salary;
                    $salary = str_replace(",", "", $value->fixed_salary);
                    $fixed_salary = round($salary);

                    $percentage_charged = $value->percentage_charged;

                    if($percentage_charged <=0 ) {

                        $billing='0';
                    }
                    else {

                        $billing = ($fixed_salary * $percentage_charged) / 100;
                    }
              
                    $userwise[$i]['candidate_name'] = $value->fname;
                    $userwise[$i]['company_name'] = $value->company_name;
                    $userwise[$i]['position'] = $value->position;
                    $userwise[$i]['fixed_salary'] = Utils::IND_money_format(round($fixed_salary));
                    $userwise[$i]['billing'] = Utils::IND_money_format(round($billing));
                    $userwise[$i]['joining_date'] = $date_class->changeYMDtoDMY($value->date_of_joining);

                    $efforts = Bills::getEmployeeEffortsNameById($value->id);
                	$efforts_str = '';
                	foreach ($efforts as $key => $value) {
                        if($user_name == $key)
                        {
                            $t = ($billing * $value) / 100;
                            $total += round($t);
                        }
                    	if($efforts_str == ''){
                        	$efforts_str = $key . '(' . (int)$value . '%)';
                    	}
                    	else{
                        	$efforts_str .= ',' . $key . '(' . (int)$value . '%)';
                    	}
                	}
                	$userwise[$i]['efforts'] = $efforts_str;
                    $i++;
                }
        	}

        	if ($quaterdata == 2) {

                $m1 = date('m-d',strtotime("first day of october"));
                $m2 = date('m-d',strtotime("last day of December"));

                $date_class = new Date();

                $userwise_report = Bills::getUserwiseReport($userdata,$m1,$m2,'',$year);
                $userwise = array();
                $i = 0;
                $total = 0;
                foreach ($userwise_report as $key => $value) {

                    //$fixed_salary = $value->fixed_salary;
                    $salary = str_replace(",", "", $value->fixed_salary);
                    $fixed_salary = round($salary);

                    $percentage_charged = $value->percentage_charged;
                    
                    if($percentage_charged <=0 ) {

                        $billing='0';
                    }
                    else {

                        $billing = ($fixed_salary * $percentage_charged) / 100;
                    }
              
                    $userwise[$i]['candidate_name'] = $value->fname;
                    $userwise[$i]['company_name'] = $value->company_name;
                    $userwise[$i]['position'] = $value->position;
                    $userwise[$i]['fixed_salary'] = Utils::IND_money_format(round($fixed_salary));
                    $userwise[$i]['billing'] = Utils::IND_money_format(round($billing));
                    $userwise[$i]['joining_date'] = $date_class->changeYMDtoDMY($value->date_of_joining);

                    $efforts = Bills::getEmployeeEffortsNameById($value->id);
                	$efforts_str = '';
                	foreach ($efforts as $key => $value) {
                        if($user_name == $key)
                        {
                            $t = ($billing * $value) / 100;
                            $total += round($t);
                            
                        }
                    	if($efforts_str == ''){
                        	$efforts_str = $key . '(' . (int)$value . '%)';
                    	}
                    	else{
                        	$efforts_str .= ',' . $key . '(' . (int)$value . '%)';
                    	}
                	}
                	$userwise[$i]['efforts'] = $efforts_str;
                    $i++;
                }
        	}

        	if ($quaterdata == 3) {

                $m1 = date('m-d',strtotime("first day of january"));
                $m2 = date('m-d',strtotime("last day of march"));

                $date_class = new Date();

                $userwise_report = Bills::getUserwiseReport($userdata,$m1,$m2,'',$year);
                $userwise = array();
                $i = 0;
                $total = 0;

                foreach ($userwise_report as $key => $value) {

                    //$fixed_salary = $value->fixed_salary;
                    $salary = str_replace(",", "", $value->fixed_salary);
                    $fixed_salary = round($salary);

                    $percentage_charged = $value->percentage_charged;
                    
                    if($percentage_charged <= 0) {

                        $billing='0';
                    }
                    else {

                        $billing = ($fixed_salary * $percentage_charged) / 100;
                    }
              
                    $userwise[$i]['candidate_name'] = $value->fname;
                    $userwise[$i]['company_name'] = $value->company_name;
                    $userwise[$i]['position'] = $value->position;
                    $userwise[$i]['fixed_salary'] = Utils::IND_money_format(round($fixed_salary));
                    $userwise[$i]['billing'] = Utils::IND_money_format(round($billing));
                    $userwise[$i]['joining_date'] = $date_class->changeYMDtoDMY($value->date_of_joining);

                    $efforts = Bills::getEmployeeEffortsNameById($value->id);
                	$efforts_str = '';
                	foreach ($efforts as $key => $value) {
                        if($user_name == $key)
                        {
                            $t = ($billing * $value) / 100;
                            $total += round($t);
                        }
                    	if($efforts_str == ''){
                        	$efforts_str = $key . '(' . (int)$value . '%)';
                    	}
                    	else{
                        	$efforts_str .= ',' . $key . '(' . (int)$value . '%)';
                    	}
                	}
                	$userwise[$i]['efforts'] = $efforts_str;
                    $i++;
                }
        	}
        }

    	return view("adminlte::reports.userwise",compact('users','select','month_array','quater','year_array','default','userwise_report','userwise','user_name','total'));
    }

    public function export() {

        Excel::create('Userwise Report', function($excel) {

            $excel->sheet('Sheet 1', function($sheet) {
                $user = \Auth::user();
                $user_id = $user->id;

                $userdata   = Input::get('user');
                $selectdata = Input::get('select');
                $quaterdata = Input::get('quater');
                
                // Custom wish
                if ($selectdata == 0) {
                    if(isset($_POST['from_date']) && $_POST['from_date']!=''){
                        $month = $_POST['from_date'];
                    }
                    else{
                        $month = '';
                    }
                    if(isset($_POST['to_date']) && $_POST['to_date']!=''){
                        $year = $_POST['to_date'];
                    }
                    else{
                        $year = '';
                    }
                    $userwise_report = Bills::getUserwiseReportdata($userdata,'','',$month,$year);
                }

                // Month wish
                else if ($selectdata == 1) {
                    if(isset($_POST['month']) && $_POST['month']!=''){
                        $month = $_POST['month'];
                    }
                    else{
                        $month = '';
                    }
                    if(isset($_POST['year']) && $_POST['year']!=''){
                        $year = $_POST['year'];
                    }
                    else{
                        $year = '';
                    }
                    $userwise_report = Bills::getUserwiseReportdata($userdata,'','',$month,$year);
                }

                // Ouater wise
                else if ($selectdata == 2) {
                    if(isset($_POST['year']) && $_POST['year']!=''){
                        $year = $_POST['year'];
                    }
                    else{
                        $year = '';
                    }

                    // Get Quater 1-4
                    if ($quaterdata == 0) {

                        $m1 = date('m-d',strtotime("first day of april"));
                        $m2 = date('m-d',strtotime("last day of june"));
                        $userwise_report = Bills::getUserwiseReportdata($userdata,$m1,$m2,'',$year);
                    }

                    if ($quaterdata == 1) {

                        $m1 = date('m-d',strtotime("first day of july"));
                        $m2 = date('m-d',strtotime("last day of september"));
                        $userwise_report = Bills::getUserwiseReportdata($userdata,$m1,$m2,'',$year);
                    }

                    if ($quaterdata == 2) {

                        $m1 = date('m-d',strtotime("first day of october"));
                        $m2 = date('m-d',strtotime("last day of december"));
                        $userwise_report = Bills::getUserwiseReportdata($userdata,$m1,$m2,'',$year);
                    }

                    if ($quaterdata == 3) {

                        $m1 = date('m-d',strtotime("first day of january"));
                        $m2 = date('m-d',strtotime("last day of march"));
                        $userwise_report = Bills::getUserwiseReportdata($userdata,$m1,$m2,'',$year);
                    }
                }

                $sheet->fromArray($userwise_report, null, 'A1', false, false);

                $heading = array('Candidate Name','Company Name','Position/Dept','Salary Offered(fixed)','Billing','Joining Date','Efforts With');

                $sheet->prependRow(1,$heading);
            });
        })->export('xls');
    }
}
