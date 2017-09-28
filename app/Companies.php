<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Companies extends Model
{
    public $table = "companies";


    public static function getCompanies(){
        $response = Companies::select('*')
            ->get();

        $companies = array();
        if(isset($response) && sizeof($response)>0){
            foreach ($response as $r) {
                $companies[$r->id] = $r->name;
            }
        }

        return $companies;
    }
}
