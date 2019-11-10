<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\PasswordRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Mail\RegistrationVerifyLink;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Organization;
use App\Suscription;
use App\Membership;
use Carbon\Carbon;
use App\Payment;
use App\User;
use App\Role;
use Session;
use Mail;

class UserController extends Controller
{
    /**
     * Display a listing of the users
     *
     * @param  \App\User  $model
     * @return \Illuminate\View\View
     */
    public function index(User $model)
    {
        return view('users.index', ['users' => auth()->user()->getOrganization()->users()->orderBy('created_at','desc')->get()]);
    }

    public function show(Request $request)
    {

        $user = User::find($request->id);

        if ($user->organization_id != auth()->user()->getOrganization()->id || !auth()->user()->hasAnyRole(['admin','superadmin'])) {
            Session::flash('flash_message', __('- No está autorizado para realizar esta acción'));
            Session::flash('flash_type', 'alert-danger');
            return back();
        }

        return view('users.show', ['user' => $user]);
    }

    public function update(Request $request)
    {
        $user = User::find($request->user);
        if ($user->organization_id != auth()->user()->getOrganization()->id || !auth()->user()->hasAnyRole(['admin','superadmin']) || $user->id != auth()->user()->id) {
            Session::flash('flash_message', __('- No está autorizado para realizar esta acción'));
            Session::flash('flash_type', 'alert-danger');
            return back();
        }

        $user->update($request->only('name'));

        Session::flash('flash_message', __('+ Actualización exitosa.'));
        Session::flash('flash_type', 'alert-success');
        return back()->withStatus(__('Actualización exitosa.'));
    }

    public function updatePass(PasswordRequest $request)
    {
        $user = User::find($request->id);

        if ($user->organization_id != auth()->user()->getOrganization()->id || !auth()->user()->hasAnyRole(['admin','superadmin']) || $user->id != auth()->user()->id) {
            Session::flash('flash_message', __('- No está autorizado para realizar esta acción'));
            Session::flash('flash_type', 'alert-danger');
            return back();
        }

        $user->update(['password' => Hash::make($request->get('password'))]);

        Session::flash('flash_message', __('+ Password cambiada con éxito.'));
        Session::flash('flash_type', 'alert-success');
        return back()->withStatusPassword(__('Password cambiada con éxito.'));
    }

    public function create(Request $request)
    {
        return view('users.create');
    }

    protected function store(Request $request)
    {
        if ($request->organization_id != auth()->user()->getOrganization()->id || !auth()->user()->hasAnyRole(['admin','superadmin'])) {
            Session::flash('flash_message', __('- No está autorizado para realizar esta acción'));
            Session::flash('flash_type', 'alert-danger');
            return back();
        }

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', 'string', 'in:admin,user'],
        ]);

        if ( $validator->fails() ) {
            Session::flash('flash_message', __('- Error en los datos, por favor verifique e intente de nuevo.'));
            Session::flash('flash_type', 'alert-danger');
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'organization_id' => $request->organization_id
        ]);


        if (is_null($user)) {
            Session::flash('flash_message', __('- Error en los datos, por favor verifique e intente de nuevo.'));
            Session::flash('flash_type', 'alert-danger');
            return back()->withErrors(['create' => 'No se pudo guardar, intenta más tarde']);
        }

        $user->roles()->attach(Role::where('name', $request->role)->first());

        Session::flash('flash_message', __('+ Usuario creado con éxito.'));
        Session::flash('flash_type', 'alert-success');
        return redirect()->route('user.index');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'membership' => ['required', 'exists:memberships,id'],
        ]);

        if ( $validator->fails() ) {
            Session::flash('flash_message', __('- Error en los datos, por favor verifique e intente de nuevo.'));
            Session::flash('flash_type', 'alert-danger');
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);


        if (is_null($user)) {
            Session::flash('flash_message', __('- Error en los datos, por favor verifique e intente de nuevo.'));
            Session::flash('flash_type', 'alert-danger');
            return back()->withErrors(['create' => 'No se pudo guardar, intenta más tarde']);
        }

        $user->roles()->attach(Role::where('name', 'admin')->first());
        
        $organization = Organization::create([
            'name' => '',
            'commercial_name' => '',
            'email' => $request->email,
            'nif' => '',
            'address' => '',
            'city' => '',
            'postal_code' => '',
            'state' => '',
            'country' => '',
            'phone' => '',
        ]);

        $user->organization_id = $organization->id;
        $user->verify_token = Str::random(20);
        $user->save();

        $membership = Membership::find($request->membership);

        $suscription = Suscription::create([
            'organization_id' => $organization->id,
            'membership_id' => $request->membership,
            'price' => $membership->price,
            'iva' => $membership->iva
        ]);

        $user->createAsStripeCustomer();

        Mail::to( $user->email )->send( new RegistrationVerifyLink( $user ) );

        Session::flash('flash_message', __('- Verifica tu email para completar el registro.'));
        Session::flash('flash_type', 'alert-success');
        return redirect()->route('login');
    }

    public function verify(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (! is_null($user) ) {

            if (! is_null($user->email_verified_at) ) {
                Session::flash('flash_message', __('+ Ya has verificado tu cuenta, por favor inicia sesión.'));
                Session::flash('flash_type', 'alert-success');
                return redirect()->route('login');
            }

            if ( $user->verify_token == $request->token ) {
                $user->email_verified_at = Carbon::now();
                $user->save();

                Session::flash('flash_message', __('+ Ya has verificado tu cuenta, por favor inicia sesión.'));
                Session::flash('flash_type', 'alert-success');
                return redirect()->route('login');
            } else {
                Session::flash('flash_message', __('+ El token no corresponde, verifica tu email e intenta de nuevo.'));
                Session::flash('flash_type', 'alert-danger');
                return redirect()->route('login');
            }
        }

        return redirect()->route('register');
    }
}
