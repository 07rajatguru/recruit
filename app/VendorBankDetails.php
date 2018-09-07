<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorBankDetails extends Model
{
    //
    public $table="vendor_bank_details";

    public static $rules = array(
        'bank_name' => 'required',
        'bank_address' => 'required',
        'account' => 'required',
        'acc_type' => 'required',
        'ifsc' => 'required'

    );


      public function messages()
    {
        return [
            'bank_name.required' => 'Name is Required field',
            'bank_address.required'  => 'Address required field',
            'account.required'=>'Account No is required field',
            'acc_type.required'=>'Type of account is required field',
            'ifsc.required'=>'IFSC Code is required field'
        ];
    }
}
