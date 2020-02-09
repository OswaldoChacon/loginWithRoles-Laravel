<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Requests\Registro;
use App\Roles;
use App\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Mail\Confirmacion;
use App\Notificaciones;
use App\Proyectos;
use Illuminate\Support\Facades\Crypt;

class UsersController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    protected function create(Registro $request)
    {
        $cod_confirmacion = Str::random(25);
        $datos_formulario = [
            'nombre' => $request->nombre,
            'email' => $request->email,
            'cod_confirmacion' => $cod_confirmacion,
        ];
        $rol = Roles::where('nombre', $request->rol)->get();
        $nuevoUsuario = new User();
        $nuevoUsuario->num_control = $request->num_control;
        $nuevoUsuario->email = $request->email;
        $nuevoUsuario->password = bcrypt($request->num_control);
        $nuevoUsuario->cod_confirmacion = $cod_confirmacion;
        $nuevoUsuario->save();
        $nuevoUsuario->roles()->attach($rol);
        Mail::to($request->email)->send(new Confirmacion($datos_formulario));
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

    public function notificaciones(Request $request, $id)
    {
        $notificacion = Notificaciones::find(Crypt::decrypt($id));
        $notificacion->respuesta = $request->response;
        $notificacion->save();
        $this->aceptarAsesor($notificacion);
        $this->aceptarAlumno($notificacion);
        $notificaciones_pendientes = Notificaciones::where('proyecto_id', $notificacion->proyecto_id)->where('respuesta', 0)->orWhere('respuesta', null)->count();
        $proyecto = new Proyectos();
        $proyecto = $notificacion->proyecto()->first();
        if ($notificaciones_pendientes == 0) {
            $proyecto->aceptado = true;
        } else {
            $proyecto->aceptado = false;
        }
        $proyecto->save();
        if ($notificacion->respuesta)
            return back()->with('success', 'Proyecto aceptado.');
        else
            return back()->with('error', 'Proyecto rechazado.');
    }
    public function aceptarAsesor(Notificaciones $notificacion)
    {
        $asesor = Roles::where('nombre', 'Docente')->first()->users()->where('users.id', $notificacion->receptor)->first();
        $proyecto = $notificacion->proyecto()->first();
        if (!is_null($asesor)) {
            if ($notificacion->respuesta == 1)
                $proyecto->asesor = $asesor->id;
            else
                $proyecto->asesor = null;
        }
        $proyecto->save();
        return;
    }
    public function aceptarAlumno(Notificaciones $notificacion)
    {
        $proyecto = $notificacion->proyecto()->first();
        $alumno = Roles::where('nombre', 'Alumno')->first()->users()->where('users.id', $notificacion->receptor)->first();
        if (!is_null($alumno)) {
            if ($notificacion->respuesta == 1)
                $alumno->proyectos()->attach($proyecto);
            else
                $alumno->proyectos()->detach($proyecto);
        }
        return;
    }
}
