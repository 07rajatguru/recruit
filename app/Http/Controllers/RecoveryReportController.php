<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bills;

class RecoveryReportController extends Controller
{
    public function index(){

    	$recovery_report = Bills::getRecoveryReport();

    	//print_r($recovery_report);exit;
    	return view('adminlte::reports.recovery',compact('recovery_report'));
    }
}