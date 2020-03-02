@extends('home.oficina')
@section('content')
<div class="card">
    <h5 class="card-header">Horario del jurado</h5>
    <div class="card-body">        
        @if (is_null($foro))
            <div class="alert alert-danger">No existe ningún foro activo</div>
        @elseif($foro->fechas()->count() == 0)
            <div class="alert alert-info">No se han definido las fechas para el foro actual </div>

        @elseif ($docentes->count() == 0)
            <div class="alert alert-info">No existe ningún jurado para el actual foro</div>       
    
        @else
        <div class="table-responsive">
            <table class="table table-striped table-hover table-sm table-docentes">
                <thead>
                    <tr>
                        <th>No. Control</th>
                        <th>Nombre</th>
                        <th>Fechas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($docentes as $docente)
                    <tr>
                        <td>{{$docente->num_control}}</td>
                        <td>{{$docente->getFullName()}}</td>
                        <td>
                            @php
                            $posicion = 0;
                            $recesoDocente = $docente->horarios()->get();
                            //dd($recesoDocente);
                            @endphp
                            @foreach ($foro->fechas()->get() as $fecha)
                            <ul class="list-unstyled components">
                                <li>
                                    <a href="#fecha-{{$fecha->id}}-{{$docente->id}}" data-toggle="collapse"
                                        aria-expanded="false" class="dropdown-toggle">Editar horarios de fecha
                                        {{$fecha->fecha}}</a>
                                    <ul id="fecha-{{$fecha->id}}-{{$docente->id}}" class="collapse list-unstyled">
                                        @foreach($fecha->horarioIntervalos($foro->duracion,1) as $key => $itemHoras)

                                        @if (!$fecha->receso()->get()->contains('hora',$itemHoras))
                                        <li>
                                            <div class="inputContainer" style="width: 180px; height: 26px;">
                                                <input type="checkbox" fecha-foro="{{$fecha->id}}" id="{{$docente->id}}"
                                                    value="{{$posicion}}" class="checkHorarioJurado"
                                                    name="checkHorarioJurado"
                                                    {{$recesoDocente->contains('posicion',$posicion) == true ? 'checked' : ''}}>
                                                <small>{{$itemHoras}}</small>
                                            </div>

                                        </li>
                                        @endif
                                        @php
                                        $posicion += 1;
                                        @endphp
                                        @endforeach
                                    </ul>
                                </li>
                            </ul>
                            @endforeach
                            <div id="my-collapse" class="collapse">
                                @foreach ($foro->fechas()->get() as $fecha)
                                <div>
                                    <button class="btn btn-primary" data-target="#fechas-{{$fecha->id}}"
                                        data-toggle="collapse" aria-expanded="false"
                                        aria-controls="fechas-{{$fecha->fecha}}">{{$fecha->fecha}}</button>
                                </div>
                                <div id="fechas-{{$fecha->id}}" class="collapse">
                                    s
                                </div>
                                @endforeach
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
   
</script>

@endpush