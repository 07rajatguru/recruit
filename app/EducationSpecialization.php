<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EducationSpecialization extends Model
{
    public $table = "education_specialization";

    public static function getAllSpecializations() {

    	$query = EducationSpecialization::query();
    	$query = $query->select('education_specialization.*');
    	$query = $query->orderBy('education_specialization.name','ASC');
    	$respose = $query->get();

    	$eduction_specialization_array = array();
    	$i = 0;

    	foreach ($respose as $key => $value){

    		$eduction_specialization_array[$value->id] = $value->name;
    	}
    	return $eduction_specialization_array;
    }

    public static function getSpecializationByEducationId($education_id) {

    	$query = EducationSpecialization::query();
    	$query = $query->leftjoin('eduction_qualification','eduction_qualification.id','=','education_specialization.education_qualification_id');
    	$query = $query->select('education_specialization.*');
    	$query = $query->where('education_specialization.education_qualification_id','=',$education_id);

    	$response = $query->get();

    	$specalization_array = array();
    	$i=0;

    	foreach ($response as $key => $value) {
    		$specalization_array[$i]['specalization_id'] = $value->id;
    		$specalization_array[$i]['specalization_nm'] = $value->name;
    		$i++;
    	}

    	return $specalization_array;
    }
}
