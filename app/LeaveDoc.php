<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaveDoc extends Model
{
    public $table= 'leave_doc';

    public static function getLeaveDocById($leave_id){
    	$utils = new Utils();

    	$query = LeaveDoc::query();
    	$query = $query->select('leave_doc.*');
    	$query = $query->where('leave_doc.leave_id',$leave_id);
    	$res = $query->get();

    	$leave_doc = array();
    	$i = 0;
    	foreach ($res as $key => $value) {
    		$leave_doc[$i]['id'] = $value->id;
            $leave_doc[$i]['fileName'] = $value->file;
            $leave_doc[$i]['url'] = "../../".$value->file;
            $leave_doc[$i]['name'] = $value->name ;
            $leave_doc[$i]['size'] = $utils->formatSizeUnits($value->size);
            $i++;
    	}

    	return $leave_doc;
    }
}
