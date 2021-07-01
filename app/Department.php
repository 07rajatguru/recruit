<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    public $table = "department";

    public static function getDepartmentNameById($department_id) {

        $department_nm = '';

        $query = Department::query();
        $query = $query->where('department.id','=',$department_id);
        $query = $query->first();

        if(isset($query)) {
            $department_nm = $query->name;
        }
        return $department_nm;
    }
}