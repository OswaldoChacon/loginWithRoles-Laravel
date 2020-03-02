@extends('home.oficina')
@section('content')
<div class="card">
    <h5 class="card-header">{{$url}}</h5>
    <div class="card-body">
        @if (Session::has('success'))
            <div class="alert alert-success" id="alert-fade">{{Session::get('success')}}</div>
        @endif
        <div class="table-responsive mb-3">
            <table class="table table-hover table-sm">
                <thead>
                    <tr>
                        <th># de control</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Confirmado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($usuarios as $usuario)
                    <tr>
                        <td class="num_control-{{$usuario->num_control}}">{{$usuario->num_control}}</td>
                        <td class="nombre-{{$usuario->num_control}}">
                            @php
                                if($usuario->nombre==null || $usuario->apellidoP==null || $usuario->apellidoM == null)
                                    echo '<span class="text-danger">Datos incompletos</span>';
                                else
                                    echo $usuario->getFullName()
                            @endphp 
                            
                        </td>
                        <td class="email-{{$usuario->num_control}}">{{$usuario->email}}</td>
                        <td>
                            @if ($usuario->confirmado)
                            <i class="fas fa-check-circle"></i>
                            @else
                            <i class="fas fa-times"></i>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group" aria-label="Button group">
                            <form action="{{route('eliminarusuario',Crypt::encrypt($usuario->id))}}">
                                <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('¿Desea eliminar el registro?')"><i
                                    class="fa fa-trash"></i></button>
                            </form>                            
                                <button class="btn btn-warning btn-sm get-user" data-toggle="modal"
                                    data-target="#usuario-modal" value="{{Crypt::encrypt($usuario->id)}}"><i
                                        class="fa fa-edit"></i></button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>            
        </div>
        {!! $usuarios->links() !!}
    </div>
</div>

<div id="usuario-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="my-modal-title">Información del usuario</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="num_control">Número de control</label>
                    <input type="number" class="form-control" name="num_control" id="num_control">
                </div>
                <div class="form-group">
                    <label for="nombre">Nombre</label>
                    <input type="text" class="form-control uppercase" name="nombre" id="nombre">
                </div>
                <div class="row form-group">
                    <div class="col-6">
                        <label for="apellidoP">Apellido paterno</label>
                        <input type="text" class="form-control uppercase" name="apellidoP" id="apellidoP">
                    </div>
                    <div class="col-6">
                        <label for="apellidoM">Apellido materno</label>
                        <input type="text" class="form-control uppercase" name="apellidoM" id="apellidoM">
                    </div>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" name="email" id="email">
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-primary btn-sm actualizar-usuario">Guardar</button>
                <button class="btn btn-warning btn-sm" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $('.get-user').click(function(){        
        $(".loaderContainer").addClass('active');
        var id = $(this).val();
            $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "get",
            url: "getUsuario/"+ $(this).val(),            
            dataType: "json",
            success: function (response) {                
                $(".loaderContainer").removeClass('active');
                $('input[name="email"').val(response.email);
                $('input[name="nombre"').val(response.nombre);
                $('input[name="apellidoP"').val(response.apellidoP);
                $('input[name="apellidoM"').val(response.apellidoM);
                $('input[name="num_control"').val(response.num_control);
                $('.actualizar-usuario').val(id);                
                $('.actualizar-usuario').attr('id',response.num_control);                
            },
            error: function(error) {
            $(".loaderContainer").removeClass('active');
            $(".messageContainer").addClass('active');
            $(".messageContainer .message .icon").html('');
            $(".messageContainer .message .icon").append('<i class="fas fa-envelope"></i>');
            $(".messageContainer .message .title p").text('¡Error!');
            $(".messageContainer .message .description p").text('Ocurrió un error al intentar completar la petición.');
            setTimeout(() => {
                $(".messageContainer").removeClass('active');
            }, 2000);
        }
        });
    });
    $('.actualizar-usuario').click(function(e){
        //$(".loaderContainer").addClass('active');
        $('.text-danger').remove();
        var id = $(this).val();
        var old_num_control = $(this).attr('id');
        var new_num_control = $('input[name="num_control"]').val();       
        $.ajax({
            headers:{
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "put",
            url: "actualizarUsuario/"+$(this).val(),
            data: {
                nombre: $('input[name="nombre"]').val(),
                apellidoP:$('input[name="apellidoP"]').val(),
                apellidoM:$('input[name="apellidoM"]').val(),
                email: $('input[name="email"]').val(),
                num_control: $('input[name="num_control"]').val(),
                // prefijo: $('input[name="prefijo"]').val() 
            },            
            success: function () {
               // $(".loaderContainer").removeClass('active');                
                $('.modal-body').prepend('<div class="alert alert-success">Registro actualizado</div>');
                $('td.num_control-'+old_num_control).html($('input[name="num_control"]').val());
                $('td.nombre-'+old_num_control).html($('input[name="nombre"]').val()+" "+$('input[name="apellidoP"]').val()+" "+$('input[name="apellidoM"]').val());
                $('td.email-'+old_num_control).html($('input[name="email"]').val());     
                $('td.num_control-'+old_num_control).removeAttr('class').addClass('num_control-'+new_num_control);
                $('td.nombre-'+old_num_control).removeAttr('class').addClass('nombre-'+new_num_control);
                $('td.email-'+old_num_control).removeAttr('class').addClass('email-'+new_num_control);
                //$('input[name="num_control"]').val()
                $('.alert-success').delay(1000).fadeOut('slow');
            },
            error: function(error){
                var er = error.responseJSON.errors;                                
                $.each(er, function(name, message) {
                    $('input[name=' + name + ']').after('<span class="text-danger">' + message + '</span>');
                });
                $('.text-danger').delay(3000).fadeOut();
                $(".loaderContainer").removeClass('active');
                // $(".messageContainer").addClass('active');
                // $(".messageContainer .message .icon").html('');
                // $(".messageContainer .message .icon").append('<i class="fas fa-envelope"></i>');
                // $(".messageContainer .message .title p").text('¡Error!');
                // $(".messageContainer .message .description p").text('Ocurrió un error al intentar completar la petición.');
                // setTimeout(() => {
                //     $(".messageContainer").removeClass('active');
                // }, 2000);   
            }
        });
        //e.preventDefault();
    });  
    
</script>
@endpush