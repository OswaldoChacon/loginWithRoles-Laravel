$(document).ready(function() {
    $('#sidebarCollapse').on('click', function() {
        $('#sidebar').toggleClass('active');
    });
});
$(document).ready(function() {            
    $('#alert-fade').delay(2000).fadeOut('fast',function(){
        $(this).remove();
    });
});

$(document).on('click', '.modal .editar', function() {
    // $('.editar').on('click', function() {    
    var element = $(this).parent().parent();
    var idLinea = element.find('input[name="idLinea"]').val();
    var clave = element.find("input[name='clave']").val();
    var nombre = element.find("input[name='nombre']").val();
    // var nombre = $(this).data('')
    // var nombre = $('input[name="nombre"]').val();        
    var token = $("[name='_token']").val();
    $(".loaderContainer").addClass('active');
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': token
        },
        type: 'put',
        url: '/lineadeinvestigacionactualizar',
        data: {
            'idLinea': idLinea,
            'clave': clave,
            'nombre': nombre
        },
        success: function() {
            $(".loaderContainer").removeClass('active');
            location.reload();
        },
        error: function(error) {
            var er = error.responseJSON.errors;
            console.log(er);
            $.each(er, function(name, message) {
                $('.modal-body input[name=' + name + ']').after('<span class="text-danger">' + message + '</span>');
            })
            $(".loaderContainer").removeClass('active');
            setTimeout(() => {
                $('.modal-body').find('span').hide('fade');
            }, 3000);
            // setTimeout(function () { $('#alert-fade').hide("fade"); }, duration);
            // $(".messageContainer").addClass('active');
            // $(".messageContainer .message .title p").text('¡Error!');
            // $(".messageContainer .message .description p").text('Ocurrió un error al intentar conectar al servidor. Inténtelo más tarde.');
            // setTimeout(() => {
            //     $(".messageContainer").removeClass('active');
            // }, 3000);
        }
    });
});