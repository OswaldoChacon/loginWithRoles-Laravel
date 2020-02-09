@extends('home.alumno')
@section('content')
<div class="card">
    <h5 class="card-header">Registro de proyecto</h5>
    <div class="card-body">
        @if(Session::has('error'))
        <div class="alert alert-danger" id="alert-fade">{{Session::get('error')}}</div>
        @endif

        @if(Auth::user()->proyectos()->where('aceptado',1)->where('promedio','>=',70)->count() > 0)
        <div class="alert alert-info" role="alert">
            No puedes registrar un nuevo proyecto, ya has aprobado
        </div>

        @else
        @if(!is_null($foro))
        <!-- si se desea mantener el registro -->
        <!-- intentar quitar esto, puede que con $proyecto baste  -->
        @php
        $receptor_negado = Auth::user()->notificaciones_receptor()->where('respuesta',0)->count();
        $receptor_sinrespuesta = Auth::user()->notificaciones_receptor()->whereNull('respuesta')->count();
        $emisor_negado = Auth::user()->notificaciones_emisor()->where('respuesta',0)->count();
        $emisor_sinrespuesta = Auth::user()->notificaciones_emisor()->whereNull('respuesta')->count();
        $proyecto = Auth::user()->proyectos()->where('aceptado',0)->first();

        $proyectos_foro = $foro->proyectos()->get();
        @endphp
        <!-- dd($proyecto); -->
        <!-- dd($receptor_negado,$receptor_sinrespuesta,$emisor_negado,$emisor_sinrespuesta,$proyecto);     -->
        <!-- ->where('aceptado',1)->where('promedio','>=',70)->count() > 0)               -->
        @php
        $proyectos = Auth::user()->proyectos()->get();
        @endphp
        @if(!$proyectos->contains('foros_id',$foro->id))
        <div class="container">
            <h6>{{$foro->no_foro}}º {{$foro->nombre}} <br>
                {{$foro->periodo}} {{$foro->anio}}</h6>
        </div>
        <form method="post" action="{{route('registrarProyecto',Crypt::encrypt($foro->id))}}" class="form-center">
            @csrf
            <div class="row">
                <div class="col form-group">
                    <label for="titulo">Titulo</label>
                    <input type="text" class="form-control" type="text" name="titulo" placeholder=" Titulo" />
                    {!! $errors->first('titulo','<span class="text-danger">:message</span>')!!}
                </div>
                <div class="col form-group">
                    <label for="" class="control-label"> Linea de investigacion</label>
                    <select name="lineadeinvestigacion_id" id="categorias" class="form-control">
                        <option value="">Linea de investigacion</option>
                        @foreach($lineas as $linea)
                        <option value="{{$linea->id}}">{{$linea->nombre}}</option>
                        @endforeach
                    </select>
                    {!! $errors->first('lineadeinvestigacion_id','<span class="text-danger">:message</span>')!!}
                </div>
            </div>
            <div class="form-group">
                <label for="Objetivo">Objetivo</label>
                <textarea class="form-control" type="text" name="objetivo" placeholder="Objetivo"></textarea>
                {!! $errors->first('objetivo','<span class="text-danger">:message</span>')!!}
            </div>
            <div class="row">
                <!-- <div class="col form-group">
                                <label for="" class="control-label">Tipo de Titulación</label>
                                <select class="form-control" name="area_conoc">
                                    <option value="">Tipo de Titulación</option>
                                    
                                </select>
                                {!! $errors->first('area_conoc','<span class="text-danger">:message</span>')!!}
                            </div> -->
                <div class="col form-group">
                    <label for="" class="control-label">Asesor</label>
                    <select class="form-control" name="asesor">
                        <option value="">Asesor</option>
                        @foreach($docentes as $docente)
                        <option value="{{$docente->id}}">{{$docente->prefijo}} {{$docente->nombre}} {{$docente->apellidoP}} {{$docente->apellidoM}}</option>
                        @endforeach
                    </select>
                    {!! $errors->first('asesor','<span class="text-danger">:message</span>')!!}
                </div>

                <div class="col form-group">
                    <label for="name">Nombre de Empresa</label>
                    <input class="form-control" type="text" name="empresa" placeholder="Empresa">
                    {!! $errors->first('empresa','<span class="text-danger">:message</span>')!!}
                </div>
            </div>

            <div class="form-group text-right">
                <button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#integrantes" aria-expanded="false" aria-controls="integrantes">Añadir Integrantes</button>
            </div>

            <div class="collapse" id="integrantes">


                <h5>Integrantes</h5>
                <h6>Integrante #1: {{Auth::guard()->user()->nombre}} {{Auth::guard()->user()->apellidoP}} {{Auth::guard()->user()->apellidoM}} </h6>
                @foreach(range(2,$foro->lim_alumnos) as $token)
                <div class="form-group">
                    <label class="control-label">Integrante #{{$token}}</label>
                    <select class="form-control" name="alumnos[]">
                        <option value="">Alumos</option>
                        @foreach($alumnos as $alumno)
                        @if (Auth::guard()->user()->id!=$alumno->id && $alumno->id_proyecto ==null && ($alumno->nombre!=null || $alumno->apellidoP!=null))
                        <!-- && $alumno->acceso==0 -->
                        <option value="{{$alumno->id}}">{{$alumno->nombre}} {{$alumno->apellidoP}} {{$alumno->apellidoM}}</option>
                        @endif
                        @endforeach
                    </select>
                </div>
                @endforeach
            </div>
            <!-- <td name='oficina'>{{$foro->oficina}}</td> -->

            <button type="submit" class="btn btn-primary btn-sm" value="Registrar" name="">Guardar</button>

        </form>
        @elseif(Auth::user()->proyectos()->where('aceptado',true)->get()->contains('foros_id',$foro->id))
        <div class="alert alert-info">Proyecto en curso</div>
        @elseif($receptor_negado > 0 || $receptor_sinrespuesta > 0 || $emisor_negado > 0 || $emisor_sinrespuesta > 0)
        <div class="alert alert-danger">Proyecto aún no aceptado</div>
        <!-- || $proyecto->aceptado == false -->
        @elseif(is_null($proyecto))
        <div class="alert alert-danger">Proyecto aún no aceptado</div>
        @elseif($proyecto->aceptado == false)
        <div class="alert alert-danger">Proyecto aún no aceptado</div>
        @endif
        @else
        <div class="alert alert-danger" role="alert">
            No existe ningún foro activo
        </div>
        @endif
        @endif

    </div>
</div>
<script>

</script>

@endsection