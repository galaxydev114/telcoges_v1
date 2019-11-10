<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Service;
use Session;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
    	return view('services.index', ['services' => auth()->user()->getOrganization()->services()->orderBy('created_at', 'desc')->get()]);
    }

    public function create(Request $request)
    {
    	return view('services.create');
    }

    public function store(Request $request)
    {
    	$validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:80'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric'],
        ]);

        if ( $validator->fails() ) {
            Session::flash('flash_message', __('- Error en los datos, por favor verifique e intente de nuevo.'));
            Session::flash('flash_type', 'alert-danger');
            return back()->withErrors($validator)->withInput();
        }

        if ( auth()->user()->getOrganization()->services()->where('name', $request->name)->count() ) {
        	Session::flash('flash_message', __('- El servicio ya existe.'));
            Session::flash('flash_type', 'alert-danger');
            return back()->withErrors($validator)->withInput();
        }

        if ( ! is_null($request->code) ) {
            $code = $request->code;
        } else {
            $code = null;
        }

        $service = Service::create([
        	'name' => $request->name,
	        'description' => $request->description,
	        'price' => $request->price,
            'code' => $code,
	        'organization_id' => auth()->user()->getOrganization()->id
        ]);

        if ($service) {
        	Session::flash('flash_message', __('- Servicio agregado.'));
            Session::flash('flash_type', 'alert-success');
    		return redirect()->route('services.index');
        }

        Session::flash('flash_message', __('- Error, por favor intente más tarde.'));
        Session::flash('flash_type', 'alert-danger');
        return back()->withErrors($validator)->withInput();
    }

    public function delete(Request $request)
    {
    	$service = Service::find($request->id);
        if ($service->organization_id != auth()->user()->getOrganization()->id || !auth()->user()->hasAnyRole(['admin','superadmin'])) {
            Session::flash('flash_message', __('- No está autorizado para realizar esta acción'));
            Session::flash('flash_type', 'alert-danger');
            return back();
        }

        if ( $service->delete() ) {
        	Session::flash('flash_message', __('- Servicio eliminado.'));
            Session::flash('flash_type', 'alert-success');
            return redirect()->route('services.index');
        }

        Session::flash('flash_message', __('- Por favor intente más tarde'));
        Session::flash('flash_type', 'alert-danger');
        return back();
    }

    public function edit(Request $request)
    {
    	$service = Service::find($request->id);
        if ($service->organization_id != auth()->user()->getOrganization()->id || !auth()->user()->hasAnyRole(['admin','superadmin'])) {
            Session::flash('flash_message', __('- No está autorizado para realizar esta acción'));
            Session::flash('flash_type', 'alert-danger');
            return back();
        }

        return view('services.edit', ['service' => $service]);
    }

    public function update(Request $request)
    {
    	$validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:80'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric'],
        ]);

        if ( $validator->fails() ) {
            Session::flash('flash_message', __('- Error en los datos, por favor verifique e intente de nuevo.'));
            Session::flash('flash_type', 'alert-danger');
            return back()->withErrors($validator)->withInput();
        }


        $exists = auth()->user()->getOrganization()->services()->where('name', $request->name)->first();
        if ( !is_null($exists) && ($request->name == $exists->name) && ($request->description == $exists->description) && ($request->price == $exists->price) ) {
            return redirect()->route('services.index');
        } else {
	        if ( !is_null($exists) && ($exists->id != $request->id) ) {
	        	Session::flash('flash_message', __('- El servicio ya existe.'));
	            Session::flash('flash_type', 'alert-danger');
	            return back()->withErrors($validator)->withInput();
	        }
        }

        if ( ! is_null($request->code) ) {
            $code = $request->code;
        } else {
            $code = null;
        }

        $service = Service::where('id', $request->id)->update([
        	'name' => $request->name,
	        'description' => $request->description,
	        'price' => $request->price,
            'code' => $code
        ]);

        if ($service) {
        	Session::flash('flash_message', __('- Servicio actualizado.'));
            Session::flash('flash_type', 'alert-success');
    		return redirect()->route('services.index');
        }

        Session::flash('flash_message', __('- Error, por favor intente más tarde.'));
        Session::flash('flash_type', 'alert-danger');
        return back()->withErrors($validator)->withInput();
    }
}
