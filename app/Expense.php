<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    public $table = "expense";

    public static $rules = array(
        'date' => 'required',
        'amount' => 'required',
        'paid_to' => 'required',
        'head' => 'required',
        'remarks' => 'required',
        'pmode' => 'required',
        'ptype' => 'required'
    );

    public function messages()
    {
        return [
            'date.required' => 'Date is required field',
            'amount.required' => 'Amount is required field',
            'paid_to.required' => 'Paid To is required field',
            'head.required' => 'Expense Head is required field',
            'remarks.required' => 'Remarks is required field',
            'pmode.required' => 'Payment Mode is required field',
            'ptype.required' => 'Payment Type is required field'
        ];
    }

    public static function getPaymentMode(){
    	$pmode = array(''=>'Select Payment Mode');
    	$pmode['petty cash-accounts'] = 'Petty cash-Accounts';
    	$pmode['petty cash-director'] = 'Petty cash-Director';
    	$pmode['AMEX'] = 'AMEX';
    	$pmode['ICICI Bank'] = 'ICICI Bank';
    	$pmode['HDFC Bank'] = 'HDFC Bank';
    	$pmode['Paytm'] = 'Paytm';
    	$pmode['Freecharge'] = 'Freecharge';

    	return $pmode;
    }

    public static function getPaymentType(){
    	$ptype = array(''=>'Select Payment Type');
    	$ptype['neft'] = 'NEFT';
    	$ptype['imps'] = 'IMPS';
    	$ptype['cheque'] = 'Cheque';

    	return $ptype;
    }

    public static function getAllExpense(){

        $query = Expense::query();
        $query = $query->join('accounting_heads','accounting_heads.id','=','expense.expense_head');
        $query = $query->select('expense.*','accounting_heads.name as aname');
        $resource = $query->get();

        $expenseArray = array();
        $i = 0;
        foreach ($resource as $res) {
            $expenseArray[$i]['id'] = $res->id;
            $expenseArray[$i]['date'] = $res->date;
            $expenseArray[$i]['amount'] = $res->amount;
            $expenseArray[$i]['paid_to'] = $res->paid_to;
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
