<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientTimeline extends Model
{
    public $table = "client_timeline";

    public static function checkTimelineEntry($user_id,$client_id){
    	
        $query = ClientTimeline::query();
        $query = $query->where('user_id','=',$user_id);
        $query = $query->where('client_id','=',$client_id);
        $query = $query->select('client_timeline.*');

        $response = $query->first();
        
        return $response;
    }

    public static function getDetailsByClientId($client_id){

        $query = ClientTimeline::query();
        $query = $query->where('client_id','=',$client_id);
        $query = $query->orderBy('id','DESC');
        $query = $query->select('client_timeline.*');

        $response = $query->get();
        
        return $response;
    }
}
