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
            $module_name[$value->id] = $value->name;
        }

        return $module_name;
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
}
