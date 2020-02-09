@extends('home.index')
@section('navRole')
<ul class="list-unstyled components">
    <li>
        <a href="{{route('homeAlumno')}}"> Inicio</a>
    </li>
    <li>
        <a href="{{route('notificacionesAlumno')}}">
            <div class="row">

                <div class="col-9 col-sm-9 col-md-9 col-lg-9 col-xl-9">
                    Notificaciones
                </div>
                @php
                $count = Auth::user()->notificaciones_receptor()->whereNull('respuesta')->count();
                @endphp
                <div class="col-3 col-sm-3 col-md-3 col-lg-3 col-xl-3" style="color:#000;border-radius:10%; background:{{$count > 0 ? 'red' : 'none'}};">
                    <span> {{$count}}</span>
                </div>
            </div>
        </a>
    </li>
    <li>
        <a href="{{route('registrarProyectoView')}}">Registrar Proyecto</a>
    </li>
    <li>
        <a href="{{route('misProyectos')}}">Mis proyectos</a>
    </li>
    <li>
        <a href="#">Horario asignado para exponer</a>
    </li>
    <li>
        <a href="#">Calificación del seminario</a>
    </li>
    <li>
        <a class="list-group-item" href="#">Estado de Proyecto</a>
    </li>
</ul>
@endsection