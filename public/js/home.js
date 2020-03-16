$(document).ready(function() {
    $("#sidebarCollapse").on("click", function() {
        $("#sidebar").toggleClass("active");
    });
});
$(document).ready(function() {
    $("#alert-fade")
        .delay(2000)
        .fadeOut("fast", function() {
            $(this).remove();
        });
});

$(".checkItemDocenteForo").change(function() {
    $(".loaderContainer").addClass("active");
    var idDocente = $(this).val();
    var idForo = $(this).attr("id-foro");
    var value = $(this).prop("checked") == true ? 1 : 0;
    var url;
    if (value == 1) url = "/Oficina/foroDocente";
    else url = "/Oficina/foroEliminarDocente";
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('input[name="_token"]').val()
        },
        type: "post",
        url: url,
        data: {
            idForo: idForo,
            idDocente: idDocente
        },
        success: function() {
            $(".loaderContainer").removeClass("active");
            location.reload();
        },
        error: function() {
            $(".loaderContainer").removeClass("active");
            $(".messageContainer").addClass("active");
            $(".messageContainer .message .icon").html("");
            $(".messageContainer .message .icon").append(
                '<i class="fas fa-envelope"></i>'
            );
            $(".messageContainer .message .title p").text("¡Error!");
            $(".messageContainer .message .description p").text(
                "Ocurrió un error al intentar completar la petición."
            );
            setTimeout(() => {
                $(".messageContainer").removeClass("active");
            }, 2000);
        }
    });
});

$(".checkItemHoras").change(function() {
    $(".loaderContainer").addClass("active");
    var valueCheckebox = $(this).prop("checked") == true ? 1 : 0;
    //var horariobreak = valueItemHoras
    var hora = $("label[for='" + this.id + "']").text();
    var posicion = $(this).val();
    var idFecha = $(this).attr("fecha-foro");
    var urlHorarioBreak;
    if (valueCheckebox == 1) urlHorarioBreak = "horariobreak";
    else urlHorarioBreak = "deletehorariobreak";
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('input[name="_token"]').val()
        },
        type: "post",
        url: urlHorarioBreak,
        data: {
            hora: hora,
            posicion: posicion,
            idFecha: idFecha
        },
        //dataType: "dataType",
        success: function() {
            $(".loaderContainer").removeClass("active");
        },
        error: function(error) {
            var er = error.responseJSON;
            if (er.Error == undefined) er.Error = "Payload inválido";
            $(".loaderContainer").removeClass("active");
            $(".messageContainer").addClass("active");
            $(".messageContainer .message .icon").html("");
            $(".messageContainer .message .icon").append(
                '<i class="fas fa-envelope"></i>'
            );
            $(".messageContainer .message .title p").text("¡Error!");
            $(".messageContainer .message .description p").text(
                "Ocurrió un error al intentar completar la petición. " +
                    er.Error
            );
            setTimeout(() => {
                $(".messageContainer").removeClass("active");
            }, 2000);
        }
    });
});

$(".edit-fecha").click(function() {
    var idFecha = $(this).val();
    $.ajax({
        type: "get",
        dataType: "json",
        url: "editarhorarioforo/" + idFecha,

        success: function(response) {
            $("#id-fecha").val(idFecha);
            $("#fecha").val(response.fecha);
            $("#hora_inicio").val(response.hora_inicio);
            $("#hora_termino").val(response.hora_termino);
        }
    });
});

$(".actualizar-fecha").click(function() {
    var idFecha = $("#id-fecha").val();
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('input[name="_token"]').val()
        },
        type: "put",
        url: "actualizarhorarioforo/" + idFecha,
        data: {
            fecha: $("#fecha").val(),
            hora_inicio: $("#hora_inicio").val(),
            hora_termino: $("#hora_termino").val()
        },
        success: function() {
            location.reload();
        },
        error: function(error) {
            var er = error.responseJSON.errors;
            $.each(er, function(name, message) {
                $(".modal input[name=" + name + "]").after(
                    '<span class="text-danger">' + message + "</span>"
                );
            });
            $(".text-danger")
                .delay(4000)
                .fadeOut();
        }
    });
});

$(".checkboxJurado").change(function() {
    //alert("p");

    // alert($(this).attr('id-proyecto-foro'));
    $(".loaderContainer").addClass("active");
    var idProyecto = $(this).attr("id-proyecto");
    var idDocente = $(this).val();
    var value = $(this).prop("checked") == true ? 1 : 0;
    var checkboxChanged = $(this).attr('id');;
    var url;
    if (value == 1) url = "/proyecto/asignar_jurado";
    else url = "/proyecto/eliminar_jurado";
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('input[name="_token"]').val()
        },
        type: "post",
        url: url,
        data: {
            idProyecto: idProyecto,
            idDocente: idDocente
        },

        success: function() {
            $(".loaderContainer").removeClass("active");
            // $(".messageContainer").addClass('active');
            // $(".messageContainer .message .icon").html('');
            // $(".messageContainer .message .icon").append('<i class="fas fa-envelope"></i>');
            // $(".messageContainer .message .title p").text('¡Registro Actualizado!');
            // $(".messageContainer .message .description p").text('Su registro ha sido actualizado correctamente');
            setTimeout(() => {
                $(".messageContainer").removeClass("active");
            }, 1000);
        },
        error: function(error) {
            var er = error.responseJSON;
            $('#'+checkboxChanged).prop('checked',true);
            $(".loaderContainer").removeClass("active");
            $(".messageContainer").addClass("active");
            $(".messageContainer .message .icon").html("");
            $(".messageContainer .message .icon").append(
                '<i class="fas fa-envelope"></i>'
            );
            $(".messageContainer .message .title p").text("¡Error!");
            $(".messageContainer .message .description p").text(
                "Ocurrió un error al intentar completar la petición. " +
                    er.Error
            );
            setTimeout(() => {
                $(".messageContainer").removeClass("active");
            }, 4000);
        }
    });
});

$(".checkHorarioJurado").change(function() {
    var valueCheckebox = $(this).prop("checked") == true ? 1 : 0;
    $(".loaderContainer").addClass("active");
    var idDocente = $(this).attr("id");
    var idFechaForo = $(this).attr("fecha-foro");
    var hora = $(this)
        .parent()
        .find("small")
        .text();
    var posicion = $(this).val();
    var url;
    if (valueCheckebox == 1) url = "horariojurado";
    else url = "eliminarhorariojurado";
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
        },
        type: "POST",
        url: url,
        data: {
            idDocente: idDocente,
            idFechaForo: idFechaForo,
            hora: hora,
            posicion: posicion
        },
        success: function(response) {
            console.log(response);
            $(".loaderContainer").removeClass("active");
            // $(".messageContainer").addClass('active');
            // $(".messageContainer .message .icon").html('');
            // $(".messageContainer .message .icon").append('<i class="fas fa-envelope"></i>');
            // $(".messageContainer .message .title p").text('¡Registro Actualizado!');
            // $(".messageContainer .message .description p").text('Su registro ha sido actualizado correctamente');
            setTimeout(() => {
                $(".messageContainer").removeClass("active");
            }, 500);
        },
        error: function() {
            $(".loaderContainer").removeClass("active");
            $(".messageContainer").addClass("active");
            $(".messageContainer .message .icon").html("");
            $(".messageContainer .message .icon").append(
                '<i class="fas fa-envelope"></i>'
            );
            $(".messageContainer .message .title p").text("¡Error!");
            $(".messageContainer .message .description p").text(
                "Ocurrió un error al completar la petición."
            );
            setTimeout(() => {
                $(".messageContainer").removeClass("active");
            }, 3000);
        }
    });
});

$(".proyecto-participa").change(function() {
    // alert($(this).attr('id-proyecto-foro'));
    $(".loaderContainer").addClass("active");
    var idProyectoForo = $(this).attr("id-proyecto-foro");
    var value = $(this).prop("checked") == true ? 1 : 0;
    $.ajax({
        headers: {
            "X-CSRF-TOKEN": $('input[name="_token"]').val()
        },
        type: "post",
        // url: '/proyecto/edit-participa',
        url: "/proyecto/participa",
        data: {
            id: idProyectoForo,
            value: value
        },
        success: function() {
            $(".loaderContainer").removeClass("active");
            // $(".messageContainer").addClass('active');
            // $(".messageContainer .message .icon").html('');
            // $(".messageContainer .message .icon").append('<i class="fas fa-envelope"></i>');
            // $(".messageContainer .message .title p").text('¡Registro Actualizado!');
            // $(".messageContainer .message .description p").text('Su registro ha sido actualizado correctamente');
            setTimeout(() => {
                $(".messageContainer").removeClass("active");
            }, 500);
        },
        error: function() {
            $(".loaderContainer").removeClass("active");
            $(".messageContainer").addClass("active");
            $(".messageContainer .message .icon").html("");
            $(".messageContainer .message .icon").append(
                '<i class="fas fa-envelope"></i>'
            );
            $(".messageContainer .message .title p").text("¡Error!");
            $(".messageContainer .message .description p").text(
                "Ocurrió un error al intentar completar la petición."
            );
            setTimeout(() => {
                $(".messageContainer").removeClass("active");
            }, 2000);
        }
    });
});

$(".edit-linea").click(function() {
    var idLinea = $(this).val();
    $("span").remove();
    $.ajax({
        type: "get",
        dataType: "json",
        url: "getlineadeinvestigacion/" + idLinea,
        success: function(response) {
            $('input[name="clave"]').val(response.clave);
            $('input[name="nombre"]').val(response.nombre);
            $(".data-linea").prepend(
                '<input type="hidden" name="_method" value="PUT">'
            );
            $(".data-linea").attr(
                "action",
                "lineadeinvestigacionactualizar/" + idLinea
            );
        }
    });
});

$(document).ready(function() {
    $(".data-linea").trigger("reset");
    $(".data-tipo").trigger("reset");
    // $('#form_id').trigger("reset");
});


$('.uppercase').keyup(function() {
    this.value = this.value.toUpperCase();
});