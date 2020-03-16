@extends('home.oficina')
@section('content')
<style>
    .btn-danger {
        font-size: 5px;
    }
</style>
<div class="card">
    <h5 class="card-header">Asignar jurado</h5>
    <div class="card-body">
        @if(is_null($foro))
        <div class="alert alert-danger">No existe ningún foro activo</div>
        @else
        @php
        $proyectos_foro = $foro->proyectos()->where('participa',1)->get();
        @endphp
        @if($proyectos_foro->isEmpty())
        <div class="alert alert-info">No hay ningún proyecto participante para el actual foro</div>
        @else
        <div class="table-responsive table-hover table-sm">
            <table class="table">
                <thead>
                    <th>Folio</th>
                    <th>Nombre del proyecto</th>
                    <th>Empresa</th>
                    <th>Acciones</th>
                </thead>
                <tbody>
                    @foreach($proyectos_foro as $proyecto)
                    <tr>
                        <td>{{$proyecto->folio}}</td>
                        <td>{{$proyecto->titulo}}</td>
                        <td>{{$proyecto->empresa}}</td>                        
                        <td>
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="collapse" data-target="#detalles-{{$proyecto->folio}}" aria-expanded="false" aria-controls="detalles"><i class="fas fa-chalkboard-teacher"></i></button>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5">
                            <div class="row collapse" id="detalles-{{$proyecto->folio}}">
                                @php
                                $jurado = $proyecto->proyecto_jurado()->get();
                                @endphp
                                @foreach($docentes as $docente)
                                <div class="form-check col-12 col-sm-6 col-md-6 col-xl-4" style="font-size:15px">
                                    <input class="checkboxJurado" id-proyecto="{{Crypt::encrypt($proyecto->id)}}" id="jurado-{{$docente->num_control}}-{{$proyecto->folio}}" type="checkbox" name="jurado" value="{{Crypt::encrypt($docente->id)}}" {{$jurado->contains($docente->id) == true ? 'checked' : ''}} />
                                    {{$docente->getFullName()}} 
                                </div>
                                <!-- <label for="jurado-{{$docente->id}}"></label> -->
                                @endforeach
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
        @endif
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
   
var limite = {{$foro == null ? 0 : $foro->num_maestros}}
</script>
@endpush