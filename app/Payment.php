<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
	    'suscription_id',
	    'transaction_id',
		'sub_total',
		'tax',
		'tax_rate',
		'date_from',
		'date_to',
    ];

    public function suscription()
    {
    	return $this->belongsTo('App\Suscription', 'suscription_id', 'id');
    }
}
