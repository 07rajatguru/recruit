<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{

    public $table = "receipt";

    public static function getBankType(){

    	$banktype = array('' => 'Select Bank Type');
    	$banktype['hdfc'] = 'HDFC';
    	$banktype['icici'] = 'ICICI';
    	$banktype['other'] = 'Other';

    	return $banktype;
    }

    public static function getCheckDuplicateEntry($ref_no=NULL,$transaction_id=NULL,$voucher_no=NULL){

    	$query = Receipt::query();
        if (isset($ref_no) && $ref_no != '') {
    	   $query = $query->where('ref_no','=',$ref_no);
        }
        if (isset($transaction_id) && $transaction_id != '') {
            $query = $query->where('trans_id','=',$transaction_id);
        }
        if (isset($voucher_no) && $voucher_no != '') {
            $query = $query->where('voucher_no','=',$voucher_no);
        }
    	$res = $query->first();

    	if (isset($res) && $res != '') {
    		return true;
    	}
    	else {
    		return false;
    	}
    }

    public static function getReceiptdata($bank,$type){

        $query = Receipt::query();
        $query = $query->leftjoin('client_basicinfo','client_basicinfo.id','receipt.name_of_company');
        $query = $query->select('receipt.*','client_basicinfo.name as company_name');
        $query = $query->where('bank_type','=',$bank);
        $query = $query->where('type','=',$type);
        $res = $query->get();

        $receipt = array();
        $i = 0;
        foreach ($res as $key => $value) {
            $receipt[$i]['id'] = $value->id;
            $receipt[$i]['date'] = date('d-m-Y', strtotime($value->date));
            $receipt[$i]['ref_no'] = $value->ref_no;
            $receipt[$i]['value_date'] = date('d-m-Y', strtotime($value->value_date));
            $receipt[$i]['company_name'] = $value->company_name;
            $receipt[$i]['amount'] = $value->amount;
            $receipt[$i]['trans_id'] = $value->trans_id;
            $receipt[$i]['txn_posted_date'] = date('d-m-Y H:i A', strtotime($value->txn_posted_date));
            $receipt[$i]['cr'] = $value->cr;
            $receipt[$i]['voucher_no'] = $value->voucher_no;
            $receipt[$i]['mode_of_receipt'] = $value->mode_of_receipt;
            $receipt[$i]['description'] = $value->description;
            $receipt[$i]['remarks'] = $value->remarks;
            $i++;
        }

        return $receipt;
    }

    public static function getTypeById($id){

        $query = Receipt::query();
        $query = $query->select('type');
        $query = $query->where('id',$id);
        $res = $query->first();

        if (isset($res) && $res != '') {
            $type = $res->type;
        }

        return $type;
    }
}
