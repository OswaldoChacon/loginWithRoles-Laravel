@extends('home.index')
@section('navRole')
<ul class="list-unstyled components">
    @if(Auth::user()->foros_user()->where('acceso',1)->count() > 0)
    @endif
    <li>
        <a href="{{route('registrarViewDocente')}}">Registar Alumno</a>
    </li>
    <li>
        <a href="{{route('notificacionesDocente')}}">
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
        <a href="#">Proyectos como asesor</a>
    </li>
    <li>
        <a href="#">Proyectos como jurado</a>
    </li>
    <li>
        <a href="#">Horario asignado del evento Foro</a>
    </li>
    <li>
        <a href="#">Seminario</a>
    </li>
    <li>
        <a href="#">Proyectos</a>
    </li>

    <li>
        <a href="#">Seguimiento de Proyectos</a>
    </li>
</ul>
@endsection