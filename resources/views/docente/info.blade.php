<div class="form-group">
    <label for="num_control">No. Control</label>
    <input type="text" class="form-control" name="num_control" id="num_control" value="{{Auth::user()->num_control}}">
</div>
<div class="form-group">
    <label for="prefijo">Prefijo</label>
    <input id="prefijo" class="form-control uppercase" type="text" name="prefijo" value="{{ strpos(Request::url(),'Docente') !== false ? Auth::user()->prefijo : ''}}">
</div>
<div class="form-group">
    <label for="nombre">Nombre</label>
    <input id="nombre" class="form-control uppercase" type="text" name="nombre" value="{{Auth::user()->nombre}}">
</div>
<div class="row">
    <div class="col-6 form-group">
        <label for="apellidoP">Apellido paterno</label>
        <input id="apellidoP" class="form-control uppercase" type="text" name="apellidoP" value="{{Auth::user()->apellidoP}}">
    </div>
    <div class="col-6 form-group">
        <label for="apellidoM">Apellido materno</label>
        <input id="apellidoM" class="form-control uppercase" type="text" name="apellidoM" value="{{Auth::user()->apellidoM}}">
    </div>
</div>