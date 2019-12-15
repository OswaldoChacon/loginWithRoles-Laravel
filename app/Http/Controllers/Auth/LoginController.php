<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use Illuminate\Http\Request;
use users;

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
    public function login(Request $request)
    {
        $errors=
        $credentials = $this->validate($request, [
            $this->username() => 'required|string|exists:users',
            'password' => 'required|string',
        ]);
        // if (Auth::attempt($credentials)) {            
        if (Auth::guard()->attempt($this->getCredentials($request))) {                                    
            return redirect('/');
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
