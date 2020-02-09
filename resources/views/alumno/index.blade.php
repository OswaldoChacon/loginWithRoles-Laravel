@extends('home.alumno')
@section('content')
<style>
    .respuestas {
        border: 2px solid #148497;
    }

    .norespuestas {
        border: 2px solid red;
    }

    span {
        margin-left: 20px;
    }
</style>
<div class="row">
    <div class="col-md-6 col-lg-5 col-xl-5">
        <div class="card">
            <div class="card-body">
                <div class="row mx-auto">

                    <div class="col-md-2 col-lg-2 col-xl-2">
                        <i class="fa fa-user-circle mr-1"></i>
                    </div>
                    <div class="col-xl-10">
                        Nombre                    
                        {{Auth::user()->getFullName()}}
                    </div>

                </div>
                @if(is_null(Auth::user()->nombre) || is_null(Auth::user()->apellidoP) || is_null(Auth::user()->apellidoM))
                <span style="color:red">Datos incompletos.</span>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-6 col-xl-6">
        <div class="card">
            <div class="card-body">
                @if(Session::has('success'))
                <div class="alert alert-success" id="alert-fade">{{Session::get('success')}}</div>
                @endif                
                @if(Auth::user()->notificaciones_emisor()->whereNull('respuesta')->count() >0 || Auth::user()->notificaciones_emisor()->where('respuesta',0)->count() > 0)                
                <div class="alert alert-danger">
                    Proyecto aun no aceptado
                </div>
                <div class="mb-3 norespuestas container"><span class="notResponse">Usuarios pendientes por aceptar</span>
                    @php
                    $emisores =Auth::user()->notificaciones_emisor()->whereNull('respuesta')->orWhere('respuesta',0)->get();
                    @endphp
                    @foreach($emisores as $emisor)
                    <div class="row container">
                        <div class="col col-xl-7 mb-2"><span>{{$emisor->user_receptor['nombre']}} {{$emisor->user_receptor['apellidoP']}} {{$emisor->user_receptor['apellidoM']}}</span></div>
                        <div class="col col-xl-5">
                            @php
                            if($emisor->respuesta ==false && $emisor->respuesta !==null)
                            {
                            echo 'Rechazado';
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
                <i class="fas fa-exclamation-triangle" style="color:red"></i><span>No existen mensajes nuevos.</span>
                @endif

            </div>
        </div>
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