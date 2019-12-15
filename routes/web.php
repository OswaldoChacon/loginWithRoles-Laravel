<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('home');
})->middleware('auth');


Route::get('/registro/verificacion/{cod_confirmacion}', 'Auth\RegisterController@verificacion')->name('verificacion');

// Route::match(['get', 'post'],'/login',

Route::get('/login', function () {
    return view('login.login');
})->name('loginView')->middleware('guest');

Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/registrar', function () {
    return view('login.registrar');
})->name('register');

Route::post('/login', 'Auth\LoginController@login')->name('login');
Route::post('/registrar', 'Auth\RegisterController@create')->name('registrar');    
    
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');

Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');

// Route::group( ['middleware' => 'post'],function()
// {

// });
