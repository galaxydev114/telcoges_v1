<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Pbrand;
use Session;

class PbrandController extends Controller
{
    public function index(Request $request)
    {
    	return view('repairs.index_brands', ['brands' => auth()->user()->getOrganization()->brands()->orderBy('id','desc')->get()] );
    }

    public function create(Request $request)
    {
    	return view('repairs.create_brand');
    }

    public function store(Request $request)
    {
    	$validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:80'],
        ]);

        if ( $validator->fails() ) {
            Session::flash('flash_message', __('- Error en los datos, por favor verifique e intente de nuevo.'));
            Session::flash('flash_type', 'alert-danger');
            return back()->withErrors($validator)->withInput();
        }

        $name = ucwords( strtolower($request->name) );

        if ( !Pbrand::create(['name' => $name, 'organization_id' => auth()->user()->getOrganization()->id]) ) {
	        Session::flash('flash_message', __('- Error en los datos, por favor verifique e intente de nuevo 2.'));
	        Session::flash('flash_type', 'alert-danger');
	        return back()->withErrors($validator)->withInput();
        }

        Session::flash('flash_message', __('- Marca creada con éxito'));
        Session::flash('flash_type', 'alert-success');
    	return redirect()->route('repairs.brands.index');
    }

    public function edit(Request $request)
    {
    	return view('repairs.edit_brand', [ 'brand' => Pbrand::find($request->brandid) ]);
    }

    public function update(Request $request)
    {
    	$validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:80'],
        ]);

        if ( $validator->fails() ) {
            Session::flash('flash_message', __('- Error en los datos, por favor verifique e intente de nuevo.'));
            Session::flash('flash_type', 'alert-danger');
            return back()->withErrors($validator)->withInput();
        }
        $name = ucwords( strtolower($request->name) );

        $exists = auth()->user()->getOrganization()->brands()->where('name', $name)->get();
        
        if ( count($exists) ) {
			Session::flash('flash_message', __('- El nombre ya existe'));
	        Session::flash('flash_type', 'alert-danger');
	        return back()->withErrors($validator)->withInput();
        }

        $pbrand = Pbrand::find($request->brandid);

        if ( !$pbrand->update(['name' => $name]) ) {
	        Session::flash('flash_message', __('- Error en los datos, por favor verifique e intente de nuevo 3.'));
	        Session::flash('flash_type', 'alert-danger');
	        return back()->withErrors($validator)->withInput();
        }

        Session::flash('flash_message', __('- Marca actualizada con éxito'));
        Session::flash('flash_type', 'alert-success');
    	return redirect()->route('repairs.brands.index');
    }

    public function delete(Request $request)
    {
        $pbrand = Pbrand::find($request->brandid);

        if ( $pbrand->organization_id != auth()->user()->getOrganization()->id ) {
        	Session::flash('flash_message', __('- Acción no autorizada.'));
	        Session::flash('flash_type', 'alert-danger');
	        return back();
        }

        $pbrand->delete();

        Session::flash('flash_message', __('- Marca eliminada'));
        Session::flash('flash_type', 'alert-success');
        return redirect()->route('repairs.brands.index');
    }
}