<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Utils;

class CustomerSupportDoc extends Model
{
    public $table = "customer_support_doc";

    public static function getCustomerSupportDocsById($id)
    {
    	$query = CustomerSupportDoc::query();
    	$query = $query->orderBy('customer_support_doc.id','DESC');
    	$query = $query->select('customer_support_doc.*');
    	$response = $query->get();

    	$i=0;
    	$docdetails['files'] = array();
    	$utils = new Utils();

    	foreach ($response as $key => $value)
    	{
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
