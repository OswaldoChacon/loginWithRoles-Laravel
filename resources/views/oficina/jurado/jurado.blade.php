@extends('home.oficina')
@section('content')
<style>
    .btn-danger {
        font-size: 5px;
    }

    span {
        font-weight: bold;
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
        <div class="alert alert-info">No existe ningún proyecto para el actual foro</div>
        @else
        <div class="table-responsive">
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
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="collapse" data-target="#detalles-{{$proyecto->id}}" aria-expanded="false" aria-controls="detalles">Asignar jurado</button>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="5">
                            <div class="row collapse" id="detalles-{{$proyecto->id}}">
                                @php
                                $jurado = $proyecto->proyecto_jurado()->get();
                                @endphp
                                @foreach($docentes as $docente)
                                <div class="form-check col">
                                    <input id-proyecto="{{$proyecto->id}}" id="jurado-{{$docente->id}}" type="checkbox" name="jurado" value="{{$docente->id}}" {{$jurado->contains($docente->id) == true ? 'checked' : ''}} />
                                    {{$docente->nombre}} {{$docente->apellidoP}} {{$docente->apellidoM}}
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
    var limite = @php if(!is_null($foro)) $foro->num_maestros @endphp
    $(document).on('change', "input[type='checkbox']", function() {
        if ($('input[type=checkbox]:checked').length > limite) {
            $(this).prop('checked', false);
            $('.alert').remove();
            $('.table-responsive').before('<div class="alert alert-danger">Limite de maestros: ' + limite +
                '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                '<span aria-hidden="true"> &times; </span> </button>' +
                '</div>');
        } else {


            // alert($(this).attr('id-proyecto-foro'));
            $(".loaderContainer").addClass('active');
            var idProyecto = $(this).attr('id-proyecto');
            var idDocente = $(this).val();
            var value = $(this).prop('checked') == true ? 1 : 0;
            var url;
            if (value == 1)
                url = '/proyecto/asignar_jurado';
            else
                url = '/proyecto/eliminar_jurado';
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('input[name="_token"]').val()
                },
                type: 'post',
                url: url,
                data: {
                    idProyecto: idProyecto,
                    idDocente: idDocente,
                },

                success: function() {
                    $(".loaderContainer").removeClass('active');
                    // $(".messageContainer").addClass('active');
                    // $(".messageContainer .message .icon").html('');
                    // $(".messageContainer .message .icon").append('<i class="fas fa-envelope"></i>');
                    // $(".messageContainer .message .title p").text('¡Registro Actualizado!');
                    // $(".messageContainer .message .description p").text('Su registro ha sido actualizado correctamente');
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
        }
    });
</script>
@endpush