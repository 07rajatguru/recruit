<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PermissionRole extends Model
{
    public $table = "permission_role";
    public $timestamps = false;

    public static function getPermissionsByRoleID($role_id) {

    	$query = PermissionRole::query();
    	$query = $query->leftjoin('permissions','permissions.id','=','permission_role.permission_id');
    	$query = $query->select('permissions.module_id as module_id','permission_role.permission_id','permissions.name as permission_name');

        if(isset($role_id) && $role_id > 0) {
    	   $query = $query->where('permission_role.role_id','=',$role_id);
        }
        
    	$response = $query->get();

    	$module_permissions = array();
    	$i = 0;

    	foreach ($response as $key => $value) {

    		$module_permissions[$i]['module_id'] = $value->module_id;
    		$module_permissions[$i]['permission_id'] = $value->permission_id;
            $module_permissions[$i]['permission_name'] = $value->permission_name;
    		$i++;
    	}

    	return $module_permissions;
    }

    public static function getPermissionsStringByRoleID($role_id) {

        $query = PermissionRole::query();
        $query = $query->select('permission_role.*');

        if(isset($role_id) && $role_id > 0) {
           $query = $query->where('permission_role.role_id','=',$role_id);
        }
        
        $response = $query->get();

        $permissions_string = '';

        foreach ($response as $key => $value) {

            if($permissions_string == '') {
                $permissions_string = $value->permission_id;
            }
            else {
                $permissions_string = $permissions_string . "," . $value->permission_id;
            }
        }

        return $permissions_string;
    }

    public static function checkExistorNot($role_id,$permission_id) {

        $query = PermissionRole::query();
        $query = $query->select('permission_role.*');

        $query = $query->where('permission_role.role_id','=',$role_id);
        $query = $query->where('permission_role.permission_id','=',$permission_id);

        $response = $query->first();

        return $response;
    }
}