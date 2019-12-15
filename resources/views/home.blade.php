<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" type="image/png" sizes="32x32" href="{{URL::asset('/img/favicon-32x32.png')}}">
    <title>Departamento de proyectos de investigación</title>


    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
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
            <ul class="list-unstyled components">
                @if(Auth::user()->hasRole('admin'))
                <li>
                    <a href="#"> Inicio</a>
                </li>
                @endif
                @if(Auth::user()->hasRole('estudiante'))
                <li>
                    <a href="">Linea de investigación</a>
                </li>
                @endif
                <li>
                    <a href="">Registrar token para docentes</a>
                </li>
                <li>
                    <a href="">Registrar token para alumnos</a>
                </li>
                <li class="active">
                    <a href="#homeSubmenu" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Foros</a>
                    <ul class="collapse list-unstyled" id="homeSubmenu">
                        <li>
                            <a href="">Crear Foros</a>
                        </li>
                        <li>
                            <a href="">Foros</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#menuhorarios" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">Horarios</a>
                    <ul class="collapse list-unstyled" id="menuhorarios">
                        <li>
                            <a href="">Proyectos registrados</a>
                        </li>
                        <li>
                            <a href="">Asignar horario del jurado</a>
                        </li>
                        <li>
                            <a href="">Proyectos participantes</a>
                        </li>
                        <li>
                            <a href="">Generar horario</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="">Alumnos</a>
                </li>
                <li>
                    <a href="">Docentes</a>
                </li>
                <li>
                    <a href="">Seminario</a>
                </li>
                <li>
                    <a href="">Asignar jurado</a>
                </li>
                <li>
                    <a href="">Asignar criterios</a>
                </li>
                <li>
                    <a href="">Segumiento De Proyectos</a>
                </li>
            </ul>
        </nav>
        <div id="content">
            <nav class="navbar navbar-expand-lg navbar-light fixed-top">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-default">
                        <i class="fas fa-align-justify" style="color:#fff"></i>
                    </button>
                    <span style="color:#fff">Oficina de .......</span>
                    <!-- Arreglar para el boton de usuario -->
                    <!-- <button class="btn btn-dark d-inline-block d-lg-none ml-auto" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
						<i class='fas fa-user' style='font-size:24px; color:#fff'></i>

					</button> -->
                    <button class="btn  d-print-inline-block d-lg-none ml-auto dropdown" type="button" data-toggle="dropdown" style="background: transparent !important; border:none;">
                        ss<i class='fas fa-user' style='font-size:24px; color:#fff'></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" method="POTS" href="">
                            {{csrf_field()}}
                            <i class="fas fa-sign-out-alt"></i><span>Cerrar sesion</span>
                        </a>
                        <a class="dropdown-item" method="POST" href="">
                            {{csrf_field()}}
                            <i class="far fa-edit"></i><span>Editar</span>
                        </a>
                    </div>
                    <!--  -->
                    <!--  -->
                    <!--  -->
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="nav navbar-nav ml-auto">
                            <li class="nav-item active">
                                <!-- <strong></strong>{{auth()->user()->name}} -->
                                <!-- <strong>Email:</strong>{{auth()->user()->email}} -->
                                <!-- <strong>nombre:</strong>{{auth()->user()->prefijo}} {{auth()->user()->nombre}} {{auth()->user()->paterno}} {{auth()->user()->materno}} -->
                            </li>
                            <li class="nav-item d-none d-lg-block">
                                <button class="btn btn-secondary dropdown-toggle" type="button" data-toggle="dropdown" style="background: transparent !important; border:none;">
                                    <span>{!!Auth::user()->nombre!!}</span>&nbsp;&nbsp;<i class='fas fa-user' style='font-size:24px; color:#fff'></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" method="POTS" href="{{url('logout')}}">
                                        {{csrf_field()}}
                                        <i class="fas fa-sign-out-alt"></i><span>Cerrar sesion</span>
                                    </a>
                                    <a class="dropdown-item" method="POST" href="">
                                        {{csrf_field()}}
                                        <i class="far fa-edit"></i><span>Editar</span>
                                    </a>
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

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/21cd8d42ed.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    <script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#sidebarCollapse').on('click', function() {
                $('#sidebar').toggleClass('active');
            });
        });
    </script>
</body>

</html>