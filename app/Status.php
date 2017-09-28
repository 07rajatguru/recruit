<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    //
    public $table = "status";

    public static function getStatusArray(){
        $status = Status::all();
        $statusArr = array();

        if(isset($status) && sizeof($status) > 0){
            foreach ($status as $item) {
                $statusArr[$item->id] = $item->name;
            }
        }

        return $statusArr;
    }
}
