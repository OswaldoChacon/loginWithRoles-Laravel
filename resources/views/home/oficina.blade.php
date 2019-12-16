@extends('home.index')
@section('navRole')
<ul class="list-unstyled components">
    <li>
        <a href="#"> Inicio</a>
    </li>
    <li>
        <a href="">Linea de investigación</a>
    </li>
    <li>
        <a href="">Registrar token para docentes</a>
    </li>
    <li>
        <a href="">Registrar token para alumnos</a>
    </li>
    <li class="active">
        <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Foros</a>
        <ul class="collapse list-unstyled" id="homeSubmenu">
            <li>
                <a href="">Crear Foros</a>
            </li>
            <li>
                <a href="">Foros</a>
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
                <a href="">Asignar horario del jurado</a>
            </li>
            <li>
                <a href="">Proyectos participantes</a>
            </li>
            <li>
                <a href="">Generar horario</a>
            </li>
        </ul>
    </li>
    <li>
        <a href="">Alumnos</a>
    </li>
    <li>
        <a href="">Docentes</a>
    </li>
    <li>
        <a href="">Seminario</a>
    </li>
    <li>
        <a href="">Asignar jurado</a>
    </li>
    <li>
        <a href="">Asignar criterios</a>
    </li>
    <li>
        <a href="">Segumiento De Proyectos</a>
    </li>
</ul>
@endsection