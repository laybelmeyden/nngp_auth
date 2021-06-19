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


Route::get('/', 'MainController@index')->name('welcome');

Auth::routes();
Route::post('/createUser', 'MainController@createUser')->name('createUser');
Route::delete('/deletedUser', 'MainController@deletedUser')->name('deletedUser');

Route::post('/user/import', 'MainController@store')->name('storeUser');
