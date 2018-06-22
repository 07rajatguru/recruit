<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

class SelectionReportController extends Controller
{
    public function index(){

    	$select = array('0'=>'Custom','1'=>'Monthly','2'=>'Quarterly','3'=>'Yearly');

    	$month = array();
    	for ($i=1; $i <=12 ; $i++) { 
    		$month[$i] = date('M',mktime(0,0,0,$i));
        }

        $starting_year = date('Y');
        $ending_year = date('Y',strtotime('+10 year'));

        $year = array();
    	for ($y=$starting_year; $y <= $ending_year ; $y++) { 
    		$year[$y] = $y;
        }

        $quater = array();
        $quater['0'] = 'Quarter 1(Apr-Jun)';
        $quater['1'] = 'Quarter 2(July-Sept)';
        $quater['2'] = 'Quarter 3(Oct-Dec)';
        $quater['3'] = 'Quarter 4(Jan-Mar)';

        //print_r($year);exit;

    	return view('adminlte::reports.selection',compact('select','month','year','quater'));
    }
}
