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

    public static function getSearchRemarks($term){
        
        $query = ClientRemarks::query();

        if($term!=''){
            $query = $query->where('client_remarks.remarks','like',"%$term%");
        }

        $query = $query->select('client_remarks.remarks','client_remarks.id');
        $query = $query->orderBy('client_remarks.id','DESC');
        $query = $query->limit(50);
        $response = $query->get();

        $data = array();
        $i=0;

        foreach ($response as $key=>$value){
            $data[$i]['label'] = $value->remarks;
            $data[$i]['id'] = $value->id;
            $i++;
        }
        if(sizeof($data)==0){
            $data['label'] = '';
            $data['id'] = '';
        }
        return $data;
    }
}
