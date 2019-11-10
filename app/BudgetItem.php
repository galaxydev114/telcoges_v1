<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BudgetItem extends Model
{
	protected $fillable = [
		'budget_id',
        'name',
        'description',
        'quantity',
        'price',
        'tax_rate',
        'total',
        'tax',
        'grand_total'
	];

    public function budget()
    {
    	return $this->belongTo('App\Budget', 'id', 'budget_id');
    }
}