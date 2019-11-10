<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Suscription extends Model
{
    protected $fillable = [
    	'organization_id',
        'membership_id',
        'price',
        'iva',
    ];

    public function organization()
    {
    	return $this->belongsTo('App\Organization');
    }

    public function membership()
    {
    	return $this->hasOne('App\Membership', 'id', 'membership_id');
    }

    public function payments()
    {
    	return $this->hasMany('App\Payment', 'suscription_id', 'id');
    }
}