<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Foros;
use App\Http\Requests\Proyectos\ProyectoRequest;
use App\LineasDeInvestigacion;
use App\Notificaciones;
use App\Proyectos;
use App\Roles;
use App\TiposProyectos;
use App\User;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Builder;

class AlumnoController extends Controller
{
    //
    public function __construct()
    {
        //$this->middleware('auth');
        $this->middleware('alumno');
    }
    public function registrarProyectoView()
    {
        $foro = Foros::where('acceso', true)->first();
        $lineas = LineasDeInvestigacion::all();
        $tipos_proyecto = TiposProyectos::all();
        $docentes = Roles::where('nombre', 'Docente')->first()->users()->get();        
        $alumnos = Roles::where('nombre', 'Alumno')->first()->users()->get();        
        // $alumnos = User::whereHas('proyectos.foro', function (Builder $query) {
        //     $query->where('promedio', '<', 70)->where('acceso',false);
        // })->orWhereDoesntHave('proyectos')->get();
        $alumnos= User::whereHas('proyectos.foro', function (Builder $query) {
            $query->where('promedio', '<', 70)->where('acceso',false);
        })->orWhereDoesntHave('proyectos')->whereHas('roles',function(Builder $query){
            $query->where('roles.nombre','Alumno');
        })->get();                                               
        return view('alumno.proyecto.registrarproyecto', compact('foro', 'lineas', 'docentes', 'alumnos','tipos_proyecto'));
    }
    public function registrarProyecto(ProyectoRequest $request, $id)
    {                                                 
        if(!Auth()->user()->hasProject(Auth()->user()->id))
            return back()->with('errorHasProject','No puedes registrar otro proyecto.');
        $id = Crypt::decrypt($id);
        $foro = Foros::find($id)->where('acceso',true)->first();                
        $proyecto = new Proyectos();
        $folio = $proyecto->folio($foro);
        $receptores = array();        
    
        array_push($receptores, Crypt::decrypt($request->asesor));       
        for ($i = 0; $i < sizeof($request->alumnos); $i++) {
            if ($request->alumnos[$i] != null) {                                
                if (!in_array(Crypt::decrypt($request->alumnos[$i]), $receptores)) {
                    // $user =                     
                    if(!User::find(Crypt::decrypt($request->alumnos[$i]))->hasProject($request->alumnos[$i]))
                        return back()->with('error','Uno de tus integrantes ya cuenta con un proyecto registrado');
                    array_push($receptores, Crypt::decrypt($request->alumnos[$i]));                                        
                } else {
                    return back()->with('error', 'Has elegido al mismo integrante en más de una ocasión.');
                }
            }
        }

        if (!is_null($foro)) {
            // $request->asesor = Crypt::decrypt($request->asesor);           
            $proyecto->fill($request->all());
            $proyecto->asesor = Crypt::decrypt($request->asesor);           
            $proyecto->lineadeinvestigacion_id = Crypt::decrypt($request->lineadeinvestigacion_id);
            $proyecto->tipos_proyectos_id = Crypt::decrypt($request->tipos_proyectos_id);
            $proyecto->foros_id = $id;
            $proyecto->folio = $folio;            
            // $proyecto->asesor = $request->asesor;
            $proyecto->save();
            
            Auth()->user()->proyectos()->attach($proyecto);
            for ($i = 0; $i < sizeof($receptores); $i++) {
                $notificacion = new Notificaciones();
                $notificacion->emisor = Auth()->user()->id;
                $notificacion->receptor = $receptores[$i];
                $notificacion->proyecto_id = $proyecto->id;
                $notificacion->save();                
            }                  
            return redirect()->route('homeAlumno')->with('success', 'Proyecto registrado');
        }
    }
    // public function folio(Foros $foro)
    // {
    //     $folio = $foro->prefijo;
    //     $concat_folio = $foro->proyectos()->count();
    //     if ($concat_folio < 10)
    //         $folio .= "0";
    //     $folio .= $concat_folio + 1;
    //     return $folio;
    // }
    public function misProyectos()
    {
        $proyectos = Auth()->user()->proyectos()->get();
        return view('alumno.proyecto.misProyectos');
    }

    public function actualizar_info(Request $request,$id)
    {
        $rules = [
            'email'=>'required|email|unique:users,email,'.Crypt::decrypt($id)
        ];
        $request->validate($rules);
        $user = User::find(Crypt::decrypt($id));        
        // $user->email = $request->email;
        $user->fill($request->only('email'));
        $this->authorize($user);
        $user->save();        
    }
}
