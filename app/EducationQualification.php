<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EducationQualification extends Model
{
    public $table = "eduction_qualification";

    public static function getAllEducationQualifications() {

    	$query = EducationQualification::query();
    	$query = $query->select('eduction_qualification.*');
    	$query = $query->orderBy('eduction_qualification.name','ASC');
    	$respose = $query->get();

    	$eduction_qualification_array = array();
    	$i = 0;

        $eduction_qualification_array[""] = "--- Select Education Qualification ---";

    	foreach ($respose as $key => $value){

    		$eduction_qualification_array[$value->id] = $value->name;
    	}
    	return $eduction_qualification_array;
    }
}
