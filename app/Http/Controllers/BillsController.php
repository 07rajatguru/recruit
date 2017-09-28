<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CandidateBasicInfo;

class BillsController extends Controller
{
    public function index(){

        return view('adminlte::bills.index');
    }

    public function create(){
        $action = 'add';

        $variables = array();
        $candidate = CandidateBasicInfo::getCandidateArray();

        return view('adminlte::bills.create',compact('action','candidate'));
    }

    public function store(Request $request){

    }
}
