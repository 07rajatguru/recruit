<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobOpenDoc extends Model
{
    public $table = "job_openings_doc";


    public static function getJobDocByJobId($job_id){

        $query_doc = JobOpenDoc::query();
        $query_doc = $query_doc->join('users', 'users.id', '=', 'job_openings_doc.uploaded_by');
        $query_doc = $query_doc->select('job_openings_doc.*', 'users.name as upload_name');
        $query_doc = $query_doc->where('job_id','=', $job_id);
        $query_doc_res = $query_doc->get();

        $i = 0;
        $job_doc = array();
        $utils = new Utils();
        $jobopen_model = new JobOpen();
        $upload_type = $jobopen_model->upload_type;
        foreach ($query_doc_res as $key => $value) {
        	$job_doc[$i]['name'] = $value->name;
            $job_doc[$i]['id'] = $value->id;
            $job_doc[$i]['url'] = "../" . $value->file;
            $job_doc[$i]['category'] = $value->category;
            $job_doc[$i]['uploaded_by'] = $value->upload_name;
            $job_doc[$i]['size'] = $utils->formatSizeUnits($value->size);

            if (array_search($value->category, $upload_type)) {
                unset($upload_type[array_search($value->category, $upload_type)]);
            }
	        $upload_type['Others'] = 'Others';
	        $job_doc[$i]['upload_type'] = $upload_type;
            $i++;
        }

        return $job_doc;
    }
}
