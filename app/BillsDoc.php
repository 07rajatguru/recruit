<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BillsDoc extends Model
{
    public $table = "bills_doc";
    public $timestamps = false;

    public static function getBillInvoice($bill_id,$category=''){

        $bill_invoice = BillsDoc::query();
        $bill_invoice = $bill_invoice->join('bills','bills.id','=','bills_doc.bill_id');
        $bill_invoice = $bill_invoice->select('bills_doc.*');
        $bill_invoice = $bill_invoice->where('bills_doc.bill_id','=',$bill_id);

        if(isset($category) && $category != '') {

        	$bill_invoice = $bill_invoice->where('bills_doc.category','=',$category);
        }
        
        $response = $bill_invoice->first();

        return $response; 
    }
}
