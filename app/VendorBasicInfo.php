<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VendorBasicInfo extends Model
{
    //
    public $table="vendor_basicinfo";
    public static function getTypeArray(){
        $type = array();
        $type[''] = 'Select Account Type';
        $type['Current'] = 'Current';
        $type['Savings'] = 'Savings';

        return $type;
    }

     public static $rules = array(
        'name' => 'required',
        'mobile' => 'required',
        'gst_no' => 'required',
        'pan_no' => 'required',
        'gst_charge' => 'required'

    );

      public function messages()
    {
        return [
            'name.required' => 'Name is Required field',
            'mobile.required'  => 'Mobile is required field',
            'gst_no.required'=>'GST No. is required field',
            'pan_no.required'=>'PAN No. is required field',
            'gst_charge.required' => 'GST Chaege is required field'
        ];
    }

    public static function checkVendorByEmail($email){

        $vendor_query = VendorBasicInfo::query();
        $vendor_query = $vendor_query->where('mail','like',$email);
        $vendor_cnt = $vendor_query->count();

        return $vendor_cnt;

    }


     public static function getLoggedInUserVendors($user_id){

        $vendor_query = VendorBasicInfo::query();

        $vendor_query = $vendor_query->select('vendor_basicinfo.*');

        $vendor_response = $vendor_query->get();

        return $vendor_response;
    }


    public static function getVendorInfoByVendorId($vendor_id){

        $query = VendorBasicInfo::query();
        $query = $query->where('vendor_basicinfo.id','=',$vendor_id);
        $query = $query->select('vendor_basicinfo.gst_in','vendor_basicinfo.pan_no','vendor_basicinfo.name');
        $response = $query->get();

        $vendor = array();

        foreach ($response as $k=>$v){

            $vendor['gstno'] = $v->gst_in;
            $vendor['panno'] = $v->pan_no;
            $vendor['name']=$v->name;
            $vendor['gst_res']=substr($v->gst_in,0,2);
        }
        return $vendor;
    }


    public static function getVendor($vendor)
     {
        $vendor_query = VendorBasicInfo::query();
        $vendor_query = $vendor_query->where('name','like',$vendor);

        $vendor_query = $vendor_query->select('id');
        $vendor=$vendor_query->first();

        if(isset($vendor))
        {
            $vendor_id=$vendor->id;
        }
    
        return $vendor_id;
     }
}
