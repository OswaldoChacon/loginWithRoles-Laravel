@extends('home.alumno')
@section('content')
<div class="card">
    <div class="card-header">
        <h5>Mis proyectos</h5>
    </div>
    <div class="card-body">
        @php
        $proyectos = Auth::user()->proyectos()->where('aceptado',1)->get();
        @endphp
        @if($proyectos->count() > 0)
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <th>Titulo</th>
                    <th>Empresa</th>
                    <th>Linea de investigación</th>
                    <th>Calificaciones</th>
                </thead>
                <tbody>
                    @foreach($proyectos as $proyecto)
                    <tr>
                        <td>{{$proyecto->titulo}}</td>
                        <td>{{$proyecto->empresa}}</td>
                        <td>{{$proyecto->lineadeinvestigacion['nombre']}}</td>
                        <td>
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="collapse" data-target="#calificaciones" aria-expanded="true" aria-controls="calificaciones">Calificaciones</button>
                            <div class="collapse mt-2" id="calificaciones">
                                <div style="color:{{$proyecto->calificacion_foro <70 ? 'red' : 'green'}}; font-weight:bold"><span>Foro: {{$proyecto->calificacion_foro}}</span></div>
                                <div style="color:{{$proyecto->calificacion_seminario <70 ? 'red' : 'green'}}; font-weight:bold"><span>Seminario: {{$proyecto->calificacion_seminario}}</span></div>
                                <div style="color:{{$proyecto->promedio <70 ? 'red' : 'green'}}; font-weight:bold"><span>Promedio: {{$proyecto->promedio}}</span></div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>       
        @else
            <div class="alert alert-danger">No tienes ningun proyecto registrado</div>
        @endif
        </ul>
    </div>
</div>
@endsection