<?php

use Illuminate\Http\Request;

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


Route::resource('/products','ProductController')->middleware('cors');
Route::delete('/destroy-products','ProductController@destroyMany');
Route::resource('/columns','ColumnController');
Route::resource('/templates','TemplateController');
Route::get('/template-products/{id}','TemplateController@products');
Route::get('/template-products/{id}/{product_id}','TemplateController@product');
Route::put('/template-products/{id}/{product_id}','TemplateController@updateProduct');
Route::post('/template-products','TemplateController@storeProduct');
Route::delete('/template-products','TemplateController@destroyProduct');
Route::delete('/delete-template-products','TemplateController@destroyManyProduct');
Route::get('/product-columns','OtherController@getProductColumns');
Route::delete('/delete-many-columns',['uses' => 'ColumnController@destroyMany']);
Route::post('/create-many-columns',['uses' => 'ColumnController@storeMany']);
