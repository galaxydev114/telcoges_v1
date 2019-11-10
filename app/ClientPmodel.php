<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClientPmodel extends Model
{
	protected $table = 'client_pmodel';

	protected $fillable = [
		'pmodel_id',
        'client_id',
        'imei',
        'organization_id',
	];

    public function pmodel()
    {
    	return $this->hasOne('App\Pmodel', 'id', 'pmodel_id');
    }

    public function client()
    {
    	return $this->belongsTo('App\Client', 'client_id', 'id');
    }

    public function organization()
    {
    	return $this->belongsTo('App\Organization', 'organization_id', 'id');
    }
}
