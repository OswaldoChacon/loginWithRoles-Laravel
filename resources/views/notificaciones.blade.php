@extends(strpos(Request::url(),'Oficina') !== false ? 'home.oficina': strpos(Request::url(),'Docente') !== false ? 'home.docente':'home.alumno')
@section('content')
<style>
    form {
        margin-block-end: 0px;
    }

    span {
        margin-left: 20px;
    }
</style>
<div class="card">
    <h5 class="card-header">Notificaciones</h5>
    <div class="card-body">
        @if(Session::has('success'))
        <div class="alert alert-success" id="alert-fade">{{Session::get('success')}}</div>
        @endif
        @if(Session::has('error'))
        <div class="alert alert-danger" id="alert-fade">{{Session::get('error')}}</div>
        @endif
        @php
        $notificaciones = Auth::user()->notificaciones_receptor()->whereNull('respuesta')->get();
        @endphp
        @if($notificaciones->count() >0)
        <div class="table-responsive">
            <table class="table table-sm table-hover">
                <thead>
                    <th>Registró</th>
                    <th>Proyecto</th>
                    <th>Acciones</th>
                </thead>
                <tbody>
                    @foreach($notificaciones as $notificacion)
                    <tr>
                        <td>{{$notificacion->user_receptor['nombre']}}</td>
                        <td>{{$notificacion->proyecto['titulo']}}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <form action="{{route('notificacionesResponse',Crypt::encrypt($notificacion->id))}}" method="POST">
                                    @csrf {{method_field('PUT')}}                                    
                                    <button name="response" class="btn btn-success btn-sm" value="1"><i class="fas fa-thumbs-up"></i></button>
                                    <button name="response" class="btn btn-danger btn-sm" value="0"><i class="fas fa-thumbs-down"></i></button>                                    
                                </form>
                                <button type="button" class="btn btn-info btn-sm" data-toggle="collapse" data-target="#detalles" aria-expanded="false" aria-controls="detalles"><i class="fas fa-info-circle"></i></button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <div class="collapse" id="detalles">
                                <div><span>Objetivo: {{$notificacion->proyecto['objetivo']}}</span></div>
                                <div><span>Linea de investigacion: {{$notificacion->proyecto->lineadeinvestigacion['nombre']}}</span></div>
                                <div><span>Empresa: {{$notificacion->proyecto['empresa']}}</span></div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center">
            <i class="fas fa-exclamation-triangle" style="color: red"></i><span>Sin notificaciones pendientes.</span>
        </div>

        @endif


        <div class="text-center mt-5 mb-3">
            <button class="btn btn-primary btn-sm" data-toggle="collapse" data-target="#respondidas" aria-expanded="false" aria-controls="respondidas">Respondidas</button>
        </div>
        <div class="collapse" id="respondidas">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <th>Registró</th>
                        <th>Proyecto</th>
                        <th>Respuesta</th>
                        <th>Acciones</th>
                    </thead>
                    <tbody>
                        @foreach(Auth::user()->notificaciones_receptor()->whereNotNull('respuesta')->get() as $notificacion)
                        <tr>
                            <td>{{$notificacion->user_receptor['nombre']}}</td>
                            <td>{{$notificacion->proyecto['titulo']}}</td>
                            <td>
                                @php
                                if($notificacion->respuesta == 1)
                                echo 'Aceptado';
                                else
                                echo 'Rechazado';
                                @endphp
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <form action="{{route('notificacionesResponse',Crypt::encrypt($notificacion->id))}}" method="POST">
                                        @csrf {{method_field('PUT')}}
                                        @if($notificacion->respuesta != 1)
                                        <button name="response" class="btn btn-success btn-sm" value="1"><i class="fas fa-thumbs-up"></i></button>
                                        @else
                                        <button name="response" class="btn btn-danger btn-sm" value="0"><i class="fas fa-thumbs-down"></i></button>
                                        @endif


                                    </form>
                                    <button type="button" class="btn btn-info btn-sm" data-toggle="collapse" data-target="#detalles" aria-expanded="false" aria-controls="detalles"><i class="fas fa-info-circle"></i></button>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4">
                                <div class="collapse" id="detalles">
                                    <div><span>Objetivo: {{$notificacion->proyecto['objetivo']}}</span></div>
                                    <div><span>Linea de investigacion: {{$notificacion->proyecto->lineadeinvestigacion['nombre']}}</span></div>
                                    <div><span>Empresa: {{$notificacion->proyecto['empresa']}}</span></div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>




</div>
</div>
@endsection