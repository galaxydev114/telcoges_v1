<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Pmodel;
use Session;

class PmodelController extends Controller
{
    public function index(Request $request)
    {
    	return view('repairs.index_models', ['models' => auth()->user()->getOrganization()->models()->orderBy('id','desc')->get()] );
    }

    public function create(Request $request)
    {
    	return view('repairs.create_model');
    }

    public function store(Request $request)
    {
    	$validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:80'],
            'brand' => ['required'],
        ]);

        if ( $validator->fails() ) {
            Session::flash('flash_message', __('- Error en los datos, por favor verifique e intente de nuevo.'));
            Session::flash('flash_type', 'alert-danger');
            return back()->withErrors($validator)->withInput();
        }

        $name = ucwords( strtolower($request->name) );

        $exists = auth()->user()->getOrganization()->models
        											->where('name', $request->name)
        											->where('pbrands_id', $request->brand);


        if ( count($exists) ) {
	        Session::flash('flash_message', __('- Modelo ya existe'));
	        Session::flash('flash_type', 'alert-danger');
	        return back()->withErrors($validator)->withInput();
        }

        $args = array(
        			'name' => $name,
        			'pbrands_id' => $request->brand,
        			'organization_id' => auth()->user()->getOrganization()->id
        		);

        if ( !Pmodel::create($args) ) {
        	Session::flash('flash_message', __('- Error, intente más tarde'));
	        Session::flash('flash_type', 'alert-danger');
	    	return back();
        }

        Session::flash('flash_message', __('- Modelo creado con éxito'));
        Session::flash('flash_type', 'alert-success');
    	return redirect()->route('repairs.models.index');
    }

    public function edit(Request $request)
    {
    	return view('repairs.edit_model', [ 'model' => Pmodel::find($request->modelid) ]);
    }

    public function update(Request $request)
    {
    	$validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:80'],
            'brand' => ['required'],
        ]);

        if ( $validator->fails() ) {
            Session::flash('flash_message', __('- Error en los datos, por favor verifique e intente de nuevo.'));
            Session::flash('flash_type', 'alert-danger');
            return back()->withErrors($validator)->withInput();
        }
        $name = ucwords( strtolower($request->name) );

        $exists = auth()->user()->getOrganization()->models
        											->where('name', $request->name)
        											->where('pbrands_id', $request->brand);
        
        if ( count($exists) && ($exists->first()->id != $request->modelid) ) {
			Session::flash('flash_message', __('- El nombre ya existe'));
	        Session::flash('flash_type', 'alert-danger');
	        return back()->withErrors($validator)->withInput();
        }

        $pmodel = Pmodel::find($request->modelid);

        if ( !$pmodel->update(['name' => $name, 'pbrands_id' => $request->brand]) ) {
	        Session::flash('flash_message', __('- Error, intente más tarde.'));
	        Session::flash('flash_type', 'alert-danger');
	        return back()->withErrors($validator)->withInput();
        }

        Session::flash('flash_message', __('- Modelo actualizado con éxito'));
        Session::flash('flash_type', 'alert-success');
    	return redirect()->route('repairs.models.index');
    }

    public function delete(Request $request)
    {
        $pmodel = Pmodel::find($request->modelid);

        if ( $pmodel->organization_id != auth()->user()->getOrganization()->id ) {
        	Session::flash('flash_message', __('- Acción no autorizada.'));
	        Session::flash('flash_type', 'alert-danger');
	        return back();
        }

        $pmodel->delete();

        Session::flash('flash_message', __('- Modelo eliminado'));
        Session::flash('flash_type', 'alert-success');
        return redirect()->route('repairs.models.index');
    }

    public function byBrand(Request $request)
    {
        $models = Pmodel::where('pbrands_id', $request->brandid)->get();
        $response = '';
        if (count($models)) {
            foreach ($models as $key => $value) {
                $response .= '<option value="'.$value->id.'">'.$value->name.'</option>';
            }
        } else {
            $response .= '<option value="">No hay modelos disponibles</option>';
        }

        return response()->json(['data' => $response], 200);
    }
}
