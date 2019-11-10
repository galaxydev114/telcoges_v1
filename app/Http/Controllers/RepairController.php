<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\ClientPmodel;
use App\Repair;
use Session;

class RepairController extends Controller
{
    public function index(Request $request)
    {
    	return view('repairs.index', ['repairs' => auth()->user()->getOrganization()->repairs()->orderBy('id','desc')->get()]);
    }

    public function create()
    {
    	return view('repairs.create');
    }

    public function store(Request $request)
    {
    	$validator = Validator::make($request->all(), [
            'client' => ['required', 'exists:clients,id'],
            'model' => ['required', 'exists:pmodels,id'],
            'condition' => ['required', 'string', 'max:80'],
            'imei' => ['required', 'string', 'max:80'],
	        'date' => ['required'],
	        'repair' => ['required', 'string', 'max:80'],
	        'price' => ['required', 'numeric'],
	        'note' => ['required', 'string', 'max:80'],
	        'anotation' => ['required', 'string', 'max:80'],
	        'private_anotation' => ['required', 'string', 'max:80'],
        ]);

        if ( $validator->fails() ) {
            Session::flash('flash_message', __('- Error en los datos, por favor verifique e intente de nuevo.'));
            Session::flash('flash_type', 'alert-danger');
            return back()->withErrors($validator)->withInput();
        }


        $client_pmodel = ClientPmodel::updateOrCreate([
				        	'pmodel_id' => $request->model,
					        'client_id' => $request->client,
					        'imei' => $request->imei,
					        'organization_id' => auth()->user()->getOrganization()->id,
				        ]);

        $exists = Repair::where('client_id', $request->client)
        				->where('client_pmodel_id', $client_pmodel->id)
        				->whereIn('status', ['received', 'procesing'])
        				->get();

        if ( count($exists) ) {
        	Session::flash('flash_message', __('- El equipo ya tiene una reparación en proceso.'));
            Session::flash('flash_type', 'alert-danger');
            return back();
        }

        $repair = Repair::create([
        			'client_id' => $request->client,
			        'client_pmodel_id' => $client_pmodel->id,
			        'condition' => $request->condition,
			        'date' => $request->date,
			        'repair' => $request->repair,
			        'price' => $request->price,
			        'note' => $request->note,
			        'anotation' => $request->anotation,
			        'private_anotation' => $request->private_anotation,
			        'organization_id' => auth()->user()->getOrganization()->id,
        		]);

        if (!$repair) {
        	Session::flash('flash_message', __('- Error, intente más tarde.'));
            Session::flash('flash_type', 'alert-danger');
            return back();
        }

        Session::flash('flash_message', __('- Nueva entrada agregada.'));
        Session::flash('flash_type', 'alert-success');
        return redirect()->route('repairs.index');
    }

    public function update(Request $request)
    {
    	$validator = Validator::make($request->all(), [
            'client' => ['required', 'exists:clients,id'],
            'model' => ['required', 'exists:pmodels,id'],
            'condition' => ['required', 'string', 'max:80'],
            'imei' => ['required', 'string', 'max:80'],
	        'date' => ['required'],
	        'repair' => ['required', 'string', 'max:80'],
	        'price' => ['required', 'numeric'],
	        'note' => ['required', 'string', 'max:80'],
	        'anotation' => ['required', 'string', 'max:80'],
	        'private_anotation' => ['required', 'string', 'max:80'],
        ]);

        if ( $validator->fails() ) {
            Session::flash('flash_message', __('- Error en los datos, por favor verifique e intente de nuevo.'));
            Session::flash('flash_type', 'alert-danger');
            return back()->withErrors($validator)->withInput();
        }


        $client_pmodel = ClientPmodel::updateOrCreate([
				        	'pmodel_id' => $request->model,
					        'client_id' => $request->client,
					        'imei' => $request->imei,
					        'organization_id' => auth()->user()->getOrganization()->id,
				        ]);

        $repair = Repair::find($request->repairid);
        $repair->update([
        			'client_id' => $request->client,
			        'client_pmodel_id' => $client_pmodel->id,
			        'condition' => $request->condition,
			        'date' => $request->date,
			        'repair' => $request->repair,
			        'price' => $request->price,
			        'note' => $request->note,
			        'anotation' => $request->anotation,
			        'private_anotation' => $request->private_anotation,
			        'organization_id' => auth()->user()->getOrganization()->id,
        		]);

        if (!$repair) {
        	Session::flash('flash_message', __('- Error, intente más tarde.'));
            Session::flash('flash_type', 'alert-danger');
            return back();
        }

        Session::flash('flash_message', __('- Nueva entrada agregada.'));
        Session::flash('flash_type', 'alert-success');
        return redirect()->route('repairs.index');
    }

    public function show(Request $request)
    {
    	return view('repairs.show', ['repair' => Repair::find($request->repairid)]);
    }

    public function edit(Request $request)
    {
    	return view('repairs.edit', ['repair' => Repair::find($request->repairid)]);
    }

    public function delete(Request $request)
    {
    	$repair = Repair::find($request->repairid);

    	if (! ($repair->organization_id == auth()->user()->getOrganization()->id) ) {
    		Session::flash('flash_message', __('- Acción no autorizada.'));
            Session::flash('flash_type', 'alert-danger');
            return back();
    	}

    	$repair->delete();
    	return redirect()->route('repairs.index');
    }

    public function status(Request $request)
    {
        $repair = Repair::find($request->repairid);

        if (! ($repair->organization_id == auth()->user()->getOrganization()->id) ) {
            Session::flash('flash_message', __('- Acción no autorizada.'));
            Session::flash('flash_type', 'alert-danger');
            return back();
        }

        $repair->status = $request->status;
        $repair->save();
        return response()->json(['response' => 'success', 'status' => $request->status], 200);
    }
}