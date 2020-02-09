<?php

Route::group(['prefix' => 'Docente', 'middleware' => 'docente'], function () {
    Route::get('/', function () {
        return view('home.docente');
    })->name('Docente');

    //notificaciones
    Route::get('notificaciones', function () {
        return view('notificaciones');
    })->name('notificacionesDocente');

    //registrar usuario
    Route::get('registrar', function () {
        $roles = App\Roles::where('nombre', 'Alumno')->get();
        return view('registrar', compact('roles'));
    })->name('registrarViewDocente');
    Route::post('registrar', 'UsersController@create')->name('registrar');

    //mi perfil
    Route::get('perfil', function () {
        return view('miPerfil');
    })->name('miPerfilDocente');
});
