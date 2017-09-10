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
Route::resource('/bill','BillController')->middleware('auth');
Route::get('/invoice/create/{id}','InvoiceController@create')->middleware('auth');
Route::resource('/invoice','InvoiceController',['except'=>'create'])->middleware('auth');
Route::get('/register/verify/{token}', 'Auth\RegisterController@verify');