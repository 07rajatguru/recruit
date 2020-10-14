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
        $query = $query->leftjoin('users','users.id','=','client_timeline.user_id');
        $query = $query->where('client_id','=',$client_id);
        $query = $query->orderBy('id','DESC');
        $query = $query->select('client_timeline.*','users.name as user_name');

        $response = $query->get();
        
        $i = 0;
        $days = 0;
        $to_date = '';
        $client_timeline_array = array();

        foreach ($response as $key => $value) {

            $client_timeline_array[$i]['id'] = $value->id;
            $client_timeline_array[$i]['user_id'] = $value->user_id;

            if($value->user_id != '0') {
                $client_timeline_array[$i]['user_name'] = $value->user_name;
            }
            else {
                $client_timeline_array[$i]['user_name'] = 'Yet to Assign';
            }
            
            $client_timeline_array[$i]['client_id'] = $value->client_id;
            $client_timeline_array[$i]['from_date'] = date("d-m-Y", strtotime($value->created_at));

            if($value->to_date != ''){

                $client_timeline_array[$i]['to_date'] = date("d-m-Y", strtotime($value->to_date));
            }
            else{

                $client_timeline_array[$i]['to_date'] = '-';
            }

            if($value->days == '0'){

                $client_timeline_array[$i]['days'] = '-';
            }
            else{

                $client_timeline_array[$i]['days'] = $value->days;
            }
            
            $i++;
        }
        return $client_timeline_array;
    }
}
