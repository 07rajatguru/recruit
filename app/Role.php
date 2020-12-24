<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Zizaco\Entrust\EntrustRole;
use App\Module;

class Role extends EntrustRole
{
    public static function getUserRoleNameById($role_id) {

        $role_name = '';

        $query = Role::query();
        $query = $query->where('id','=',$role_id);
        $query = $query->first();

        if(isset($query)) {
            $role_name = $query->display_name;
        }
        return $role_name;
    }

    public static function getModulesByUserRoleId($user_role_id) {

        $query = Role::query();
        $query = $query->select('roles.*');

        if(isset($user_role_id) && $user_role_id > 0) {
           $query = $query->where('roles.id','=',$user_role_id);
        }
        
        $response = $query->first();

        $module_ids = explode(",", $response->module_ids);

        if(isset($module_ids) && sizeof($module_ids) > 0) {

            $module_names = array();
            $i = 0;

            foreach ($module_ids as $key => $value) {
                $module_names[$i] = "." . Module::getModuleNameById($value);
                $i++;
            }
        }
        return $module_names;
    }

    public static function getAllRolesDetails() {

        $query = Role::query();
        $query = $query->select('roles.*');
        $query = $query->orderBy('roles.id','asc');
        $response = $query->get();

        $roles = array();
        $i = 0;

        foreach ($response as $key => $value) {

            $roles[$i]['id'] = $value->id;
            $roles[$i]['name'] = $value->name;
            $roles[$i]['display_name'] = $value->display_name;
            $roles[$i]['description'] = $value->description;
            $i++;
        }

        return $roles;
    }
}