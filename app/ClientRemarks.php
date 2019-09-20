<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientRemarks extends Model
{
    public $table = "client_remarks";

     public static function getAllClientRemarks(){

    	$query = ClientRemarks::query();
    	$query = $query->select('client_remarks.*');
    	$query = $query->orderBy('id','desc');
    	$res = $query->get();

    	$client_remarks = array();
    	$i = 0;
    	foreach ($res as $key => $value) {
    		$client_remarks[$i]['id'] = $value->id;
    		$client_remarks[$i]['remarks'] = $value->remarks;
    		$i++;
    	}

    	return $client_remarks;
    }
}
