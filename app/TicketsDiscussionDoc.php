<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Utils;

class TicketsDiscussionDoc extends Model
{
    public $table = "tickets_discussion_doc";

    public static function getTicketDocsById($id) {

        $query = TicketsDiscussionDoc::query();
        $query = $query->orderBy('tickets_discussion_doc.id','DESC');
        $query = $query->select('tickets_discussion_doc.*');
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