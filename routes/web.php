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

Route::get('login', ['as' => 'login',	'uses' => 'Auth\LoginController@showLoginForm']);
Route::post('login', ['as' => 'login.go',	'uses' => 'Auth\LoginController@login']);

Route::group(['middleware' => ['auth']], function () {
    Route::get('/', function () {
        return Inertia\Inertia::render('Landing');
    })->name('home');

    Route::post('logout', ['as' => 'logout',	'uses' => 'Auth\LoginController@logout']);

    Route::get('change-password', ['as' => 'change-password',  'uses' => 'Auth\ChangePasswordController@showForm']);
    Route::patch('change-password', ['as' => 'change-password.go', 'uses' => 'Auth\ChangePasswordController@update']);
});
