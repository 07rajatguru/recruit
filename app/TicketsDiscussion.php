<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketsDiscussion extends Model
{
    public $table = "tickets_discussion";

    public function post() {
        return $this->hasMany('App\TicketDiscussionPost','tickets_discussion_id');
    }

    public static function getAllDetails($all,$user_id) {

        $query = TicketsDiscussion::query();
        $query = $query->leftjoin('users','users.id','=','tickets_discussion.added_by');
        $query = $query->leftjoin('module','module.id','=','tickets_discussion.module_id');
        $query = $query->orderBy('tickets_discussion.id','DESC');

        if($all == 0) {

            $query = $query->where('tickets_discussion.added_by','=',$user_id);
        }
        $query = $query->select('tickets_discussion.*','users.name as added_by','module.name as module_name');
        $response = $query->get();

        $i=0;
        $tickets_res = array();

        foreach ($response as $key => $value) {

            $tickets_res[$i]['id'] = $value->id;
            $tickets_res[$i]['module_name'] = $value->module_name;
            $tickets_res[$i]['status'] = $value->status;
            $tickets_res[$i]['question_type'] = $value->question_type;
            $tickets_res[$i]['added_by'] = $value->added_by;
            $tickets_res[$i]['added_date'] = date('d-m-Y', strtotime("$value->created_at"));

            $i++;
        }
        return $tickets_res;
    }

    public static function getTicketDetailsById($id) {

        $query = TicketsDiscussion::query();
        $query = $query->leftjoin('users','users.id','=','tickets_discussion.added_by');
        $query = $query->leftjoin('module','module.id','=','tickets_discussion.module_id');
        $query = $query->orderBy('tickets_discussion.id','DESC');
        $query = $query->select('tickets_discussion.*','users.name as added_by','module.name as module_name');
        $query = $query->where('tickets_discussion.id','=',$id);
        $response = $query->first();

        $ticket_res = array();

        $ticket_res['id'] = $response->id;
        $ticket_res['module_name'] = $response->module_name;
        $ticket_res['status'] = $response->status;
        $ticket_res['question_type'] = $response->question_type;
        $ticket_res['description'] = $response->description;
        $ticket_res['added_by'] = $response->added_by;
    
        return $ticket_res;
    }
}