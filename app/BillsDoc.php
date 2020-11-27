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

    public static function getBillDocs($bill_id) {

        $bill_docs = BillsDoc::query();
        $bill_docs = $bill_docs->select('bills_doc.*');
        $bill_docs = $bill_docs->where('bills_doc.bill_id','=',$bill_id);
        $bill_docs = $bill_docs->where('bills_doc.category','!=','Invoice');
        
        $response = $bill_docs->get();
        $docs_array = array();
        $i=0;

        if(isset($response) && sizeof($response) > 0) {

            foreach ($response as $key => $value) {

                $docs_array[$i]['id'] = $value->id;
                $docs_array[$i]['bill_id'] = $value->bill_id;
                $docs_array[$i]['category'] = $value->category;
                $docs_array[$i]['file'] = public_path() . "/" . $value->file;
                $docs_array[$i]['name'] = $value->name;

                $i++;
            }
        }
        return $docs_array; 
    }
}