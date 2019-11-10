<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Client;
use Session;

class ClientController extends Controller
{
    public function index(Request $request)
    {
    	return view('clients.index', ['clients' => auth()->user()->getOrganization()->clientsNotDeleted()->orderBy('created_at', 'desc')->get()]);
    }

    public function show(Request $request)
    {
        $client = Client::find($request->id);

        if ($client->organization_id != auth()->user()->getOrganization()->id || !auth()->user()->hasAnyRole(['admin','superadmin'])) {
            Session::flash('flash_message', __('- No está autorizado para realizar esta acción'));
            Session::flash('flash_type', 'alert-danger');
            return back();
        }

    	return view('clients.edit', ['client' => $client]);
    }

    public function create(Request $request)
    {
    	return view('clients.create');
    }

    public function store(Request $request)
    {
        if ($request->organization_id != auth()->user()->getOrganization()->id || !auth()->user()->hasAnyRole(['admin', 'superadmin'])) {
            Session::flash('flash_message', __('- No está autorizado para realizar esta acción'));
            Session::flash('flash_type', 'alert-danger');
            return back();
        }

    	$validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:80'],
            'email' => ['nullable','string', 'email', 'max:50'],
			"nif" => ['required', 'string', 'max:15'],
			"type" => ['required', 'string','in:person,business'],
			"address" => ['required', 'string', 'max:100'],
			"population" => ['required', 'string', 'max:100'],
            "postal_code" => ['required','regex:/\b\d{5}\b/'],
			"province" => ['required', 'string', 'max:100'],
			"country" => ['required', 'string', 'max:100', 'in:España'],
			"commercial_name" => ['nullable', 'string', 'max:100'],
			"phone" => ['nullable', 'string', 'max:100'],
			"celphone" => ['nullable', 'string', 'max:100'],
			"website" => ['nullable', 'string', 'max:100'],
        ]);

        if ( $validator->fails() ) {
            Session::flash('flash_message', __('- Error en los datos, por favor verifique e intente de nuevo.'));
            Session::flash('flash_type', 'alert-danger');
            return back()->withErrors($validator)->withInput();
        }

        Client::create($request->all());

        Session::flash('flash_message', __('+ Datos actualizados'));
        Session::flash('flash_type', 'alert-success');
        return redirect()->route('clients.index');
    }

    public function update(Request $request)
    {
    	$validator = Validator::make($request->except(['email', 'country']), [
            'name' => ['required', 'string', 'max:80'],
            'email' => ['nullable','string', 'email', 'max:50'],
			"nif" => ['required', 'string', 'max:15'],
			"type" => ['required', 'string','in:person,business'],
			"address" => ['required', 'string', 'max:100'],
			"population" => ['required', 'string', 'max:100'],
			"postal_code" => ['required', 'integer', 'digits_between:1,10'],
			"province" => ['required', 'string', 'max:100'],
			"commercial_name" => ['nullable', 'string', 'max:100'],
			"phone" => ['nullable', 'string', 'max:100'],
			"celphone" => ['nullable', 'string', 'max:100'],
			"website" => ['nullable', 'string', 'max:100'],
        ]);

        if ( $validator->fails() ) {
            Session::flash('flash_message', __('- Error en los datos, por favor verifique e intente de nuevo.'));
            Session::flash('flash_type', 'alert-danger');
            return back()->withErrors($validator)->withInput();
        }

        $client = Client::where('email', $request->email)->first();
        if ($client->organization_id != auth()->user()->getOrganization()->id || !auth()->user()->hasAnyRole(['admin','superadmin'])) {
            Session::flash('flash_message', __('- No está autorizado para realizar esta acción'));
            Session::flash('flash_type', 'alert-danger');
            return back();
        }
        
        $client->update($request->except(['email', 'country', '_token', '_method']));

        Session::flash('flash_message', __('+ Datos actualizados'));
        Session::flash('flash_type', 'alert-success');
        return redirect()->route('clients.index');
    }
/*
    public function search(Request $request)
    {
        $data = Client::select("name")
        ->where("name","LIKE","%{$request->input('query')}%")
        ->get();

        return response()->json($data);
    }
    */
    public function search(Request $request)
    {
        if($request->get('query'))
        {
            $query = $request->get('query');
            $data = Client::select("name", "nif")
                    ->where("name","LIKE","%{$request->input('query')}%")
                    ->where('organization_id', auth()->user()->getOrganization()->id)
                    ->where('deleted_at', NULL)
                    ->get();
            $output = '<ul class="autocomplete-box">';
            foreach($data as $key => $row) {
                $output .= '<li class="list-client"><a href="#">'.$row->name.'-'.$row->nif.'</a></li>';
            }
            $output .= '</ul>';
            
            echo $output;
        }
    }

    public function searchProvider(Request $request)
    {
        if($request->get('query'))
        {
            $query = $request->get('query');
            $data = Client::select("name", "nif")
                    ->where("name","LIKE","%{$request->input('query')}%")
                    ->where('organization_id', auth()->user()->getOrganization()->id)
                    ->where('deleted_at', NULL)
                    ->get();
            $output = '<ul class="autocomplete-box">';
            foreach($data as $key => $row) {
                $output .= '<li class="provider-client"><a href="#">'.$row->name.'-'.$row->nif.'</a></li>';
            }
            $output .= '</ul>';
            
            echo $output;
        }
    }

    public function delete(Request $request)
    {
        Client::where('id', $request->id)->delete();

        return redirect()->route('clients.index');
    }

    public function apiClientData(Request $request)
    {
        return response()->json(auth()->user()->getOrganization()->clients, 200);
    }
}
