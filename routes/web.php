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
Route::any('/sample001', 'sample001Controller@index')->name('sample001.index');
Route::get('/sample001/add', 'sample001Controller@add')->name('sample001.add');
Route::post('/sample001/post', 'sample001Controller@post')->name('sample001.post');
