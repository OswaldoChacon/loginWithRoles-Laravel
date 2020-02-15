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
                <i class="fas fa-envelope"></i>
            </div>
            <div class="title">
                <p></p>
            </div>
            <div class="description">
                <p></p>
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
            <nav class="navbar navbar-expand-lg navbar-light fixed-top">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-default">
                        <i class="fas fa-align-justify" style="color:#fff"></i>
                    </button>

                    <!-- Arreglar para el boton de usuario -->
                    <!-- <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
						<i class='fas fa-user' style='font-size:24px; color:#fff'></i>

					</button> -->
                    <button class="btn  d-print-inline-block d-lg-none ml-auto dropdown" type="button" data-toggle="dropdown" style="background: transparent !important; border:none;">
                        <span class="username">{!!Auth::user()->getFullName()!!}</span>&nbsp;&nbsp; <i class='fas fa-user' style='font-size:24px; color:#fff'></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" method="POST" >
                            {{csrf_field()}}
                            <i class="fas fa-sign-out-alt"></i><span>Cerrar sesion</span>
                        </a>
                        <a class="dropdown-item" method="POST">
                            {{csrf_field()}}
                            <i class="far fa-edit"></i><span>Editar</span>
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
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="nav navbar-nav ml-auto">
                            <li class="nav-item active">
                                <!-- <strong></strong>{{auth()->user()->name}} -->
                                <!-- <strong>Email:</strong>{{auth()->user()->email}} -->
                                <!-- <strong>nombre:</strong>{{auth()->user()->prefijo}} {{auth()->user()->nombre}} {{auth()->user()->paterno}} {{auth()->user()->materno}} -->
                            </li>
                            <li class="nav-item d-none d-lg-block">
                                <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" style="background: transparent !important; border:none;">
                                    <span class="username">{!!Auth::user()->getFullName()!!}</span>&nbsp;&nbsp;<i class='fas fa-user' style='font-size:24px; color:#fff'></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" method="POTS" href="{{url('logout')}}">
                                        {{csrf_field()}}
                                        <i class="fas fa-sign-out-alt"></i><span>Cerrar sesion</span>
                                    </a>
                                    <a class="dropdown-item" href="{{route(strpos(Request::url(),'Docente') !== false ? 'miPerfilDocente':'miPerfilAlumno')}}">
                                        <i class="far fa-edit"></i><span>Editar</span>
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
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>
    <script src="{{ asset('js/home.js') }}"></script>    
    @stack('scripts')    
</body>

</html>