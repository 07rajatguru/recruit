<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class New_PermissionRole extends Model
{
    public $table = "new_permission_role";
    public $timestamps = false;

    public static function getPermissionsByRoleID($role_id)
    {
    	$query = New_PermissionRole::query();
    	$query = $query->leftjoin('new_permissions','new_permissions.id','=','new_permission_role.permission_id');
    	$query = $query->select('new_permissions.module_id as module_id','new_permission_role.permission_id');

        if(isset($role_id) && $role_id > 0) {
    	   $query = $query->where('new_permission_role.role_id','=',$role_id);
        }
        
    	$response = $query->get();

    	$module_permissions = array();
    	$i = 0;

    	foreach ($response as $key => $value) {

    		$module_permissions[$i]['module_id'] = $value->module_id;
    		$module_permissions[$i]['permission_id'] = $value->permission_id;
    		$i++;
    	}

    	return $module_permissions;
    }
}
