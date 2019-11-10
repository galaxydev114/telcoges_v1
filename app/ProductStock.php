<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductStock extends Model
{
    protected $table = 'products_stock';

    protected $fillable = [
		'product_id',
		'serie',
		'provider',
		'status',
    ];

    public function product()
    {
    	return $this->belongsTo('App\Product', 'product_id', 'id');
    }
}
