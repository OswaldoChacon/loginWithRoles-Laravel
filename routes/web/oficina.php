<?php

use App\Http\Controllers\OficinaController;
use Illuminate\Support\Facades\Crypt;

Route::group(['middleware' => 'admin'], function () {
    Route::get('Oficina', function () {
        return view('home.oficina');
    })->name('Oficina');

    //usuarios
    Route::get('Oficina/alumnos',function(){
        $url = 'Alumnos';
        $usuarios = App\Roles::where('nombre','Alumno')->first()->users()->paginate(20);
        return view('oficina.usuarios.users',compact('url','usuarios'));
    })->name('alumnosView');

    Route::get('Oficina/docentes',function(){
        $url = 'Docentes';
        $usuarios = App\Roles::where('nombre','Docente')->first()->users()->paginate(20);        
        return view('oficina.usuarios.users',compact('url','usuarios'));
    })->name('docentesView');

    Route::get('Oficina/getUsuario/{usuario}','OficinaController@getUsuario');
    Route::put('Oficina/actualizarUsuario/{usuario}','OficinaController@actualizarUsuario');

    Route::get('Oficina/eliminarusuario/{usuario}','OficinaController@eliminarusuario')->name('eliminarusuario');
    //lineas de investigacion
    Route::get('Oficina/lineasdeinvestigacion', 'OficinaController@lineasdeinvestigacion')->name('lineas');
    Route::get('Oficina/getlineadeinvestigacion/{linea}','OficinaController@getlineadeinvestigacion');
    Route::post('Oficina/lineadeinvestigacionguardar', 'OficinaController@lineadeinvestigacionguardar')->name('lineasdeinvestigacionguardar');
    Route::delete('Oficina/lineadeinvestigacioneliminar/{linea}', 'OficinaController@lineadeinvestigacioneliminar')->name('lineadeinvestigacioneliminar');
    Route::put('Oficina/lineadeinvestigacionactualizar/{linea}', 'OficinaController@lineadeinvestigacionactualizar')->name('lineadeinvestigacionactualizar');


    Route::get('Oficina/tipos_proyectos', 'OficinaController@tipos_proyectos')->name('tipos_proyectos');
    Route::post('Oficina/registrar_tipos_proyectos','OficinaController@registrar_tipos_proyectos')->name('registrar_tipos_proyectos');
    Route::delete('Oficina/eliminar_tipos_proyectos/{tipo}','OficinaController@eliminar_tipos_proyectos')->name('eliminar_tipos_proyectos');
    Route::get('Oficina/get_tipos_proyectos/{tipo}','OficinaController@get_tipos_proyectos')->name('get_tipos_proyectos');
    Route::put('Oficina/actualizar_tipos_proyectos/{tipo}','OficinaController@actualizar_tipos_proyectos')->name('actualizar_tipos_proyectos');

    //foros
    Route::get('Oficina/foros', 'OficinaController@foros')->name('foros');
    Route::get('Oficina/crearforo', function () {
        return view('oficina.foros.crearForo');
    })->name('crearforo');
    Route::post('Oficina/guardarForo', 'OficinaController@guardarForo')->name('guardarForo');
    Route::put('Oficina/desactivarForo/{foro}', 'OficinaController@desactivarForo')->name('desactivarForo');
    Route::put('Oficina/activarForo/{foro}', 'OficinaController@activarForo')->name('activarForo');
    Route::delete('Oficina/eliminarForo/{foro}', 'OficinaController@eliminarForo')->name('eliminarForo');
    Route::get('Oficina/configurarForo/{foro}', 'OficinaController@configurarForoView')->name('configurarForoView');
    Route::put('Oficina/configurarForo/{foro}', 'OficinaController@configurarForo')->name('configurarForo');
    Route::get('Oficina/foro/proyectos/{foro}', 'OficinaController@proyectos_foro')->name('proyectosForo');

    //horario más bien es fecha-foro
    Route::post('Oficina/horarioforo/{foro}','OficinaController@horarioforo')->name('horarioforo');
    Route::delete('Oficina/horarioforo/{foro}/eliminar/{horario}','OficinaController@eliminarhorarioforo')->name('eliminarhorarioforo');

    //foro_docente esta en la petición ajax, considerar eliminar
    Route::post('Oficina/foroDocente/{id}', 'OficinaController@agregarForo_Docente')->name('foroDocente');
    Route::delete('Oficina/foroDocente/{foro}/eliminar/{docente}','OficinaController@eliminarForo_Docente')->name('foroDocenteDelete');

    //registrar_usuario
    Route::get('Oficina/registrar', function () {
        $roles = App\Roles::orderBy('nombre')->get();
        return view('oficina.usuarios.registrar', compact('roles'));
    })->name('registrarView');
    Route::post('Oficina/registrar', 'OficinaController@create')->name('registrarOficina');

    //jurado uno de ellos no funciona, creo que es el segundo tampoco este que era la vcista
    Route::get('Oficina/proyectos_jurado', function () {
        $foro = App\Foros::where('acceso', 1)->first();
        $docentes = App\Roles::where('nombre', 'Docente')->first()->users()->get();
        return view('oficina.jurado.jurado', compact('foro','docentes'));
    })->name('jurado');

    // Route::get('Oficina/proyectos_jurado/{proyecto}', function ($id) {
    //     $proyecto = App\Proyectos::find(Crypt::decrypt($id));
    //     $docentes = App\Roles::where('nombre', 'Docente')->first()->users()->get();
    //     return view('oficina.jurado.asignar_jurado', compact('proyecto', 'docentes'));
    // })->name('asignarjurado');

    //Route::post('Oficina/asignar_jurado/{proyecto}', 'OficinaController@asignarJurado')->name('asignarJuradoPOST');
    //Route::delete('Oficina/proyectos_jurado/{proyecto}/eliminar_jurado/{docente}', 'OficinaController@eliminarJurado')->name('eliminarJurado');

    //ajax
    Route::post('proyecto/participa', 'OficinaController@proyectoParticipa');
    Route::post('proyecto/asignar_jurado', 'OficinaController@asignarJurado');
    Route::post('proyecto/eliminar_jurado', 'OficinaController@eliminarJurado');

    Route::post('Oficina/foroDocente', 'OficinaController@agregarForo_Docente');
    Route::post('Oficina/foroEliminarDocente', 'OficinaController@eliminarForo_Docente');

    //proyectos participantes tal vez no sirva
    
    //horario
    Route::get('Oficina/horariojurado','OficinaController@horariojuradoView')->name('horariojuradoView');
    Route::post('Oficina/configurarForo/horariobreak','OficinaController@horariobreak');
    Route::post('Oficina/configurarForo/deletehorariobreak','OficinaController@eliminarhorariobreak');
    Route::get('Oficina/configurarForo/editarhorarioforo/{horario_foro}','OficinaController@editarhorarioforo');
    Route::put('Oficina/configurarForo/actualizarhorarioforo/{horario_foro}','OficinaController@actualizarhorarioforo');

    // horario-jurado
    Route::post('Oficina/horariojurado','OficinaController@horariojurado')->name('horariojurado');
    Route::post('Oficina/eliminarhorariojurado','OficinaController@eliminarhorariojurado')->name('eliminarhorariojurado');

    //horario
    Route::get('Oficina/horario','OficinaController@horarioView')->name('horarioView');


    Route::post('Oficina/generarHorarioAnt','OficinaController@generarhorario')->name('generarhorario');
    Route::get('Oficina/proyectosJurado', 'OficinaController@proyectosHorarioMaestros');
   
});

