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
                    <th>Folio</th>
                    <th>Proyecto</th>
                    <th>Empresa</th>
                    <th>T.P.</th>
                    <th>Linea de inv.</th>                    
                    <th>Calificaciones</th>
                </thead>
                <tbody>
                    @foreach($proyectos as $proyecto)
                    <tr>
                        <td>{{$proyecto->folio}}</td>
                        <td>{{$proyecto->titulo}}</td>
                        <td>{{$proyecto->empresa}}</td>
                        <td>{{$proyecto->tipos_proyectos['nombre']}}</td>
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
        @elseif(Auth::user()->proyectos()->where('aceptado',0)->get())
            <div class="alert alert-danger">Proyecto aun no aceptado</div>
        @else
            <div class="alert alert-danger">No tienes ningun proyecto registrado</div>
        @endif
        </ul>
    </div>
</div>
@endsection