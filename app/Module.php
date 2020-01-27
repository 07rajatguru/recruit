<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{

	public $table = "module";

    public static function getAllModules(){

    	$query = Module::query();
    	$query = $query->select('module.*');
    	$query = $query->orderBy('module.id','desc');
    	$res = $query->get();

    	$module = array();
    	$i = 0;
    	foreach ($res as $key => $value) {
    		$module[$i]['id'] = $value->id;
    		$module[$i]['name'] = $value->name;
    		$module[$i]['description'] = $value->description;
    		$i++;
    	}

    	return $module;
    }

    public static function getAllModulesName(){

        $query = Module::query();
        $query = $query->select('module.*');
        $query = $query->orderBy('module.id','desc');
        $res = $query->get();

        $module_name = array();
        foreach ($res as $key => $value) {
            $module_name[$value->id]['name'] = $value->name;
            $module_name[$value->id]['status'] = $value->status;
        }

        return $module_name;
    }

    public static function getModules()
    {
        $query = Module::query();
        $query = $query->orderBy('id','ASC');

        $response = $query->get();
        $module = array();

        foreach ($response as $k=>$v)
        {
            $module[$v->id] = $v->name;  
        }

        return $module;
    }

    public static function getAllModulesNameAjax(){

        $query = Module::query();
        $query = $query->select('module.*');
        $query = $query->orderBy('module.id','desc');
        $res = $query->get();

        $module_name = array();
        $i = 0;
        foreach ($res as $key => $value) {
            $module_name[$i] = "." . $value->name;
            $i++;
        }

        return $module_name;
    }

    public static function getModuleNameById($module_id){

        $module_name = '';

        $query = Module::query();
        $query = $query->where('id','=',$module_id);
        $query = $query->first();

        if(isset($query)){
            $module_name = $query->name;
        }

        return $module_name;
    }
}
