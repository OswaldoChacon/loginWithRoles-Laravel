@extends('home.oficina')
@section('content')
<div class="card">
    <h5 class="card-header">Horario del jurado</h5>
    <div class="card-body">
        <div class="table-responsive">

        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>#</th>
                </tr>
            </thead>
            <tbody>
                @foreach($docentes as $docente)
                <tr>
                    <td>{{$docente->num_control}}</td>
                    <td>{{$docente->prefijo}} {{$docente->prefijo}} {{$docente->prefijo}} </td>
                    <td>{{$docente->getFullName()}}</td>
                    <td>
                        <button class="btn btn-primary btn-sm" data-target="#my-collapse" data-toggle="collapse" aria-expanded="false" aria-controls="my-collapse">Text</button>
                        <div id="my-collapse" class="collapse">
                            s
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection