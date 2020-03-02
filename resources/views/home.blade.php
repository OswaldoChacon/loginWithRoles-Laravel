@extends(strpos(Request::url(),'Oficina') !== false ? 'home.oficina': strpos(Request::url(),'Docente') !== false ?
'home.docente':'home.alumno')
@section('content')
<style>
    .respuestas {
        /* border: 2px solid #148497; */
    }

    .norespuestas {
        /* border: 2px solid red; */
    }

    span {
        /* margin-left: 20px; */
    }

    .mi-info {
        border-bottom: 1px solid #E5DEDC;
        margin-bottom: 20px;
        padding: 5px 20px;
        /* background: red */
    }

    .info {
        color: gray;
    }

    img {
        max-width: 100%;
        height: 100%;
    }
</style>
<div class="row">
    <div class="col-md-6 col-lg-6 col-xl-6">
        <div class="card">
            <h5 class="card-header">Información personal</h5>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6 col-xl-6 d-flex justify-content-center" style="height:200px;">
                        <img src="{{URL::asset('/img/logo_isc.jpg')}}" alt="ISC">
                        <div style="background:red">                        
                        </div>

                    </div>
                    <div class="col-lg-6 col-xl-6">
                        {{-- @if (Auth::user()->hasRole('Alumno'))
                        @include('alumno.info')
                        @else
                        @include('docente.info')
                        @endif --}}
                        <div class="mi-info">
                            <div class="info">Número de control</div>
                            <div class="num_control">{{Auth::user()->num_control}}</div>
                        </div>
                        <div class="mi-info">
                            <div class="info">Nombre</div>
                            <div class="nombre">{{Auth::user()->nombre}}</div>
                        </div>
                        <div class="mi-info">
                            <div class="info">Apellido paterno</div>
                            <div class="apellidoP">{{Auth::user()->apellidoP}}</div>
                        </div>
                        <div class="mi-info">
                            <div class="info">Apellido materno</div>
                            <div class="apellidoM">{{Auth::user()->apellidoM}}</div>
                        </div>
                    </div>                    
                </div>
                <div class="mi-info col-lg-12 col-xl-12">
                    <div class="info">
                        Correo electronico
                    </div>
                    <div class="email">{{Auth::user()->email}}</div>
                </div>             
            </div>
            <div class="card-footer">
                <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editar-info">Editar</button>
                <button class="btn btn-info btn-sm " data-toggle="modal" data-target="#cambiar-contrasenia">Cambiar contraseña</button>
            </div>
        </div>
    </div>
@include('password')

<div id="editar-info" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="my-modal-title">Tu información</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">                                     
                @if (Auth::user()->hasRole('Docente') && strpos(Request::url(),'Docente') !== false)
                    @include('docente.info')
                @endif         
                <div class="form-group">
                    <label for="email">Correo electronico</label>
                    <input type="email" class="form-control" name="email" id="email" value="{{Auth::user()->email}}">
                </div>               
            </div>
            <div class="modal-footer">
            <button class="btn btn-primary btn-sm float-right save-user" value="{{Crypt::encrypt(Auth::user()->id)}}">Guardar</button>
                <button class="btn btn-warning btn-sm" data-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>



    <div class="col-md-6 col-lg-6 col-xl-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">Mensajes</h5>
                @if(Session::has('success'))
                <div class="alert alert-success" id="alert-fade">{{Session::get('success')}}</div>
                @endif
                @if(Auth::user()->notificaciones_emisor()->whereNull('respuesta')->count() >0 ||
                Auth::user()->notificaciones_emisor()->where('respuesta',0)->count() > 0)
                <div class="alert alert-danger mb-3">
                    Proyecto aun no aceptado
                </div>
                <div class="mb-3 norespuestas container"><span class="notResponse">Usuarios pendientes por
                        aceptar</span>
                    @php
                    $emisores
                    =Auth::user()->notificaciones_emisor()->whereNull('respuesta')->orWhere('respuesta',0)->get();
                    @endphp
                    @foreach($emisores as $emisor)
                    <div class="row container">
                        <div class="col col-xl-7 mb-2"><span class="text-danger">{{$emisor->user_receptor()->first()->getFullName()}}</span>
                        </div>
                        <div class="col col-xl-5">
                            @php
                            if($emisor->respuesta ==false && $emisor->respuesta !==null)
                            {
                            echo '<span class="text-danger">Rechazado</span>';
                            }
                            else{
                            echo 'Sin respuesta';
                            }
                            @endphp
                        </div>
                    </div>

                    @endforeach
                </div>
                <!-- <div class="mb-3 respuestas container"><span class="response">Respuestas: </span>
                    @foreach(Auth::user()->notificaciones_emisor()->get() as $emisor)
                    @if($emisor->respuesta != null)
                    <div class="row container">
                        <div class="col col-xl-7 mb-2"><span>{{$emisor->user_receptor['nombre']}} {{$emisor->user_receptor['apellidoP']}} {{$emisor->user_receptor['apellidoM']}}</span></div>
                        <div class="col col-xl-5">
                            @php
                            if($emisor->respuesta == 1){
                            echo 'Aceptado';
                            }
                            else{
                            echo 'Rechazado';
                            }

                            @endphp
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div> -->
                @else
                <div class="mr-4">
                    <i class="fas fa-exclamation-triangle" style="color:red"></i><span class="ml-3">No existen mensajes
                        nuevos.</span>
                </div>

                @endif

            </div>
        </div>


        <div class="card">
            <div class="card-body">

            </div>

        </div>
    </div>
    <div class="col-md-6 col-lg-6 col-xl-6">

    </div>
</div>

@php
global $proyecto_actual;
$proyectos = Auth::user()->proyectos()->get();
foreach($proyectos as $proyecto)
{
if(!$proyecto->foro()->where('acceso',1)->get()->isEmpty())
{
$proyecto_actual = $proyecto;
break;
}
}
@endphp
@if(!is_null($proyecto_actual))
<div class="card">
    <div class="card-body">
        {{$proyecto_actual->titulo}}
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
    $('.save-user').click(function(){        
        $.ajax({
            headers:{
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: "put",
            url: "actualizar-info/"+$(this).val(),
            data: {
                //id: $('input[name="id"]').val(),
                num_control : $('input[name="num_control"]').val(),
                nombre: $('input[name="nombre"]').val(),
                apellidoP: $('input[name="apellidoP"]').val(),
                apellidoM: $('input[name="apellidoM"]').val(),
                email: $('input[name="email"]').val(),
                prefijo: $('input[name="prefijo"]').val(),                
            },            
            success: function () {
                $('.modal-body').prepend('<div class="alert alert-success">Registro actualizado</div>');
                $('.alert-success').delay(1000).fadeOut('slow');
                $('div.num_control').html($('input[name="num_control"]').val());
                $('div.nombre').html($('input[name="nombre"]').val());
                $('div.apellidoP').html($('input[name="apellidoP"]').val());
                $('div.apellidoM').html($('input[name="apellidoM"]').val());
                $('div.email').html($('input[name="email"]').val());
                // $(".loaderContainer").removeClass('active');
                // $(".messageContainer").addClass('active');
                // $(".messageContainer .message .icon").html('');
                // $(".messageContainer .message .icon").append('<i class="fas fa-envelope"></i>');
                // $(".messageContainer .message .title p").text('¡Registro Actualizado!');
                // $(".messageContainer .message .description p").text('Su registro ha sido actualizado correctamente');
                // setTimeout(() => {
                //     $(".messageContainer").removeClass('active');
                // }, 1000);
                
            },
            error: function(error){
                $(".loaderContainer").removeClass('active');             
                var er = error.responseJSON.errors;                     
                $.each(er, function(name, message) {
                    $('input[name=' + name + ']').after('<span class="text-danger">' + message + '</span>');
                });
                $('.text-danger').delay(5000).fadeOut();
                $(".loaderContainer").removeClass('active');
                $(".messageContainer").addClass('active');
                $(".messageContainer .message .icon").html('');
                $(".messageContainer .message .icon").append('<i class="fas fa-envelope"></i>');
                $(".messageContainer .message .title p").text('¡Error!');
                $(".messageContainer .message .description p").text('Ocurrió un error al intentar completar la petición.');
                setTimeout(() => {
                    $(".messageContainer").removeClass('active');
                }, 1000);
            }
        });
    });
</script>

@endpush






