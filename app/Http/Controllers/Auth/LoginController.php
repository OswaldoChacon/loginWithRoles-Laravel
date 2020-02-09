<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use Illuminate\Http\Request;
use users;
use App\Roles;

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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function showLoginForm()
    {
        $roles = Roles::all();
        return view('login.login', compact('roles'));
    }
    public function login(Request $request)
    {
        $credentials = $this->validate($request, [
            $this->username() => 'required|string|exists:users',
            'password' => 'required|string',            
        ]);
        // if (Auth::attempt($credentials)) {                    
        $user = User::where('email', $request->input('email'))->first();
        // if (!$user->hasRole($request->input('role'))) {            
        //     return back()
        //         ->withErrors([$this->username() => 'No cuentas con el permiso para ingresar como '.$request->role])
        //         ->withInput(request([$this->username()]));
        // }
        if (Auth::guard()->attempt($this->getCredentials($request))) {
            $rol = $user->roles()->get();
            if ($rol->contains('nombre', 'Oficina'))
                return redirect('/Oficina');
            elseif ($rol->contains('nombre', 'Alumno'))
                return redirect('/Alumno');
            elseif ($rol->contains('nombre', 'Docente'))
                return redirect('/Docente');
            // switch ($request->input('role')) {
            //     case "Oficina":
            //         return redirect('/Oficina');
            //         break;
            //     case "Alumno":
            //         return redirect('/Alumno');
            //         break;
            //     case "Docente":
            //         return redirect('/Docente');
            // }
            // return redirect('/');
            // if ($this->sendFailedLoginResponse($request)) {            
        } else {
            return back()
                ->withErrors(['password' => trans('Contraseña incorrecta o cuenta inactiva')])
                ->withInput(request([$this->username()]));
        }
    }

    protected function getCredentials(Request $request)
    {
        return [
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'confirmado' => true
        ];
    }
    protected function authenticated(Request $request, $user)
    {

        //return $this->authenticated($request, $this->guard()->user())
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }

    public function username()
    {
        return 'email';
    }
}
