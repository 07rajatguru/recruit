<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountingHeads extends Model
{
	public $table = "accounting_heads";

	public static function getAllHead(){
		$query = AccountingHeads::query();
		$query = $query->select('accounting_heads.*');
		$res = $query->get();

		$head = array();
		if(isset($res) && sizeof($res)){
            foreach ($res as $row) {
                $head[$row->id] = $row->name;
            }
        }

        return $head;
	}

	public static function getHead($expensehead)
     {
        $head_query = AccountingHeads::query();
        $head_query = $head_query->where('name','like',$expensehead);

        $head_query = $head_query->select('id');
        $head = $head_query->first();

        $head_id = 0;
        if(isset($head)){
            $head_id=$head->id;
        }
    
        return $head_id;
     }
}
