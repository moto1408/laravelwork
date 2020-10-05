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
Route::get('/sample001/update', 'sample001Controller@update')->name('sample001.update');
Route::post('/sample001/ajaxDelete', 'sample001Controller@ajaxDelete')->name('sample001ajax.delete');

Route::any('/sampleAsyn', 'sampleAsynController@index')->name('sampleAsyn.index');
// Route::any('/sampleAsyn', 'sampleAsynController@index')->name('sampleAsyn.index');
// Route::any('/sampleAsynAjax', 'sampleAsynController@index')->name('sampleAsynAjax.index');
Route::post('/sampleAsyn/ajax/search', 'sampleAsynController@ajaxSearch')->name('sampleAsyn.ajax.ajaxSearch');
Route::post('/sampleAsyn/ajax/delete', 'sampleAsynController@ajaxDelete')->name('sampleAsyn.ajax.ajaxDelete');
Route::post('/sampleAsyn/ajax/upsert', 'sampleAsynController@ajaxUpsert')->name('sampleAsyn.ajax.ajaxUpsert');
Route::post('/sampleAsyn/ajax/getData', 'sampleAsynController@ajaxGetData')->name('sampleAsyn.ajax.ajaxGetData');


Route::any('/sampleOutputPdf', 'sampleOutputPdfController@index')->name('sampleOutputPdf.index');
Route::any('/sampleOutputPdf/downloadPdf', 'sampleOutputPdfController@downloadPdf')->name('sampleOutputPdf.downloadPdf');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

