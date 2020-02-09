pppp
@php
$fechas = $foro->fechas()->get();
@endphp
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
                            <button type="submit" class="btn btn-danger btn-sm"><i
                                    class="fa fa-trash"></i></button>

                        </form>
                        <button class="btn btn-primary btn-sm" data-target="#receso-{{$fecha->id}}"
                            data-toggle="collapse" aria-expanded="false"
                            aria-controls="receso-{{$fecha->id}}">Text</button>
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
                                    {{$itemHoras}}</label>
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