<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobVisibleUsers extends Model
{
    public $table = "job_visible_users";
    public $timestamps = false;

    public static function getAllUsersByJobId($job_id){

    }
}
