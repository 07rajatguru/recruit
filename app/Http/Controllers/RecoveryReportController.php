<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bills;
use Excel;
use App\Utils;


class RecoveryReportController extends Controller
{
    public function index(){

    	$recovery_report = Bills::getRecoveryReport();

    	//print_r($recovery_report);exit;
    	return view('adminlte::reports.recovery',compact('recovery_report'));
    }

    public function export(){

    	Excel::create('Recovery Report', function($excel){
    		$excel->sheet('Sheet 1', function($sheet){

    			$recovery = Bills::getRecoveryReportdata();

    			$sheet->fromarray($recovery, null ,'A1', null, null);

    			$heading = array('Candidate Name','Company Name','Position/Dept','Salary Offered(fixed)','Billing','Expected payment','Joining Date','Efforts','Contact Person');

    			$sheet->prependRow(1,$heading);
    		});
    	})->export('xls');
    }
}