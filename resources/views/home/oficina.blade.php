@extends('home.index')
@section('navRole')
<ul class="list-unstyled components">
    <li>
        <a href="{{route('Oficina')}}">Inicio</a>
    </li>
    <li class="active">
        <a href="#usuarios" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Usuarios</a>
        <ul class="collapse list-unstyled" id="usuarios">
            <li>
                <a href="{{route('registrarView')}}">Registrar usuario</a>
            </li>
            <li>
                <a href="">Alumnos</a>
            </li>
            <li>
                <a href="">Docentes</a>
            </li>
        </ul>
    </li>    
    <li>
        <a href="{{route('lineas')}}">Linea de investigación</a>
    </li>
    <li class="active">
        <a href="#foros" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Foros</a>
        <ul class="collapse list-unstyled" id="foros">
            <li>
                <a href="{{route('crearforo')}}">Crear Foros</a>
            </li>
            <li>
                <a href="{{route('foros')}}">Foros</a>
            </li>
        </ul>
    </li>
    <li>
        <a href="#menuhorarios" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Horarios</a>
        <ul class="collapse list-unstyled" id="menuhorarios">
            <li>
                <a href="">Proyectos registrados</a>
            </li>
            <li>
                <a href="{{route('horariojuradoView')}}">Asignar horario del jurado</a>
            </li>
            <li>
                <a href="">Generar horario</a>
            </li>
        </ul>
    </li>    
    <li>
        <a href="">Seminario</a>
    </li>
    <li>
        <a href="{{route('jurado')}}">Asignar jurado</a>
    </li>
    <li>
        <a href="">Asignar criterios</a>
    </li>
    <li>
        <a href="">Segumiento de proyectos</a>
    </li>
</ul>
@endsection