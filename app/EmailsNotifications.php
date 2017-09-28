<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmailsNotifications extends Model
{
    //
    use SoftDeletes;

    public $table = "emails_notification";
}
