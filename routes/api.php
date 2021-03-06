<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('users', 'Api\Auth\RegisterController@users');
Route::post('store_image', 'Api\Auth\RegisterController@store_image');
Route::get('fetch_image/{id}', 'Api\Auth\RegisterController@fetch_image');
Route::get('activation/{token}', 'Api\Auth\RegisterController@activation');

Route::post('register', 'Api\Auth\RegisterController@register');
Route::post('insert', 'Api\Auth\RegisterController@insert');
Route::post('login', 'Api\Auth\LoginController@login');
Route::post('refresh', 'Api\Auth\LoginController@refresh');

Route::post('create_interest', 'Api\UsersInterestsController@create_interest');
Route::get('get_user_interests', 'Api\UsersInterestsController@get_user_interests');
Route::get('get_all_interests', 'Api\UsersInterestsController@get_all_interests');

Route::post('send_reset', 'Api\Auth\ResetPasswordController@send_reset');
Route::get('find_token/{token}', 'Api\Auth\ResetPasswordController@find_token');
Route::post('reset_password', 'Api\Auth\ResetPasswordController@reset_password');
Route::get('tokens', 'Api\Auth\ResetPasswordController@tokens');

Route::middleware('auth:api')->group( function () {

	Route::get('posts', 'Api\PostController@index');
	Route::get('user', 'Api\Auth\RegisterController@user');
    Route::post('logout', 'Api\Auth\LoginController@logout');

});
