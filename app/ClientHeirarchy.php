<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientHeirarchy extends Model
{

	public $table = "client_heirarchy";
    /*public function index(){

        $modules = Module::getAllModules();

        return view('adminlte::module.index',compact('modules'));
    }*/

    public static function getAllClientHeirarchy(){

    	$query = ClientHeirarchy::query();
    	$query = $query->select('client_heirarchy.*');
    	$query = $query->orderBy('id','desc');
    	$res = $query->get();

    	$client_heirarchy = array();
    	$i = 0;
    	foreach ($res as $key => $value) {
    		$client_heirarchy[$i]['id'] = $value->id;
    		$client_heirarchy[$i]['name'] = $value->name;
    		$client_heirarchy[$i]['order'] = $value->order;
    		$i++;
    	}

    	return $client_heirarchy;
    }

    public static function getAllClientHeirarchyName(){

        $query = ClientHeirarchy::query();
        $query = $query->select('client_heirarchy.*');
        $res = $query->get();

        $client_heirarchy_name = array('0' => 'Select Position');
        foreach ($res as $key => $value) {
            $client_heirarchy_name[$value->id] = $value->name;
        }

        return $client_heirarchy_name;
    }
}
