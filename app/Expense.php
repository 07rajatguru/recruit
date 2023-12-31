<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    public $table = "expense";

    public static $rules = array(
        'date' => 'required',
        'vendor_id'=>'required',
        // 'gst_no'=>'required',
        // 'pan_no'=>'required',
        'amount' => 'required',
        'gst'=>'required',
        'cgst'=>'required',
        'sgst'=>'required',
        'igst'=>'required',
        'bill_amount' => 'required',
        'paid_amount' => 'required',
        'head' => 'required',
        'pmode' => 'required',
        'ptype' => 'required'
    );

    public function messages()
    {
        return [
            'date.required' => 'Date is required field',
            'vendor_id'=>'Paid To is required field.',
            // 'gst_no'=>'GST No. is required field.',
            // 'pan_no'=>'PAN No. is required field.',
            'gst'=>'GST(%) is required field.',
            'cgst'=>'CGST is required field.',
            'sgst'=>'SGST is required field.',
            'igst'=>'IGST is required field.',
            'bill_amount' => 'Bill Amount is required field.',
            'paid_amount' => 'Paid Amount is required field.',
            'amount.required' => 'Amount is required field',
            'head.required' => 'Expense Head is required field',
            'pmode.required' => 'Payment Mode is required field',
            'ptype.required' => 'Payment Type is required field'
        ];
    }

    public static function getPaymentMode(){
    	$pmode = array(''=>'Select Payment Mode');
    	$pmode['Petty Cash - Accounts'] = 'Petty Cash - Accounts';
    	$pmode['Petty Cash - Director'] = 'Petty Cash - Director';
    	$pmode['AMEX'] = 'AMEX';
    	$pmode['ICICI Bank'] = 'ICICI Bank';
    	$pmode['HDFC Bank'] = 'HDFC Bank';
    	$pmode['Paytm - AMEX'] = 'Paytm - AMEX';
    	$pmode['Freecharge - AMEX'] = 'Freecharge - AMEX';

    	return $pmode;
    }

    public static function getPaymentType(){

    	$ptype = array(''=>'Select Payment Type');
    	$ptype['NEFT'] = 'NEFT';
    	$ptype['IMPS'] = 'IMPS';
    	$ptype['Cheque'] = 'Cheque';
        $ptype['RTGS']='RTGS';
        $ptype['Cash']='Cash';
        $ptype['Enet']='ENET';
        $ptype['CMS']='CMS';
    	
        return $ptype;
    }

    public static function getInputTax(){

        $ptype = array(''=>'Select Input Tax');
        $ptype['YES'] = 'YES';
        $ptype['NO'] = 'NO';
        return $ptype;
    }

    public static function getAllExpense($limit=0,$offset=0,$search=0,$order=0,$type='desc'){
        $dateClass = new Date();

        $query = Expense::query();
        $query = $query->join('accounting_heads','accounting_heads.id','=','expense.expense_head');
        $query = $query->join('vendor_basicinfo','vendor_basicinfo.id','=','expense.vendor_id');
        if (isset($limit) && $limit > 0) {
          $query = $query->limit($limit);
        }
        if (isset($offset) && $offset > 0) {
          $query = $query->offset($offset);
        }
        if (isset($order) && $order !='') {
          $query = $query->orderBy($order,$type);
        }
        if (isset($search) && $search != '') {

            $query = $query->where(function($query) use ($search){

                $date_search = false;
                $date_array = explode("-",$search);
                if(isset($date_array) && sizeof($date_array)>0){
                    $stamp = strtotime($search);
                    if (is_numeric($stamp)){
                        $month = date( 'm', $stamp );
                        $day = date( 'd', $stamp );
                        $year = date( 'Y', $stamp );

                        if(checkdate($month, $day, $year)){
                            $date_search = true;
                        }
                    }
                }

                //$query = $query->where('expense.date','like',"%$search%");
                $query = $query->orwhere('expense.paid_amount','like',"%$search%");
                $query = $query->orwhere('vendor_basicinfo.name','like',"%$search%");
                $query = $query->orwhere('accounting_heads.name','like',"%$search%");
                $query = $query->orwhere('expense.remarks','like',"%$search%");
                $query = $query->orwhere('expense.payment_mode','like',"%$search%");
                $query = $query->orwhere('expense.type_of_payment','like',"%$search%");
                $query = $query->orwhere('expense.reference_number','like',"%$search%");


                if($date_search){        
                    $dateClass = new Date();
                    $search_string = $dateClass->changeDMYtoYMD($search);
                    $from_date = date("Y-m-d 00:00:00",strtotime($search_string));
                    $to_date = date("Y-m-d 23:59:59",strtotime($search_string));
                    $query = $query->orwhere('expense.date','>=',"$from_date");
                    $query = $query->Where('expense.date','<=',"$to_date");
                }

            });
        }
        $query = $query->select('expense.*','accounting_heads.name as aname','vendor_basicinfo.name as vname');
        $resource = $query->get();

        $expenseArray = array();
        $i = 0;
        foreach ($resource as $res) {
            $expenseArray[$i]['id'] = $res->id;
            $expenseArray[$i]['date'] = $dateClass->changeYMDtoDMY($res->date);
            $expenseArray[$i]['paid_amount'] = Utils::IND_money_format($res->paid_amount);
            $expenseArray[$i]['paid_to'] = $res->vname;
            $expenseArray[$i]['expense_head'] = $res->aname;
            $expenseArray[$i]['remarks'] = $res->remarks;
            $expenseArray[$i]['payment_mode'] = $res->payment_mode;
            $expenseArray[$i]['payment_type'] = $res->type_of_payment;
            $expenseArray[$i]['number'] = $res->reference_number;
            $i++;
        }

        return $expenseArray;
    }
}
