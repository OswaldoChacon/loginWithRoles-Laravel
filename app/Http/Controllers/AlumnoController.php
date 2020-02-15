<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Foros;
use App\Http\Requests\ProyectoRequest;
use App\LineasDeInvestigacion;
use App\Notificaciones;
use App\Proyectos;
use App\Roles;
use App\User;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Builder;

class AlumnoController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('alumno');
    }
    public function registrarProyectoView()
    {
        $foro = Foros::where('acceso', true)->first();
        $lineas = LineasDeInvestigacion::all();
        // $docentes = User::with(['roles' => function ($q) {
        //     $q->where('roles.nombre', 'Docente');            
        // }])->get();        
        $docentes = Roles::where('nombre', 'Docente')->first()->users()->get();
        // $docentes = User::with('roles')->where('nombre','jose')->get();        
        $alumnos = Roles::where('nombre', 'Alumno')->first()->users()->get();
        // $alumnosWithP = Foros::where('acceso',true)->first()->proyectos()->users()->get();        
        $alumnos = User::whereHas('proyectos.foro', function (Builder $query) {
            $query->where('promedio', '<', 70)->where('acceso',false);
        })->orWhereDoesntHave('proyectos')->get();
        //alumnos que no tengan ningun proyecto asociado o que tenga pero reprobado        
        // $alumnos = User::with(['roles'], function ($query) {
        //     $query->where('nombre', 'Alumno');
        // })->get();        
        return view('alumno.proyecto.registrarproyecto', compact('foro', 'lineas', 'docentes', 'alumnos'));
    }
    public function registrarProyecto(ProyectoRequest $request, $id)
    {        
        $id = Crypt::decrypt($id);
        $foro = Foros::find($id)->where('acceso',true)->first();        
        $folio = $this->folio($foro);
        $proyecto = new Proyectos();
        $emisores = array();
        $user = User::find(Auth()->user()->id);
        array_push($emisores, $request->asesor);
        //validación 'masiva' pendiente        
        for ($i = 0; $i < sizeof($request->alumnos); $i++) {
            if ($request->alumnos[$i] != null) {
                if (!in_array($request->alumnos[$i], $emisores)) {
                    array_push($emisores, $request->alumnos[$i]);
                } else {
                    return back()->with('error', 'Has repetido.');
                }
            }
        }
        if (!is_null($foro)) {
            $proyecto->fill($request->all());
            $proyecto->foros_id = $id;
            $proyecto->folio = $folio;
            $proyecto->save();
            $user->proyectos()->attach($proyecto);
            for ($i = 0; $i < sizeof($emisores); $i++) {
                $notificacion = new Notificaciones();
                $notificacion->emisor = $user->id;
                $notificacion->receptor = $emisores[$i];
                $notificacion->proyecto_id = $proyecto->id;
                $notificacion->save();
            }
            return redirect()->route('homeAlumno')->with('success', 'Proyecto registrado');
        }
    }
    public function folio(Foros $foro)
    {
        $folio = $foro->prefijo;
        $concat_folio = $foro->proyectos()->count();
        if ($concat_folio < 10)
            $folio .= "0";
        $folio .= $concat_folio + 1;
        return $folio;
    }
    public function misProyectos()
    {
        $proyectos = Auth()->user()->proyectos()->get();
        return view('alumno.proyecto.misProyectos');
    }
}
