<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ToDos extends Model
{
    //
    public $table = "to_dos";

    public static $rules = array(
        'subject' => 'required',
        'candidate' => 'required',
        'due_date' => 'required',
        'type' => 'required',
    );

    public function messages()
    {
        return [
            'subject.required' => 'Subject is required field',
            'candidate.required' => 'Candidate is required field',
            'due_date.required' => 'Due Date is required field',
            'type.required' => 'Type is required field',
        ];
    }

    public static function getPriority(){
        $priority = array();
        $priority['Normal'] = 'Normal';
        $priority['High'] = 'High';
        $priority['Highest'] = 'Highest';
        $priority['Low'] = 'Low';
        $priority['Lowest'] = 'Lowest';

        return $priority;
    }
}
