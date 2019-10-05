<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FunctionalRoles extends Model
{
    public $table = "functional_roles";

    public static function getAllFunctionalRoles() {

    	$query = FunctionalRoles::query();
    	$query = $query->select('functional_roles.*');
    	$query = $query->orderBy('functional_roles.id','desc');
    	$respose = $query->get();

    	$functional_roles_array = array();
    	$i = 0;

    	foreach ($respose as $key => $value){

    		$functional_roles_array[$value->id] = $value->name;
    	}
        
    	return $functional_roles_array;
    }
}
