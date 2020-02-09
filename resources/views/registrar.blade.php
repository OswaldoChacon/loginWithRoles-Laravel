@extends(strpos(Request::url(),'Oficina') !== false ? 'home.oficina':'home.docente')
@section('content')

<div class="card">
    <h5 class="card-header">Registrar usuario</h5>
    <div class="card-body">
        <form action="{{route('registrar')}}" method="POST">
            @csrf
            <div class="form-row">
                <div class="form-group col-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                    <label for="numControl">Número de control</label>
                    <input class="form-control" type="number" name="num_control" placeholder="Número de control">
                    {!! $errors->first('num_control','<span class="text-danger">:message</span>')!!}
                </div>
                <div class="form-group col-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                    <label for="email">Email</label>
                    <input class="form-control" type="email" name="email" placeholder="Email">
                    @if ($errors->has('email'))
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                    @endif
                </div>
                <div class="form-group col-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
                    <label for="roles">Rol</label>
                    <select class="form-control" name="rol" id="roles">
                        <!-- btn dropdown-toggle -->
                        @foreach($roles as $role)
                        <option value="{{$role->nombre}}" class="dropdown-item">{{$role->nombre}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
        </form>
    </div>
</div>
@endsection