<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Receipt;
use Excel;

class ReceiptController extends Controller
{
    public function receiptTalent(){

    	return view('adminlte::receipt.receipttalentindex');
    }

    public function receiptTalentImport(){
    	
    	$bank_type = Receipt::getBankType();

    	return view('adminlte::receipt.receipttalentimport',compact('bank_type'));
    }

    public function receiptTalentImportStore(Request $request){

    	$bank_type = $request->bank_type;
    	// HDFC bank data import
    	if ($bank_type == 'hdfc') {
	    	if ($request->hasFile('import_file')) {
	    		$path = $request->file('import_file')->getRealPath();

	    		$data = Excel::load($path, function ($reader) {})->get();

	    		if (!empty($data) && $data->count()) {
	    			foreach ($data->toArray() as $key => $value) {
	    				foreach ($value as $k => $v) {
	    					print_r($v);exit;
	    					$date = $v['date'];
	    					$desc = $v['description'];
	    					$ref_no = $v['ref_no'];
	    					$value_date = $v['value_date'];
	    					$amount = $v['amount'];
	    					$name = $v['name'];
	    					$remarks = $v['nature_of_income_remarks'];

	    					// Check ref_no already exist or not, if exist then not added
	    				}
	    			}
	    		}
	    	}
    	}
    }
}
