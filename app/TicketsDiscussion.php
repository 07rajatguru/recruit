<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketsDiscussion extends Model
{
    public $table = "tickets_discussion";

    public function post() {
        return $this->hasMany('App\TicketDiscussionPost','tickets_discussion_id');
    }

    public static function ticketStatus() {

        $status_array = array();
        
        $status_array['Open'] = 'Open';
        $status_array['In Progress'] = 'In Progress';
        $status_array['Closed'] = 'Closed';

        return $status_array;
    }

    public static function ticketTicketQuestionType() {

        $question_type = array();
        
        $question_type['Problem on module'] = 'Problem on module';
        $question_type['How to use functionality'] = 'How to use functionality';
        $question_type['Other'] = 'Other';

        return $question_type;
    }

    public static function getAllTicketDetails($all,$user_id,$status=NULL) {

        $query = TicketsDiscussion::query();
        $query = $query->leftjoin('users','users.id','=','tickets_discussion.added_by');
        $query = $query->leftjoin('module','module.id','=','tickets_discussion.module_id');
        $query = $query->orderBy('tickets_discussion.id','DESC');

        if($all == 0) {

            $query = $query->where('tickets_discussion.added_by','=',$user_id);
        }

        if(isset($status) && $status != '') {

            $query = $query->where('tickets_discussion.status','=',$status);
        }
        
        $query = $query->select('tickets_discussion.*','users.name as added_by','module.name as module_name');
        $response = $query->get();

        $i=0;
        $tickets_res = array();

        foreach ($response as $key => $value) {

            $tickets_res[$i]['id'] = $value->id;
            $tickets_res[$i]['ticket_no'] = $value->ticket_no;
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

        if(isset($response) && $response != '') {

            $ticket_res['id'] = $response->id;
            $ticket_res['ticket_no'] = $response->ticket_no;
            $ticket_res['module_name'] = $response->module_name;
            $ticket_res['status'] = $response->status;
            $ticket_res['question_type'] = $response->question_type;
            $ticket_res['description'] = $response->description;
            $ticket_res['added_by'] = $response->added_by;
        }
        
        return $ticket_res;
    }
}