<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Industry extends Model
{
    public $table = "industry";
    public $timestamps = false;

    public static function existIndustryInClient($id)
    {
    	$query = Industry::query();
    	$query = $query->leftjoin('client_basicinfo','client_basicinfo.industry_id','=','industry.id');
    	$query = $query->select('industry.*','client_basicinfo.industry_id as industry_id');
    	$query = $query->where('client_basicinfo.industry_id','=',$id);

    	$response = $query->first();

        return $response;
    }
}
