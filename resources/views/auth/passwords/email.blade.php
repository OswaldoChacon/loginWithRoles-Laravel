@extends('login.index')
@section('content')
<div class="col-12 col-sm-7 col-md-6 col-lg-5 col-xl-4 mb-5 mt-n5 mx-auto">
    <div class="card">
        <h5 class="card-header" style="z-index: 1">Restablecer contraseña</h5>
        <div class="card-body">
            <div id="alert-fade">
                @if(session('status'))
                <div class="alert-success">
                    <span>{{session('status')}}</span>
                </div>
                @endif
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
            <form method="POST" action="{{route('password.email')}}">
                @csrf
                <div class="input-group input-group-sm mb-2 ">
                    <div class="input-group-prepend">
                        <span class="input-group-text">@</span>
                    </div>
                    <input type="email" name="email" class="form-control" placeholder="Email">
                </div>
                {!! $errors->first('email','<span class="text-danger">:message</span>')!!}
                <button type="submit" class="btn btn-success btn-sm col">Enviar correo</button>
            </form>
        </div>
    </div>
</div>
@endsection