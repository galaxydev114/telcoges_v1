<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pbrand extends Model
{
	use SoftDeletes;
	
	protected $fillable = [
		'name',
		'organization_id'
	];

    public function organization()
    {
        return $this->belongsTo('App\Organization', 'organization_id', 'id');
    }
}
