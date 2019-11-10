<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use SoftDeletes;

    protected $fillable = [
    	'name',
        'email',
		"nif",
		"type",
		"address",
		"population",
		"postal_code",
		"province",
		"country",
		"commercial_name",
		"phone",
		"celphone",
		"website",
        'organization_id',
    ];

    public function invoices()
    {
    	return $this->hasMany('App\Invoice', 'client_id', 'id')->withTrashed();
    }

    public function organization()
    {
    	return $this->belongsTo('App\Organization', 'id', 'organization_id')->withTrashed();
    }

    public function budgets()
    {
        return $this->hasMany('App\Budget', 'client_id', 'id')->withTrashed();
    }

    public function pmodels()
    {
        return $this->belongsToMany('App\Pmodel');
    }
}
