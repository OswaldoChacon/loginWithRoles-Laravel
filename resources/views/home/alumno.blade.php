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
</ul>
@endsection