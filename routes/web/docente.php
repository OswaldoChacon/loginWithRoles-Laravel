<?php

Route::group(['prefix' => 'Docente', 'middleware' => 'docente'], function () {
    Route::get('/', function () {
        return redirect()->route('homeDocente');
        // return view('home.alumno');
    })->name('Docente');

    Route::get('home', function () {
        return view('home');
    })->name('homeDocente');

    //notificaciones
    Route::get('notificaciones','DocenteController@notificaciones')->name('notificacionesDocente');

    //registrar usuario
    Route::get('registrar','DocenteController@registrarView')->name('registrarViewDocente');
    
    Route::post('registrar', 'DocenteController@create')->name('registrarDocente');
    Route::put('actualizar-info/{usuario}','DocenteController@actualizar_info');
    // Route::put('actualizar-password/{usuario}','DocenteController@actualizar_password');

    Route::get('proyectos_jurado',function(){
        $titulo = 'jurado';
        $proyectos = Auth()->user()->jurado_proyecto()->paginate(15);        
        // dd($proyectos);
        return view('docente.misProyectos',compact('titulo','proyectos'));
    })->name('proyectos_jurado');
    // DocenteController@proyectos_jurado
    Route::get('proyectos_asesor',function(){
        $titulo = 'asesor';
        $proyectos = Auth()->user()->asesor()->paginate(15);        
        return view('docente.misProyectos',compact('titulo','proyectos'));
    })->name('proyectos_asesor');
    // DocenteController@proyectos_asesor


});
