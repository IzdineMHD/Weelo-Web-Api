<?php

use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::get('/admin', function () {
    return view('admin');
});

Route::post('/login/admin', 'Auth\LoginController@adminLogin');
Route::get('/login/admin', 'Auth\LoginController@showAdminLoginForm');

Route::post('/register/admin', 'Auth\RegisterController@createAdmin');
Route::get('/register/admin', 'Auth\RegisterController@showAdminRegisterForm');