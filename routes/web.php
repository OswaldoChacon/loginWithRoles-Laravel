<?php




Route::get('/', function () {
    return redirect('/login');
});

Route::group(['middleware'=>'auth'],function(){
    Route::put('responseNotification/{id}','UsersController@notificaciones')->name('notificacionesResponse');


    Route::put('actualizar-password/{usuario}','UsersController@actualizar_password');
    //mi perfil
    

    Route::get('notificaciones', function () {
        return view('notificaciones');
    })->name('notificaciones');


    //Route::put('actualizar-info','UsersController@actualizar_info');
});

Route::get('/registro/verificacion/{cod_confirmacion}', 'Auth\RegisterController@verificacion')->name('verificacion');

// Route::match(['get', 'post'],'/login',

Route::get('/login', 'Auth\LoginController@showLoginForm')->name('loginView')->middleware('guest');

Route::get('/logout', 'Auth\LoginController@logout')->name('logout');





Route::post('/login', 'Auth\LoginController@login')->name('login');
Route::get('/email',function(){
    return view('mail.confirmacion');
});

Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');

Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

// Route::group( ['middleware' => 'post'],function()
// {

// });
