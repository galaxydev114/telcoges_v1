<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InvoiceSerie extends Model
{
    protected $table = 'invoice_series';

    protected $fillable = [
		'organization_id',
		'name',
		'serie',
		'start_from',
		'current_number'
    ];

    public function organization()
    {
    	return $this->belongsTo('App\Organization', 'organization_id', 'id');
    }
}
