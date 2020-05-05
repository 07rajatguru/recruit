<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    public static function getUserRoleNameById($role_id){

        $role_name = '';

        $query = Role::query();
        $query = $query->where('id','=',$role_id);
        $query = $query->first();

        if(isset($query)){
            $role_name = $query->display_name;
        }
        return $role_name;
    }
}