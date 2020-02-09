@extends('home.oficina')
@section('content')
<div class="card">
    <h5 class="card-header">Proyectos</h5>
    <div class="card-body">
        @if($proyectos->isEmpty())
        <div class="alert alert-info">
            No existe ningún proyecto para este foro
        </div>
        @else

        <div class="table-responsive mb-5">
            @csrf
            <table class="table">
                <thead>
                    <th>Folio</th>
                    <th>Titulo</th>
                    <th>Empresa</th>
                    @if($foro->acceso == true)
                    <th>Participa</th>
                    @endif
                </thead>
                <tbody>
                    @foreach($proyectos as $proyecto)
                    @if($proyecto->aceptado)
                    <tr>
                        <td>{{$proyecto->folio}}</td>
                        <td>{{$proyecto->titulo}}</td>
                        <td>{{$proyecto->empresa}}</td>
                        @if($foro->acceso == true)
                        <td>
                            <input id-proyecto-foro="{{$proyecto->id}}" style="width: 22px; height: 22px" type="checkbox" name="status" {{$proyecto->participa  == 0 ?'' : 'checked' }}>
                        </td>
                        @endif
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="row mx-auto mb-4">
            <button class="btn btn-primary btn-sm mx-auto" data-toggle="collapse" data-target="#noAceptados" aria-expanded="false" aria-controls="noAceptados">Proyectos sin aceptar</button>
        </div>
        <div class="collapse table-responsive" id="noAceptados">
            <table class="table">
                <thead>
                    <th>Folio</th>
                    <th>Titulo</th>
                    <th>Empresa</th>
                </thead>
                <tbody>
                    @foreach($proyectos as $proyecto)
                    @if(!$proyecto->aceptado)
                    <td>{{$proyecto->folio}}</td>
                    <td>{{$proyecto->titulo}}</td>
                    <td>{{$proyecto->empresa}}</td>
                    @endif
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).on('change', "input[type='checkbox']", function() {
        // alert($(this).attr('id-proyecto-foro'));
        $(".loaderContainer").addClass('active');
        var idProyectoForo = $(this).attr('id-proyecto-foro');
        var value = $(this).prop('checked') == true ? 1 : 0;
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            },
            type: 'post',
            // url: '/proyecto/edit-participa',
            url: '/proyecto/participa',
            data: {
                id: idProyectoForo,
                value: value
            },
            success: function() {
                $(".loaderContainer").removeClass('active');
                $(".messageContainer").addClass('active');
                $(".messageContainer .message .icon").html('');
                $(".messageContainer .message .icon").append('<i class="fas fa-envelope"></i>');
                $(".messageContainer .message .title p").text('¡Registro Actualizado!');
                $(".messageContainer .message .description p").text('Su registro ha sido actualizado correctamente');
                setTimeout(() => {
                    $(".messageContainer").removeClass('active');
                }, 1000);
            },
            error: function() {
                $(".loaderContainer").removeClass('active');
                $(".messageContainer").addClass('active');
                $(".messageContainer .message .icon").html('');
                $(".messageContainer .message .icon").append('<i class="fas fa-envelope"></i>');
                $(".messageContainer .message .title p").text('¡Error!');
                $(".messageContainer .message .description p").text('Ocurrió un error al intentar conectar al servidor. Inténtelo más tarde.');
                setTimeout(() => {
                    $(".messageContainer").removeClass('active');
                }, 3000);
            }
        });
    });
</script>
@endpush