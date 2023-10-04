<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role as SpatieRole;
use App\Module;

class Role extends SpatieRole
{
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

    public static function getRolesByDepartmentId($department_id,$user_id) {

        $superadmin = getenv('SUPERADMINUSERID');
        $saloni_user_id = getenv('SALONIUSERID');
        $super_array = array($superadmin,$saloni_user_id);

        $query = Role::query();

        if(in_array($user_id,$super_array)) {
        }
        else {

            $query = $query->where('roles.role_visible_to_all','=',1);
            $query = $query->where('roles.role_in_mngmnt_team','=',0);
        }
        
        $query = $query->where('roles.department','=',$department_id);
        $query = $query->select('roles.*');
        $query = $query->orderBy('roles.position','ASC');

        $response = $query->get();

        $roles = array();

        foreach ($response as $k=>$v) {

            $roles[$v->id] = $v->display_name;
        }
        return $roles;
    }

    public static function getRoleNameById($role_id) {

        $role_nm = '';

        $query = Role::query();
        $query = $query->where('roles.id','=',$role_id);
        $query = $query->first();

        if(isset($query)) {
            $role_nm = $query->name;
        }
        return $role_nm;
    }

    public static function getRolesByDepartment($department_ids) {

        $query = Role::query();
        $query = $query->whereIn('roles.department',$department_ids);
        $query = $query->select('roles.id','roles.display_name','roles.department');
        $query = $query->orderBy('roles.position','ASC');
        $response = $query->get();

        $roles_array = array();
        $hr_role_id = getenv('HR');

        // Get the role with ID 1
         $role_id = Role::find(1);

          if ($role_id) {
              $roles_array[$role_id->id] = $role_id->display_name;
          }

        if(isset($response) && sizeof($response) > 0) {

            foreach ($response as $key => $value) {

                if($value->department == 3) {

                    if($value->id == $hr_role_id) {

                        $roles_array[$value->id] = $value->display_name;
                    }
                    else {

                    }
                }
                else {
                    
                    $roles_array[$value->id] = $value->display_name;
                }
            }
        }
        return $roles_array;
    }
}