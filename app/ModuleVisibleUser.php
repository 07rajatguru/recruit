<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModuleVisibleUser extends Model
{
    public $table = "module_visible_user";

    public static function getAllModuleVisibleUser(){

    	$query = ModuleVisibleUser::query();
    	$query = $query->leftjoin('users','users.id','=','module_visible_user.user_id');
    	$query = $query->leftjoin('module','module.id','=','module_visible_user.module_id');
    	$query = $query->select('module_visible_user.*','users.name as user_name','module.name as module_name');
    	$query = $query->orderBy('module_visible_user.id','desc');
        $query = $query->groupBy('module_visible_user.user_id');
    	$res = $query->get();

    	$module_user = array();
    	$i = 0;
    	foreach ($res as $key => $value) {
            $module_user[$i]['main_id'] = $value->id;
            $module_user[$i]['id'] = $value->user_id;
    		$module_user[$i]['uname'] = $value->user_name;
            $name = ModuleVisibleUser::getModuleByUserId($value->user_id);
            $name_str = '';
            foreach ($name as $k=>$v){
                if($name_str==''){
                    $name_str = $v;
                }
                else{
                    $name_str .= ', '. $v ;
                }
            }
    		$module_user[$i]['mname'] = $name_str;
    		$i++;
    	}

    	return $module_user;
    }

    public static function getModuleByUserId($user_id){

        $query = ModuleVisibleUser::query();
        $query = $query->leftjoin('module','module.id','=','module_visible_user.module_id');
        $query = $query->select('module_visible_user.*','module.name as module_name','module.id as module_id');
        $query = $query->where('module_visible_user.user_id',$user_id);
        $res = $query->get();

        $module_arr = array();
        $i = 0;
        foreach ($res as $key => $value) {
            $module_arr[$value->module_id] = $value->module_name;
            $i++;
        }

        return $module_arr;
    }

    public static function getModuleByUserIdAjax($user_id){

        $query = ModuleVisibleUser::query();
        $query = $query->leftjoin('module','module.id','=','module_visible_user.module_id');
        $query = $query->select('module_visible_user.*','module.name as module_name','module.id as module_id');
        $query = $query->where('module_visible_user.user_id',$user_id);
        $res = $query->get();

        $module_arr = array();
        $i = 0;
        foreach ($res as $key => $value) {
            $module_arr[$i] = "." . $value->module_name;
            $i++;
        }

        return $module_arr;
    }
}
