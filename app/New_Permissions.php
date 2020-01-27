<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Module;

class New_Permissions extends Model
{
    public $table = "new_permissions";

    public static function getAllPermissionsDetails()
    {
    	$query = New_Permissions::query();
    	$query = $query->leftjoin('module','module.id','=','new_permissions.module_id');
    	$query = $query->select('new_permissions.*','module.name as module_name');
    	$query = $query->orderBy('new_permissions.id','asc');
    	$response = $query->get();

    	$permissions = array();
    	$i = 0;

    	foreach ($response as $key => $value) {
    		$permissions[$i]['id'] = $value->id;
    		$permissions[$i]['module_name'] = $value->module_name;
    		$permissions[$i]['display_name'] = $value->display_name;
    		$permissions[$i]['description'] = $value->description;
    		$i++;
    	}

    	return $permissions;
    }

    public static function getPermissionsByModuleID($module_id)
    {
        $query = New_Permissions::query();

        if(isset($module_id) && $module_id > 0) {
            $query = $query->where('new_permissions.module_id',$module_id);
        }
        
        $query = $query->select('new_permissions.*');
        $query = $query->orderBy('new_permissions.id','asc');
        $response = $query->get();

        $permissions = array();
        $i = 0;

        foreach ($response as $key => $value) {
            $permissions[$i]['id'] = $value->id;
            $permissions[$i]['display_name'] = $value->display_name;
            $permissions[$i]['description'] = $value->description;
            $permissions[$i]['module_name'] = Module::getModuleNameById($value->module_id);
            $i++;
        }
        return $permissions;
    }

    public static function getPermissionsByModuleIDArray($module_ids)
    {
        $module_ids_array = explode(",", $module_ids);
        
        $query = New_Permissions::query();
        $query = $query->leftjoin('module','module.id','=','new_permissions.module_id');
        $query = $query->select('new_permissions.*','module.name as module_name');
        $query = $query->whereIn('new_permissions.module_id',$module_ids_array);
        $query = $query->orderBy('new_permissions.id','asc');
        $response = $query->get();

        $permissions = array();
        $i = 0;

        foreach ($response as $key => $value) {
            $permissions[$i]['id'] = $value->id;
            $permissions[$i]['module_id'] = $value->module_id;
            $permissions[$i]['module_name'] = $value->module_name;
            $permissions[$i]['display_name'] = $value->display_name;
            $permissions[$i]['description'] = $value->description;
            $i++;
        }
        return $permissions;
    }
}
