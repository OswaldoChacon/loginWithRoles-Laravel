@extends('login.index')
@section('content')
<div class="col-12 col-sm-7 col-md-6 col-lg-5 col-xl-4 mb-5 mt-n5 mx-auto">
    <div class="card">
        <h5 class="card-header" style="z-index: 1">Iniciar sesión</h5>
        <div class="card-body">
            <div id="alert-fade">
                @if(Session::has('message'))
                <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
            </div>
            <div class="row mb-4">
                <div class="col-6">
                    <img src="{{asset('/img/tecnm.png')}}" class="img-fluid float-left" style="height: 100px">
                </div>
                <div class="col-6">
                    <img src="{{asset('/img/logo.png')}}" class="img-fluid float-right" style="height: 100px;">
                </div>
            </div>
            <form method="POST" action="{{route('login')}}">
                @csrf
                <select name="role" id="role">
                    <option value="admin">Admin</option>
                    <option value="estudiantes">Estudiante</option>
                </select>
                <div class="input-group input-group-sm mb-2 ">
                    <div class="input-group-prepend">
                        <span class="input-group-text">@</span>
                    </div>
                    <input type="email" name="email" class="form-control" placeholder="Email">
                </div>
                {!! $errors->first('email','<span class="text-danger">:message</span>')!!}
                <div class="input-group input-group-sm mb-2">
                    <div class="input-group-prepend">
                        <span class="input-group-text">
                            <i class="fa fa-key icon"></i>
                        </span>
                    </div>
                    <input type="password" name="password" class="form-control" placeholder="Contraseña">
                </div>
                {!! $errors->first('password','<span class="text-danger">:message</span>')!!}
                <button type="submit" class="btn btn-success btn-sm col">Iniciar sesión</button>
            </form>
            <div style="text-align: center; margin-top:20px">
                <a href="{{route('register')}}">Registrarse</a>
            </div>
            <div style="text-align: center; margin-top:20px">
                <a href="{{route('password.request')}}">¿Olvidaste tu contraseña?</a>
            </div>
        </div>
    </div>
</div>
@endsection