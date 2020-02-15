@extends('home.oficina')
@section('content')
<div class="row">
    <div class="card col-xl-4 col-md-5">
        <h5 class="card-header">Registrar linea de investigación</h5>
        <div class="card-body">
            <form method="post" action="{{route('lineasdeinvestigacionguardar')}}" class="form-center">
                {{csrf_field()}}
                @if (Session::has('success'))
                <div class="alert alert alert-success" id="alert-fade">{{ Session::get('success') }}</div>
                @endif
                @if (Session::has('error'))
                <div class="alert alert alert-danger" id="alert-fade">{{ Session::get('error') }}</div>
                @endif
                <div class="form-group ">
                    <label for="name">Clave</label>
                    <input class="form-control" type="text" name="clave" placeholder='Clave'>
                    @if ($errors->has('clave'))
                    <span class="text-danger">{{ $errors->first('clave') }}</span>
                    @endif
                </div>
                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input class="form-control" type="text" name="nombre" placeholder='Lineas de Investigacion'>
                    @if ($errors->has('nombre'))
                    <span class="text-danger">{{ $errors->first('nombre') }}</span>
                    @endif
                </div>
                <button type="submit" class="btn btn-primary btn-sm" value="Registrar" name="">Guardar</button>
            </form>
        </div>
    </div>




    <div class="card col-xl-7 col-md-6">
        <h5 class="card-header">Lineas de Investigacion: <span style="font-weight: bold">{{$lineasdeinvestigacion->count()}}</span></h5>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover table-sm">
                    <thead>
                        <th>Clave</th>
                        <th>Nombre</th>
                        <th>Acciones</th>
                    </thead>
                    <tbody>
                        @foreach ($lineasdeinvestigacion as $linea)
                        <tr>
                            <td>{{$linea->clave}}</td>
                            <td>{{$linea->nombre}}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <form action="{{route('lineadeinvestigacioneliminar',Crypt::encrypt($linea->id))}}" method="POST">
                                        {{ csrf_field() }} {{ method_field('delete') }}
                                        <button type="submit" class="btn btn-danger btn-sm"><i class="far fa-trash-alt"></i></a>
                                    </form>
                                
                                    <button class="btn btn-warning btn-sm edit" data-toggle="modal" data-target="#editModal_{{$linea->id}}"><i class="far fa-edit"></i></button>
                                    <div class="modal fade" id="editModal_{{$linea->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">                                                
                                                <form class="form-center" action="{{route('lineadeinvestigacionactualizar',Crypt::encrypt($linea->id))}}" method="POST">
                                                    @csrf {{ method_field('put') }}                                                    
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Editar linea de inv.</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="clave" class="col-form-label">Clave </label>
                                                            <input type="text" name="clave" value="{{$linea->clave}}" class="form-control" />
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="nombre" class="col-form-label">Nombre </label>
                                                            <input type="text" name="nombre" value="{{$linea->nombre}}" class="form-control" />
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Cancelar</button>
                                                        <button type="submit" class="btn btn-primary btn-sm editar">Guardar</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
            </div>
        </div>
    </div>
</div>
<script>
    
</script>


@endsection