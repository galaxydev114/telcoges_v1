<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Organization extends Model
{
    use SoftDeletes;

	protected $table = 'organizations';

    protected $casts = [
        'paymethods' => 'array'
    ];

    protected $fillable = [
            'name',
            'commercial_name',
            'email',
			'nif',
            'paymethods',
			'address',
			'city',
			'postal_code',
			'state',
			'country',
			'phone',
			'logo',
            'legal_terms',
    	];

    public function users()
    {
    	return $this->hasMany('App\User', 'organization_id', 'id');
    }

    public function invoices()
    {
    	return $this->hasMany('App\Invoice', 'organization_id', 'id')->withTrashed();
    }

    public function invoicesNotDeleted()
    {
        return $this->hasMany('App\Invoice', 'organization_id', 'id');
    }

    public function clients()
    {
    	return $this->hasMany('App\Client', 'organization_id', 'id')->withTrashed();
    }

    public function clientsNotDeleted()
    {
        return $this->hasMany('App\Client', 'organization_id', 'id');
    }

    public function hasFullData()
    {
        if ($this->name == '' || $this->commercial_name == '' || $this->nif == '' || $this->address == '' || $this->city == '' || $this->postal_code == '' || $this->state == '' || $this->country == '' || $this->phone == '' || $this->legal_terms == '') {
            return false;
        }
        return true;
    }

    public function invoiceSeries()
    {
        return $this->hasMany('App\InvoiceSerie', 'organization_id', 'id');
    }

    public function getInvoiceSeries()
    {
        return $this->invoiceSeries()->get();
    }

    public function currentInvoiceSeries()
    {
        return $this->invoiceSeries()->where('status', 1)->get();
    }

    public function budgets()
    {
        return $this->hasMany('App\Budget', 'organization_id', 'id');
    }

    public function budgetsWithDeleted()
    {
        return $this->hasMany('App\Budget', 'id', 'organization_id')->withTrashed();
    }

    public function currentBudget()
    {
        return $this->budgets()->orderBy('id', 'desc')->first() ? $this->budgets()->orderBy('id', 'desc')->first() : null;
    }

    public function products()
    {
        return $this->hasMany('App\Product', 'organization_id', 'id');
    }

    public function productsWithDeleted()
    {
        return $this->hasMany('App\Product', 'organization_id', 'id')->withTrashed();
    }

    public function services()
    {
        return $this->hasMany('App\Service', 'organization_id', 'id');
    }

    public function servicesWithDeleted()
    {
        return $this->hasMany('App\Service', 'organization_id', 'id')->withTrashed();
    }

    public function suscription()
    {
        return $this->hasMany('App\Suscription', 'organization_id', 'id');
    }

    public function haveSuscriptionActive()
    {
        return count($this->suscription->where('status', 1)) ? true : false;
    }

    public function activeSuscription()
    {
        if ( $this->haveSuscriptionActive() ) {
            return $this->suscription->where('status', 1)->first();
        }

        return '';
    }

    public function currentPayment()
    {
        if ( $this->haveSuscriptionActive() ) {
            $suscription = $this->suscription->where('status', 1)->first();
            return $suscription->payments->sortByDesc('id')->first();
        }

        return 0;
    }

    public function currentPaymentIsUpToDate()
    {
        if ( $this->currentPayment() ) {
            $dateTo = Carbon::create($this->currentPayment()->date_to)->startOfDay();
            return $dateTo->gt( Carbon::now()->startOfDay() ) && $this->currentPayment()->status == 'payed';
        }

        return 0;
    }

    public function nextPaymentDate()
    {
        if ( $this->currentPayment() ) {
            if ( $this->currentPaymentIsUpToDate() ) {
              return  __('Fecha de proximo pago: '. $this->currentPayment()->date_to);
            } else {
                switch ($this->currentPayment()->status) {
                    case 'pending':
                        $status = 'pendiente';
                        break;
                    default:
                        $status = 'pagado';
                        break;
                }
              return  __('Fecha proximo pago: '. $this->currentPayment()->date_to. '/Estatus pago actual: '.$status);
            }
        }

        return 0;
    }

    public function brands()
    {
        return $this->hasMany('App\Pbrand');
    }

    public function models()
    {
        return $this->hasMany('App\Pmodel');
    }

    public function repairs()
    {
        return $this->hasMany('App\Repair');
    }
}