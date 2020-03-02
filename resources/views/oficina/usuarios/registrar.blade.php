@extends(strpos(Request::url(),'Oficina') !== false ? 'home.oficina':'home.docente')
@section('content')

<div class="card">
    <h5 class="card-header">Registrar usuario</h5>
    <div class="card-body">
        @if(Session::has('success'))
        <div class="alert alert-success" id="alert-fade">{{Session::get('success')}}</div>
        @endif
        @if(Session::has('error'))
        <div class="alert alert-danger" id="alert-fade">{{Session::get('error')}}</div>
        @endif
        <form action="{{route(strpos(Request::url(),'Oficina') !== false ? 'registrarOficina':'registrarDocente')}}" method="POST">
            @csrf
            <div class="form-row">
                <div class="form-group col-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                    <label for="num_control">Número de control</label>
                    <input id="num_control" class="form-control" type="text" name="num_control" placeholder="Número de control">
                    {!! $errors->first('num_control','<span class="text-danger">:message</span>')!!}
                </div>
                <div class="form-group col-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                    <label for="email">Email</label>
                    <input id="email" class="form-control" type="email" name="email" placeholder="Email">
                    @if ($errors->has('email'))
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                    @endif
                </div>
                <div class="form-group col-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                    <label for="roles">Rol</label>
                    <select class="form-control" name="rol" id="roles">
                        <!-- btn dropdown-toggle -->
                        {{-- <option value="">Seleccione un rol</option> --}}
                        @foreach($roles as $role)
                        <option value="{{Crypt::encrypt($role->id)}}" class="dropdown-item">{{$role->nombre}}</option>
                        @endforeach
                    </select>
                    {!! $errors->first('rol','<span class="text-danger">:message</span>')!!}
                </div>
                <div class="form-group col-4 col-sm-4 col-md-4 col-lg-4 col-xl-4 alumno-name">
                    <label for="nombre">Nombre</label>
                    <input id="nombre" class="form-control uppercase" type="text" name="nombre" placeholder="Nombre">
                    {!! $errors->first('nombre', '<span class="text-danger">:message</span>')!!}
                </div>
                <div class="form-group col-4 col-sm-4 col-md-4 col-lg-4 col-xl-4 alumno-name">
                    <label for="apellidoP">Apellido paterno</label>
                    <input id="apellidoP" class="form-control uppercase" type="text" name="apellidoP" placeholder="Apellido paterno">
                </div>
                <div class="form-group col-4 col-sm-4 col-md-4 col-lg-4 col-xl-4 alumno-name">
                    <label for="apellidoM">Apellido materno</label>
                    <input id="apellidoM" class="form-control uppercase" type="text" name="apellidoM" placeholder="Apellido materno">
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
        </form>
    </div>
</div>
@endsection

@push('scripts')

<script>
    // $('select#roles').change(function(){        
    //     if($('option:selected').text() == 'Alumno'){            
    //         var form = `            
    //             <div class="form-group col-4 col-sm-4 col-md-4 col-lg-4 col-xl-4 alumno-name">
    //                 <label for="email">Nombre</label>
    //                 <input class="form-control" type="text" name="nombre" placeholder="Nombre">
    //             </div>
    //             <div class="form-group col-4 col-sm-4 col-md-4 col-lg-4 col-xl-4 alumno-name">
    //                 <label for="email">Apellido paterno</label>
    //                 <input class="form-control" type="text" name="apellidoP" placeholder="Apellido paterno">
    //             </div>
    //             <div class="form-group col-4 col-sm-4 col-md-4 col-lg-4 col-xl-4 alumno-name">
    //                 <label for="email">Apellido materno</label>
    //                 <input class="form-control" type="text" name="apellidoM" placeholder="Apellido materno">
    //             </div>

    //         `
    //         $('.form-row').append(form);
    //     }
    //     else
    //         $('.alumno-name').remove();

    // });

  
</script>

@endpush