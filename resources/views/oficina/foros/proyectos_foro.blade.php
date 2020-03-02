@extends('home.oficina')
@section('content')
<div class="card">
    <h5 class="card-header">Proyectos</h5>
    <div class="card-body">
        @if($foro->proyectos()->get()->isEmpty())
        <div class="alert alert-info">
            No existe ningún proyecto para este foro
        </div>
        @else
        @if (!$proyectosAceptados->isEmpty())
        <div class="table-responsive mb-5">
            @csrf
            <table class="table table-sm table-hover">
                <thead>
                    <th>Folio</th>
                    <th>Titulo</th>
                    <th>Empresa</th>
                    @if($foro->acceso == true)
                    <th>Participa</th>
                    @endif
                </thead>
                <tbody>
                    @foreach($proyectosAceptados as $proyecto)
                    <tr>
                        <td>{{$proyecto->folio}}</td>
                        <td>{{$proyecto->titulo}}</td>
                        <td>{{$proyecto->empresa}}</td>
                        @if($foro->acceso == true)
                        <td>
                            <input class="proyecto-participa" id-proyecto-foro="{{Crypt::encrypt($proyecto->id)}}" style="width: 22px; height: 22px"
                                type="checkbox" name="status" {{$proyecto->participa  == 0 ?'' : 'checked' }}>
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center mb-4">
            <i class="fas fa-exclamation-triangle" style="color: red"></i><span class="alerta-icon">No existen proyectos aceptados para este foro.</span>
        </div>

        @endif

        <div class="row mx-auto mb-4">
            <button class="btn btn-primary btn-sm mx-auto" data-toggle="collapse" data-target="#noAceptados"
                aria-expanded="false" aria-controls="noAceptados">Proyectos sin aceptar</button>
        </div>

        <div class="collapse table-responsive" id="noAceptados">

            @if ($proyectosPendientes->count() == 0)
            <div class="alert alert-info">
                No hay ningún proyecto pendiente por aceptar
            </div>

            @else
            <table class="table">
                <thead>
                    <th>Folio</th>
                    <th>Titulo</th>
                    <th>Empresa</th>
                </thead>
                <tbody>
                    @foreach($proyectosPendientes as $proyecto)
                    @if(!$proyecto->aceptado)
                    <td>{{$proyecto->folio}}</td>
                    <td>{{$proyecto->titulo}}</td>
                    <td>{{$proyecto->empresa}}</td>
                    @endif
                    @endforeach
                </tbody>
            </table>
            @endif

        </div>

        @endif
    </div>
</div>

@endsection

@push('scripts')
<script>
 
</script>
@endpush