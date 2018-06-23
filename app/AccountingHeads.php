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
}
