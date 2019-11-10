<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Organization;
use App\User;
use App\Role;
use Session;

class OrganizationController extends Controller
{
    public function index(Request $request)
    {
    return view('organizations.index', ['organizations' => Organization::orderBy('created_at', 'desc')->get()]);
    }

    public function create(Request $request)
    {
    return view('organizations.create');
    }

    public function show(Request $request)
    {
    return view('organizations.show', ['organization' => Organization::find($request->id)]);
    }

    public function store(Request $request)
    {
    $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:80', 'unique:organizations'],
            'commercial_name' => ['required', 'string', 'max:80', 'unique:organizations'],
            'email' => ['required', 'string', 'email', 'max:50', 'unique:organizations'],
    "nif" => ['required', 'string', 'max:15', 'unique:organizations'],
    "address" => ['required', 'string', 'max:100'],
    "city" => ['required', 'string', 'max:100'],
    "postal_code" => ['required', 'integer', 'digits_between:1,10'],
    "state" => ['required', 'string', 'max:100'],
    "country" => ['required', 'string', 'max:100', 'in:españa'],
    "phone" => ['nullable', 'string', 'max:100'],
            'adminmail' => ['required', 'string', 'max:80', 'unique:users,email'],
            'adminuser' => ['required', 'string', 'max:80'],
            'adminpass' => ['required', 'string', 'max:80'],
        ]);

        if ( $validator->fails() ) {
            Session::flash('flash_message', __('- Error en los datos, por favor verifique e intente de nuevo.'));
            Session::flash('flash_type', 'alert-danger');
            return back()->withErrors($validator)->withInput();
        }

        $organization = Organization::create($request->all());

        $file = $request->file('logo');
        if (!is_null($file)) {
            //obtenemos el nombre del archivo
            $nombre = time().'_'.$file->getClientOriginalName();
            \Storage::disk('local')->put('public/images/organization/'.$nombre,  \File::get($file));
            $organization->logo = $nombre;
            $organization->save();
        }


        $user = User::create([
            'name' => $request->adminuser,
            'email' => $request->adminmail,
            'password' => Hash::make($request->adminpass),
            'organization_id' => $organization->id
        ]);

        $user->roles()->attach(Role::where('name', 'admin')->first());

        if ( is_null( auth()->user() ) ) {
            if ( Auth::login($user) ) {
                return redirect()->route('home');
            }
        }

        Session::flash('flash_message', __('+ Empresa creada con éxito.'));
        Session::flash('flash_type', 'alert-success');
        return redirect()->route('organizations.index');
    }

    public function update(Request $request)
    {
    if ($request->clicked == 0) {
        Session::flash('flash_message', __('- No está autorizado para realizar esta acción'));
            Session::flash('flash_type', 'alert-danger');
            return back();
    }
    if ( !auth()->user()->hasAnyRole(['admin', 'superadmin']) ) {
        Session::flash('flash_message', __('- No está autorizado para realizar esta acción'));
            Session::flash('flash_type', 'alert-danger');
            return back();
    }

    $validator = Validator::make($request->except('clicked','_token','_method'), [
            'name' => ['required', 'string', 'max:80', 'unique:organizations,id'],
            'commercial_name' => ['required', 'string', 'max:80', 'unique:organizations,id'],
            'email' => ['required', 'string', 'email', 'max:50', 'unique:organizations,id'],
            "nif" => ['required', 'string', 'max:15', 'unique:organizations,id'],
            "address" => ['required', 'string', 'max:100'],
            "city" => ['required', 'string', 'max:100'],
            "postal_code" => ['required', 'integer', 'digits_between:1,10'],
            "state" => ['required', 'string', 'max:100'],
            "country" => ['required', 'string', 'max:100', 'in:España'],
            "phone" => ['nullable', 'string', 'max:100'],
            "legal_terms" => ['required', 'string'],
        ]);

        if ( $validator->fails() ) {
            Session::flash('flash_message', __('- Error en los datos, por favor verifique e intente de nuevo.'));
            Session::flash('flash_type', 'alert-danger');
            return back()->withErrors($validator)->withInput();
        }

        Organization::where('id', $request->id)->update($request->except('clicked','_token','_method', 'logo'));

        $file = $request->file('logo');
        if (!is_null($file)) {
            //obtenemos el nombre del archivo
            $nombre = time().'_'.$file->getClientOriginalName();
            \Storage::disk('local')->put('public/images/organization/'.$nombre,  \File::get($file));
            Organization::where('id', $request->id)->update(['logo' => $nombre]);
        }

        Session::flash('flash_message', __('+ Datos actualizados con éxito.'));
        Session::flash('flash_type', 'alert-success');
        return redirect()->route('organizations.show', ['id' => $request->id]);
    }

    public function status(Request $request)
    {
        if ( Organization::where('id', $request->id)->update(['status' => $request->status]) ) {
            return response()->json([
                'status' => 'success',
                'data' => 'Estatus cambiado con éxito'
            ],200);
        }

        return response()->json([
            'status' => 'error',
            'data' => 'Intente más tarde'
        ], 400);
    }

    public function delete(Request $request)
    {
        if ( Organization::where('id', $request->id)->update(['status' => 'inactive']) && Organization::where('id', $request->id)->delete() ) {
            Session::flash('flash_message', __('+ Empresa eliminada.'));
            Session::flash('flash_type', 'alert-success');
            return redirect()->route('organizations.index');
        }

        Session::flash('flash_message', __('- Error, intente más tarde.'));
        Session::flash('flash_type', 'alert-danger');
        return redirect()->route('organizations.index');
    }



    public function payments(Request $request)
    {
        return view('organizations.payments');
    }
}
