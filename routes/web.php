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

Route::get('/',['uses' => 'HomeController@index','as' => 'home']);
Route::get('/columns',['uses' => 'HomeController@columns','as' => 'columns']);
Route::get('/templates',['uses' => 'HomeController@templates','as' => 'templates']);
Route::get('/template/{id}',['uses' => 'HomeController@template','as' => 'template']);
Route::get('/export-excel',['uses' => 'HomeController@exportExcel','as' => 'export.excel']);
