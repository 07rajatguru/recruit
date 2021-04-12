<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientHeirarchy extends Model
{
	public $table = "client_heirarchy";

    public static function getAllClientHeirarchy() {

    	$query = ClientHeirarchy::query();
    	$query = $query->select('client_heirarchy.*');
    	$query = $query->orderBy('order','asc');
    	$res = $query->get();

    	$client_heirarchy = array();
    	$i = 0;
    	foreach ($res as $key => $value) {
    		$client_heirarchy[$i]['id'] = $value->id;
    		$client_heirarchy[$i]['name'] = $value->name;
    		$client_heirarchy[$i]['order'] = $value->order;

            if($value->position == '0') {

                $client_heirarchy[$i]['position'] = 'Below AM';
            }
            else {

                $client_heirarchy[$i]['position'] = 'Above AM';
            }
    		$i++;
    	}

    	return $client_heirarchy;
    }

    public static function getAllClientHeirarchyName() {

        $query = ClientHeirarchy::query();
        $query = $query->select('client_heirarchy.*');
        $query = $query->orderBy('order','asc');
        $res = $query->get();

        $client_heirarchy_name = array('0' => 'Select Position');
        
        foreach ($res as $key => $value) {
            $client_heirarchy_name[$value->id] = $value->name;
        }

        return $client_heirarchy_name;
    }

    public static function getClientHeirarchyPositionById($position_id) {

        $query = ClientHeirarchy::query();
        $query = $query->where('id','=',$position_id);
        $query = $query->select('client_heirarchy.*');
        $res = $query->first();

        if(isset($res) && $res != '') {

            if($res->position == '0'){

                $position = 'Below AM';
            }
            else{
                
                $position = 'Above AM';
            }
        }
        else {

            $position = '';
        }

        return $position;
    }
    public static function getClientHeirarchyNameById($level_id) {

        $query = ClientHeirarchy::query();
        $query = $query->where('id','=',$level_id);
        $query = $query->select('client_heirarchy.*');
        $res = $query->first();

        $level_name = '';

        if(isset($res) && $res != '') {

            $level_name = $res->name;
        }

        return $level_name;
    }
}
