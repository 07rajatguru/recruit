<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientAddress extends Model
{
    public $table = "client_address";

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_id', 'billing_country', 'billing_state','billing_street1',
        'billing_street2','billing_code','shipping_country','shipping_state',
        'shipping_street1','shipping_street2','shipping_code' ,'billing_city',
        'shipping_city'
    ];
}
