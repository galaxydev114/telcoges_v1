<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
	use SoftDeletes;

	protected $fillable = [
		'name',
        'description',
        'price',
        'organization_id',
        'code'
	];

	public function organization()
	{
		return $this->belongsTo('App\Organization', 'organization_id', 'id');
	}
}
