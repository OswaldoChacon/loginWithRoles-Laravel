@extends('home.docente')
@section('content')

<div class="card">
    <h5 class="card-header">
        Proyectos como {{$titulo}}
    </h5>
    <div class="card-body">
        {{-- @if (is_null($proyectos['items']))
            <div class="alert alert-info">No tienes ningún proyecto asignado como {{$titulo}}</div>
        @else --}}
        <div class="table-responsive">
            <table class="table table-sm table-hover">
                <thead>
                    <th>Folio</th>
                    <th>Proyecto</th>
                    <th>Empresa</th>
                    <th>T.P.</th>
                    <th>Linea de inv.</th>
                    <th>Acciones</th>
                </thead>
                <tbody>
                    @foreach ($proyectos as $proyecto)
                    <tr>
                        <td>{{$proyecto->folio}}</td>
                        <td>{{$proyecto->titulo}}</td>
                        <td>{{$proyecto->empresa}}</td>
                        <td>{{$proyecto->tipos_proyectos['nombre']}}</td>
                        <td>{{$proyecto->lineadeinvestigacion()->first()->nombre}}</td>
                        <td>
                            <div class="btn-group" role="group" aria-label="Button group">
                                <button type="button" class="btn btn-info btn-sm" data-toggle="collapse"
                                    data-target="#detalles" aria-expanded="false" aria-controls="detalles"><i
                                        class="fas fa-info-circle"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6">
                            <div class="collapse" id="detalles">
                                <div><span> <strong>Objetivo:</strong> {{$proyecto['objetivo']}}</span></div>
                                <div><span> <strong>Integrantes:</strong> {{$proyecto['objetivo']}}</span></div>
                                {{-- <div><span><strong> Registró:</strong> {{$notificacion->user_emisor()->first()->getFullName()}}</span>
                            </div> --}}
        </div>
        </td>
        </tr>
        @endforeach

        </tbody>
        </table>
    </div>
    {!! $proyectos->links() !!}
    {{-- @endif --}}

</div>
</div>

@endsection