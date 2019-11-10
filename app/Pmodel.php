<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pmodel extends Model
{
	use SoftDeletes;
	
	protected $fillable = [
		'name',
		'pbrands_id',
		'organization_id'
	];
    public function organization()
    {
        return $this->belongsTo('App\Organization', 'organization_id', 'id');
    }

    public function clients()
    {
    	return $this->belongsToMany('App\Client');
    }

    public function brand()
    {
    	return $this->belongsTo('App\Pbrand', 'pbrands_id', 'id');
    }
}