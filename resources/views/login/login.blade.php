@extends('login.index')
@section('content')
<div class="col-12 col-sm-7 col-md-6 col-lg-5 col-xl-4 mb-5 mt-n5 mx-auto">
    <div class="card">
        <form method="POST" action="{{route('login')}}">
            @csrf
            <div class="card-header">
                <div class="row">
                    <div class="col-6">
                        <h5>Iniciar sesión</h5>
                    </div>
                    <!-- <div class="col-6 text-right">
                        <div class="dropdowsn">                            
                            <select class="btn" name="role" id="roles">                                
                                @foreach($roles as $role)
                                <option value="{{$role->nombre}}" class="dropdown-item">{{$role->nombre}}</option>
                                @endforeach
                            </select>                            

                        </div>
                    </div> -->
                </div>
            </div>
            <div class="card-body">
                <div id="alert-fade">
                    @if(Session::has('success'))                    
                    <p class="alert alert-success">{{ Session::get('success') }}</p>
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
                <div class="input-group input-group-sm mb-2 ">
                    <div class="input-group-prepend">
                        <span class="input-group-text">@</span>
                    </div>
                    <input type="text" name="num_control" class="form-control" placeholder="Número de control">
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
                <div style="text-align: center; margin-top:20px">
                    <a href="{{route('password.request')}}">¿Olvidaste tu contraseña?</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection