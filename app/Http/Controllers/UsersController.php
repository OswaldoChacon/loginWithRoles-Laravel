<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Roles;
use App\User;
use App\Notificaciones;
use App\Proyectos;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class UsersController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');               
    }
    public function notificacionesView()
    {
        $notificaciones = Notificaciones::whereHas('proyecto.foro', function ($query) {
                $query->where('acceso', 1);
        })->where('receptor', Auth::user()->id)->whereNULL('respuesta')->get();

        $notificacionesRespondidas = Notificaciones::whereHas('proyecto.foro',function($query){
            $query->where('acceso',1);
        })->where('receptor',Auth::user()->id)->whereNotNull('respuesta')->get();

        return view('notificaciones',compact('notificaciones','notificacionesRespondidas'));
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
            if ($notificacion->respuesta == 1){
                $asesor->jurado_proyecto()->attach($proyecto); 
                $proyecto->asesor = $asesor->id;
            }             
            else{
                $proyecto->asesor = null;
                $asesor->jurado_proyecto()->detach($proyecto); 
            }             
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

    public function actualizar_info(Request $request)
    {
        $user = User::find(Crypt::decrypt($request->id));
        //dd(json_decode($user));
        // Crypt::decrypt($request->id)
        $user->fill($request->all());
        $this->authorize($user);
        $user->save();
        return response()->json(Auth::user());
    }
    public function actualizar_password(Request $request, $id)
    {
        $rules = [
            'password'=>'required|min:6'
        ];
        $request->validate($rules);
        $user = User::find(Crypt::decrypt($id));
        $user->password = bcrypt($request->password);
        $user->save();
    }
}
