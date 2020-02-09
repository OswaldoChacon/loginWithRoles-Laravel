<?php

namespace App\Http\Controllers;

use App\FechasForo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use App\LineasDeInvestigacion;
use App\Foros;
use App\HorarioBreak;
use App\Roles;
use App\Http\Requests\ForoRequest;
use App\Http\Requests\RegistroLineaRequest;
use App\Http\Requests\EditLineaRequest;
use App\Http\Requests\JuradoRequest;
use App\Http\Requests\HorarioRequest;
use App\Proyectos;
use App\User;
use Exception;

class OficinaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }
    //
    public function lineasdeinvestigacion()
    {
        $lineasdeinvestigacion = LineasDeInvestigacion::all();
        return view('oficina.linea', compact('lineasdeinvestigacion'));
    }
    public function lineadeinvestigacionguardar(RegistroLineaRequest $request)
    {
        $linea = new LineasDeInvestigacion;
        $linea->clave = $request->clave;
        $linea->nombre = $request->nombre;
        $linea->save();
        return back()->with('success', 'Linea registrada');
    }
    public function lineadeinvestigacioneliminar($id)
    {
        $id = Crypt::decrypt($id);
        LineasDeInvestigacion::where('id', $id)->delete();
        return back()->with('success', 'Registro eliminado');
    }
    public function lineadeinvestigacionactualizar(EditLineaRequest $request, $id)
    {
        $id =  Crypt::decrypt($id);
        $lineaUpdate = LineasDeInvestigacion::where('id', $id)->first();
        $lineaUpdate->clave = $request->clave;
        $lineaUpdate->nombre = $request->nombre;
        $lineaUpdate->save();
        return back()->with('success', 'Registro actualizado');
    }
    public function perfil()
    {
        return view('oficina.perfil');
    }
    public function foros()
    {
        $foros = Foros::all();
        return view('oficina.foros.foros', compact('foros'));
    }
    public function guardarForo(ForoRequest $request)
    {
        $prefijo = str_split($request->anio);
        $prefijo = $prefijo[2] . $prefijo[3];
        if ($request->periodo == "Agosto-Diciembre") {
            $prefijo = $prefijo . "02-";
        } else {
            $prefijo = $prefijo . "01-";
        }
        $foro = new Foros();
        $foro->no_foro = $request->no_foro;
        $foro->nombre = $request->nombre;
        $foro->periodo = $request->periodo;
        $foro->anio = $request->anio;
        $foro->prefijo = $prefijo;
        $foro->user_id = Auth()->user()->id;
        $foro->save();

        return back()->with('success', 'Foro registrado');
    }
    public function eliminarForo($id)
    {
        try {
        } catch (\Iluminate\Database\QueryException $e) {
        }
        $id = Crypt::decrypt($id);
        Foros::find($id)->delete();
        return back()->with('success', 'Foro eliminado');
    }
    public function activarForo($id)
    {
        $id  = Crypt::decrypt($id);
        $foros = Foros::where('acceso', true)->get();
        if ($foros->isEmpty()) {
            $foro = Foros::find($id);
            $foro->acceso = true;
            $foro->save();
            return back()->with('success', 'Foro activado');
        }
        return back()->with('error', 'No se permite tener dos foros activos');
    }
    public function desactivarForo($id)
    {
        $id = Crypt::decrypt($id);
        $foro = Foros::find($id);
        $foro->acceso = false;
        $foro->save();
        return back()->with('success', 'Foro desactivado');
    }
    public function configurarForoView($id)
    {
        $id = Crypt::decrypt($id);
        $foro = Foros::find($id);
        $docentes = Roles::where('nombre', 'Docente')->first()->users()->get();
        $foro_docente = $foro->users()->get();
        return view('oficina.foros.configurarForo', compact('foro', 'docentes', 'foro_docente'));
    }
    public function configurarForo(Request $request, $id)
    {
        $rules = [
            'lim_alumnos' => 'required',
            'num_aulas' => 'required',
            'duracion' => 'required',
            'num_maestros' => 'required'
        ];
        $validate = $request->validate($rules);
        $id = Crypt::decrypt($id);
        $foro = Foros::find($id);
        $foro->lim_alumnos = $request->lim_alumnos;
        $foro->num_aulas = $request->num_aulas;
        $foro->duracion = $request->duracion;
        $foro->num_maestros = $request->num_maestros;
        $foro->save();
        return back();
    }
    public function agregarForo_Docente(Request $request)
    {
        // $id = Crypt::decrypt($request->idProyecto);                        
        $foro = Foros::find(Crypt::decrypt($request->idForo))->where('acceso', true)->first();
        $user = User::find(Crypt::decrypt($request->idDocente));
        if (is_null($foro))
            return response()->json(['error' => 'Error al identificar el foro activo'], 404);
        if (is_null($user))
            return response()->json(['error' => 'Error al identificar el docente '], 404);
        $user->foros_user()->attach($foro);
        // dd(json_encode($foro->id));                
    }
    public function eliminarForo_Docente(Request $request)
    {
        $foro = Foros::find(Crypt::decrypt($request->idForo))->where('acceso', true)->first();
        $user = User::find(Crypt::decrypt($request->idDocente));
        if (is_null($foro))
            return response()->json(['error' => 'Error al identificar el foro activo'], 404);
        if (is_null($user))
            return response()->json(['error' => 'Error al identificar el docente '], 404);
        $user->foros_user()->detach($foro);

        // return back();
    }
    public function proyectos_foro($id)
    {
        $id = Crypt::decrypt($id);
        $foro = Foros::find($id);
        $proyectos = $foro->proyectos()->get();
        return view('oficina.foros.proyectos_foro', compact('foro', 'proyectos'));
    }

    public function proyectoParticipa(Request $request)
    {
        $id = $request->get('id');
        $value = $request->get('value');
        $proyecto = Proyectos::find($id);
        $proyecto->participa = $value;
        $proyecto->save();
        // DB::table('proyectos')
        //     ->where('id', $id)
        //     ->update(['participa' => $value]);
    }
    public function asignarJurado(Request $request)
    {
        // pendiente validacion        
        $proyecto = Proyectos::find($request->idProyecto);
        $foro = $proyecto->foro()->first();
        $jurado = User::find($request->idDocente);
        $jurado->jurado_proyecto()->attach($proyecto);
    }
    public function eliminarJurado(Request $request)
    {
        $jurado = User::find($request->idDocente);
        $proyecto = Proyectos::find($request->idProyecto);
        $jurado->jurado_proyecto()->detach($proyecto);
    }

    public function horarioforo(HorarioRequest $request, $foro_id)
    {
        $foro = Foros::find(Crypt::decrypt($foro_id));
        $fecha = new FechasForo();
        $fecha->fill($request->all());
        $fecha->foros_id = $foro->id;
        $fecha->save();
        return back();
    }
    public function eliminarhorarioforo($foro_id, $fecha_id)
    {
        $foro = Foros::find(Crypt::decrypt($foro_id))->where('acceso', true)->first();
        $fecha = FechasForo::find(Crypt::decrypt($fecha_id));
        $fecha->delete();
        return back();
        // dd($foro,$fecha);
    }
    public function editarhorarioforo($id)
    {
        $fecha = FechasForo::find($id);
        return response()->json($fecha);
    }
    public function actualizarhorarioforo(Request $request, $idFecha)
    {
        $fecha = FechasForo::find($idFecha);
        
        $fecha->fill($request->all());
        $fecha->save();
        return response()->json(['Success'=>'Fecha actualizado']);
    }

    public function horariojuradoView()
    {
        $foro = Foros::where('acceso', true)->first();
        if (!is_null($foro)) 
            $docentes = $foro->proyectos()->with('proyecto_jurado')->get()->pluck('proyecto_jurado')->flatten()->unique('num_control');
        $fechas = $foro->fechas()->get();
        // dd($fechas);
        // $docentes = $docentes->unique('num_control');                        
        return view('oficina.horario.horariojurado',compact('docentes'));
    }

    public function horariobreak(Request $request)
    {               
        $fecha = FechasForo::find(Crypt::decrypt($request->get('idFecha')));
        $foro = $fecha->foro()->first();
//        dd(json_encode($fecha->id));
        if(is_null($fecha))
            return response()->json(['Error'=>'Error al identificar la fecha del foro activo'],404);
        $receso = new HorarioBreak();
        $receso->fill($request->all());
        $receso->fechas_foros_id = $fecha->id;
        $receso->save();        
    }
    public function eliminarhorariobreak(Request $request)
    {
        $receso = HorarioBreak::where('posicion',$request->posicion)->first();
        if(!is_null($receso));
            $receso->delete();

    }
}
