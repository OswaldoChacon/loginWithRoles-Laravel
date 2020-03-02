<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{URL::asset('/img/favicon-32x32.png')}}">
    <title>Departamento de proyectos de investigación</title>


    <!-- <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">        
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet"> -->

    <!-- Bootstrap CSS CDN -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <!-- Our Custom CSS	 -->
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{asset('css/alerts.css')}}">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    @stack('styles')

</head>

<body>
    <div class="loaderContainer">
        <div class="loader"></div>
    </div>
    <div class="messageContainer">
        <div class="message">
            <div class="icon">
                {{-- <i class="fas fa-envelope"></i> --}}
            </div>
            <div class="title">
                <p></p>
            </div>
            <div class="description">
                <p class="container"></p>
            </div>
        </div>
    </div>
    <div class="wrapper">
        <!-- Sidebar  -->
        <nav id="sidebar">
            <div class="sidebar-header">
            </div>
            @yield('navRole')
        </nav>
        <div id="content">
            @php
            $notificaciones = App\Notificaciones::whereHas(
            'proyecto.foro',function($query){
            $query->where('acceso',1);
            }
            )->where('receptor',Auth::user()->id)->whereNULL('respuesta')->get();

            $notificacionesRespondidas = App\Notificaciones::whereHas(
            'proyecto.foro',function($query){
            $query->where('acceso',1);
            }
            )->where('receptor',Auth::user()->id)->get();
            @endphp

            <nav class="navbar navbar-expand-lg navbar-light fixed-top">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-default">
                        <i class="fas fa-align-justify" style="color:#fff"></i>
                    </button>
                    <div class="dropdown ml-auto">
                        <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-badge="{{Auth::user()->notificaciones_receptor()->whereNull('respuesta')->count()}}" class="d-block btn btn-outline  d-lg-none  dropdown badge-notification "><i class="fas fa-bell" style='font-size:20px; color:#fff'></i></button>
                        <div class="dropdown-menu dropdown-menu-right mt-3 d-lg-none notificaciones-dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <div class="notificaciones-menu">
                                <span>Notificaciones</span>
                            </div>
                            @if ($notificaciones->count() >0)
                            @foreach ($notificaciones as $notificacion)
                            <div class="dropdown-item" style="border-bottom: 1px solid #EFE7E5">
                                <div class="row" style="overflow:hidden;
                                text-overflow:ellipsis;">                                    
                                    <div class="col-12">
                                        <span style="font-size:12px">Registró: {{$notificacion->user_emisor()->first()->getFullName()}}</span><br>
                                        <span style="font-size:12px">{{$notificacion->proyecto['titulo']}}</span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @else
                            <div class="dropdown-item notificaciones-contenido">
                                <span>Sin notificaciones a</span>
                            </div>
                            @endif
                            <div class="mt-3 d-flex justify-content-center">
                                @if (strpos(Request::url(),'Docente') !== false)
                                <a role="button" href="{{route('notificacionesDocente')}}" class="btn btn-primary btn-sm notificationes-button-more">{{$notificaciones->count() > 0 ? 'Responder..':'Ver más...'}}</a>
                                @elseif (strpos(Request::url(),'Alumno') !== false)
                                <a role="button" href="{{route('notificacionesAlumno')}}" class="btn btn-primary btn-sm notificationes-button-more">{{$notificaciones->count() > 0 ? 'Responder..':'Ver más...'}}</a>
                                @else
                                <a role="button" href="{{route('notificaciones')}}" class="btn btn-primary btn-sm notificationes-button-more">{{$notificaciones->count() > 0 ? 'Responder..':'Ver más...'}}</a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="dropdown">
                        <button class="btn d-print-inline-block d-lg-none  dropdown" type="button" data-toggle="dropdown" style="background: transparent !important; border:none;">
                            <i class='fas fa-user' style='font-size:20px; color:#fff'></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-right mt-3 d-lg-none " aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" method="POST">
                                {{csrf_field()}}
                                <i class="fas fa-sign-out-alt"></i><span>Cerrar sesion</span>
                            </a>
                            <?php
                            $roles = Auth::user()->roles->toArray();
                            if (sizeof($roles) > 1) {
                                echo '<div class="dropdown-divider"></div>';
                                foreach ($roles as $role) {
                            ?>
                                    <a class="dropdown-item" href="{{url($role['nombre'])}}">{{ $role['nombre']}}</a>
                            <?php
                                }
                            }
                            ?>
                        </div>
                    </div>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="nav navbar-nav ml-auto">
                            <li class="nav-item d-none d-lg-block">
                                <div class="dropdown">
                                    <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-badge="{{Auth::user()->notificaciones_receptor()->whereNull('respuesta')->count()}}" class="d-block btn btn-outline badge-notification"><i class="fas fa-bell" style='font-size:20px; color:#fff'></i></button>
                                    <div class="dropdown-menu dropdown-menu-right mt-3 notificaciones-dropdown-menu" aria-labelledby="my-dropdown">
                                        <div class="notificaciones-menu">
                                            <span>Notificaciones</span>
                                        </div>
                                        @if ($notificaciones->count() >0)
                                        @foreach ($notificaciones as $notificacion)
                                        <div class="dropdown-item" style="border-bottom: 1px solid #EFE7E5">
                                            <div class="row" style="overflow:hidden;
                                            text-overflow:ellipsis;">
                                                <div class="col-1" style="background: red">
                                                    s
                                                </div>
                                                <div class="col-11">
                                                    <div ><span style="font-size:12px">Registró: {{$notificacion->user_emisor()->first()->getFullName()}}</span></div>
                                                    <div>                                                        
                                                        <span style="font-size:12px">{{$notificacion->proyecto['titulo']}}</span>
                                                    </div>                                                                                                        
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                        @else
                                        <div class="dropdown-item notificaciones-contenido">
                                            <span>Sin notificaciones a</span>
                                        </div>
                                        @endif


                                        <div class="mt-3 d-flex justify-content-center">
                                            @if (strpos(Request::url(),'Docente') !== false)
                                            <a role="button" href="{{route('notificacionesDocente')}}" class="btn btn-primary btn-sm notificationes-button-more">{{$notificaciones->count() > 0 ? 'Responder..':'Ver más...'}}</a>
                                            @elseif (strpos(Request::url(),'Alumno') !== false)
                                            <a role="button" href="{{route('notificacionesAlumno')}}" class="btn btn-primary btn-sm notificationes-button-more">{{$notificaciones->count() > 0 ? 'Responder..':'Ver más...'}}</a>
                                            @else
                                            <a role="button" href="{{route('notificaciones')}}" class="btn btn-primary btn-sm notificationes-button-more">{{$notificaciones->count() > 0 ? 'Responder..':'Ver más...'}}</a>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            </li>
                            <li class="nav-item d-none d-lg-block">
                                <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" style="background: transparent !important; border:none;">
                                    <i class='fas fa-user' style='font-size:20px; color:#fff'></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right mr-3" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" method="POTS" href="{{url('logout')}}">
                                        {{csrf_field()}}
                                        <i class="fas fa-sign-out-alt"></i><span>Cerrar sesion</span>
                                    </a>
                                    <?php
                                    $roles = Auth::user()->roles->toArray();
                                    if (sizeof($roles) > 1) {
                                        echo '<div class="dropdown-divider"></div>';
                                        foreach ($roles as $role) {
                                    ?>
                                            <a class="dropdown-item" href="{{url($role['nombre'])}}">{{ $role['nombre']}}</a>
                                    <?php
                                        }
                                    }
                                    ?>
                                </div>
                            </li>

                        </ul>
                    </div>
                </div>
            </nav>
            <div class="content-layout">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.4.1.js" integrity="sha256-WpOohJOqMqqyKL9FccASB9O0KwACQJpFTUBLTYOVvVU=" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/21cd8d42ed.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <script src="{{ asset('js/home.js') }}"></script>
    @stack('scripts')
</body>

</html>