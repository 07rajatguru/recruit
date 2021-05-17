<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Companies extends Model
{
    public $table = "companies";

    public static function getCompanies() {

        $response = Companies::select('*')->get();

        $companies = array();

        if(isset($response) && sizeof($response)>0) {

            foreach ($response as $r) {
                $companies[$r->id] = $r->name;
            }
        }

        return $companies;
    }

    public static function getCompanyIdByName($company_nm) {

        $company_id = '';

        $query = Companies::query();
        $query = $query->where('companies.name','like',"%$company_nm%");
        $query = $query->first();

        if(isset($query)){
            $company_id = $query->id;
        }
        return $company_id;
    }
}