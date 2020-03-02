<div id="cambiar-contrasenia" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="my-modal-title"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="my-modal-title">Cambiar contraseña</h5>
                <button class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Password</label>
                    <div class="input-group" id="show_hide_password">
                        <input class="form-control" type="password" name="password" placeholder="Cambiar contraseña"
                            aria-label="text-password" aria-describedby="my-addon" value="">
                        <div class="input-group-append">
                            <a href="" class="input-group-text" id="my-addon"><i class="fa fa-eye-slash"
                                    aria-hidden="true"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary btn-sm change-pass"
                    value="{{Crypt::encrypt(Auth()->user()->id)}}">Guardar</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')

<script>
    $(document).ready(function() {
    $("#show_hide_password a").on('click', function(event) {
        event.preventDefault();
        if($('#show_hide_password input').attr("type") == "text"){
            $('#show_hide_password input').attr('type', 'password');
            $('#show_hide_password i').addClass( "fa-eye-slash" );
            $('#show_hide_password i').removeClass( "fa-eye" );
        }else if($('#show_hide_password input').attr("type") == "password"){
            $('#show_hide_password input').attr('type', 'text');
            $('#show_hide_password i').removeClass( "fa-eye-slash" );
            $('#show_hide_password i').addClass( "fa-eye" );
        }
    });
});
$('.change-pass').click(function() {         
    $.ajax({
    headers:{
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr("content")
    },
    type: "put",
    url: "/actualizar-password/"+$(this).val(),
    data: {
        password: $('input[name="password"]').val()
    },
    success: function (response) {
        $('.modal-body').prepend('<div class="alert alert-success">Registro actualizado</div>');
        $('.alert-success').delay(1000).fadeOut('slow');  
    },
    error: function (error){
        var er = error.responseJSON.errors;
            $.each(er, function(name, message) {
                $("#show_hide_password").after(
                    '<span class="text-danger">' + message + "</span>"
                );
            });
            $(".text-danger")
                .delay(4000)
                .fadeOut();
    }
});    
});

</script>

@endpush