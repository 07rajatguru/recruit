<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DailyReport extends Model
{
    public $table = "daily_report";

    public static $rules = array(
        'position_name' => 'required',
        'client_id' => 'required',
        'location' => 'required',
        'candidate_status_id' => 'required',
        'report_date' => 'required',
    );

    public function messages()
    {
        return [
            'position_name.required' => 'Posting Name is required field',
            'client_id.required' => 'Client is required field',
            'location.required' => 'Location is required field',
            'candidate_status_id.required' => 'Candidate Status is required field',
            'report_date.required' => 'Report Date is required field',
        ];
    }
}
