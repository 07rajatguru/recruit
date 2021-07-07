<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketsDiscussion extends Model
{
    public $table = "tickets_discussion";

    public static function getAllDetails() {

        $query = TicketsDiscussion::query();
        $query = $query->leftjoin('users','users.id','=','tickets_discussion.added_by');
        $query = $query->orderBy('tickets_discussion.id','DESC');
        $query = $query->select('tickets_discussion.*','users.name as added_by');
        $response = $query->get();

        $i=0;
        $tickets_res = array();

        foreach ($response as $key => $value) {

            $tickets_res[$i]['id'] = $value->id;
            $tickets_res[$i]['question_type'] = $value->question_type;
            $tickets_res[$i]['added_by'] = $value->added_by;
            $i++;
        }
        return $tickets_res;
    }

    public static function getTicketDetailsById($id) {

        $query = TicketsDiscussion::query();
        $query = $query->leftjoin('users','users.id','=','tickets_discussion.added_by');
        $query = $query->orderBy('tickets_discussion.id','DESC');
        $query = $query->select('tickets_discussion.*','users.name as added_by');
        $query = $query->where('tickets_discussion.id','=',$id);
        $response = $query->first();

        $ticket_res = array();

        $ticket_res['id'] = $response->id;
        $ticket_res['question_type'] = $response->question_type;
        $ticket_res['description'] = $response->description;
        $ticket_res['added_by'] = $response->added_by;
    
        return $ticket_res;
    }
}