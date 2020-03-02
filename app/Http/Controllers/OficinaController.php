<?php

namespace App\Http\Controllers;

use App\FechasForo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

use App\LineasDeInvestigacion;
use App\Foros;
use App\HorarioBreak;
use App\HorarioJurado;
use App\Roles;
use App\Proyectos;
use App\User;
use App\TiposProyectos;

use App\Http\Requests\Foro\ForoRequest;

use App\Http\Requests\Linea\RegistroLineaRequest;
use App\Http\Requests\Linea\EditLineaRequest;

use App\Http\Requests\Usuarios\RegistroRequest;
use App\Http\Requests\Usuarios\EditarUsuarioRequest;


use App\Http\Requests\Horarios\GenerarHorarioRequest;
use App\Http\Requests\JuradoRequest;
use App\Http\Requests\Horarios\HorarioRequest;

use App\GenerarHorario\Problema;
use App\GenerarHorario\Main;
use App\Http\Controllers\Traits\Uppercase;
use App\Http\Requests\Horarios\EditarHorarioRequest;
use App\Http\Requests\Tipos\EditarTiposRequest;
use App\Http\Requests\Tipos\RegistrarTiposRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\Confirmacion;


class OficinaController extends Controller
{
    use Uppercase;
    public function __construct()
    {
        //$this->middleware('auth');        
        $this->middleware('admin');
        // ,['only'=>['create']]
    }
    //
    public function create(RegistroRequest $request)
    {
        $cod_confirmacion = Str::random(25);
        $datos_formulario = [
            'nombre' => strtoupper($request->nombre),
            'email' => $request->email,
            'cod_confirmacion' => $cod_confirmacion,
        ];
        // dd($request->nombre);
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
    public function getUsuario($id)
    {
        $usuario = User::find(Crypt::decrypt($id));
        return response()->json($usuario);
    }
    public function actualizarUsuario(EditarUsuarioRequest $request, $id)
    {
        $usuario = User::find(Crypt::decrypt($id));
        $usuario->fill($request->all());
        $usuario->save();
    }
    public function eliminarusuario($id)
    {
        User::find(Crypt::decrypt($id))->delete();
        return back()->with('success', 'Usuario eliminado');
    }
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
        $linea = LineasDeInvestigacion::find(Crypt::decrypt($id));
        if ($linea->proyectos()->count() > 0)
            return back()->with('error', 'No se puede eliminar el registro');
        $linea->delete();
        return back()->with('success', 'Registro eliminado');
    }
    public function getlineadeinvestigacion($id_linea)
    {
        $linea = LineasDeInvestigacion::find(Crypt::decrypt($id_linea));
        //validacion necesaria?
        return $linea;
    }
    public function lineadeinvestigacionactualizar(EditLineaRequest $request, $id)
    {
        //dd($request);
        //$id =  Crypt::decrypt($id);        
        $lineaUpdate = LineasDeInvestigacion::find(Crypt::decrypt($id));
        if (is_null($lineaUpdate))
            return back()->with('error', 'Error al actualizar el registro');
        $lineaUpdate->clave = $request->clave;
        $lineaUpdate->nombre = $request->nombre;
        $lineaUpdate->save();
        return back()->with('success', 'Registro actualizado');
    }
    public function tipos_proyectos()
    {
        $tipos_proyectos = TiposProyectos::all();
        return view('oficina.tipos', compact('tipos_proyectos'));
    }
    public function registrar_tipos_proyectos(RegistrarTiposRequest $request)
    {
        $tipo_proyecto = new TiposProyectos();
        $tipo_proyecto->fill($request->all());
        // $linea->clave = $request->clave;
        // $linea->nombre = $request->nombre;
        $tipo_proyecto->save();
        return back()->with('success', 'Linea registrada');
    }
    public function eliminar_tipos_proyectos($id)
    {
        $tipo_proyecto = TiposProyectos::find(Crypt::decrypt($id));
        if ($tipo_proyecto->proyectos()->count() > 0)
            return back()->with('error', 'No se puede eliminar el registro');
        $tipo_proyecto->delete();
        return back()->with('success', 'Registro eliminado');
    }
    public function get_tipos_proyectos($id)
    {
        $tipo_proyecto = TiposProyectos::find(Crypt::decrypt($id));
        //validacion necesaria?
        return $tipo_proyecto;
    }
    public function actualizar_tipos_proyectos(EditarTiposRequest $request, $id)
    {
        $tipo_proyecto = TiposProyectos::find(Crypt::decrypt($id));
        if (is_null($tipo_proyecto))
            return back()->with('error', 'Error al actualizar el registro');
        // $lineaUpdate->clave = $request->clave;
        // $lineaUpdate->nombre = $request->nombre;
        $tipo_proyecto->fill($request->all());
        $tipo_proyecto->save();
        return back()->with('success', 'Registro actualizado');
    }
    public function perfil()
    {
        return view('oficina.perfil');
    }
    public function foros()
    {
        $foros = Foros::orderBy('anio')->orderBy('periodo', 'desc')->get();
        //dd($foros);
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
        $id = Crypt::decrypt($id);
        $foro = Foros::find($id);
        if ($foro->proyectos()->count() > 0)
            return back()->with('error', 'No se puede eliminar registro');
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
            'lim_alumnos' => 'required|numeric|min:1',
            'num_aulas' => 'required|numeric|min:1',
            'duracion' => 'required|numeric|min:15',
            'num_maestros' => 'required|numeric|min:1'
        ];
        $validate = $request->validate($rules);
        $id = Crypt::decrypt($id);
        $foro = Foros::find($id);
        if ($foro->acceso == false)
            return back()->with('error', 'El foro no esta activo');
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
        $proyectosAceptados = $foro->proyectos()->where('aceptado', 1)->get();
        $proyectosPendientes = $foro->proyectos()->where('aceptado', 0)->get();
        return view('oficina.foros.proyectos_foro', compact('foro', 'proyectosAceptados', 'proyectosPendientes'));
    }

    public function proyectoParticipa(Request $request)
    {
        $id = Crypt::decrypt($request->get('id'));
        $value = $request->get('value');
        $proyecto = Proyectos::find($id);
        if (!$proyecto->aceptado)
            return response()->json(['Error' => 'El proyecto aún no ha sido aceptado'], 500);
        $proyecto->participa = $value;
        $proyecto->save();
        // DB::table('proyectos')
        //     ->where('id', $id)
        //     ->update(['participa' => $value]);
    }
    public function asignarJurado(Request $request)
    {
        // pendiente validacion        
        $proyecto = Proyectos::find(Crypt::decrypt($request->idProyecto));
        $foro = $proyecto->foro()->first();
        if ($proyecto->proyecto_jurado()->count() + 1 > $foro->num_maestros)
            return response()->json(['Error' => 'Cantidad de maestros excedido'], 422);
        $jurado = User::find(Crypt::decrypt($request->idDocente));
        $jurado->jurado_proyecto()->attach($proyecto);
    }
    public function eliminarJurado(Request $request)
    {
        $jurado = User::find(Crypt::decrypt($request->idDocente));
        $proyecto = Proyectos::find(Crypt::decrypt($request->idProyecto));
        if ($proyecto->asesor == $jurado->id)
            return response()->json(['Error' => 'No se puede quitar al asesor como parte del jurado'], 422);
        $jurado->jurado_proyecto()->detach($proyecto);
    }

    public function horarioforo(HorarioRequest $request, $foro_id)
    {
        $foro = Foros::find(Crypt::decrypt($foro_id));
        $fecha = new FechasForo();
        $fecha->fill($request->all());
        $fecha->foros_id = $foro->id;
        $fecha->save();
        //HorarioBreak::truncate();
        return back();
    }
    public function eliminarhorarioforo($foro_id, $fecha_id)
    {
        $foro = Foros::find(Crypt::decrypt($foro_id))->where('acceso', true)->first();
        $fecha = FechasForo::find(Crypt::decrypt($fecha_id));
        // dd($fecha->has('receso')->delete(),$fecha->receso()->get()->flatten());        
        $fecha->delete();
        return back();
        // dd($foro,$fecha);
    }
    public function editarhorarioforo($id)
    {
        $fecha = FechasForo::find(Crypt::decrypt($id));
        if (is_null($fecha))
            return response()->json(['Error' => 'Error al identificar la fecha'], 404);
        return response()->json($fecha);
    }
    public function actualizarhorarioforo(EditarHorarioRequest $request, $idFecha)
    {
        $fecha = FechasForo::find(Crypt::decrypt($idFecha));
        $fecha->fill($request->all());
        $fecha->save();
        $fecha->receso()->delete();
        $fecha->horario_jurado()->delete();
        return response()->json(['Success' => 'Fecha actualizada']);
    }

    public function horariojuradoView()
    {
        $foro = Foros::where('acceso', true)->first();
        if (!is_null($foro))
            $docentes = $foro->proyectos()->where('participa', 1)->with('proyecto_jurado')->get()->pluck('proyecto_jurado')->flatten()->unique('num_control');
        //$fechas = $foro->fechas()->get();
        // dd($fechas);
        // $docentes = $docentes->unique('num_control');                        
        return view('oficina.horario.horariojurado', compact('docentes', 'foro'));
    }
    public function horariojurado(Request $request)
    {
        $horariojurado = new HorarioJurado();
        $horariojurado->fill($request->all());
        $horariojurado->docente_id = $request->idDocente;
        $horariojurado->fechas_foros_id = $request->idFechaForo;
        $horariojurado->save();
        return response()->json($horariojurado);
    }
    public function eliminarhorariojurado(Request $request)
    {
        $horariojurado = HorarioJurado::where('posicion', $request->posicion)->where('docente_id', $request->idDocente)->where('fechas_foros_id', $request->idFechaForo)->first();
        $horariojurado->delete();

        return response()->json(['Success' => 'Registro eliminado']);
    }

    public function horariobreak(Request $request)
    {
        $fecha = FechasForo::find(Crypt::decrypt($request->get('idFecha')));
        $foro = $fecha->foro()->first();
        //        dd(json_encode($fecha->id));
        if (is_null($fecha))
            return response()->json(['Error' => 'Error al identificar la fecha del foro activo'], 404);
        $receso = new HorarioBreak();
        $receso->fill($request->all());
        $receso->fechas_foros_id = $fecha->id;
        $test = $receso->fechas_foros()->with(['horario_jurado' => function ($query) use ($request) {
            $query->where('posicion', $request->posicion);
        }])->get()->pluck('horario_jurado')->flatten()->toArray();
        //dd(json_decode($test));
        //return $test;
        $ids_to_delete = array_map(function ($item) {
            return $item['id'];
        }, $test);
        //return $ids_to_delete;
        DB::table('horario_jurado')->whereIn('id', $ids_to_delete)->delete();

        //dd(json_decode($test));
        $receso->save();
    }
    public function eliminarhorariobreak(Request $request)
    {
        //verificar si es por id o por posicion, cual es mas conveniente
        $receso = HorarioBreak::where('posicion', $request->posicion)->first();
        if (is_null($receso))
            return response()->json(['Error' => 'Error al identificar la fecha'], 404);
        $receso->delete();
    }

    public function horarioView()
    {
        $foro = Foros::where('acceso', 1)->first();
        return view('oficina.horario.horario', compact('foro'));
    }

    public function generarHorario(GenerarHorarioRequest $request)
    {
        // Proyectos participantes
        $foro = Foros::where('acceso', 1)->first();
        $salones = $foro->num_aulas;
        $proyectos = $foro->proyectos()->where('participa', 1)->get();
        $recesos = $foro->fechas()->with('receso')->get()->pluck('receso')->flatten()->pluck('posicion');
        $proyectos_maestros = DB::table('jurados')->select('proyectos.folio as id_proyecto', 'proyectos.titulo', DB::raw('group_concat( Distinct users.nombre," ",users.apellidoP," ",users.apellidoM) as maestros'))
            ->join('users', 'jurados.docente_id', '=', 'users.id')
            ->join('proyectos', 'jurados.proyecto_id', '=', 'proyectos.id')
            ->where('proyectos.participa', 1)
            ->groupBy('proyectos.titulo')
            ->get()->each(function ($query) {
                $query->maestros = explode(",", $query->maestros);
            });
        // maestros con sus espacios de tiempo
        $maestro_et_FIX = DB::table('horario_jurado')->select(DB::raw('group_concat(distinct users.nombre," ",users.apellidoP," ",users.apellidoM) as nombre'), DB::raw('count(hora) as cantidad'), DB::raw('group_concat(horario_jurado.posicion) as horas'))
            ->rightJoin('users', 'horario_jurado.docente_id', '=', 'users.id')
            ->groupBy('nombre')
            ->get()->each(function ($query) {
                // quite arrayfilter para solucionar que no agarra el 0
                // $integerIDs = array_map('intval', explode(',', $string));
                // $query->horas = array_filter(array_map('intval',explode(",", $query->horas)),function($value) {
                $query->horas = array_filter(explode(",", $query->horas), function ($value) {
                    return ($value !== null && $value !== false && $value !== '');
                });
            });
        $maestro_et = DB::table('horario_jurado')
            ->select(DB::raw('group_concat(distinct users.nombre," ",users.apellidoP," ",users.apellidoM) as nombre'), DB::raw('count(hora) as cantidad'), DB::raw('group_concat(horario_jurado.posicion) as horas'))
            ->join('users', 'horario_jurado.docente_id', '=', 'users.id')
            ->join('fechas_foros', 'horario_jurado.fechas_foros_id', '=', 'fechas_foros.id')
            ->join('foros', 'fechas_foros.foros_id', '=', 'foros.id')
            ->where('foros.acceso', 1)
            ->groupBy('docente_id')
            ->orderBy('cantidad')->get()->each(function ($query) {
                // quite arrayfilter para solucionar que no agarra el 0
                // $integerIDs = array_map('intval', explode(',', $string));
                // $query->horas = array_filter(array_map('intval',explode(",", $query->horas)),function($value) {
                $query->horas = array_filter(explode(",", $query->horas), function ($value) {
                    return ($value !== null && $value !== false && $value !== '');
                });
            });
        $horarios = $foro->fechas()->orderBy('fecha')->get();

        // dd($horarios[0]->fecha);
        $minutos = $foro->duracion;
        $intervalosContainer = array();
        foreach ($horarios as $item) {
            $intervalo = $item->horarioIntervalos($minutos, 2);
            $testTable[] = $intervalo[sizeof($intervalo) - 1];
            array_push($intervalosContainer, $intervalo);
        }
        $intervalosUnion = array();
        foreach ($intervalosContainer as $intervaloTotal) {
            foreach ($intervaloTotal as $itemIntervaloTotal) {
                $intervalosUnion[] = $itemIntervaloTotal;
            }
        }
        //Nuevo 05 dic
        //$cantidadMaestro_Jurado = DB::select('SELECT jurados.* FROM jurados inner join proyectos on jurados.proyecto_id=proyectos.id INNER join foros on proyectos.foros_id=foros.id where foros.acceso=1 and proyectos.participa=1 group by jurados.docente_id');        
        //$horarioDocentes = DB::select('SELECT horario_jurado.* FROM `horario_jurado` inner join fechas_foros on horario_jurado.fechas_foros_id=horario_jurado.id inner join foros on fechas_foros.foros_id=foros.id inner join jurados on horario_jurado.docente_id=jurados.docente_id inner join proyectos on jurados.proyecto_id=proyectos.id where foros.acceso=1 and proyectos.participa=1 group by docente_id');

        $cantidadMaestro_Jurado = $foro->proyectos()->where('participa', 1)->with('proyecto_jurado')->get()->pluck('proyecto_jurado')->flatten()->unique('num_control');
        //$test2 = $foro->fechas()->with('horario_jurado')->get()->pluck('horario_jurado')->flatten()->unique('docente_id');
        $horarioDocentes = $foro->fechas()->with('horario_jurado')->get()->pluck('horario_jurado')->flatten()->unique('docente_id');
        //$foro->proyectos()->where('participa',1)->with('proyecto_jurado')->get();
        //dd(json_decode($test2));


        $cantidadDeET = count($intervalosUnion) * $foro->num_aulas;
        //dd($cantidadMaestro_Jurado,$horarioDocentes,$cantidadDeET,sizeof($proyectos_maestros));
        // dd($proyectos_maestros,$maestro_et);
        if (sizeof($horarioDocentes) != sizeof($cantidadMaestro_Jurado) || $cantidadDeET < sizeof($proyectos_maestros)) {
            return response()->noContent();
        }
        
        //Nuevo 05 dic

        $main = new Main($proyectos_maestros, $maestro_et, $intervalosUnion, $request->alpha, $request->beta, $request->Q, $request->evaporation, $request->iterations, $request->ants, $request->estancado,  $request->t_minDenominador, $foro->num_aulas, $recesos);
        //validacion ultima
        // $cantidadProyectosMA = DB::table('jurados')->select(DB::raw('count(id_docente) as cantidad, group_concat(distinct docentes.prefijo," ",docentes.nombre," ",docentes.paterno," ",docentes.materno) as nombre'))
        //     //DB::raw('group_concat(distinct docentes.prefijo," ",docentes.nombre," ",docentes.paterno," ",docentes.maternos) as nombre')
        //     ->join('docentes', 'jurados.id_docente', '=', 'docentes.id')
        //     ->join('proyectos', 'jurados.id_proyecto', '=', 'proyectos.id')
        //     ->where('proyectos.participa', 1)
        //     ->groupBy('id_docente')
        //     ->orderBy('cantidad')->get();

        // $cantidadETMaestros = DB::select('select id_docente, count(hora) as cantidad from horariodocentes,docentes,horarioforos,foros where horariodocentes.id_docente = docentes.id and horariodocentes.id_horarioforos = horarioforos.id and horarioforos.id_foro = foros.id and foros.acceso = 1 group by id_docente order by cantidad asc');
        // // dd($horarioDocentes,"l",$cantidadMaestro_Jurado);
        // $maestro__foro = $salones->num_maestros;
        //return $main->problema->eventos[0];

        if ($main->problema->eventos[0]->sizeComun == 0) {
            return response()->noContent();
        }
        foreach ($main->problema->eventos as $evento) {
            //dd($evento,sizeof($evento->maestroList));
            $maestro_evento = sizeof($evento->maestroList);
            if ($maestro_evento < $foro->num_maestros) {
                return response()->noContent();
            }
        }
        $main->start();
        $matrizSolucion = $main->matrizSolucion;

        $resultado_aux = array();
        $resultadoItem = array();
        $resultado = array();
        $resul = array();
        foreach ($matrizSolucion as $key => $items) {
            for ($i = 0; $i < sizeof($items); $i++) {
                $resul[$i] = [];
            }
            foreach ($items as $keyItems => $item) {
                unset($aux);
                $aux = array_filter(explode(",", $item), function ($value) {
                    return ($value !== null && $value !== false && $value !== '');
                });
                $resul[$keyItems] = $aux;
            }
            $resultado_aux[$key] = $resul;
            unset($resul);
        }


        $indice = 0;
        $tituloLlave = array();
        foreach ($resultado_aux as $key => $item) {
            $tituloLlave = array();
            foreach ($item as $keyItem => $itemItems) {
                if (sizeof($itemItems) > 1) {
                    $temporalLlave = $itemItems[0];
                    unset($itemItems[0]);
                    $tituloLlave[$temporalLlave] = $itemItems;
                } else {
                    $tituloLlave[$keyItem] = $itemItems;
                }
            }
            if ($key == $testTable[$indice]) {
                $resultadoItem[str_replace($horarios[$indice]->fecha, '', $key)] = $tituloLlave;
                $resultado[$horarios[$indice]->fecha] = $resultadoItem;
                $indice += 1;
                $resultadoItem = array();
            } else {
                $resultadoItem[str_replace($horarios[$indice]->fecha, '', $key)] = $tituloLlave;
            }
        }

        // DB::table('horariogenerado')
        //     ->delete();
        // DB::statement('ALTER TABLE horariogenerado AUTO_INCREMENT =1');

        //database
        $testFinal2 = array();
        $testFinal = array();
        foreach ($resultado as $date => $dates) {
            foreach ($dates as $hour => $hours) {
                $cont = 0;
                foreach ($hours as $event => $events) {
                    // $cont= 0;
                    // $cont++;
                    if ($events != null && sizeof($events) > 1) {
                        $cont++;
                        // dd($hours,$event,$events,$cont);
                        foreach ($events as $keyItem => $item) {
                            //dd($item);
                            $project = DB::table('proyectos')->select('proyectos.id as id')
                                ->join('foros', 'proyectos.foros_id', '=', 'foros.id')
                                ->where('proyectos.folio', '=', $event)->where('foros.acceso', 1)->first();
                            //  dd($project);
                            $docentes = DB::TABLE('users')->select('id')
                                ->where(DB::raw("CONCAT(nombre, ' ', apellidoP,' ', apellidoM)"), '=', $item)->first();
                            array_push($testFinal, $date, $hour, $project->id, $docentes->id, $cont);
                            array_push($testFinal2, $testFinal);
                            $testFinal = array();
                        }
                    }
                }
            }
        }
        // return $testFinal2;
        // foreach ($testFinal2 as $registro) {
        //     DB::table('horariogenerado')->insert([
        //         [
        //             'fecha' => $registro[0],
        //             'hora' => $registro[1],
        //             'id_proyecto' => $registro[2],
        //             'id_docente' => $registro[3],
        //             'salon' => $registro[4],
        //         ],
        //     ]);
        // }
        return $resultado;
    }
    public function proyectosHorarioMaestros()
    {
        
        $foro = Foros::where('acceso', 1)->first();
        $horarios =    $horarios = $foro->fechas()->orderBy('fecha')->get();
        
        // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // // //
        $min =  $foro->duracion;        
        $minutos =  $foro->duracion;
        $longitud = count($horarios);
        // dd($longitud);
        $temp = " ";
        $temp2 = " ";
        $intervalosContainer = array();
        $testTable = array();
        $intervalosUnion = array();
        foreach ($horarios as $item) {
            $intervalosContainer[$item->fecha] = [];
        }        
        $indice = 0;
        foreach ($horarios as $item) {
            $intervalo = array();            
            while ($item->hora_inicio <= $item->hora_termino) {
                $intervalo[$indice] = [];
                $newDate = strtotime('+0 hour', strtotime($item->hora_inicio));
                $newDate = strtotime('+' . $minutos . 'minute', $newDate);                
                $newDate = date('H:i', $newDate);
                $temp = date('H:i', strtotime($item->hora_inicio)) . " - " . $newDate;                                                
                $item->hora_inicio = $newDate;
                if ($newDate <= $item->hora_termino) 
                {
                    $intervalo[$indice] = $temp;                
                    $intervalosContainer[$item->fecha] = $intervalo;
                }                    
                $indice++;                
            }                        
        }                                
        foreach ($intervalosContainer as $intervaloTotal) {
            foreach ($intervaloTotal as $itemIntervaloTotal) {
                $intervalosUnion[] = $itemIntervaloTotal;
            }
        }               
        $proyectos_maestros =  DB::table('jurados')->select('proyectos.folio as id_proyecto', 'proyectos.titulo', DB::raw('group_concat( Distinct users.prefijo," ",users.nombre," ",users.apellidoP," ",users.apellidoM) as maestros'))
        ->join('users', 'jurados.docente_id', '=', 'users.id')
        ->join('proyectos', 'jurados.proyecto_id', '=', 'proyectos.id')
        
        ->join('foros','proyectos.foros_id','=','foros.id')

        ->where('proyectos.participa', 1)
        ->where('acceso',1)
        ->groupBy('proyectos.titulo')
        ->get()->each(function ($query) {
            $query->maestros = explode(",", $query->maestros);
        });    
        $maestro_et = DB::table('horario_jurado')
        ->select(DB::raw('group_concat(distinct users.prefijo," ",users.nombre," ",users.apellidoP," ",users.apellidoM) as nombre'), DB::raw('count(hora) as cantidad'), DB::raw('group_concat(horario_jurado.posicion) as horas'))
        ->join('users', 'horario_jurado.docente_id', '=', 'users.id')
        ->join('fechas_foros', 'horario_jurado.fechas_foros_id', '=', 'fechas_foros.id')
        ->join('foros', 'fechas_foros.foros_id', '=', 'foros.id')
        // ->join('proyectos','foros.id','=','proyectos.foros_id')
        ->where('foros.acceso', 1)
        // ->where('proyectos.participa',1)
        ->groupBy('docente_id')
        ->orderBy('cantidad')->get()->each(function ($query) {            
            $query->horas = array_filter(explode(",", $query->horas), function ($value) {
                return ($value !== null && $value !== false && $value !== '');
            });
        });              
        $problema = new Problema($proyectos_maestros, $maestro_et, []);
        $proyectos = $problema->eventos;

// dd($maestro_et);
        $cantidadProyectosMA = DB::table('jurados')->select(DB::raw('count(docente_id) as cantidad, group_concat(distinct users.prefijo," ",users.nombre," ",users.apellidoP," ",users.apellidoM) as nombre'))
            //DB::raw('group_concat(distinct docentes.prefijo," ",docentes.nombre," ",docentes.paterno," ",docentes.maternos) as nombre')
            ->join('users', 'jurados.docente_id', '=', 'users.id')
            ->join('proyectos', 'jurados.proyecto_id', '=', 'proyectos.id')
            ->where('proyectos.participa', 1)
            ->groupBy('docente_id')
            ->orderBy('cantidad')->get();                                   
        $receso = $foro->fechas()->with('receso')->get()->pluck('receso')->flatten()->count();
        $espacios_de_tiempo = sizeof($intervalosUnion)-$receso;
        $aulas = ($foro->num_aulas * $espacios_de_tiempo);             
        // dd($foro->num_aulas, sizeof($intervalosUnion), $receso, $aulas,$intervalosUnion);        
        return view('oficina.horario.proyectos', compact('foro','proyectos', 'intervalosContainer', 'cantidadProyectosMA', 'aulas'));
    }
}
