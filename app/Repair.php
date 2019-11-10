<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Repair extends Model
{
    protected $fillable = [
    	'client_id',
        'client_pmodel_id',
        'condition',
        'date',
        'repair',
        'price',
        'note',
        'anotation',
        'status',
        'private_anotation',
        'organization_id',
    ];

    public function organization()
    {
    	return $this->belongsTo('App\Organization', 'organization_id', 'id');
    }

    public function client()
    {
    	return $this->hasOne('App\Client', 'id', 'client_id');
    }

    public function device()
    {
    	return $this->hasOne('App\ClientPmodel', 'id', 'client_pmodel_id');
    }
}
