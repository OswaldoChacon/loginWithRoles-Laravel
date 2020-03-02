<?php

namespace App\Http\Controllers;

use App\Http\Requests\Usuarios\EditarUsuarioRequest;
use App\User;
use App\Roles;
use App\Notificaciones;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\Confirmacion;
use App\Http\Requests\Usuarios\RegistroRequest;
use Illuminate\Support\Facades\Auth;

class DocenteController extends Controller
{
    //
    public function __construct()
    {
        //$this->middleware('auth');
        $this->middleware('docente');
    }
    public function registrarView()
    {
        $roles = Roles::where('nombre', 'Alumno')->get();
        $users = User::whereHas('foros_user',function($query){
            $query->where('acceso',1);
        })->where('id',Auth()->user()->id)->first();            
        $this->authorize('registrarView',$users);                
        return view('oficina.usuarios.registrar', compact('roles'));
    }
    public function create(RegistroRequest $request)
    {
        $this->authorize('registrarView',$users);                
        $cod_confirmacion = Str::random(25);
        $datos_formulario = [
            'nombre' => $request->nombre,
            'email' => $request->email,
            'cod_confirmacion' => $cod_confirmacion,
        ];
        $rol = Roles::find(Crypt::decrypt($request->rol));
        $nuevoUsuario = new User();
        $nuevoUsuario->fill($request->all());
        $nuevoUsuario->password = bcrypt($request->num_control);
        $nuevoUsuario->cod_confirmacion = $cod_confirmacion;
        $nuevoUsuario->save();
        $nuevoUsuario->roles()->attach($rol);
        Mail::to($request->email)->send(new Confirmacion($datos_formulario));
        return back()->with('success', 'Usuario registrado');
    }
    public function notificaciones()
    {
        $notificaciones = Notificaciones::whereHas('proyecto.foro', function ($query) {
                $query->where('acceso', 1);
        })->where('receptor', Auth::user()->id)->whereNULL('respuesta')->get();

        $notificacionesRespondidas = Notificaciones::whereHas('proyecto.foro',function($query){
            $query->where('acceso',1);
        })->where('receptor',Auth::user()->id)->whereNotNull('respuesta')->get();

        return view('notificaciones',compact('notificaciones','notificacionesRespondidas'));
    }
    public function actualizar_info(EditarUsuarioRequest $request, $id)
    {
        // $request->validate($rules);

        $user = User::find(Crypt::decrypt($id));
        // $user->email = $request->email;
        $user->fill($request->all());
        $this->authorize($user);
        $user->save();
    }
  
}
