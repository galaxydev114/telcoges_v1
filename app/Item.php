<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
	use SoftDeletes;

	protected $fillable = [
		'invoice_id',
        'name',
        'description',
        'quantity',
        'price',
        'tax_rate',
        'total',
        'tax',
        'grand_total',
	];

	public function invoice()
	{
		return $this->belongsTo('App\Invoice', 'id', 'invoice_id')->withTrashed();
	}
}
