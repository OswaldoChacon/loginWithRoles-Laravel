<?php

Route::group(['prefix' => 'Alumno', 'middleware' => 'alumno'], function () {
    Route::get('/', function () {
        return redirect()->route('homeAlumno');
        // return view('home.alumno');
    })->name('Alumno');

    Route::get('home', function () {
        return view('home');
    })->name('homeAlumno');

    //proyecto
    Route::get('registrarproyecto', 'AlumnoController@registrarProyectoView')->name('registrarProyectoView');
    Route::post('registrarProyecto/{id}', 'AlumnoController@registrarProyecto')->name('registrarProyecto');
    Route::get('misproyectos', 'AlumnoController@misProyectos')->name('misProyectos');


    //notificaciones
    Route::get('notificaciones','UsersController@notificacionesView')->name('notificacionesAlumno');

    Route::put('actualizar-info/{usuario}','AlumnoController@actualizar_info');
});
