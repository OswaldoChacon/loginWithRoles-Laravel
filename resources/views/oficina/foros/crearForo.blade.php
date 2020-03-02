@extends('home.oficina')

@section('content')

<div class="card">
    <h5 class="card-header">Crear foro</h5>
    <div class="card-body">
        @if (Session::has('message'))
        <div class="alert alert alert-danger">{{ Session::get('message') }}</div>
        @endif
        @if(Session::has('success'))
        <div class="alert alert-success" id="alert-fade">{{Session::get('success')}}</div>
        @endif
        <form method="post" action="{{ route('guardarForo') }}" class="form-center">
            {{csrf_field()}}
            <div class="row">
                <div class="form-group col-md-2">
                    <label for="name">Numero</label>
                    <input class="form-control" type="text" name="no_foro" placeholder="Ejemplo: 1">
                    <!-- {!! $errors->first('noforo','<span class="help-block alert alert-danger">:message</span>')!!} -->
                    @if ($errors->has('no_foro'))
                    <span class="text-danger">{{ $errors->first('no_foro') }}</span>
                    @endif
                </div>
                <div class="form-group col-md-10">
                    <label for="name">Titulo</label>
                    <input class="form-control uppercase" type="text" name="nombre" value="FORO DE PROPUESTAS DE PROYECTOS PARA TITULACIÓN INTEGRAL">
                    <!-- {!! $errors->first('titulo','<span class="help-block alert alert-danger">:message</span>')!!} -->
                    @if ($errors->has('nombre'))
                    <span class="text-danger">{{ $errors->first('nombre') }}</span>
                    @endif
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-md-6">
                    <select class="form-control" name="periodo">
                        <option disabled selected>Perido</option>
                        <option value="Enero-Junio">Enero - Junio</option>
                        <option value="Agosto-Diciembre">Agosto - Diciembre</option>                        
                    </select>
                    <!-- {!! $errors->first('periodo','<span class="help-block alert alert-danger">:message</span>')!!} -->
                    @if ($errors->has('periodo'))
                    <span class="text-danger">{{ $errors->first('periodo') }}</span>
                    @endif
                </div>
                <div class="form-group col-md-6">
                    <select class="form-control" name="anio">
                        <option disabled selected>Año</option>
                        {{$year=date('Y')}}
                        @foreach (range($year,$year+2) as $anio)
                        <option value="{{$anio}}">{{$anio}}</option>
                        @endforeach
                    </select>
                    <!-- {!! $errors->first('anoo','<span class="help-block alert alert-danger">:message</span>')!!} -->
                    @if ($errors->has('anio'))
                    <span class="text-danger">{{ $errors->first('anio') }}</span>
                    @endif
                </div>
            </div>
            <br>
            <button type="submit" class="btn btn-primary btn-sm" value="Registrar" name="">Guardar</button>
        </form>
    </div>
</div>



@endsection