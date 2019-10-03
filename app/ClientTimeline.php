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
        $query = $query->orderBy('id','ASC');
        $query = $query->select('client_timeline.*');

        $response = $query->get();
        
        $i = 0;
        $days = 0;
        $to_date = '';
        $client_timeline_array = array();

        foreach ($response as $key => $value) {

            $client_timeline_array[$i]['id'] = $value->id;
            $client_timeline_array[$i]['user_id'] = $value->user_id;
            $client_timeline_array[$i]['client_id'] = $value->client_id;

            $to_date = date('Y-m-d', strtotime($value->created_at));
            $client_timeline_array['to_date'] = $to_date;

            $from_date = date('Y-m-d', strtotime($value->created_at));

            $client_timeline_array[$i]['from_date'] = $from_date;

            $days = \Carbon\Carbon::parse($to_date)->diffInDays(\Carbon\Carbon::parse($from_date));

            $client_timeline_array[$i]['days'] = $days;
            
            $i++;
        }
        return $client_timeline_array;
    }
}
