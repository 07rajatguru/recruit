<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomerSupport extends Model
{
    public $table = "customer_support";

    public static function getAllDetails()
    {
    	$query = CustomerSupport::query();
    	$query = $query->leftjoin('module','module.id','=','customer_support.module');
    	$query = $query->leftjoin('users','users.id','=','customer_support.user_id');
    	$query = $query->orderBy('customer_support.id','DESC');
    	$query = $query->select('customer_support.id as customer_support_id','customer_support.subject as subject','module.name as module_nm','users.name as user_nm');
    	$response = $query->get();

    	$i=0;
    	$customer_support_array = array();

    	foreach ($response as $key => $value)
    	{
    		$customer_support_array[$i]['id'] = $value->customer_support_id;
    		$customer_support_array[$i]['user_nm'] = $value->user_nm;
    		$customer_support_array[$i]['module'] = $value->module_nm;
    		$customer_support_array[$i]['subject'] = $value->subject;
    		$i++;
    	}

    	return $customer_support_array;

    }

    public static function getCustomerSupportDetailsById($id)
    {
        $query = CustomerSupport::query();
        $query = $query->leftjoin('module','module.id','=','customer_support.module');
        $query = $query->leftjoin('users','users.id','=','customer_support.user_id');
        $query = $query->orderBy('customer_support.id','DESC');
        $query = $query->select('customer_support.*','module.name as module_nm','users.name as user_nm');
        $query = $query->where('customer_support.id','=',$id);
        $response = $query->first();

        $customer_support_array = array();

        $customer_support_array['id'] = $response->id;
        $customer_support_array['user_nm'] = $response->user_nm;
        $customer_support_array['subject'] = $response->subject;
        $customer_support_array['module'] = $response->module_nm;
        $customer_support_array['message'] = $response->message;
    
        return $customer_support_array;
    }
}
