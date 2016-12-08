<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index');
Route::get('/waiter', 'WaiterToolController@index')->middleware('role:waiter');
Route::get('/kitchen', 'KitchenToolController@index')->middleware('role:cook');
Route::get('/manager', 'ManagerController@getUsersList')->middleware('role:manager');
Route::post('/order', 'OrderController@create');
Route::post('/change_status', 'OrderController@changeStatus');
Route::post('/delete_order', 'OrderController@deleteOrder');
Route::post('/fire_employee', 'ManagerController@fireEmployee');
Route::post('/change_employee_position', 'ManagerController@changeEmployeePosition');
