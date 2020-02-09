<?php

use App\Http\Controllers\OficinaController;
use Illuminate\Support\Facades\Crypt;

Route::group(['middleware' => 'admin'], function () {
    Route::get('Oficina', function () {
        return view('home.oficina');
    })->name('Oficina');


    //lineas de investigacion
    Route::get('Oficina/lineasdeinvestigacion', 'OficinaController@lineasdeinvestigacion')->name('lineas');
    Route::post('Oficina/lineadeinvestigacionguardar', 'OficinaController@lineadeinvestigacionguardar')->name('lineasdeinvestigacionguardar');
    Route::delete('Oficina/lineadeinvestigacioneliminar/{linea}', 'OficinaController@lineadeinvestigacioneliminar')->name('lineadeinvestigacioneliminar');
    Route::put('Oficina/lineadeinvestigacionactualizar/{linea}', 'OficinaController@lineadeinvestigacionactualizar')->name('lineadeinvestigacionactualizar');

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

    //horario
    Route::post('Oficina/horarioforo/{foro}','OficinaController@horarioforo')->name('horarioforo');
    Route::delete('Oficina/horarioforo/{foro}/eliminar/{horario}','OficinaController@eliminarhorarioforo')->name('eliminarhorarioforo');

    //foro_docente
    Route::post('Oficina/foroDocente/{id}', 'OficinaController@agregarForo_Docente')->name('foroDocente');
    Route::delete('Oficina/foroDocente/{foro}/eliminar/{docente}','OficinaController@eliminarForo_Docente')->name('foroDocenteDelete');

    //registrar_usuario
    Route::get('Oficina/registrar', function () {
        $roles = App\Roles::all();
        return view('registrar', compact('roles'));
    })->name('registrarView');
    Route::post('Oficina/registrar', 'UsersController@create')->name('registrar');

    //jurado
    Route::get('Oficina/proyectos_jurado', function () {
        $foro = App\Foros::where('acceso', 1)->first();
        $docentes = App\Roles::where('nombre', 'Docente')->first()->users()->get();
        return view('oficina.jurado.jurado', compact('foro','docentes'));
    })->name('jurado');

    Route::get('Oficina/proyectos_jurado/{proyecto}', function ($id) {
        $proyecto = App\Proyectos::find(Crypt::decrypt($id));
        $docentes = App\Roles::where('nombre', 'Docente')->first()->users()->get();
        return view('oficina.jurado.asignar_jurado', compact('proyecto', 'docentes'));
    })->name('asignarjurado');

    Route::post('Oficina/asignar_jurado/{proyecto}', 'OficinaController@asignarJurado')->name('asignarJuradoPOST');
    Route::delete('Oficina/proyectos_jurado/{proyecto}/eliminar_jurado/{docente}', 'OficinaController@eliminarJurado')->name('eliminarJurado');

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


    Route::view('Oficina/fechasForo','oficina.foros.fechas',
    ['foro'=> App\Foros::where('acceso',true)->first()]);
});
