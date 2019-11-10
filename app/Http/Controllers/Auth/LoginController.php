<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Providers\RouteServiceProvider;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;
use Illuminate\Http\Request;
use App\User;
use Session;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return Response
     */
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = User::where('email', $credentials['email'])->first();
            Auth::login($user, true);
            // Authentication passed...
            if ( $user->getOrganization()->status == 'inactive' ) {
                Auth::logout();
                Session::flash('flash_message', __('- Empresa inactiva, consulta con el administrador'));
                Session::flash('flash_type', 'alert-danger');
                return back()->withErrors(['error' => 'Try later']);
                //return redirect('/login')->with( 'failform', 'Agradecemos tu interés, por el momento no podemos atender tu solicitud. Si requires asistencia considere utilizar nuestro formulario de contacto, por favor indique su número de cliente #' . $user->resultados->first()->id);
            }

            if ( !$user->isVerified() ) {
                Auth::logout();
                Session::flash('flash_message', __('- Debes verificar tu email para poder iniciar sesion'));
                Session::flash('flash_type', 'alert-danger');
                return back()->withErrors(['error' => 'Try later']);
                //return redirect('/login')->with( 'failform', 'Agradecemos tu interés, por el momento no podemos atender tu solicitud. Si requires asistencia considere utilizar nuestro formulario de contacto, por favor indique su número de cliente #' . $user->resultados->first()->id);
            }
         
            return redirect($this->redirectTo);
        } else {
            $errors = new MessageBag(['password' => ['Email y/o password incorrecto(s).']]);
            return back()->withErrors($errors);
        }
        
        // print_r(Auth::user()->id);exit;
    }
}
