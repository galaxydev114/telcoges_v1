<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
	use SoftDeletes;
	
    protected $fillable = [
    	'name',
    	'frequency',
    	'price',
    	'iva',
    	'tax_rate',
        'stripe_id',
    ];

    public function organization()
    {
    	return $this->belongsToMany('App\Organization', 'organization_membership', 'membership_id', 'organization_id');
    }

    public function suscription()
    {
        
    }
}
