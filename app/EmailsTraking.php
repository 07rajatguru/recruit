<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmailsTraking extends Model
{
    use SoftDeletes;
    //
    public $table = "emails_traking";
}
