<?php

Route::group(['prefix' => 'Alumno', 'middleware' => 'alumno'], function () {
    Route::get('/', function () {
        return redirect()->route('homeAlumno');
        // return view('home.alumno');
    })->name('Alumno');
    Route::get('home', function () {
        return view('alumno.index');
    })->name('homeAlumno');

    //proyecto
    Route::get('registrarproyecto', 'AlumnoController@registrarProyectoView')->name('registrarProyectoView');
    Route::post('registrarProyecto/{id}', 'AlumnoController@registrarProyecto')->name('registrarProyecto');
    Route::get('misproyectos', 'AlumnoController@misProyectos')->name('misProyectos');

    Route::get('perfil', function () {
        return view('miPerfil');
    })->name('miPerfilAlumno');

    //notificaciones
    Route::get('notificaciones', function () {
        return view('notificaciones');
    })->name('notificacionesAlumno');
});
