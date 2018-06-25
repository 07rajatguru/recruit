<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Bills;
use App\Date;

class SelectionReportController extends Controller
{
    public function index(){

    	$select = array('0'=>'Custom','1'=>'Monthly','2'=>'Quarterly','3'=>'Yearly');

    	$month_array = array();
    	for ($i=1; $i <=12 ; $i++) { 
    		$month_array[$i] = date('M',mktime(0,0,0,$i));
        }

        $starting_year = date('Y');
        $ending_year = date('Y',strtotime('+10 year'));

        $year_array = array();
    	for ($y=$starting_year; $y <= $ending_year ; $y++) { 
    		$year_array[$y] = $y;
        }

        $quater = array();
        $quater['0'] = 'Quarter 1(Apr-Jun)';
        $quater['1'] = 'Quarter 2(July-Sept)';
        $quater['2'] = 'Quarter 3(Oct-Dec)';
        $quater['3'] = 'Quarter 4(Jan-Mar)';


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
        //echo "<pre>"; print_r($selection);exit;

    	return view('adminlte::reports.selection',compact('select','month_array','year_array','quater','month','year','selection_report'));
    }

    public function selectdata(){

        $date_class = new Date();
        $selectedValue = Input::get('selectedValue');

        if ($selectedValue == 2) {
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
           /* $percentage_charged = $value['percentage_charged'];
            $billing = ($fixed_salary * $percentage_charged) / 100;
            $gst = ($billing * 18 ) / 100;
            $invoice = $billing+$gst;
            $payment = (($billing * 90) / 100) + (($billing * 18) / 100);*/

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
    }
}
