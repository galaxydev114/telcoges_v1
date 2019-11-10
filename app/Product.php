<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
	use SoftDeletes;

	protected $fillable = [
        'code',
		'name',
        'description',
        'price',
        'cost',
        'cost_tax',
        'cost_tax_rate',
        'quantity',
        'organization_id',
	];

	public function organization()
	{
		return $this->belongsTo('App\Organization', 'organization_id', 'id');
	}

    public function productStock()
    {
        return $this->hasMany('App\ProductStock', 'product_id', 'id');
    }
}
