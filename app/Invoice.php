<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
	use SoftDeletes;

	protected $fillable = [
		'id',
		'doc_number',
        'client_id',
        'custom_invoice_id',
        'date',
        'due_date',
        'type',
        'comment',
        'pay_way',
        'description',
        'status',
        'iva_rate',
        'total',
        'iva',
        'grand_total',
        'client_id',
        'organization_id',
	];

    public function client()
    {
    	return $this->hasOne('App\Client', 'id', 'client_id')->withTrashed();
    }

    public function items()
    {
    	return $this->hasMany('App\Item', 'invoice_id', 'id');
    }

    public function organization()
    {
        return $this->belongsTo('App\Organization', 'organization_id', 'id');
    }
}
