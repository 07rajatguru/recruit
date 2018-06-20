<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    public $table = "expense";

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
}
