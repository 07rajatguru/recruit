<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    public static function getBankType(){

    	$banktype = array('' => 'Select Bank Type');
    	$banktype['hdfc'] = 'HDFC';
    	$banktype['icici'] = 'ICICI';
    	$banktype['other'] = 'Other';

    	return $banktype;
    }
}
