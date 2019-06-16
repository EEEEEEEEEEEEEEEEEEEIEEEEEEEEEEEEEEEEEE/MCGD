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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::post('/home', 'HomeController@save')->name('home');

Route::get('/-{id}', 'DrawController@png')->name('draw');
Route::post('/-/base64', 'DrawController@base64')->name('base64');

Route::post('/upload', 'HomeController@upload')->name('uploadimg');
