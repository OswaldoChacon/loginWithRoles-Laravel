@extends('home.oficina')

@section('content')
<style>
    li:hover {
        /* background: blue; */

    }

    a:active {
        background-color: blue;
    }

    .itemsHours {
        background: #DEDEDE;
    }

    span {
        font-weight: bold;
    }
</style>
<div class="card">
    @if(Session::has('mesage'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('mesage') }}</p>
    @endif
    <h5 class="card-header">Proyectos participantes ({{count($proyectos)}})</h5>
    <div class="card-body">
        @if($aulas < sizeof($proyectos)) <div class="alert-danger">
            <span>No hay suficiente espacios de tiempo para asignar a todos los proyectos. Aguegue más salones o extienda la hora del foro</span>
    </div>
    @endif
    <div class="table-responsive">

        <table class="table">
            <thead>
                <th colspan="2">Folio</th>
                @for($i = 0; $i< $foro->num_maestros; $i++)
                    <th>Maestro</th>
                    @endfor
                    <!-- <th>c</th>
                    <th>d</th> -->
                    <th><span>Esp. de tiempo en común</span></th>
            </thead>
            <tbody>
                @foreach($proyectos as $proyecto)
                <tr>
                    <td colspan="2">{{$proyecto->id}}</td>
                    <!-- <td>{{$proyecto->name}}</td> -->
                    @foreach($proyecto->maestroList as $key => $maestro)
                    <!-- <td>{{$maestro->nombre}}</td> -->
                    <td>
                        <ul class="list-unstyled components">
                            <li>
                                {{$maestro->nombre}}
                            </li>
                            @foreach($intervalosContainer as $keyFechas => $horas)
                            <!-- <ul class="list-unstyled components"> -->
                            <li class="itemsHours">
                                <!-- {{$keyFechas}} -->
                                <a href="#posicion-{{$proyecto->id}}-{{$key}}-{{$keyFechas}}" data-toggle="collapse" aria-expanded="false"><i class="fas fa-calendar-week"></i>: &nbsp;{{$keyFechas}}</a>
                                <ul class="collapse list-unstyled" id="posicion-{{$proyecto->id}}-{{$key}}-{{$keyFechas}}">
                                    @foreach($horas as $keyHoras => $hoursItem)
                                    @foreach($maestro->horario as $itemHorarios)
                                    @if($keyHoras == $itemHorarios)
                                    <li>
                                        <!-- <div class="container"> -->
                                        <span>{{$hoursItem}}</span>
                                        <!-- </div> -->
                                    </li>
                                    @endif
                                    @endforeach
                                    @endforeach
                                </ul>
                            </li>                        
                        @endforeach
                        <span><i class="far fa-clock"></i>: {{count($maestro->horario)}}</span><br>
                        @foreach($cantidadProyectosMA as $item)                        
                        @if($maestro->nombre == $item->nombre)
                        
                        <span><i class="fas fa-book"></i> {{$item->cantidad}}</span>
                        @endif
                        @endforeach
                        </ul>                        
                    </td>
                    @endforeach
                    <td colspan="5">
                        <ul class="list-unstyled components">
                            <li>
                                <!-- {{$maestro->nombre}} ({{count($maestro->horario)}}) -->
                                <!-- <span>Esp. de tiempo en comunsssssssssssssss</span> -->
                                <span>Cantidad: {{count($proyecto->espaciosComun)}}</span>
                            </li>
                            @foreach($intervalosContainer as $keyFechas => $horas)
                            <!-- <ul class="list-unstyled components"> -->
                            <li class="list-unstyled components">
                                <!-- {{$keyFechas}} -->
                                <a class="itemsHours" href="#posicion-{{$proyecto->id}}-{{$keyFechas}}" data-toggle="collapse" aria-expanded="false"><i class="fas fa-calendar-week"></i>: &nbsp;{{$keyFechas}}</a>
                                <ul class="collapse list-unstyled" id="posicion-{{$proyecto->id}}-{{$keyFechas}}">
                                    @foreach($horas as $keyHoras => $hoursItem)
                                    @foreach($proyecto->espaciosComun as $espaciosComunItems)

                                    @if($keyHoras == $espaciosComunItems)
                                    <li>
                                        <!-- <div class="container"> -->
                                        <span>{{$hoursItem}}</span>
                                        <!-- </div> -->
                                    </li>
                                    @endif
                                    @endforeach
                                    @endforeach
                                </ul>
                            </li>
                            <!-- </ul> -->
                            @endforeach
                        </ul>
                    </td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>
</div>
</div>
<!-- <script src="{{asset('js/jquery.js')}}"></script> -->
@endsection