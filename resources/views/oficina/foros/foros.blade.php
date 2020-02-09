@extends('home.oficina')
@section('content')
<div class="card">
    <h5 class="card-header">Foros</h5>
    <div class="card-body">
        @if (Session::has('success'))
        <div class="alert alert-success" id="alert-fade">{{ Session::get('success') }}</div>
        @endif
        @if(Session::get('error'))
        <div class="alert alert-danger" id="alert-fade">{{Session::get('error')}}</div>
        @endif
        @foreach ($foros as $foro)
        @if ($foro->acceso==1)
        <div class="alert alert alert-warning">
            <tr>
                <td> Foro activado:
                    {{$foro->no_foro}}
                </td>
            </tr>
        </div>
        @endif
        @endforeach

        <h5 class="panel-title">Total de foros: <span style="font-weight: bold">{{$foros->count()}}</span></h5>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead>
                    <th>Numero </th>
                    <th>Titulo</th>
                </thead>
                <tbody>
                    @foreach ($foros as $foro)
                    <tr style="background-color: {{$foro->acceso == 1 ? '#00b963' : '#e8e8e8'}}">
                        <td>{{$foro->no_foro}}</td>
                        <td>{{$foro->nombre}}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <?php
                                $conteo = 0;
                                // foreach ($foros as $foro) {                       
                                //  dd($foro->acceso);
                                if ($foro->acceso == 1)
                                    $conteo++;
                                // }
                                if ($foro->acceso == 1 && $conteo == 1) {
                                ?>
                                    <form action="{{route('desactivarForo',Crypt::encrypt($foro->id))}}" method="POST">
                                        @csrf
                                        {{method_field('PUT')}}
                                        <button title="Finalizar foro" type="submit" class="btn btn-danger btn-sm"><i class="fa fa-power-off"></i></button>
                                    </form>
                                    <button title="Configurar foro" class="btn btn-success btn-sm" onclick="location.href='configurarForo/{{Crypt::encrypt($foro->id)}}'"><i class="fas fa-cogs"></i></button>
                                    <?php
                                } elseif ($conteo == 0) {
                                    $meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
                                    $fechas_foro = explode("-", $foro->periodo);
                                    $mes_actual = date("m");                                    
                                    if (date("Y") == $foro->anio) {                                                                                 
                                        if (array_search($fechas_foro[0],$meses)+1 <= $mes_actual && $mes_actual <= array_search($fechas_foro[1], $meses) + 1 ) {
                                            // <= $mes_actual && (($mes_actual <= array_search($fechas_foro[1], $meses) + 1) || ($mes_actual >= array_search($fechas_foro[1], $meses) + 1))
                                    ?>
                                            <form action="{{route('activarForo',Crypt::encrypt($foro->id))}}" method="POST">
                                                @csrf
                                                {{method_field('PUT')}}
                                                <button type="submit" title="Iniciar foro" class="btn btn-success btn-sm"><i class="fa fa-power-off"></i></button>
                                            </form>

                                <?php
                                        }
                                    }
                                }
                                ?>                                
                                <a href="{{route('proyectosForo',Crypt::encrypt($foro->id))}}" title="Proyectos" class="btn btn-info btn-sm btn-block"><i class="fas fa-book"></i></a>
                                @if($foro->proyectos()->count() == 0)
                                <form action="{{route('eliminarForo',Crypt::encrypt($foro->id))}}" method="POST">
                                    @csrf
                                    {{method_field('DELETE')}}
                                    <button type="subumit" title="Eliminar foro" class="btn btn-danger btn-sm btnbreak"><i class="fas fa-trash-alt"></i></button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection