<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\InvoiceSerie;
use Session;

class InvoiceSeriesController extends Controller
{
    public function index(Request $request)
    {
    	return view('invoiceseries.index', ['series' => auth()->user()->getOrganization()->invoiceSeries()->orderBy('created_at', 'desc')->get() ]);
    }

    public function create(Request $request)
    {
    	return view('invoiceseries.create');
    }

    public function store(Request $request)
    {
    	$validator = Validator::make($request->all(), [
            'organization_id' => ['required', 'integer', 'exists:organizations,id'],
			'name' => ['required', 'string', 'min:3'],
			'serie' => ['required', 'string', 'unique:invoice_series,serie'],
			'start_from' => ['required', 'integer']
        ]);

        if ( $validator->fails() ) {
            Session::flash('flash_message', __('- Error en los datos, por favor verifique e intente de nuevo.'));
            Session::flash('flash_type', 'alert-danger');
            return back()->withErrors($validator)->withInput();
        }

        $args = [
        	'name' => $request->name,
        	'serie' => $request->serie,
        	'start_from' => $request->start_from,
        	'current_number' => $request->start_from,
        	'organization_id' => $request->organization_id,
        ];

        if ( InvoiceSerie::create($args) ) {
        	Session::flash('flash_message', __('+ Serie guardada'));
        	Session::flash('flash_type', 'alert-success');
        	return redirect()->route('invoice.series.index');
        }

        Session::flash('flash_message', __('- Error al guardar, por favor verifique e intente de nuevo.'));
        Session::flash('flash_type', 'alert-danger');
        return redirect()->route('invoice.series.index');
    }

    public function delete(Request $request)
    {
    	if ( InvoiceSerie::where('id', $request->id)->first()->organization_id != auth()->user()->getOrganization()->id || !auth()->user()->hasAnyRole(['admin', 'superadmin']) ) {
            Session::flash('flash_message', __('- No está autorizado para realizar esta acción'));
            Session::flash('flash_type', 'alert-danger');
            return back();
        }

        if ( InvoiceSerie::where('id', $request->id)->delete() ) {
        	Session::flash('flash_message', __('+ Serie eliminada con éxito'));
        	Session::flash('flash_type', 'alert-success');
        	return redirect()->route('invoice.series.index');
        }

        Session::flash('flash_message', __('- Error, por favor intente más tarde.'));
        Session::flash('flash_type', 'alert-danger');
        return redirect()->route('invoice.series.index');
        return redirect()->route('invoice.series.index');
    }

    public function activate(Request $request)
    {
    	$org = auth()->user()->getOrganization();

        $org->invoiceSeries()->where('id', $request->id)->update(['status' => $request->status]);

    	Session::flash('flash_message', __('+ Cambio guardado'));
        Session::flash('flash_type', 'alert-success');

    	return response()->json([
    		'status' => 'success',
    	], 200);
    }

    public function getData(Request $request)
    {
        $is = InvoiceSerie::where('id', $request->serie_id)->first();
        if ($is) {
            return response()->json(['data' => $is], 200);
        }
        
        return response()->json(['data' => ''], 404);
    }
}
