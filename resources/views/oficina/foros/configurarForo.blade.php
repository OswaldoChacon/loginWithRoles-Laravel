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
                <table class="table table-striped table-hover">
                    <thead>
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
                                    name="lim_alumnos" inputmode="Numero de  foro" style='width:70px; height:25px' />
                                {!! $errors->first('lim_alumnos','<span class="text-danger">:message</span>')!!}
                            </td>
                            <!-- <p id="agregarHora">&nbsp;</p> -->
                        </tr>
                        <tr>
                            <td>Duración de exposición por evento: <strong> {{$foro->duracion}} min </strong></td>
                            <td><input class="form-inline" type="number" name="duracion" value="{{$foro->duracion}}"
                                    class="form-control" min="10" pattern="[0-9]" style='width:70px; height:25px' />
                                {!! $errors->first('duracion','<span class="text-danger">:message</span>')!!}
                            </td>
                        </tr>
                        <tr>
                            <td>Número de aulas a utilizar en el evento: <strong> {{$foro->num_aulas}} </strong>
                            </td>
                            <td><input class="form-inline" type="number" name="num_aulas" value="{{$foro->num_aulas}}"
                                    class="form-control" min="1" max="5" pattern="[0-9]"
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
        $fechas = $foro->fechas()->get();
        @endphp
        @if($fechas->count()>0)

        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="thead-light">
                    <tr>
                        <th>Fecha</th>
                        <th>Hora para el inicio del evento</th>
                        <th>Hora para el final del evento</th>
                        <th></th>
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
                                    aria-controls="receso-{{$fecha->id}}">Elegir receso</button>
                                <button class="btn btn-warning btn-sm edit-fecha" value="{{$fecha->id}}" type="button"
                                    data-toggle="modal" data-target="#edit-fecha"><i class="fa fa-edit"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" class="notPadding">
                            <div id="receso-{{$fecha->id}}" class="collapse">
                                <div class="row">
                                    @foreach($fecha->horarioIntervalos($foro->duracion) as $key => $itemHoras)

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
                    Tendrá que elegir de nuevo las horas para el receso
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
            <div class="form-check col-lg-3 col-xl-3">
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
    $('.checkItemDocenteForo').change(function() {
        $(".loaderContainer").addClass('active');
        var idDocente = $(this).val();
        var idForo = $(this).attr('id-foro');
        var value = $(this).prop('checked') == true ? 1 : 0;
        var url;
        if (value == 1)
            url = '/Oficina/foroDocente';
        else
            url = '/Oficina/foroEliminarDocente';
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            },
            type: 'post',
            url: url,
            data: {
                idForo: idForo,
                idDocente: idDocente,
            },
            success: function() {
                $(".loaderContainer").removeClass('active');
                location.reload();
            },
            error: function(error) {
                var errors = error.responseText;
                var json = error.responseJSON;
                console.log(json.error);
                $(this).prop('checked', false);
                $(".loaderContainer").removeClass('active');
                $(".messageContainer").addClass('active');
                $(".messageContainer .message .icon").html('');
                $(".messageContainer .message .icon").append('<i class="fas fa-envelope"></i>');
                $(".messageContainer .message .title p").text('¡Error!');
                $(".messageContainer .message .description p").text(json.error);
                // 'Ocurrió un error al intentar conectar al servidor. Inténtelo más tarde.'
                setTimeout(() => {
                    $(".messageContainer").removeClass('active');
                }, 1000);
            }
        });

    });
    $('.checkItemHoras').change(function() {
        $(".loaderContainer").addClass('active');
        var valueCheckebox = $(this).prop('checked') == true ? 1 : 0;
        //var horariobreak = valueItemHoras
        var hora = $("label[for='" + this.id + "']").text();
        var posicion = $(this).val();
        var idFecha = $(this).attr('fecha-foro');
        var url;
        if (valueCheckebox == 1)
            url = 'horariobreak';
        else
            url = 'deletehorariobreak';
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            },
            type: 'post',
            url: url,
            data: {
                hora: hora,
                posicion: posicion,
                idFecha: idFecha
            },
            //dataType: "dataType",
            success: function(response) {
                $(".loaderContainer").removeClass('active');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert(jqXHR.status); //500
                alert(textStatus); //error
                alert(errorThrown); //mensaje intersal

                var errors = error.responseText;
                var json = error.responseJSON;
                console.log(json.error);
                $(this).prop('checked', false);
                $(".loaderContainer").removeClass('active');
                $(".messageContainer").addClass('active');
                $(".messageContainer .message .icon").html('');
                $(".messageContainer .message .icon").append('<i class="fas fa-envelope"></i>');
                $(".messageContainer .message .title p").text('¡Error!');
                $(".messageContainer .message .description p").text(json.error);
                // 'Ocurrió un error al intentar conectar al servidor. Inténtelo más tarde.'
                setTimeout(() => {
                    $(".messageContainer").removeClass('active');
                }, 1000);
            }
        });

    });
    $('.edit-fecha').click(function() {
        var idFecha = $(this).val();
        $.ajax({
            type: "get",
            dataType: "json",
            url: "editarhorarioforo/" + idFecha,

            success: function(response) {
                console.log(response.fecha);
                $('#id-fecha').val(response.id);
                $('#fecha').val(response.fecha);
                $('#hora_inicio').val(response.hora_inicio);
                $('#hora_termino').val(response.hora_termino);
                
            }
        });
    });

    $('.actualizar-fecha').click(function(){
        var idFecha = $('#id-fecha').val();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            },
            type: "put",
            url: "actualizarhorarioforo/"+idFecha,
            data: {
                fecha: $('#fecha').val(),
                hora_inicio: $('#hora_inicio').val(),
                hora_termino: $('#hora_termino').val()
            },
            success: function (response) {
                //$('.modal-body').prepend('<div class="alert alert-success" id="alert-fade">Fecha actualizada</div>');
                //setTimeout(function(){
                 //   $('.alert-success').fadeOut("slow",function(){
                  //      $(this).remove();
                    //});
                //},2000);
                location.reload();
            }
        });
    });
   
</script>
@endpush