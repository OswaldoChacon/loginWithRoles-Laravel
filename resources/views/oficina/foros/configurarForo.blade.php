@extends('home.oficina')
@section('content')

<style>
    .foro_docente:hover {
        background-color: red;
        color: white
    }
</style>
<!-- <div class="card">
    <div class="card-body"> -->
@if($foro->acceso == 0)
<div class="alert alert-danger">
    <span>Foro no activo</span>
</div>
@else
@if($foro_docente->count() == 0)
<div class="alert alert-danger fade show">
    No has agregado a ningún maestro para este foro
</div>
@endif
@if($foro->fechas()->count() == 0)
    <div class="alert alert-danger">No se han estipulado fechas para este foro</div>
@endif
<div class="card">
    <div class="card-header">
        <h5>Configuración</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive mb-4">

            <!-- <form action="/configurarForoAtributos/{{Crypt::encrypt($foro->id)}}" method="POST"> -->
            <form action="{{route('configurarForo',Crypt::encrypt($foro->id))}}" method="POST">
                {{csrf_field()}}
                {{method_field('PUT')}}
                <!-- <input type="hidden" value="{{Crypt::encrypt($foro->id)}}" name="id"> -->
                <table class="table table-strsiped table-hover table-sm">
                    <thead class="thead-light">
                        <th>
                            <h6> <strong>{{$foro->no_foro}}º {{$foro->nombre}}</strong></h6>
                            <h6>{{$foro->periodo}} {{$foro->anio}}</h6>
                        </th>
                        <th>
                        </th>
                    </thead>
                    <tbody style="table-layout:fixed">
                        <tr>
                            <td colspan="1"> Jefe de Oficina: </td>
                            <td colspan="2"> {{$foro->user['nombre']}} {{$foro->user['apellidoP']}}
                                {{$foro->user['apellidoM']}}</td>
                        </tr>
                        <tr>
                            <td>Limite de alumnos por proyecto: <strong>{{$foro->lim_alumnos}}</strong> </td>
                            <td><input class="form-inline" type="number" value="{{$foro->lim_alumnos}}"
                                    name="lim_alumnos" min="1"  style='width:70px; height:25px' />
                                {!! $errors->first('lim_alumnos','<span class="text-danger">:message</span>')!!}
                            </td>
                            <!-- <p id="agregarHora">&nbsp;</p> -->
                        </tr>
                        <tr>
                            <td>Duración de exposición por evento: <strong> {{$foro->duracion}} min </strong></td>
                            <td><input class="form-inline" type="number" name="duracion" value="{{$foro->duracion}}"
                                    class="form-control" min="15" pattern="[0-9]" style='width:70px; height:25px' />
                                {!! $errors->first('duracion','<span class="text-danger">:message</span>')!!}
                            </td>
                        </tr>
                        <tr>
                            <td>Número de aulas a utilizar en el evento: <strong> {{$foro->num_aulas}} </strong>
                            </td>
                            <td><input class="form-inline" type="number" name="num_aulas" value="{{$foro->num_aulas}}"
                                    class="form-control" min="1" pattern="[0-9]"
                                    style='width:70px; height:25px' />
                                {!! $errors->first('num_aulas','<span class="text-danger">:message</span>')!!}
                            </td>
                        </tr>
                        <tr>
                            <td>Número de maestros a considerar como jueces para cada proyecto: <strong>
                                    {{$foro->num_maestros}} </strong></td>
                            <td><input class="form-inline" type="number" name="num_maestros" class="form-control"
                                    min="1" value="{{$foro->num_maestros}}" pattern="[0-9]"
                                    style='width:70px; height:25px' />
                                {!! $errors->first('num_maestros','<span class="text-danger">:message</span>')!!}
                            </td>
                        </tr>
                    </tbody>
                </table>
                <button type="submit" class="btn-sm btn-primary">Guardar</button>
            </form>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5>Fechas</h5>
    </div>
    <div class="card-body">
        <form method="post" action="{{route('horarioforo',Crypt::encrypt($foro->id))}}" class="form-center">
            @csrf
            <div class="form-group">
                <div class="form-group row">
                    <div class="form-group col-xl-3">
                        <label>Fecha</label>
                        <input type="date" name="fecha" class="form-control" min="<?php $hoy = date('Y-m-d');
                                                                                    echo $hoy; ?>" />
                        {!! $errors->first('fecha','<span class="text-danger">:message</span>')!!}
                    </div>
                    <div class="form-group col-xl-3">
                        <label>Hora de inicio</label>
                        <input type="time" name="hora_inicio" class="form-control" min="07:00" max="18:00" />
                        {!! $errors->first('hora_inicio','<span class="text-danger">:message</span>')!!}
                    </div>
                    <div class="form-group col-xl-3">
                        <label>Hora de finalización</label>
                        <input type="time" name="hora_termino" class="form-control" min="07:00" max="18:00" />
                        {!! $errors->first('hora_termino','<span class="text-danger">:message</span>')!!}
                    </div>
                    <div class="form-group col-xl-3">
                        <label for="">&nbsp;</label>
                        <button type="submit" class="form-control btn btn-primary btn-sm">Guardar</button>
                    </div>
                </div>
            </div>
        </form>
        @php
        $fechas = $foro->fechas()->orderBy('fecha')->get();
        @endphp
        @if($fechas->count()>0)
        <div class="table-responsive">
            <table class="table table-hover table-borderless table-sm justify-content-center">
                <thead class="thead-light">
                    <tr>
                        <th><i class="fas fa-calendar-week"></i></th>
                        <th><i class="fas fa-hourglass-start"></i></th>
                        <th><i class="fa fa-hourglass-end"></i></th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $posicion = 0;
                    @endphp
                    @foreach($fechas as $fecha)
                    @php
                    $receso = $fecha->receso()->get();
                    //dd($receso);
                    @endphp
                    <tr class="row-fechas">
                        <td id="fecha-{{$fecha->id}}">{{$fecha->fecha}}</td>
                        <td id="hora_inicio-{{$fecha->id}}">{{date('H:i', strtotime($fecha->hora_inicio))}}</td>
                        <td id="hora_termino-{{$fecha->id}}">{{date('H:i', strtotime($fecha->hora_termino))}}</td>
                        <td>
                            <div class="btn-group">
                                <form method="post"
                                    action="{{route('eliminarhorarioforo',[Crypt::encrypt($foro->id),Crypt::encrypt($fecha->id)])}}">
                                    @csrf @method('delete')
                                    <button type="submit" onclick="return confirm('¿Desea eliminar el registro?')" class="btn btn-danger btn-sm"><i
                                            class="fa fa-trash"></i></button>

                                </form>
                                <button class="btn btn-primary btn-sm" data-target="#receso-{{$fecha->id}}"
                                    data-toggle="collapse" aria-expanded="false"
                                    aria-controls="receso-{{$fecha->id}}"><i class="fa fa-coffee"></i></button>
                                <button class="btn btn-warning btn-sm edit-fecha" value="{{Crypt::encrypt($fecha->id)}}" type="button"
                                    data-toggle="modal" data-target="#edit-fecha"><i class="fa fa-edit"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" class="notPadding">
                            <div id="receso-{{$fecha->id}}" class="collapse">
                                <div class="row">
                                    @foreach($fecha->horarioIntervalos($foro->duracion,1) as $key => $itemHoras)
                                    <div class="col-sm-6 col-md-4 col-lg-3 col-xl-3">
                                        <input fecha-foro="{{Crypt::encrypt($fecha->id)}}" type="checkbox"
                                            class="checkItemHoras" name="itemHoras" id="check-{{$posicion}}"
                                            value="{{$posicion}}"
                                            {{$receso->contains('hora',$itemHoras)==true ? 'checked' : ''}}>
                                        <label for="check-{{$posicion}}" class="valueItemHoras">
                                            {{$itemHoras}}
                                           </label>
                                    </div>
                                    @php
                                    $posicion += 1;
                                    @endphp
                                    @endforeach
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>        
        @php
            $receso = $foro->fechas()->with('receso')->get()->pluck('receso')->flatten()->count();
        @endphp
        {{-- @dd($posici, (($posicion)- $receso) * $foro->num_aulas,$foro->proyectos()->where('participa',1)->count()) --}}
        @if ((($posicion) - $receso) * $foro->num_aulas < $foro->proyectos()->where('participa',1)->count())
        <div class="alert alert-danger">No hay suficientes espacios de tiempo para asignar todos los proyectos</div>
        @endif
        @endif
    </div>
</div>



<div id="edit-fecha" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">

        <div class="modal-content">
            <div class="modal-header">
                <h5>Editar fecha del foro</h5>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    ¡ADVERTENCIA!
                    Tendrá que elegir de nuevo las horas de descanso y la de los maestros participantes
                </div>
                <input type="hidden" name="id" id="id-fecha">
                <div class="form-group">
                    <label for="fecha">Fecha</label>
                    <input id="fecha" class="form-control" type="date" name="fecha" min="<?php echo date('Y-m-d'); ?>"
                        value="">
                </div>
                <div class="form-group">
                    <label for="hora_inicio">Text</label>
                    <input id="hora_inicio" class="form-control" type="time" name="hora_inicio">
                </div>
                <div class="form-group">
                    <label for="hora_termino">Text</label>
                    <input id="hora_termino" class="form-control" type="time" name="hora_termino">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary btn-sm actualizar-fecha">Guardar</button>
                <button class="btn btn-warning btn-sm" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5>Maestros</h5>
    </div>
    <div class="card-body">
        <div class="row mb-5">
            @php
            $maestros = $foro->users()->get();

            @endphp
            @foreach($docentes as $docente)
            <div class="form-check col-lg-4 col-xl-4">
                <input id-foro="{{Crypt::encrypt($foro->id)}}" class="checkItemDocenteForo" id="jurado-{{$docente->id}}"
                    type="checkbox" name="docente" value="{{Crypt::encrypt($docente->id)}}"
                    {{$maestros->contains($docente->id) == true ? 'checked' : ''}}>
                <label for="jurado-{{$docente->id}}">{{$docente->nombre}} {{$docente->apellidoP}}
                    {{$docente->apellidoM}}</label>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif
<!-- </div>
</div> -->

@endsection


@push('scripts')
<script>

  
    
</script>
@endpush