<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Receipt;
use App\VendorBasicInfo;
use Excel;
use App\Date;

class ReceiptController extends Controller
{
    public function receiptTalent(){

    	$bank_type = Receipt::getBankType();

    	if (isset($_POST['bank']) && $_POST['bank'] != '') {
    		$bank = $_POST['bank'];
    	}
    	else {
    		$bank = 'hdfc';
    	}
    	$type = 'Talent';

    	$receipt_data = Receipt::getReceiptdata($bank,$type);

    	return view('adminlte::receipt.receipttalentindex',compact('bank_type','receipt_data','bank'));
    }

    public function receiptTalentImport(){
    	
    	$bank_type = Receipt::getBankType();

    	return view('adminlte::receipt.receipttalentimport',compact('bank_type'));
    }

    public function receiptTalentImportStore(Request $request){

    	$bank_type = Receipt::getBankType();
    	$dateClass = new Date();

    	$bank_type_data = $request->bank_type;
    	// HDFC bank data import
    	if ($bank_type_data == 'hdfc') {
	    	if ($request->hasFile('import_file')) {
	    		$path = $request->file('import_file')->getRealPath();

	    		$data = Excel::load($path, function ($reader) {})->get();

	    		if (!empty($data) && $data->count()) {
	    			foreach ($data->toArray() as $key => $value) {
	    				if(!empty($value)) {
		    				foreach ($value as $k => $v) {
		    					//print_r($v);exit;
		    					$date = $v['date'];
		    					$desc = $v['description'];
		    					$ref_no = $v['ref_no'];
		    					$value_date = $v['value_date'];
		    					$amount = $v['amount'];
		    					$name = $v['name'];
		    					$remarks = $v['nature_of_income_remarks'];

		    					$date_form = $dateClass->changeDMYtoYMD($date);
		    					$value_date_form = $dateClass->changeDMYtoYMD($value_date);

		    					// Check ref_no already exist or not, if exist then not added
		    					$check_ref_no = Receipt::getCheckDuplicateEntry($ref_no,NULL,NULL);
		    					if ($check_ref_no == true) {
		    						$messages[] = "$ref_no is already exist";
		    					}
		    					else {
		    						// Get Vendor Id from vendor name
		    						$vendor_id = VendorBasicInfo::getVendor($name);

		    						if ($vendor_id > 0) {
		    							// Create Receipt Talent HDFC
		    							$receipt_talent_hdfc = new Receipt();
		    							$receipt_talent_hdfc->date = $date_form;
		    							$receipt_talent_hdfc->description = $desc;
		    							$receipt_talent_hdfc->ref_no = $ref_no;
		    							$receipt_talent_hdfc->value_date = $value_date_form;
		    							$receipt_talent_hdfc->amount = $amount;
		    							$receipt_talent_hdfc->name_of_company = $vendor_id;
		    							$receipt_talent_hdfc->remarks = $remarks;
		    							$receipt_talent_hdfc->bank_type = $bank_type_data;
		    							$receipt_talent_hdfc->type = 'Talent';
		    							$receipt_talent_hdfc->save();

		    							$receipt_id = $receipt_talent_hdfc->id;
		    							if ($receipt_id > 0) {
		    								$messages[] = "$ref_no record added successfully";
		    							}
		    						}
		    						else {
		    							$messages[] = "$ref_no vendor not added.";
		    						}
		    					}
		    				}
	    				}
	    				else {
	    					$messages[] = "No data in file";
	    				}
	    			}
	    		}
	    		return view('adminlte::receipt.receipttalentimport',compact('messages','bank_type'));
	    	}
    	}
    	else if ($bank_type_data == 'icici') {
    		if ($request->hasFile('import_file')) {
	    		$path = $request->file('import_file')->getRealPath();

	    		$data = Excel::load($path, function ($reader) {})->get();

	    		if (!empty($data) && $data->count()) {
	    			foreach ($data->toArray() as $key => $value) {
	    				if(!empty($value)) {
		    				foreach ($value as $k => $v) {
		    					//print_r($v);exit;
		    					$transaction_id = $v['transaction_id'];
		    					$value_date = $v['value_date'];
		    					$txn_posted_date = $v['txn_posted_date'];
		    					$desc = $v['description'];
		    					$crdr = $v['crdr'];
		    					$amount = $v['transaction_amountinr'];
		    					$name = $v['name_of_company_nature_of_income'];
		    					$remarks = $v['remarks_if_any'];

		    					$value_date_form = $dateClass->changeDMYtoYMD($value_date);
		    					$txn_posted_date_form = $dateClass->changeDMYtoYMD($txn_posted_date);

		    					// Check transaction Id already exist or not, if exist then not added
		    					$check_trans_no = Receipt::getCheckDuplicateEntry(NULL,$transaction_id,NULL);
		    					if ($check_trans_no == true) {
		    						$messages[] = "$transaction_id is already exist";
		    					}
		    					else {
		    						// Get Vendor Id from vendor name
		    						$vendor_id = VendorBasicInfo::getVendor($name);

		    						if ($vendor_id > 0) {
		    							// Create Receipt Talent ICICI
		    							$receipt_talent_icici = new Receipt();
		    							$receipt_talent_icici->trans_id = $transaction_id;
		    							$receipt_talent_icici->value_date = $value_date_form;
		    							$receipt_talent_icici->txn_posted_date = $txn_posted_date_form;
		    							$receipt_talent_icici->description = $desc;
		    							$receipt_talent_icici->cr = $crdr;
		    							$receipt_talent_icici->amount = $amount;
		    							$receipt_talent_icici->name_of_company = $vendor_id;
		    							$receipt_talent_icici->remarks = $remarks;
		    							$receipt_talent_icici->bank_type = $bank_type_data;
		    							$receipt_talent_icici->type = 'Talent';
		    							$receipt_talent_icici->save();

		    							$receipt_id = $receipt_talent_icici->id;
		    							if ($receipt_id > 0) {
		    								$messages[] = "$transaction_id record added successfully";
		    							}
		    						}
		    						else {
		    							$messages[] = "$transaction_id vendor not added.";
		    						}
		    					}
		    				}
	    				}
	    				else {
	    					$messages[] = "No data in file";
	    				}
	    			}
	    		}
	    		return view('adminlte::receipt.receipttalentimport',compact('messages','bank_type'));
	    	}
    	}
    	else if ($bank_type_data == 'other') {
    		if ($request->hasFile('import_file')) {
	    		$path = $request->file('import_file')->getRealPath();

	    		$data = Excel::load($path, function ($reader) {})->get();

	    		if (!empty($data) && $data->count()) {
	    			foreach ($data->toArray() as $key => $value) {
	    				if(!empty($value)) {
		    				foreach ($value as $k => $v) {
		    					//print_r($v);exit;
		    					$voucher_no = $v['voucher_no'];
		    					$value_date = $v['value_date'];
		    					$amount = $v['transaction_amountinr'];
		    					$mode_of_receipt = $v['mode_of_receipt'];
		    					$name = $v['name_of_company_nature_of_income'];
		    					$remarks = $v['remarks_if_any'];

		    					$value_date_form = $dateClass->changeDMYtoYMD($value_date);

		    					// Check transaction Id already exist or not, if exist then not added
		    					$check_voucher_no = Receipt::getCheckDuplicateEntry(NULL,NULL,$voucher_no);
		    					if ($check_voucher_no == true) {
		    						$messages[] = "$voucher_no is already exist";
		    					}
		    					else {
		    						// Get Vendor Id from vendor name
		    						$vendor_id = VendorBasicInfo::getVendor($name);

		    						if ($vendor_id > 0) {
		    							// Create Receipt Talent Other
		    							$receipt_talent_other = new Receipt();
		    							$receipt_talent_other->voucher_no = $voucher_no;
		    							$receipt_talent_other->value_date = $value_date_form;
		    							$receipt_talent_other->amount = $amount;
		    							$receipt_talent_other->mode_of_receipt = $mode_of_receipt;
		    							$receipt_talent_other->name_of_company = $vendor_id;
		    							$receipt_talent_other->remarks = $remarks;
		    							$receipt_talent_other->bank_type = $bank_type_data;
		    							$receipt_talent_other->type = 'Talent';
		    							$receipt_talent_other->save();

		    							$receipt_id = $receipt_talent_other->id;
		    							if ($receipt_id > 0) {
		    								$messages[] = "$voucher_no record added successfully";
		    							}
		    						}
		    						else {
		    							$messages[] = "$voucher_no vendor not added.";
		    						}
		    					}
		    				}
	    				}
	    				else {
	    					$messages[] = "No data in file";
	    				}
	    			}
	    		}
	    		return view('adminlte::receipt.receipttalentimport',compact('messages','bank_type'));
	    	}
    	}
    }

    public function receiptTalentCreate(){

    	$bank_type = Receipt::getBankType();
    	
    	return view('adminlte::receipt.receipttalentcreate',compact('bank_type'));
    }

    public function receiptTalentStore(){
    	
    }

    public function receiptTemp(){

    	$bank_type = Receipt::getBankType();

    	if (isset($_POST['bank']) && $_POST['bank'] != '') {
    		$bank = $_POST['bank'];
    	}
    	else {
    		$bank = 'hdfc';
    	}
    	$type = 'Tamp';

    	$receipt_data = Receipt::getReceiptdata($bank,$type);

    	return view('adminlte::receipt.receipttempindex',compact('bank_type','receipt_data','bank'));
    }

    public function receiptTempImport(){
    	
    	$bank_type = Receipt::getBankType();

    	return view('adminlte::receipt.receipttempimport',compact('bank_type'));
    }

    public function receiptTempImportStore(Request $request){

    	$bank_type = Receipt::getBankType();
    	$dateClass = new Date();

    	$bank_type_data = $request->bank_type;
    	// HDFC bank data import
    	if ($bank_type_data == 'hdfc') {
	    	if ($request->hasFile('import_file')) {
	    		$path = $request->file('import_file')->getRealPath();

	    		$data = Excel::load($path, function ($reader) {})->get();

	    		if (!empty($data) && $data->count()) {
	    			foreach ($data->toArray() as $key => $value) {
	    				if(!empty($value)) {
		    				foreach ($value as $k => $v) {
		    					//print_r($v);exit;
		    					$date = $v['date'];
		    					$desc = $v['description'];
		    					$ref_no = $v['ref_no'];
		    					$value_date = $v['value_date'];
		    					$amount = $v['amount'];
		    					$name = $v['name'];
		    					$remarks = $v['nature_of_income_remarks'];

		    					$date_form = $dateClass->changeDMYtoYMD($date);
		    					$value_date_form = $dateClass->changeDMYtoYMD($value_date);

		    					// Check ref_no already exist or not, if exist then not added
		    					$check_ref_no = Receipt::getCheckDuplicateEntry($ref_no,NULL,NULL);
		    					if ($check_ref_no == true) {
		    						$messages[] = "$ref_no is already exist";
		    					}
		    					else {
		    						// Get Vendor Id from vendor name
		    						$vendor_id = VendorBasicInfo::getVendor($name);

		    						if ($vendor_id > 0) {
		    							// Create Receipt Temp HDFC
		    							$receipt_temp_hdfc = new Receipt();
		    							$receipt_temp_hdfc->date = $date_form;
		    							$receipt_temp_hdfc->description = $desc;
		    							$receipt_temp_hdfc->ref_no = $ref_no;
		    							$receipt_temp_hdfc->value_date = $value_date_form;
		    							$receipt_temp_hdfc->amount = $amount;
		    							$receipt_temp_hdfc->name_of_company = $vendor_id;
		    							$receipt_temp_hdfc->remarks = $remarks;
		    							$receipt_temp_hdfc->bank_type = $bank_type_data;
		    							$receipt_temp_hdfc->type = 'Temp';
		    							$receipt_temp_hdfc->save();

		    							$receipt_id = $receipt_temp_hdfc->id;
		    							if ($receipt_id > 0) {
		    								$messages[] = "$ref_no record added successfully";
		    							}
		    						}
		    						else {
		    							$messages[] = "$ref_no vendor not added.";
		    						}
		    					}
		    				}
	    				}
	    				else {
	    					$messages[] = "No data in file";
	    				}
	    			}
	    		}
	    		return view('adminlte::receipt.receipttempimport',compact('messages','bank_type'));
	    	}
    	}
    	else if ($bank_type_data == 'icici') {
    		if ($request->hasFile('import_file')) {
	    		$path = $request->file('import_file')->getRealPath();

	    		$data = Excel::load($path, function ($reader) {})->get();

	    		if (!empty($data) && $data->count()) {
	    			foreach ($data->toArray() as $key => $value) {
	    				if(!empty($value)) {
		    				foreach ($value as $k => $v) {
		    					//print_r($v);exit;
		    					$transaction_id = $v['transaction_id'];
		    					$value_date = $v['value_date'];
		    					$txn_posted_date = $v['txn_posted_date'];
		    					$desc = $v['description'];
		    					$crdr = $v['crdr'];
		    					$amount = $v['transaction_amountinr'];
		    					$name = $v['name_of_company_nature_of_income'];
		    					$remarks = $v['remarks_if_any'];

		    					$value_date_form = $dateClass->changeDMYtoYMD($value_date);
		    					$txn_posted_date_form = $dateClass->changeDMYtoYMD($txn_posted_date);

		    					// Check transaction Id already exist or not, if exist then not added
		    					$check_trans_no = Receipt::getCheckDuplicateEntry(NULL,$transaction_id,NULL);
		    					if ($check_trans_no == true) {
		    						$messages[] = "$transaction_id is already exist";
		    					}
		    					else {
		    						// Get Vendor Id from vendor name
		    						$vendor_id = VendorBasicInfo::getVendor($name);

		    						if ($vendor_id > 0) {
		    							// Create Receipt Temp ICICI
		    							$receipt_temp_icici = new Receipt();
		    							$receipt_temp_icici->trans_id = $transaction_id;
		    							$receipt_temp_icici->value_date = $value_date_form;
		    							$receipt_temp_icici->txn_posted_date = $txn_posted_date_form;
		    							$receipt_temp_icici->description = $desc;
		    							$receipt_temp_icici->cr = $crdr;
		    							$receipt_temp_icici->amount = $amount;
		    							$receipt_temp_icici->name_of_company = $vendor_id;
		    							$receipt_temp_icici->remarks = $remarks;
		    							$receipt_temp_icici->bank_type = $bank_type_data;
		    							$receipt_temp_icici->type = 'Temp';
		    							$receipt_temp_icici->save();

		    							$receipt_id = $receipt_temp_icici->id;
		    							if ($receipt_id > 0) {
		    								$messages[] = "$transaction_id record added successfully";
		    							}
		    						}
		    						else {
		    							$messages[] = "$transaction_id vendor not added.";
		    						}
		    					}
		    				}
	    				}
	    				else {
	    					$messages[] = "No data in file";
	    				}
	    			}
	    		}
	    		return view('adminlte::receipt.receipttempimport',compact('messages','bank_type'));
	    	}
    	}
    	else if ($bank_type_data == 'other') {
    		if ($request->hasFile('import_file')) {
	    		$path = $request->file('import_file')->getRealPath();

	    		$data = Excel::load($path, function ($reader) {})->get();

	    		if (!empty($data) && $data->count()) {
	    			foreach ($data->toArray() as $key => $value) {
	    				if(!empty($value)) {
		    				foreach ($value as $k => $v) {
		    					//print_r($v);exit;
		    					$voucher_no = $v['voucher_no'];
		    					$value_date = $v['value_date'];
		    					$amount = $v['transaction_amountinr'];
		    					$mode_of_receipt = $v['mode_of_receipt'];
		    					$name = $v['name_of_company_nature_of_income'];
		    					$remarks = $v['remarks_if_any'];

		    					$value_date_form = $dateClass->changeDMYtoYMD($value_date);

		    					// Check transaction Id already exist or not, if exist then not added
		    					$check_voucher_no = Receipt::getCheckDuplicateEntry(NULL,NULL,$voucher_no);
		    					if ($check_voucher_no == true) {
		    						$messages[] = "$voucher_no is already exist";
		    					}
		    					else {
		    						// Get Vendor Id from vendor name
		    						$vendor_id = VendorBasicInfo::getVendor($name);

		    						if ($vendor_id > 0) {
		    							// Create Receipt Temp Other
		    							$receipt_temp_other = new Receipt();
		    							$receipt_temp_other->voucher_no = $voucher_no;
		    							$receipt_temp_other->value_date = $value_date_form;
		    							$receipt_temp_other->amount = $amount;
		    							$receipt_temp_other->mode_of_receipt = $mode_of_receipt;
		    							$receipt_temp_other->name_of_company = $vendor_id;
		    							$receipt_temp_other->remarks = $remarks;
		    							$receipt_temp_other->bank_type = $bank_type_data;
		    							$receipt_temp_other->type = 'Temp';
		    							$receipt_temp_other->save();

		    							$receipt_id = $receipt_temp_other->id;
		    							if ($receipt_id > 0) {
		    								$messages[] = "$voucher_no record added successfully";
		    							}
		    						}
		    						else {
		    							$messages[] = "$voucher_no vendor not added.";
		    						}
		    					}
		    				}
	    				}
	    				else {
	    					$messages[] = "No data in file";
	    				}
	    			}
	    		}
	    		return view('adminlte::receipt.receipttempimport',compact('messages','bank_type'));
	    	}
    	}
    }

    public function receiptTempCreate(){

    }

    public function receiptTempStore(){
    	
    }

    public function receiptOther(){

    	$bank_type = Receipt::getBankType();

    	if (isset($_POST['bank']) && $_POST['bank'] != '') {
    		$bank = $_POST['bank'];
    	}
    	else {
    		$bank = 'hdfc';
    	}
    	$type = 'Other';

    	$receipt_data = Receipt::getReceiptdata($bank,$type);

    	return view('adminlte::receipt.receiptotherindex',compact('bank_type','receipt_data','bank'));
    }

    public function receiptOtherImport(){
    	
    	$bank_type = Receipt::getBankType();

    	return view('adminlte::receipt.receiptotherimport',compact('bank_type'));
    }

    public function receiptOtherImportStore(Request $request){

    	$bank_type = Receipt::getBankType();
    	$dateClass = new Date();

    	$bank_type_data = $request->bank_type;
    	// HDFC bank data import
    	if ($bank_type_data == 'hdfc') {
	    	if ($request->hasFile('import_file')) {
	    		$path = $request->file('import_file')->getRealPath();

	    		$data = Excel::load($path, function ($reader) {})->get();

	    		if (!empty($data) && $data->count()) {
	    			foreach ($data->toArray() as $key => $value) {
	    				if(!empty($value)) {
		    				foreach ($value as $k => $v) {
		    					//print_r($v);exit;
		    					$date = $v['date'];
		    					$desc = $v['description'];
		    					$ref_no = $v['ref_no'];
		    					$value_date = $v['value_date'];
		    					$amount = $v['amount'];
		    					$name = $v['name'];
		    					$remarks = $v['nature_of_income_remarks'];

		    					$date_form = $dateClass->changeDMYtoYMD($date);
		    					$value_date_form = $dateClass->changeDMYtoYMD($value_date);

		    					// Check ref_no already exist or not, if exist then not added
		    					$check_ref_no = Receipt::getCheckDuplicateEntry($ref_no,NULL,NULL);
		    					if ($check_ref_no == true) {
		    						$messages[] = "$ref_no is already exist";
		    					}
		    					else {
		    						// Get Vendor Id from vendor name
		    						$vendor_id = VendorBasicInfo::getVendor($name);

		    						if ($vendor_id > 0) {
		    							// Create Receipt Other HDFC
		    							$receipt_other_hdfc = new Receipt();
		    							$receipt_other_hdfc->date = $date_form;
		    							$receipt_other_hdfc->description = $desc;
		    							$receipt_other_hdfc->ref_no = $ref_no;
		    							$receipt_other_hdfc->value_date = $value_date_form;
		    							$receipt_other_hdfc->amount = $amount;
		    							$receipt_other_hdfc->name_of_company = $vendor_id;
		    							$receipt_other_hdfc->remarks = $remarks;
		    							$receipt_other_hdfc->bank_type = $bank_type_data;
		    							$receipt_other_hdfc->type = 'Other';
		    							$receipt_other_hdfc->save();

		    							$receipt_id = $receipt_other_hdfc->id;
		    							if ($receipt_id > 0) {
		    								$messages[] = "$ref_no record added successfully";
		    							}
		    						}
		    						else {
		    							$messages[] = "$ref_no vendor not added.";
		    						}
		    					}
		    				}
	    				}
	    				else {
	    					$messages[] = "No data in file";
	    				}
	    			}
	    		}
	    		return view('adminlte::receipt.receiptotherimport',compact('messages','bank_type'));
	    	}
    	}
    	else if ($bank_type_data == 'icici') {
    		if ($request->hasFile('import_file')) {
	    		$path = $request->file('import_file')->getRealPath();

	    		$data = Excel::load($path, function ($reader) {})->get();

	    		if (!empty($data) && $data->count()) {
	    			foreach ($data->toArray() as $key => $value) {
	    				if(!empty($value)) {
		    				foreach ($value as $k => $v) {
		    					//print_r($v);exit;
		    					$transaction_id = $v['transaction_id'];
		    					$value_date = $v['value_date'];
		    					$txn_posted_date = $v['txn_posted_date'];
		    					$desc = $v['description'];
		    					$crdr = $v['crdr'];
		    					$amount = $v['transaction_amountinr'];
		    					$name = $v['name_of_company_nature_of_income'];
		    					$remarks = $v['remarks_if_any'];

		    					$value_date_form = $dateClass->changeDMYtoYMD($value_date);
		    					$txn_posted_date_form = $dateClass->changeDMYtoYMD($txn_posted_date);

		    					// Check transaction Id already exist or not, if exist then not added
		    					$check_trans_no = Receipt::getCheckDuplicateEntry(NULL,$transaction_id,NULL);
		    					if ($check_trans_no == true) {
		    						$messages[] = "$transaction_id is already exist";
		    					}
		    					else {
		    						// Get Vendor Id from vendor name
		    						$vendor_id = VendorBasicInfo::getVendor($name);

		    						if ($vendor_id > 0) {
		    							// Create Receipt Other ICICI
		    							$receipt_other_icici = new Receipt();
		    							$receipt_other_icici->trans_id = $transaction_id;
		    							$receipt_other_icici->value_date = $value_date_form;
		    							$receipt_other_icici->txn_posted_date = $txn_posted_date_form;
		    							$receipt_other_icici->description = $desc;
		    							$receipt_other_icici->cr = $crdr;
		    							$receipt_other_icici->amount = $amount;
		    							$receipt_other_icici->name_of_company = $vendor_id;
		    							$receipt_other_icici->remarks = $remarks;
		    							$receipt_other_icici->bank_type = $bank_type_data;
		    							$receipt_other_icici->type = 'Other';
		    							$receipt_other_icici->save();

		    							$receipt_id = $receipt_other_icici->id;
		    							if ($receipt_id > 0) {
		    								$messages[] = "$transaction_id record added successfully";
		    							}
		    						}
		    						else {
		    							$messages[] = "$transaction_id vendor not added.";
		    						}
		    					}
		    				}
	    				}
	    				else {
	    					$messages[] = "No data in file";
	    				}
	    			}
	    		}
	    		return view('adminlte::receipt.receiptotherimport',compact('messages','bank_type'));
	    	}
    	}
    	else if ($bank_type_data == 'other') {
    		if ($request->hasFile('import_file')) {
	    		$path = $request->file('import_file')->getRealPath();

	    		$data = Excel::load($path, function ($reader) {})->get();

	    		if (!empty($data) && $data->count()) {
	    			foreach ($data->toArray() as $key => $value) {
	    				if(!empty($value)) {
		    				foreach ($value as $k => $v) {
		    					//print_r($v);exit;
		    					$voucher_no = $v['voucher_no'];
		    					$value_date = $v['value_date'];
		    					$amount = $v['transaction_amountinr'];
		    					$mode_of_receipt = $v['mode_of_receipt'];
		    					$name = $v['name_of_company_nature_of_income'];
		    					$remarks = $v['remarks_if_any'];

		    					$value_date_form = $dateClass->changeDMYtoYMD($value_date);

		    					// Check transaction Id already exist or not, if exist then not added
		    					$check_voucher_no = Receipt::getCheckDuplicateEntry(NULL,NULL,$voucher_no);
		    					if ($check_voucher_no == true) {
		    						$messages[] = "$voucher_no is already exist";
		    					}
		    					else {
		    						// Get Vendor Id from vendor name
		    						$vendor_id = VendorBasicInfo::getVendor($name);

		    						if ($vendor_id > 0) {
		    							// Create Receipt Other Other
		    							$receipt_other_other = new Receipt();
		    							$receipt_other_other->voucher_no = $voucher_no;
		    							$receipt_other_other->value_date = $value_date_form;
		    							$receipt_other_other->amount = $amount;
		    							$receipt_other_other->mode_of_receipt = $mode_of_receipt;
		    							$receipt_other_other->name_of_company = $vendor_id;
		    							$receipt_other_other->remarks = $remarks;
		    							$receipt_other_other->bank_type = $bank_type_data;
		    							$receipt_other_other->type = 'Other';
		    							$receipt_other_other->save();

		    							$receipt_id = $receipt_other_other->id;
		    							if ($receipt_id > 0) {
		    								$messages[] = "$voucher_no record added successfully";
		    							}
		    						}
		    						else {
		    							$messages[] = "$voucher_no vendor not added.";
		    						}
		    					}
		    				}
	    				}
	    				else {
	    					$messages[] = "No data in file";
	    				}
	    			}
	    		}
	    		return view('adminlte::receipt.receiptotherimport',compact('messages','bank_type'));
	    	}
    	}
    }

    public function receiptOtherCreate(){

    }

    public function receiptOtherStore(){
    	
    }
}
