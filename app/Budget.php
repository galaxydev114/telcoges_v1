<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
	protected $table = 'budgets';

    protected $fillable = [
    	'client_id',
    	'custom_id',
        'date',
        'comment',
        'iva_rate',
        'total',
        'iva',
        'grand_total',
        'organization_id'
    ];

    public function organization()
    {
    	return $this->belongsTo('App\Organization', 'id', 'organization_id');
    }

    public function items()
    {
    	return $this->hasMany('App\BudgetItem', 'budget_id', 'id');
    }

    public function client()
    {
    	return $this->belongsTo('App\Client', 'client_id', 'id');
    }
  
    public function clientwithTrashed()
    {
        return $this->belongsTo('App\Client', 'client_id', 'id')->withTrashed();
    }
}
