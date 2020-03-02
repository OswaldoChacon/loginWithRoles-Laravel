@extends(strpos(Request::url(),'Oficina') !== false ? 'home.oficina': strpos(Request::url(),'Docente') !== false ?
'home.docente':'home.alumno')
@section('content')
<style>
    form {
        margin-block-end: 0px;
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
        @if($notificaciones->count() >0)
        <div class="table-responsive">
            <table class="table table-sm table-hover">
                <thead>
                    <th>Folio</th>
                    <th>Proyecto</th>
                    <th>Empresa</th>
                    <th>T.P.</th>
                    <th>Linea de inv</th>                    
                    <th>Acciones</th>
                </thead>
                <tbody>
                    @foreach($notificaciones as $notificacion)
                    <tr>
                        {{-- <td>{{$notificacion->user_emisor()->first()->getFullName()}}</td> --}}
                        <td>{{$notificacion->proyecto['folio']}}</td>
                        <td>{{$notificacion->proyecto['titulo']}}</td>
                        <td>{{$notificacion->proyecto['empresa']}}</td>              
                        <td>{{$notificacion->proyecto->tipos_proyectos['nombre']}}</td>                  
                        <td>{{$notificacion->proyecto->lineadeinvestigacion['nombre']}}</td>                        
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
                        <td colspan="6">
                            <div class="collapse" id="detalles">
                                <div><span> <strong>Objetivo:</strong> {{$notificacion->proyecto['objetivo']}}</span></div>
                                <div><span><strong> Registró:</strong> {{$notificacion->user_emisor()->first()->getFullName()}}</span></div>                                    
                                {{-- <div><span>Linea de investigacion: {{$notificacion->proyecto->lineadeinvestigacion['nombre']}}</span></div>
                                <div><span>Tipo de proyecto: {{$notificacion->proyecto->tipos_proyectos['nombre']}}</span></div>                                 --}}
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center">
            <i class="fas fa-exclamation-triangle" style="color: red"></i><span class="alerta-icon">Sin notificaciones pendientes.</span>
        </div>

        @endif

        @if (!$notificacionesRespondidas->isEmpty())
        <div class="text-center mt-5 mb-3">
            <button class="btn btn-primary btn-sm" data-toggle="collapse" data-target="#respondidas" aria-expanded="false" aria-controls="respondidas">Respondidas</button>
        </div>
        <div class="collapse" id="respondidas">
            <div class="table-responsive">
                <table class="table table-hover table-sm">
                    <thead>
                        {{-- <th>Folio</th> --}}
                        <th>Proyecto</th>
                        <th>Empresa</th>
                        <th>T.P.</th>
                        <th>Linea de inv</th>                                            
                        {{-- <th>Registró</th> --}}                        
                        <th>Respuesta</th>
                        <th>Acciones</th>
                    </thead>
                    <tbody>
                        @foreach($notificacionesRespondidas as $notificacion)
                        <tr>
                            {{-- <td>{{$notificacion->proyecto['folio']}}</td> --}}
                            <td>{{$notificacion->proyecto['titulo']}}</td>
                            <td>{{$notificacion->proyecto['empresa']}}</td>
                            <td>{{$notificacion->proyecto->tipos_proyectos['nombre']}}</td>                  
                            <td>{{$notificacion->proyecto->lineadeinvestigacion['nombre']}}</td>
                            <td>
                                @php
                                if($notificacion->respuesta == 1)
                                echo 'Aceptado';
                                else
                                echo 'Rechazado';
                                @endphp
                            </td>
                            {{-- <td>{{$notificacion->user_emisor()->first()->getFullName()}}</td> --}}                            
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
                                    <div><span> <strong>Objetivo:</strong> {{$notificacion->proyecto['objetivo']}}</span></div>
                                    <div><span><strong> Registró:</strong> {{$notificacion->user_emisor()->first()->getFullName()}}</span></div>                                    
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif


    </div>




</div>
</div>
@endsection