@extends('home.oficina')
@section('content')
<style>
    label {
        margin-right: 12%;
    }
</style>
<div class="card">
    <h5 class="card-header">Asignar jurado</h5>
    <div class="card-body">
        @if($errors->first('jurado'))
        <div class="alert alert-danger" id="alert-fade">{{ $errors->first('jurado') }}</div>
        @endif
        @if(Session::has('error'))
        <div class="alert alert-danger" id="alert-fade">{{ Session::get('error') }}</div>
        @endif
        <form action="{{route('asignarJuradoPOST',Crypt::encrypt($proyecto->id))}}" method="POST">
            @csrf
            <div class="row mb-5">
                @php
                    $jurado = $proyecto->proyecto_jurado()->get();                    
                    $docentes = $docentes->diff($jurado);                    
                @endphp
                @foreach($docentes as $docente)
                <div class="form-check col-lg-3 col-xl-3">
                    <input id="jurado-{{$docente->id}}" type="checkbox" name="jurado[]" value="{{$docente->id}}">
                    <label for="jurado-{{$docente->id}}">{{$docente->nombre}} {{$docente->apellidoP}} {{$docente->apellidoM}}</label>                    
                </div>
                @endforeach

            </div>
            <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    var limite = {{$proyecto->foro()->first()->num_maestros}};
    $('input[type=checkbox]').on('change', function(e) {
        if ($('input[type=checkbox]:checked').length > limite) {
            $(this).prop('checked', false);
            $('.alert').remove();
            $('form').before('<div class="alert alert-danger">Limite de maestros: ' + limite +
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                '<span aria-hidden="true"> &times; </span> </button>' +
                '</div>');
        }
    });
</script>
@endpush