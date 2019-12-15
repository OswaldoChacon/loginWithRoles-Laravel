<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Registro;
use Illuminate\Support\Str;
use App\User;
use App\Roles;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use App\Mail\Confirmacion;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    // protected function create(array $data)
    // {
    //     return User::create([
    //         'nombre' => $data['nombre'],
    //         'email' => $data['email'],
    //         'password' => Hash::make($data['password']),
    //     ]);
    // }

    protected function create(Registro $request)
    {
        // dd($request);        
        $cod_confirmacion = Str::random(25);
        $datos_formulario = [
            'nombre' => $request->nombre,
            'email' => $request->email,
            'cod_confirmacion' => $cod_confirmacion
        ];
        $rol = Roles::where('nombre','admin')->get();        
        $nuevoUsuario = new User();
        $nuevoUsuario->nombre = $request->nombre;
        $nuevoUsuario->email = $request->email;
        $nuevoUsuario->password = bcrypt($request->password);
        $nuevoUsuario->cod_confirmacion = $cod_confirmacion;
        $nuevoUsuario->save();        
        $nuevoUsuario->roles()->attach($rol);        
        // Mail::to($request->email)->send(new Confirmacion($datos_formulario));
        Session::flash('message', '¡Registrado!');
        Session::flash('alert-success', 'alert-success');
        return back();
        // ->with('success','Registro exitoso');
        // return User::create([
        //     'nombre' => $data['nombre'],
        //     'email' => $data['email'],
        //     'password' => Hash::make($data['password']),
        // ]);
    }
    public function verificacion($code)
    {
        $user = User::where('cod_confirmacion', $code)->first();

        if (!$user)
            return redirect('/register');

        $user->confirmado = true;
        $user->cod_confirmacion = null;
        $user->save();
        Session::flash('message', '¡Correo confirmado!');
        Session::flash('alert-success', 'alert-success');
        return redirect('/home');
    }
}
