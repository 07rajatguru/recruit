<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Bills;
use App\Date;
use Excel;

class SelectionReportController extends Controller
{
    public function index(){

    	$select = array('0'=>'Custom','1'=>'Monthly','2'=>'Quarterly','3'=>'Yearly');

        // Month data
    	$month_array = array();
    	for ($i=1; $i <=12 ; $i++) { 
    		$month_array[$i] = date('M',mktime(0,0,0,$i));
        }

        // Year Data
        $starting_year = '2017'; /*date('Y',strtotime('-1 year'))*/;
        $ending_year = date('Y',strtotime('+10 year'));
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

        $selectdata = Input::get('select');
        $quaterdata = Input::get('quater');

        // Custom wise
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
            //print_r($month);exit;

            $date_class = new Date();

            $selection_report = Bills::getSelectionReport('','',$month,$year);
            $selection = array();
            $i = 0;
            foreach ($selection_report as $key => $value) {
                $fixed_salary = $value->fixed_salary;
                $percentage_charged = (float)$value->percentage_charged;

                if($percentage_charged<=0)
                    $percentage_charged = 1;

                $billing = ((float)$fixed_salary * (float)$percentage_charged) / 100;
                $gst = ((float)$billing * 18 ) / 100;
                $invoice = (float)$billing+(float)$gst;
                $payment = (((float)$billing * 90) / 100) + (((float)$billing * 18) / 100);

                $selection[$i]['candidate_name'] = $value->fname;
                $selection[$i]['company_name'] = $value->company_name;
                $selection[$i]['position'] = $value->position;
                $selection[$i]['fixed_salary'] = $value->fixed_salary;
                $selection[$i]['billing'] = (float)$billing;
                $selection[$i]['gst'] = (float)$gst;
                $selection[$i]['invoice'] = (float)$invoice;
                $selection[$i]['payment'] = (float)$payment;
                $selection[$i]['joining_date'] = $date_class->changeYMDtoDMY($value->date_of_joining);
                $selection[$i]['contact_person'] = $value->client_name;
                $selection[$i]['location'] = $value->job_location;
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

            $selection_report = Bills::getSelectionReport('','',$month,$year);
            $selection = array();
            $i = 0;
            foreach ($selection_report as $key => $value) {
                $fixed_salary = $value->fixed_salary;
                $percentage_charged = (float)$value->percentage_charged;

                if($percentage_charged<=0)
                    $percentage_charged = 1;

                $billing = ((float)$fixed_salary * (float)$percentage_charged) / 100;
                $gst = ((float)$billing * 18 ) / 100;
                $invoice = (float)$billing+(float)$gst;
                $payment = (((float)$billing * 90) / 100) + (((float)$billing * 18) / 100);

                $selection[$i]['candidate_name'] = $value->fname;
                $selection[$i]['company_name'] = $value->company_name;
                $selection[$i]['position'] = $value->position;
                $selection[$i]['fixed_salary'] = $value->fixed_salary;
                $selection[$i]['billing'] = (float)$billing;
                $selection[$i]['gst'] = (float)$gst;
                $selection[$i]['invoice'] = (float)$invoice;
                $selection[$i]['payment'] = (float)$payment;
                $selection[$i]['joining_date'] = $date_class->changeYMDtoDMY($value->date_of_joining);
                $selection[$i]['contact_person'] = $value->client_name;
                $selection[$i]['location'] = $value->job_location;
                $i++;
            }
        }

        // Quater wise
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
                //print_r($m1);exit;

                $date_class = new Date();

                $selection_report = Bills::getSelectionReport($m1,$m2,'',$year);
                $selection = array();
                $i = 0;
                foreach ($selection_report as $key => $value) {
                    $fixed_salary = $value->fixed_salary;
                    $percentage_charged = (float)$value->percentage_charged;

                    if($percentage_charged<=0)
                        $percentage_charged = 1;

                    $billing = ((float)$fixed_salary * (float)$percentage_charged) / 100;
                    $gst = ((float)$billing * 18 ) / 100;
                    $invoice = (float)$billing+(float)$gst;
                    $payment = (((float)$billing * 90) / 100) + (((float)$billing * 18) / 100);

                    $selection[$i]['candidate_name'] = $value->fname;
                    $selection[$i]['company_name'] = $value->company_name;
                    $selection[$i]['position'] = $value->position;
                    $selection[$i]['fixed_salary'] = $value->fixed_salary;
                    $selection[$i]['billing'] = (float)$billing;
                    $selection[$i]['gst'] = (float)$gst;
                    $selection[$i]['invoice'] = (float)$invoice;
                    $selection[$i]['payment'] = (float)$payment;
                    $selection[$i]['joining_date'] = $date_class->changeYMDtoDMY($value->date_of_joining);
                    $selection[$i]['contact_person'] = $value->client_name;
                    $selection[$i]['location'] = $value->job_location;
                    $i++;
                }
            }

            if ($quaterdata == 1) {
                
                $m1 = date('m-d',strtotime("first day of july"));
                $m2 = date('m-d',strtotime("last day of september"));
                //print_r($m2);exit;

                $date_class = new Date();

                $selection_report = Bills::getSelectionReport($m1,$m2,'',$year);
                $selection = array();
                $i = 0;
                foreach ($selection_report as $key => $value) {
                    $fixed_salary = $value->fixed_salary;
                    $percentage_charged = (float)$value->percentage_charged;

                    if($percentage_charged<=0)
                        $percentage_charged = 1;

                    $billing = ((float)$fixed_salary * (float)$percentage_charged) / 100;
                    $gst = ((float)$billing * 18 ) / 100;
                    $invoice = (float)$billing+(float)$gst;
                    $payment = (((float)$billing * 90) / 100) + (((float)$billing * 18) / 100);

                    $selection[$i]['candidate_name'] = $value->fname;
                    $selection[$i]['company_name'] = $value->company_name;
                    $selection[$i]['position'] = $value->position;
                    $selection[$i]['fixed_salary'] = $value->fixed_salary;
                    $selection[$i]['billing'] = (float)$billing;
                    $selection[$i]['gst'] = (float)$gst;
                    $selection[$i]['invoice'] = (float)$invoice;
                    $selection[$i]['payment'] = (float)$payment;
                    $selection[$i]['joining_date'] = $date_class->changeYMDtoDMY($value->date_of_joining);
                    $selection[$i]['contact_person'] = $value->client_name;
                    $selection[$i]['location'] = $value->job_location;
                    $i++;
                }
            }

            if ($quaterdata == 2) {
                
                $m1 = date('m-d',strtotime("first day of october"));
                $m2 = date('m-d',strtotime("last day of December"));
                //print_r($m2);exit;

                $date_class = new Date();

                $selection_report = Bills::getSelectionReport($m1,$m2,'',$year);
                $selection = array();
                $i = 0;
                foreach ($selection_report as $key => $value) {
                    $fixed_salary = $value->fixed_salary;
                    $percentage_charged = (float)$value->percentage_charged;

                    if($percentage_charged<=0)
                        $percentage_charged = 1;

                    $billing = ((float)$fixed_salary * (float)$percentage_charged) / 100;
                    $gst = ((float)$billing * 18 ) / 100;
                    $invoice = (float)$billing+(float)$gst;
                    $payment = (((float)$billing * 90) / 100) + (((float)$billing * 18) / 100);

                    $selection[$i]['candidate_name'] = $value->fname;
                    $selection[$i]['company_name'] = $value->company_name;
                    $selection[$i]['position'] = $value->position;
                    $selection[$i]['fixed_salary'] = $value->fixed_salary;
                    $selection[$i]['billing'] = (float)$billing;
                    $selection[$i]['gst'] = (float)$gst;
                    $selection[$i]['invoice'] = (float)$invoice;
                    $selection[$i]['payment'] = (float)$payment;
                    $selection[$i]['joining_date'] = $date_class->changeYMDtoDMY($value->date_of_joining);
                    $selection[$i]['contact_person'] = $value->client_name;
                    $selection[$i]['location'] = $value->job_location;
                    $i++;
                }
            }

            if ($quaterdata == 3) {
                
                $m1 = date('m-d',strtotime("first day of january"));
                $m2 = date('m-d',strtotime("last day of february"));
                //print_r($m2);exit;

                $date_class = new Date();

                $selection_report = Bills::getSelectionReport($m1,$m2,'',$year);
                $selection = array();
                $i = 0;
                foreach ($selection_report as $key => $value) {
                    $fixed_salary = $value->fixed_salary;
                    $percentage_charged = (float)$value->percentage_charged;

                    if($percentage_charged<=0)
                        $percentage_charged = 1;

                    $billing = ((float)$fixed_salary * (float)$percentage_charged) / 100;
                    $gst = ((float)$billing * 18 ) / 100;
                    $invoice = (float)$billing+(float)$gst;
                    $payment = (((float)$billing * 90) / 100) + (((float)$billing * 18) / 100);

                    $selection[$i]['candidate_name'] = $value->fname;
                    $selection[$i]['company_name'] = $value->company_name;
                    $selection[$i]['position'] = $value->position;
                    $selection[$i]['fixed_salary'] = $value->fixed_salary;
                    $selection[$i]['billing'] = (float)$billing;
                    $selection[$i]['gst'] = (float)$gst;
                    $selection[$i]['invoice'] = (float)$invoice;
                    $selection[$i]['payment'] = (float)$payment;
                    $selection[$i]['joining_date'] = $date_class->changeYMDtoDMY($value->date_of_joining);
                    $selection[$i]['contact_person'] = $value->client_name;
                    $selection[$i]['location'] = $value->job_location;
                    $i++;
                }
            }

            
        }

        // Year wise
        else if ($selectdata == 3) {
            if(isset($_POST['year']) && $_POST['year']!=''){
                $year = $_POST['year'];
            }
            else{
                $year = '';
            }

            $date_class = new Date();

            $selection_report = Bills::getSelectionReport('','','',$year);
            $selection = array();
            $i = 0;
            foreach ($selection_report as $key => $value) {
                $fixed_salary = $value->fixed_salary;
                $percentage_charged = (float)$value->percentage_charged;

                if($percentage_charged<=0)
                    $percentage_charged = 1;

                $billing = ((float)$fixed_salary * ((float)$percentage_charged)) / 100;
                $gst = (((float)$billing) * 18 ) / 100;
                $invoice = ((float)$billing)+((float)$gst);
                $payment = ((((float)$billing) * 90) / 100) + ((((float)$billing) * 18) / 100);

                $selection[$i]['candidate_name'] = $value->fname;
                $selection[$i]['company_name'] = $value->company_name;
                $selection[$i]['position'] = $value->position;
                $selection[$i]['fixed_salary'] = $value->fixed_salary;
                $selection[$i]['billing'] = (float)$billing;
                $selection[$i]['gst'] = (float)$gst;
                $selection[$i]['invoice'] = (float)$invoice;
                $selection[$i]['payment'] = (float)$payment;
                $selection[$i]['joining_date'] = $date_class->changeYMDtoDMY($value->date_of_joining);
                $selection[$i]['contact_person'] = $value->client_name;
                $selection[$i]['location'] = $value->job_location;
                $i++;
            }
        }

        else{
            $selection[] = 'No Data Available';
        }
        
        //echo "<pre>"; print_r($selection);exit;

    	return view('adminlte::reports.selection',compact('select','month_array','year_array','quater','month','year','selection_report','selection','default'));
    }

    public function export(){

        Excel::create('Selection Report',function($excel){

            $excel->sheet('sheet 1',function($sheet){

                $selectdata = Input::get('select');
                $quaterdata = Input::get('quater');

                // Custom wise
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

                    $selection_report = Bills::getSelectionReportdata('','',$month,$year);
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
                    $selection_report = Bills::getSelectionReportdata('','',$month,$year);
                }

                // Quater wise
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
                    
                        $selection_report = Bills::getSelectionReportdata($m1,$m2,'',$year);
                    }
                    if ($quaterdata == 1) {
                        $m1 = date('m-d',strtotime("first day of july"));
                        $m2 = date('m-d',strtotime("last day of september"));
                    
                        $selection_report = Bills::getSelectionReportdata($m1,$m2,'',$year);
                    }
                    if ($quaterdata == 2) {
                        $m1 = date('m-d',strtotime("first day of october"));
                        $m2 = date('m-d',strtotime("last day of december"));
                    
                        $selection_report = Bills::getSelectionReportdata($m1,$m2,'',$year);
                    }
                    if ($quaterdata == 3) {
                        $m1 = date('m-d',strtotime("first day of january"));
                        $m2 = date('m-d',strtotime("last day of february"));
                    
                        $selection_report = Bills::getSelectionReportdata($m1,$m2,'',$year);
                    }
                }

                // Year wise
                else if ($selectdata == 3) {
                    if(isset($_POST['year']) && $_POST['year']!=''){
                        $year = $_POST['year'];
                    }
                    else{
                        $year = '';
                    }
                    $selection_report = Bills::getSelectionReportdata('','','',$year);
                }


            $sheet->fromArray($selection_report, null, 'A1', false, false);

            $heading = array('Candidate Name','Company Name','Position/Dept','Salary Offered(fixed)','Billing','GST @ 18%','invoice Raised','Payment Expected Including GST','Joining Date','Contact Person','Location');

            $sheet->prependRow(1,$heading);
            });
        })->export('xls');
        return view('selectionreport');
    }

    /*public function selectdata(){

        $date_class = new Date();
        $selectedValue = Input::get('selectedValue');

        if ($selectedValue == 1) {
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
        
            $selection_report = Bills::getSelectionReport($month,$year);
            if (isset($selection_report) && $selection_report!='') {
                
            
            $select = array();
            $i = 0;
            foreach ($selection_report as $key=>$value){

            $fixed_salary = $value['fixed_salary'];
            /*$percentage_charged = $value['percentage_charged'];
            $billing = ($fixed_salary * $percentage_charged) / 100;
            $gst = ($billing * 18 ) / 100;
            $invoice = $billing+$gst;
            $payment = (($billing * 90) / 100) + (($billing * 18) / 100);

            $select[$i]['candidate_name'] = $value['candidate_name'];
            $select[$i]['company_name'] = $value['company_name'];
            $select[$i]['position'] = $value['position'];
            $select[$i]['fixed_salary'] = $value['fixed_salary'];
            // $select[$i]['billing'] = $billing;
            // $select[$i]['gst'] = $gst;
            // $select[$i]['invoice'] = $invoice;
            // $select[$i]['payment'] = $payment;
            $select[$i]['joining_date'] = $date_class->changeYMDtoDMY($value['joining_date']);
            $select[$i]['contact_person'] = $value['contact_person'];
            $select[$i]['location'] = $value['location'];
            $i++;
            }
            }
            else{
                $select[] = "No Data Found";
            }
        }

        if ($selectedValue == 0) {
            if(isset($_POST['month']) && $_POST['month']!=''){
            $month = $_POST['month'];
        }
        else{
            $month = date("n");
        }
        if(isset($_POST['year']) && $_POST['year']!=''){
            $year = $_POST['year'];
        }
        else{
            $year = date("Y");
        }

        $selection_report = Bills::getSelectionReport($month,$year);

            $select = array();
            $i = 0;
            foreach ($selection_report as $key=>$value){

            $fixed_salary = $value['fixed_salary'];
            /*$percentage_charged = $value['percentage_charged'];
            $billing = ($fixed_salary * $percentage_charged) / 100;
            $gst = ($billing * 18 ) / 100;
            $invoice = $billing+$gst;
            $payment = (($billing * 90) / 100) + (($billing * 18) / 100);

            $select[$i]['candidate_name'] = $value['candidate_name'];
            $select[$i]['company_name'] = $value['company_name'];
            $select[$i]['position'] = $value['position'];
            $select[$i]['fixed_salary'] = $value['fixed_salary'];
            // $select[$i]['billing'] = $billing;
            // $select[$i]['gst'] = $gst;
            // $select[$i]['invoice'] = $invoice;
            // $select[$i]['payment'] = $payment;
            $select[$i]['joining_date'] = $date_class->changeYMDtoDMY($value['joining_date']);
            $select[$i]['contact_person'] = $value['contact_person'];
            $select[$i]['location'] = $value['location'];
            $i++;
            }
        }

        return json_encode($select);
    }*/
}
