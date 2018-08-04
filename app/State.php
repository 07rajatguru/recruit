<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    //
     public $table = "state";

     public static function getState($state)
     {
     	$state_query = State::query();
        $state_query = $state_query->where('state_nm','like',$state);

        $state_query = $state_query->select('state_id');
        $state=$state_query->first();

        if(isset($state))
        {
            $state_id=$state->state_id;
        }
    
        return $state_id;
     }
}
