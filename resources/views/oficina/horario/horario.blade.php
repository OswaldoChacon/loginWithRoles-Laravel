@extends('home.oficina')
@section('content')
@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.5.1/css/buttons.dataTables.min.css">
@endpush
<div class="card">
    <h5 class="card-header">Generar horario</h5>
    <div class="card-body">
        @if(is_null($foro))
        <div class="alert alert-info">No hay ningún foro activo</div>
        @else
        <div class="row">
            <!--<div class="form-group col-md-4 col-xl-2 ">
                   <label for="alpha">Alfa</label>
                   </div>-->
            <input type="hidden" name="alpha" class="form-control" value="1">
            <input type="hidden" name="beta" class="form-control" value="2">
            <input type="hidden" name="Q" class="form-control" value="1">
            <input type="hidden" name="evaporation" class="form-control" value="0.1">
            <input type="hidden" name="t_minDenominador" class="form-control" id="test" value="10">
            <div class="form-group col-md-4 col-xl-3">
                <label for="iterations">Número de iteraciones</label>
                <input type="number" name="iterations" class="form-control" value="400">
            </div>
            <div class="form-group col-md-4 col-xl-3">
                <label for="ants">Número de hormigas</label>
                <input type="number" name="ants" class="form-control" value="10">
            </div>
            <div class="form-group col-md-4 col-xl-3">
                <label for="estancado">Número de estancamiento</label>
                <input type="number" name="estancado" class="form-control" value="25">
            </div>
        </div>

        <button type="button" class="btn btn-sm btn-primary " id="generarHorario">Generar horario</button>
        <!-- <button type="submit" class="btn btn-sm btn-primary " id="generarHorari">Generar horario</button>
           </form> -->
        <div class="container no-content" style="margin-top:10px; ">
            <!-- <div class="remove">

           </div> -->
        </div>
        <div class="table-responsive">

            <table class="table table-sm display compact" id="horarioGenerado">
                <thead>
                    <tr>
                        <?php
                            echo ('<th>Fecha</th>');
                            echo ('<th>Hora</th>');
                            echo ('<th class="not-export">Viol. RS</th>');
                            for ($z = 0; $z < $foro->num_aulas; $z++) {
                                echo ('<th>Id Proy.</th>');
                                for ($y = 0; $y < $foro->num_maestros; $y++) {
                                    echo ('<th>Maestro</th>');
                                }
                            }                
                        ?>
                    </tr>
                </thead>
                <tbody id="myTableBody" style="table-layout:fixed">
                </tbody>
            </table>
        </div>


        @endif
        @if (Session::has('message'))
        <div class="alert alert alert-danger" id="alert-fade">({{ Session::get('message') }})</div>
        @endif



    </div>
</div>


@endsection

@push('scripts')
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.0/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.0/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.0/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.0/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.0/js/buttons.colVis.min.js"></script>


<script>
    var columns = document.getElementById('horarioGenerado').rows[0].cells.length
    $("#generarHorario").on('click', function() {
        $('div').remove('.remove');
        var num_maestros = {{ $foro == null ? 0 : $foro->num_maestros}}
        var num_aulas = {!! $foro == null ? 0 : $foro->num_maestros!!}
        var alpha = $('input[name="alpha"]').val();
        var beta = $('input[name="beta"]').val();
        var Q = $('input[name="Q"]').val();
        var evaporation = $('input[name="evaporation"]').val();
        var iterations = $('input[name="iterations"]').val();
        var ants = $('input[name="ants"]').val();
        var estancado = $('input[name="estancado"]').val();
        var t_minDenominador = $('input[name="t_minDenominador"]').val();        
        $(".loaderContainer").addClass('active');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: 'generarHorarioAnt',
            data: {
                alpha: alpha,
                beta: beta,
                Q: Q,
                evaporation: evaporation,
                iterations: iterations,
                ants: ants,
                estancado: estancado,
                t_minDenominador: t_minDenominador
            },
            success: function(data) {
                if(data==null)     {
                    $('.no-content').after('<div class="remove">Verifica<ul><li class="text-danger">Que todos los maestros tengan la misma cantidad de espacio de tiempo como de proyectos</li>'+
                     '<li class="text-danger">Que un proyecto tenga al menos un espacio en comun entre los maestros</span></li>'+
                     '<li class="text-danger">Que exista la suficiente cantidad de espacios de tiempo para asignar todos los proyectos</li></ul>'+
                    '<a href="proyectosJurado" class="btn-primary btn-sm btn">Ver eventos</a></div><br>');
                }               
                $(".loaderContainer").removeClass('active');
                var tableHour = '';
                $.each(data, function(date, dates) {                    
                    tableHour += '<tr>';
                    tableHour += '<td colspan="'+columns+'">'+date+'</td>';
                    for (var z = 0; z < columns-1; z++) {
                        tableHour += '<td style="display:none"></td>';
                    }
                    tableHour += '</tr>';                    
                    $.each(data[date],function(hour,hours){
                        tableHour += '<tr>';
                        tableHour += '<td></td>';
                        tableHour += '<td>' + hour + '</td>';                        
                        $.each(data[date][hour], function(event, events) {                                                        
                            if (data[date][hour][event].length == 0) {
                                for (var z = 0; z < num_maestros+1; z++) {
                                    tableHour += '<td></td>';
                                }
                            }              
                            else if(data[date][hour][event].length == 1){
                                $.each(data[date][hour][event], function(item, items) {
                                    tableHour += '<td>' + data[date][hour][event][item]+ '</td>';                                    
                                });                                
                                
                            }             
                            else 
                            {
                                tableHour += '<td>'+event+'</td>';
                                $.each(data[date][hour][event], function(item, items) {
                                    tableHour += '<td>' + data[date][hour][event][item] + '</td>';
                                
                                });
                            }
                          
                        });
                        tableHour += '</tr>';
                    });                    
                });
                var table = $('#horarioGenerado').DataTable({
                    "language": {
                        "emptyTable": "No se ha podido cargar el horario"
                    },
                    destroy: true,
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "searching": false,
                    "autoWidth": true,
                    dom: 'Bfrtip',
                    "aoColumnDefs": [{
                        "aTargets": ['_all'],
                        "bSortable": false
                    }],
                    // "columns": [],
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            className: "btn btn-primary",
                            messageTop: num_maestros,
                            exportOptions: {
                                columns: ':visible'
                            }                          
                        },
                        {
                            extend: 'pdfHtml5',
                            messageTop: num_maestros,
                            orientation: 'landscape',
                            exportOptions: {                          
                                columns: ':visible:not(.not-export)'
                            }
                        },
                        {
                            extend:'colvis',
                            text: 'Ocultar columna'
                        },
                        {
                            extend:'copy',
                            text: 'Copiar'
                        },
                    ]
                });
                table.clear();                
                table.rows.add($(tableHour)).draw();
            },
            error: function(error) {
                $(".loaderContainer").removeClass('active');             
                var er = error.responseJSON.errors;                                
                $.each(er, function(name, message) {
                    $('input[name=' + name + ']').after('<span class="text-danger">' + message + '</span>');
                });
                $('.text-danger').delay(5000).fadeOut();
                // $(".messageContainer").addClass('active');
                // $(".messageContainer .message .title p").text('¡Error!');
                // $(".messageContainer .message .description p").text('Ocurrió un error al intentar conectar al servidor. Inténtelo más tarde.');
                // setTimeout(() => {
                //     $(".messageContainer").removeClass('active');
                // }, 3000);
            }
        });
    });
</script>

@endpush