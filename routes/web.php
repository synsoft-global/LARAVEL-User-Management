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

/*************Authentication Routes starts************/

Route::middleware('guest')->group(function () {
	Route::get('/', 'Auth\LoginController@loginform');
	Auth::routes();
});
Route::get('logout', 'Auth\LoginController@logout');

//Auth::routes(['login' => false]);
/*************Authentication Routes Ends************/

Route::middleware('auth')->group(function () {

	Route::post('manage_user', 'Web\UserController@add_user_create');
	Route::get('users', 'Web\UserController@list_users');
	Route::post('getPaginatedUser', 'Web\UserController@getPaginatedUser');
	Route::post('validateEmail', 'Web\UserController@getValidateEmail');
	Route::delete('manage_user', 'Web\UserController@delete_user');
	Route::get('manage_user', 'Web\UserController@edit_user');
	Route::post('update_password', 'Web\UserController@update_user_password');

});
