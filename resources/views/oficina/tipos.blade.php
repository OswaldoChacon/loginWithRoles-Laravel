@extends('home.oficina')
@section('content')
<div class="row">


    <div class="col-xl-4 col-md-5">
        <div class="card">
            <h5 class="card-header">Registrar tipo de proyecto</h5>
            <form method="post" action="{{route('registrar_tipos_proyectos')}}" class="form-center data-tipo">
                <div class="card-body">
                    {{csrf_field()}}
                    @if (Session::has('success'))
                    <div class="alert alert alert-success" id="alert-fade">{{ Session::get('success') }}</div>
                    @endif
                    @if (Session::has('error'))
                    <div class="alert alert alert-danger" id="alert-fade">{{ Session::get('error') }}</div>
                    @endif
                    <div class="form-group ">
                        <input type="hidden" name="id">
                        <label for="name">Clave</label>
                        <input class="form-control uppercase" type="text" name="clave" placeholder='Clave'>
                        @if ($errors->has('clave'))
                        <span class="text-danger">{{ $errors->first('clave') }}</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="name">Nombre</label>
                        <input class="form-control uppercase" type="text" name="nombre" placeholder='Lineas de Investigacion'>
                        @if ($errors->has('nombre'))
                        <span class="text-danger">{{ $errors->first('nombre') }}</span>
                        @endif
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button type="submit" class="btn btn-primary btn-sm mb-2" value="Registrar">Guardar</button>
                    <button class="btn btn-warning btn-sm clear-form mb-2" onclick="this.form.reset()"
                        type="button">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
    <div class="col-xl-7 col-md-6">
        <div class="card">
            <h5 class="card-header">Tipos de proyectos</h5>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-sm">
                        <thead>
                            <th>Clave</th>
                            <th>Nombre</th>
                            <th>Acciones</th>
                        </thead>
                        <tbody>
                            @foreach ($tipos_proyectos as $tipo)
                            <tr>
                                <td>{{$tipo->clave}}</td>
                                <td>{{$tipo->nombre}}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        @if (!$tipo->proyectos()->count() > 0)
                                        <form action="{{route('eliminar_tipos_proyectos',Crypt::encrypt($tipo->id))}}"
                                            method="POST">
                                            {{ csrf_field() }} {{ method_field('delete') }}
                                            <button type="submit" class="btn btn-danger btn-sm"><i
                                                    class="far fa-trash-alt"></i></button>
                                        </form>
                                        @endif
                                        <button class="btn btn-warning btn-sm edit-tipo"
                                            value="{{Crypt::encrypt($tipo->id)}}"><i class="far fa-edit"></i></button>

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


@push('scripts')
<script>
    $('.edit-tipo').click(function() {
    var idTipo = $(this).val();   
    $('span').remove();
    $.ajax({
        type: "get",
        dataType: "json",
        url: "get_tipos_proyectos/" + idTipo,
        success: function(response) {            
            $('input[name="clave"]').val(response.clave);
            $('input[name="nombre"]').val(response.nombre);           
            $('.data-tipo').append('<input type="hidden" name="_method" value="PUT">');
            $('.data-tipo').attr('action','actualizar_tipos_proyectos/'+idTipo);            
        }
    });    
});

</script>
@endpush