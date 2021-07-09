<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Utils;

class TicketsDiscussionPostDoc extends Model
{
    public $table = "tickets_discussion_post_doc";

    public static function getTicketPostDocsById($id) {

        $query = TicketsDiscussionPostDoc::query();
        $query = $query->orderBy('tickets_discussion_post_doc.id','DESC');
        $query = $query->select('tickets_discussion_post_doc.*');
        $query = $query->where('tickets_discussion_post_doc.post_id','=',$id);
        $response = $query->get();

        $i=0;
        $docdetails['files'] = array();
        $utils = new Utils();

        foreach ($response as $key => $value) {

            $docdetails['files'][$i]['id'] = $value->id;
            $docdetails['files'][$i]['fileName'] = $value->file;
            $docdetails['files'][$i]['url'] = "../../".$value->file;
            $docdetails['files'][$i]['name'] = $value->name ;
            $docdetails['files'][$i]['size'] = $utils->formatSizeUnits($value->size);

            $i++;
        }
        return $docdetails['files'];
    }
}